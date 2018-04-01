<?php
namespace RexShop;

use \Exception as Exception;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexSettings as RexSettings;

/**
 * Class OrderAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  OrderAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class OrderAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\OrderEntity:shop.standart:1.0',
        'RexShop\OrderManager:shop.standart:1.0',
    );
    
    protected $add_dialog_width = 600;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 600;
    protected $edit_dialog_height = 424;
   
    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Дата Создания</b>', array($this, '_DGDateCreate'), array('width' => 70)),
            array('<b>Дата Изменения</b>', array($this, '_DGDateUpdate'), array('width' => 70)),
            array('<b>Пользователь</b>', array($this, '_DGUser')),
            array('<b>Статус</b>', array($this, '_DGStatus'), array('width' => 50)),
            'comment' => array('<b>Комментарий</b>', array($this, '_DGComment')),
            array('<b>Товары</b>', array($this, '_DGProducts'), array('width' => 90, 'align' => 'center')),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    function _DGDateCreate($param)
    {
        return date('Y-m-d', strtotime($param['record']['date_create']));
    }
    
    function _DGDateUpdate($param)
    {
        return date('Y-m-d', strtotime($param['record']['date_update']));
    }
    
    function _DGUser($param)
    {
        $userEntity = RexFactory::entity('user');
        if ($param['record']['user_id']) {
            $userEntity->get($param['record']['user_id']);
            return 'Login: <a href="javascript:void(0);" class="user-info" user_id="'.$userEntity->id.'">'.$userEntity->login.'</a>'; 
        } else {
            $field = 'Имя: '.$param['record']['name'].'<br/>Телефон: '.$param['record']['phone'];
            return $field;
        }
    }

    function _DGStatus($param)
    {
        $statusList = $this->manager->getOrderStatuses();
        $currentStatus = isset($statusList[$param['record']['status']]) ? $statusList[$param['record']['status']] : false;
        if (!$currentStatus) {
            return '<span style="color:#000">Статус не определен</span>';
        }

        return '<span style="color:' . $currentStatus['color'] . '">' . $currentStatus['name'] . '</span>';
    }
    
    function _DGComment($param)
    {
        $field = '';

        $orderManager = RexFactory::manager('order');
        $prod2OrderManager = RexFactory::manager('prod2Order');
        $prod2OrderManager->getByWhere('order_id = '.$param['record']['id']);
        $productList = $prod2OrderManager->getCollection();
        
        $totalPrice = 0;
        
        if ($productList and sizeof($productList) > 0) {
            $dataList = array();
            foreach ($productList as $product_key => $prod2Order) {

                $productEntity = RexFactory::entity('product');
                if ($productEntity->get($prod2Order['product_id'])) {
                    if ($prod2Order['sku']) {
                        $skuEntity = RexFactory::entity('sku');
                        $skuEntity->get($prod2Order['sku']);
                        $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                                                            htmlspecialchars('<td class="cart-attr-l">'),
                                                            htmlspecialchars(''),
                                                            htmlspecialchars('<td class="cart-attr-r">'),
                                                            htmlspecialchars('</td>'));
                        $dataList[$product_key]['sku'] = $skuName;
                        $dataList[$product_key]['skuprice'] = $skuEntity->price;
                    }//\sys::dump($dataList); exit;
                    $dataList[$product_key]['product'] = $productEntity;
                } else {
                    continue;
                }
                
                $categoryEntity = RexFactory::entity('pCatalog');
                if ($categoryEntity->get($productEntity->category_id)) {
                    $dataList[$product_key]['category'] = $categoryEntity;
                } else {
                    continue;
                }
                
                $dataList[$product_key]['prod2Order'] = $prod2Order;
                
                $dataList[$product_key]['imagesku'] = $orderManager->getImage($prod2Order['sku']);

                //\sys::dump($skuEntity->price); exit;
                $totalPrice += $productEntity->price*$prod2Order['count'];

                $pimageManager = RexFactory::manager('pImage');    
                $pimageManager->getImageByProductOrSku($productEntity->id, $prod2Order['sku']);
                $list = $pimageManager->getCollection();
                
                if (sizeof($list) == 1) {
                    $dataList[$product_key]['image'] = $list[0];
                } else {
                    $dataList[$product_key]['image'] = false;
                }
                
                $attributeList = array();
                if (strlen(trim($prod2Order['attributes'])) > 0) {
                    $tmp = explode(';', $prod2Order['attributes']);  
                                      
                    foreach ($tmp as $data) {
                        $data = explode(':', $data);

                        $arrtibuteEntity = RexFactory::entity('attribute');
                        $arrtibuteEntity->get($data[0]);
                        $attributeList[$data[0]]['key'] = $arrtibuteEntity;
                        
                        $arrtibuteEntity = RexFactory::entity('attribute');
                        $arrtibuteEntity->get($data[1]);
                        $attributeList[$data[0]]['value'] = $arrtibuteEntity;
                    }
                }
                $dataList[$product_key]['attributes'] = $attributeList;
            }
        } else {
            $dataList = false;
        }
        $valuta = RexSettings::get('filter_kurs');
        RexDisplay::assign('valuta', $valuta);
        $kurs = RexSettings::get('dolar_rate');
        RexDisplay::assign('dolar_rate', $kurs);

        if ($dataList) {
            RexDisplay::assign('dataList', $dataList);
            $list = RexDisplay::fetch('order/list.inc.tpl');    
            $field .= $list;
        }
        
        if ($valuta == 'грн')
            $field .= '<b>Сумма заказа: '.round($totalPrice * $kurs).' грн</b>';
        elseif($valuta == '$')
            $field .= '<b>Сумма заказа: $'.$totalPrice.'</b>';
        
        $field .= '<br/>Примечание: '.str_replace("\n","<br/>",mb_substr($param['record']['comment'], 0, 300, 'utf-8'));

        return $field;
    }    

    protected function _deleteEntity($entity)
    {
        XDatabase::query('DELETE FROM `prod2order` WHERE `order_id` = ?', array($entity->id));
        
        $delete = parent::_deleteEntity($entity);
        
        if ($delete !== true) {
            return $delete;
        }
        
        return true;
    }

    function _DGProducts($param)
    {
        return '<a href="index.php?mod=prod2Order&task='.$param['record']['id'].'">товары<a>';    
    }   
}