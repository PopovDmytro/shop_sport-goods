<?php
class Prod2OrderAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'allyou.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\Prod2OrderEntity:shop.standart:1.0',
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\SkuElementEntity:shop.standart:1.0',
        'RexShop\Prod2OrderManager:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
    );
    
    protected $add_dialog_width = 600;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 600;
    protected $edit_dialog_height = 424;
    
    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'order_id' => '№ заказа',
            'product_id' => '№ товара',
            array('<b>Название товара</b>', array($this, '_DGProdname')),
            'count' => 'Количество',
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    function _DGProdname($param)
    {
        $preductEntity = RexFactory::entity('product');
        $preductEntity->get($param['record']['product_id']);
        if ($preductEntity->id && $preductEntity->id > 0) {
            return $preductEntity->name;
        }
        return 'Ненайден товар';
    }
    
    protected function _getFilters($filters)
    {
        $filters = parent::_getFilters($filters);
        if(!isset($filters['order_id'])) {
            $filters['order_id'] = $this->task;
        }
        
        return $filters;
    }
    
    protected function _validate(&$arr, $entity = null)
    {    
        if(!$arr['count'] || $arr['count'] < 1) {
            return 'Please enter count. Count must be not null!';
        }
        
        if(!$arr['product_id'] || $arr['product_id'] < 1) {
            return 'Please select product in autocomplite.';
        }
        
        return true;
    }
    
    function getEdit()
    {
        $prod2orderEntity = RexFactory::entity('prod2Order');
        $prod2orderEntity->getByWhere('id = '.$this->task);
       // \sys::dump($this->task); exit;
        
        $productEntity = RexFactory::entity('product');
        $productEntity->getByWhere('id = '.$prod2orderEntity->product_id);
        RexDisplay::assign('product', $productEntity);
        
        $skuManager = RexFactory::manager('sku');
        $skuManager->getSkusFront($productEntity->id);
        $attributesForSale = $skuManager->attributes;
        RexDisplay::assign('arrtprod', $attributesForSale);
        //\sys::dump($attributesForSale); exit;
        $skus = $skuManager->skus;
        RexDisplay::assign('skus', $skus);
        
        $prod2orderManager = RexFactory::manager('prod2order');
        $selectsku = $prod2orderManager->getAttrByProduct($prod2orderEntity->sku);
        RexDisplay::assign('selectsku', $selectsku);
        
        parent::getEdit();
    }
    
    function getAdd()
    {
        RexDisplay::assign('porder_id', Request::get('order_id', false));
        parent::getAdd();
    }
    
    function getAutocomplete()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }

        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);
    
        $res = XDatabase::getAll('SELECT 
                  p.`name` AS product_name,
                  b.`name` AS brand_name,
                  pc.`name_single` AS category_name,
                  p1.`id` AS image_id,
                  p1.`image` AS image_ext,
                  pc.`alias` AS category_alias,
                  p.`id` AS product_id 
                FROM
                  `product` AS p 
                  INNER JOIN brand b 
                    ON p.`brand_id` = b.id 
                  INNER JOIN `pcatalog` pc 
                    ON p.`category_id` = pc.id 
                  INNER JOIN `pimage` AS p1 
                    ON p.id = p1.`product_id` 
                  LEFT JOIN `pimage` p2 
                    ON p1.`product_id` = p2.`product_id` 
                    AND p1.`sorder` > p2.`sorder` 
                  INNER JOIN sku s 
                    ON p.id = s.`product_id` 
                WHERE ISNULL(p2.`id`) 
                  AND (
                    p.`name` LIKE "%'.addslashes($query).'%" 
                    OR pc.`name_single` LIKE "%'.addslashes($query).'%" 
                    OR b.`name` LIKE "%b404%" 
                    OR s.`sku_article` LIKE "%'.addslashes($query).'%"
                  ) 
                  AND p.active = 1 
                GROUP BY p.`id` 
                LIMIT 30');

       // print_r($res);  exit;
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['image_id'].'='.$value['image_ext'].'='.
                    $value['category_alias'].'/'.$value['product_id'].'.html='.
                        $value['category_name'].' '.$value['brand_name'].' '.$value['product_name'].'='.$value['product_id']."\n";
            }
        }
        exit;
    }
    
    function getSkuproduct()
    {
        RexResponse::init();
        
        $skuManager = RexFactory::manager('sku');
        $skuManager->getSkusFront($this->task);
        $attributesForSale = $skuManager->attributes;
        $skus = $skuManager->skus;
        
         $skusing = array();
         foreach ($skus as $k => $sku) {
             foreach ($sku as $key => $sku_elem) {
                  if ($key == "skus_elem") {
                      foreach ($sku_elem as $k_sku => $sku_one) {
                          $skusing[$k]['elements'][$k_sku] = $sku_one['attr2prod_id'];
                      }
                  } else {
                      $skusing[$k][$key] = $sku_elem;
                  }
             }
         }
        
        $sku = array('attr' => $attributesForSale, 'skus' =>  $skusing);
        
        if ($attributesForSale && $skus) {
            RexResponse::response($sku); 
        } else {
            RexResponse::error('Product no skus');
        }     
    }         
}