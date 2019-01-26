<?php
namespace RexShop;

use \XImage as XImage;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexLang as RexLang;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;
use \PHPExcel_IOFactory as PHPExcel_IOFactory;
use \Exception as Exception;
use \ExcJs as ExcJs;
use \RexResponse as RexResponse;
use \RexRoute as RexRoute;

/**
 * Class ProductAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  ProductAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class ProductAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\QRCodeEntity:shop.standart:1.0',
        'RexShop\AttributeManager:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0'
    );

    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => 'Название',
            array('<b>Дата обновления</b>', array($this, '_DGDate'), array('width' => 70)),
            array('<b>Атач</b>', array($this, '_DGAttach'), array('width' => 40)),
            array('<b>Атрибуты</b>', array($this, '_DGAttribute'), array('width' => 60)),
            array('<b>Артикулы</b>', array($this, '_DGSku'), array('width' => 60)),
            array('<b>Изображения</b>', array($this, '_DGImage'), array('width' => 80)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }

    protected function _getActionParams($param)
    {
        $arr = array(
            array(
                'title' => RexLang::get('default.edit'),
                'item_id' => $param['record'][$this->entity->__uid],
                'url' => '/admin/?mod=product&act=edit&task=' . $param['record'][$this->entity->__uid],
                'allow' => 'edit',
                'img' => 'ui-icon-pencil'
            ),
            array(
                'title' => RexLang::get('default.delete'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemdelete',
                'allow' => 'delete',
                'img' => 'ui-icon-trash'
            ),
            array(
                'title' => 'Создать копию',
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'copyItem',
                'allow' => 'copy',
                'img' => 'ui-icon-copy'
            ),
        );

        return $arr;
    }

    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGAttribute($param)
    {
        return '<a class="attributes" product_id="'.$param['record']['id'].'" href="javascript: void(0);">атрибуты</a>';
    }

    function _DGSku($param)
    {
        return '<a class="skus" product_id="'.$param['record']['id'].'" href="javascript: void(0);">артикулы</a>';
    }

    function _DGAttach($param)
    {
        return '<a class="attach" product_id="'.$param['record']['id'].'" href="javascript: void(0);">аттачи</a>';
    }

    function _DGDate($param)
    {
        return date('Y-m-d', strtotime($param['record']['date_update']));
    }

    function _DGImage($param)
    {
        return '<a class="images" product_id="'.$param['record']['id'].'" href="javascript: void(0);">изображения</a>';
    }

    function getDefault()
    {
        $list = new RexDBList('pCatalog');
        $list->setOrderBy('`gorder` ASC');
        RexDisplay::assign('pcatalogList', $list);

        parent::getDefault();
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);

        if (!isset($filters['filter'])) $arr['filter'] = false;
        if (!isset($filters['pcatalog'])) $arr['pcatalog'] = false;

        if (isset($filters['search'])) {
            $pattern = '/([0-9]+)[-0-9]*/';
            preg_match($pattern, $filters['search'], $matches);
            if ($matches) {
                $arr['id'] = $matches[1];
            }
        }

        return $arr;
    }

    function getEdit()
    {
        $pcatalogManager = RexFactory::manager('pCatalog');
        RexDisplay::assign('pcatalogList', $pcatalogManager->getPcatalogsForProduct());

        $relatedList = XDatabase::getAssoc('SELECT `related_id`, `product_id` FROM `related` WHERE `product_id` = ?', array($this->task));
        RexDisplay::assign('relatedList', $relatedList);

        $productList = XDatabase::getAll('SELECT * FROM `product` WHERE `id` <> ?', array($this->task));
        RexDisplay::assign('productList', $productList);
        RexDisplay::assign('is_multiselect', true);

        $sql = XDatabase::getOne('SELECT brand_id FROM `product` WHERE `id` = '.$this->task.'');
        $brand_select = $sql;
        RexDisplay::assign('brand_select', $brand_select);

        $view = Request::get('view', false);
        RexDisplay::assign('view', $view);

        parent::getEdit();
    }

    function getAdd()
    {
        $pcatalogManager = RexFactory::manager('pCatalog');
        RexDisplay::assign('pcatalogList', $pcatalogManager->getPcatalogsForProduct());

        $relatedList = XDatabase::getAssoc('SELECT `related_id`, `product_id` FROM `related` WHERE `product_id` = ?', array(0));
        RexDisplay::assign('relatedList', $relatedList);

        $productList = XDatabase::getAll('SELECT * FROM `product` WHERE `id` <> ?', array(0));
        RexDisplay::assign('productList', $productList);
        RexDisplay::assign('is_multiselect', true);

        parent::getAdd();
    }

    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);
        if ($add !== true) {
            return $add;
        }

        try {
            $related = Request::get('related', false);

            XDatabase::query('DELETE FROM `related` WHERE `product_id` = ?', array($entity->id));

            if ($related) {
                foreach ($related as $related_id) {
                    XDatabase::query('INSERT INTO `related` (`product_id`, `related_id`) values (?, ?)', array($entity->id, $related_id));
                }
            }

            $pcatalog = RexFactory::entity('pCatalog');
            $pcatalog->get($entity->category_id);

            $code = 'http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('route' => 'shop_product', 'cat_alias' => $pcatalog->alias, 'task' => $entity->id));
            $qrcode = RexFactory::entity('QRCode');
            $qrcode->getByFields(array('code' => $code));
            if (!$qrcode->id || $qrcode->id <= 0) {
                $qrcode->code = $code;
                if (!$qrcode->create()) {
                    return array(
                        'error_no' => ExcJs::ErrorAfterCreate,
                        'message' => 'Error create QRCode record',
                        'dialog_uin' => RexResponse::getDialogUin(),
                        'task' => $entity->id
                    );
                }
            }

            $manager = RexFactory::manager('QRCode');
            $manager->generateQRCode($entity->id, $code);
            //$manager->generateQRCode($entity->id, $this->mod, 'M', 2.4);

            $categories = Request::get('categories', false);

            if ($categories) {
                foreach ($categories as $value) {
                    $values[] = '(0, "'.$entity->id.'", "'.$value.'")';
                }
                $this->manager->setProd2Cat($entity->id, implode(', ', $values));
            } else {
                $this->manager->setProd2Cat($entity->id);
            }
        } catch (Exception $e) {
            return array(
                'mod' => $this->mod,
                'error_no' => ExcJs::ErrorAfterCreate,
                'message' => $e->getMessage(),
                'dialog_uin' => RexResponse::getDialogUin(),
                'task' => $entity->id
            );
        }

        return true;
    }

    protected function _updateEntity($entity, $arr)
    {
        if ($arr['category_id'] && $arr['category_id'] != $entity->category_id) {
            $pcatalog = RexFactory::entity('pCatalog');
            $pcatalog->get($entity->category_id);

            $qrcode = RexFactory::entity('QRCode');
            $qrcode->getByFields(array('code' => 'http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('route' => 'shop_product', 'cat_alias' => $pcatalog->alias, 'task' => $entity->id))));
            $qrcode->delete();

            $manager = RexFactory::manager('QRCode');
            $upload_dir = $manager->_generateUploadDirForQRCode($entity->id);
            $filename = $upload_dir.'/'.$entity->id.'.png';
            if (is_file($filename)) {
                unlink($filename);
            }
        }

        $update = parent::_updateEntity($entity, $arr);

        if ($update !== true) {
            return $update;
        }

        try {
            $related = Request::get('related', false);

            XDatabase::query('DELETE FROM `related` WHERE `product_id` = ?', array($entity->id));

            if ($related) {
                foreach ($related as $related_id) {
                    XDatabase::query('INSERT INTO `related` (`product_id`, `related_id`) values (?, ?)', array($entity->id, $related_id));
                }
            }

            $pcatalog = RexFactory::entity('pCatalog');
            $pcatalog->get($entity->category_id);

            $code = 'http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('route' => 'shop_product', 'cat_alias' => $pcatalog->alias, 'task' => $entity->id));
            $qrcode = RexFactory::entity('QRCode');
            $qrcode->getByFields(array('code' => $code));
            if (!$qrcode->id || $qrcode->id <= 0) {
                $qrcode->code = $code;
                if (!$qrcode->create()) {
                    return 'Error create QRCode record';
                }
            }

            $manager = RexFactory::manager('QRCode');
            $upload_dir = $manager->_generateUploadDirForQRCode($entity->id);
            $filename = $upload_dir.'/main.png';
            if (!is_file($filename)) {
                $manager->generateQRCode($entity->id, $code);
            }

            $categories = Request::get('categories', false);

            if ($categories) {
                foreach ($categories as $value) {
                    $values[] = '(0, "'.$entity->id.'", "'.$value.'")';
                }
                $this->manager->setProd2Cat($entity->id, implode(', ', $values));
            } else {
                $this->manager->setProd2Cat($entity->id);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    protected function _validate(&$arr, $entity = null)
    {
        $this->entity = RexFactory::entity($this->mod);

        if(!$arr['name'] || strlen($arr['name']) < 3) {
            return 'Name must have min 3 characters';
        }

        if(!$arr['name'] || mb_strlen($arr['name'], 'utf-8') > 64) {
            return 'Name must have max 64 characters';
        }

        if(!isset($arr['brand_id']) || !$arr['brand_id'] || strlen($arr['brand_id']) < 1) {
            return 'Select brand please';
        }

        if(!$arr['quantity']) {
            return 'Please enter quantity';
        }

        if (isset($arr['event']) && $arr['event'] == 1) {
            if (!isset($arr['sale']) || !$arr['sale']) {
                return 'Please enter sale';
            } elseif (intval($arr['sale']) > 100) {
                return 'Sale amount can\'t be more than 100%';
            }

        }

        $arr['quantity'] = intval($arr['quantity']);

        return true;
    }

    protected function _deleteEntity($entity)
    {
        $count = XDatabase::getOne('SELECT COUNT(*) FROM `prod2order` WHERE `product_id` = ?', array($entity->id));

        if ($count > 0) {
            return 'Unable to delete Product. Please, view order list and clear linked orders';
        }

        $res = XDatabase::getAll('SELECT * FROM `pimage` WHERE `product_id` = ?', array($entity->id));
        if ($res and sizeof($res) > 0) {
            foreach ($res as $image) {
                XImage::delete(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').'pImage/source/'.$image['id'].'.'.$image['image']);
                XImage::delete(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').'pImage/'.$image['id'].'_full.'.$image['image']);
                XImage::delete(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').'pImage/'.$image['id'].'_image.'.$image['image']);
                XImage::delete(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').'pImage/'.$image['id'].'_list.'.$image['image']);
                XImage::delete(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').'pImage/'.$image['id'].'_icon.'.$image['image']);
                XDatabase::query('DELETE FROM `pimage` WHERE `id` = ?', array($image['id']));
            }

            if (XImage::isError()) {
                return XImage::getError();
            }
        }

        XDatabase::query('DELETE FROM `related` WHERE `product_id` = ? OR `related_id` = ?', array($entity->id, $entity->id));
        XDatabase::query('DELETE FROM `comment` WHERE `product_id` = ?', array($entity->id));

        $delete = parent::_deleteEntity($entity);

        if ($delete !== true) {
            return $delete;
        }

        return true;
    }

    function getProductList()
    {
        ini_set('memory_limit', '500M');
        $manager = new ProductManager();
        $manager->exportProductList();
        exit;
    }

	function getMass()
    {
        ini_set('memory_limit', '500M');

        $categories_unic_check = 'SELECT p1.* FROM `pcatalog` p1
                                  INNER JOIN `pcatalog` p2 ON p2.`name` = p1.`name` AND p2.`pid` = p1.`pid` AND p2.`id` > p1.`id`';
        $double_categories = XDatabase::getAll($categories_unic_check);
        if ($double_categories) {
            foreach ($double_categories as $dcat) {
                RexPage::addError('Найден дубль категории "'.$dcat['name'].'"');
            }
            RexPage::addError('Устраните дубли. Загрузка прайса невозможна.');
            return;
        }

        $brands_unic_check = 'SELECT p1.* FROM `brand` p1
                                  INNER JOIN `brand` p2 ON p2.`name` = p1.`name` AND p2.`id` > p1.`id`';
        $double_brands = XDatabase::getAll($brands_unic_check);
        if ($double_brands) {
            foreach ($double_brands as $dbrand) {
                RexPage::addError('Найден дубль бренда "'.$dbrand['name'].'"');
            }
            RexPage::addError('Устраните дубли. Загрузка прайса невозможна.');
            return;
        }

        if (isset($_FILES['price']) && !$_FILES['price']['error']) {

            $finfo = pathinfo($_FILES['price']['name']);
            if ($finfo['extension'] != 'xls' && $finfo['extension'] != 'xlsx') {
                RexPage::addError('Можно загружать только файлы xls!');
                return;
            }

            $data_start_row   = 2;
            $category_column  = 'A';
            $brand_column     = 'B';
            $name_column      = 'C';
            $skustring_column = 'D';
            $price_opt_column = 'E';
            $price_column     = 'F';
            $sale_column      = 'G';
            $instock_column   = 'I';
            $lastprice_column = 'H';
            
            $value_translator = array(
                'да' => 1,
                'нет' => 0);
            $field_translator = array(
                'E' => 'price_opt',
                'F' => 'price',
                'G' => 'sale',
                //'H' => array('in_stock', $value_translator),  не нужен для product - указывается для каждого sku
                'J' => array('bestseller', $value_translator),
                'K' => array('new', $value_translator),
                'L' => array('event', $value_translator),
            );
 
            $sql = 'SELECT `id`, `category_id`, `brand_id` FROM `product` WHERE `name` = ?';

            $categoryManager = new PCatalogManager();
            $brandManager = new BrandManager();
            $attributeManager = new AttributeManager();
            
            $category1_sql = 'SELECT
                            pc.id,
                            IF (NOT ISNULL(ppc.id), CONCAT(ppc.name, " /// ", pc.name), pc.name) AS category
                        FROM pcatalog pc
                        LEFT JOIN pcatalog ppc ON ppc.id = pc.pid';
            $categories_unprocessed1 = XDatabase::getAll($category1_sql);

            $category2_sql = 'SELECT
                            pc.id,
                            pc.name AS category
                        FROM pcatalog pc';
            $categories_unprocessed2 = XDatabase::getAll($category2_sql);

            $categories = array_merge(
                array_combine(
                    array_map(create_function('$v', 'return $v["category"];'), $categories_unprocessed1),
                    array_map(create_function('$v', 'return $v["id"];'), $categories_unprocessed1)),
                array_combine(
                    array_map(create_function('$v', 'return $v["category"];'), $categories_unprocessed2),
                    array_map(create_function('$v', 'return $v["id"];'), $categories_unprocessed2)));  

            $brands = $brandManager->getNameKeyList();
            $attributes = $attributeManager->getNameKeyList();

            $file = pathinfo($_FILES['price']['tmp_name'], PATHINFO_DIRNAME).DS.$_FILES['price']['name'];
            move_uploaded_file($_FILES['price']['tmp_name'], $file);
            try {
                $excel = PHPExcel_IOFactory::load($file);
            } catch (Exception $e) {
                RexPage::addError('Ошибка инициализации файла: '.$e->getMessage());
                return;
            }

            $sheet = $excel->getSheet(0);

            if (!$sheet->getCell('R1')->getValue()) {
                RexPage::addError('Неправильный формат прайса');
                return;
            }

            $sizeof_data = $sheet->getHighestRow();
            $process = false;

//            $sqlActive = 'UPDATE `product` SET `active` = 2';
//            if (XDatabase::isError()) {
//                RexPage::addError('Ошибка при деактивизации товаров');
//                return;
//            }

            for ($i = $data_start_row; $i <= $sizeof_data; ++$i) {

                if (!$sheet->getCell('A'.$i)->getValue() && !$sheet->getCell('B'.$i)->getValue()) {
                    continue; //пустая или некорректная строка
                }
                $process = true;

                $category_name = trim($sheet->getCell($category_column.$i)->getValue());
                $brand_name = trim($sheet->getCell($brand_column.$i)->getValue());
                $price = trim($sheet->getCell($price_column.$i)->getValue());
                $price_opt = trim($sheet->getCell($price_opt_column.$i)->getValue());
                $sale = trim($sheet->getCell($sale_column.$i)->getValue());
                $sale = 0; 
                                                             
                
         //       echo $category_name.' - '.$brand_name.' - '.$price.' - '.$sale.'<br>';
                
                $category_id = 0;
                $brand_id = 0;

                if (!$price) {
                    RexPage::addError('Строка '.$i.'. Не указана цена');
                    continue;
                }

                if (!$category_name) {
                    RexPage::addError('Строка '.$i.'. Не указана категория');
                    continue;
                }
                if (!isset($categories[$category_name])) {
                    RexPage::addError('Строка '.$i.'. Не известная категория "'.$category_name.'"');
                    continue;
                }
                $category_id = $categories[$category_name];

                if (!$brand_name) {
                    RexPage::addError('Строка '.$i.'. Не указан бренд');
                    continue;
                }

                if (!isset($brands[$brand_name])) {
                    RexPage::addError('Строка '.$i.'. Не известный бренд "'.$brand_name.'"');
                    continue;
                }
                $brand_id = $brands[$brand_name]['id'];

                $name = trim($sheet->getCell($name_column.$i)->getValue());
                if (!$name) {
                    RexPage::addError('Строка '.$i.'. Не указано наименование продукта');
                    continue;
                }
                
                $db_rows = XDatabase::getAll($sql, array($name));

                if (is_array($db_rows) && sizeof($db_rows) > 1) {
                    RexPage::addError('Строка '.$i.'. Указан товар, содержащий дубли в базе');
                    continue;
                } 

                $product_id = 0;
                if (is_array($db_rows) && sizeof($db_rows) > 0) {
                    $product_id = $db_rows[0]['id'];
                    $db_category_id = $db_rows[0]['category_id'];
                    $db_brand_id = $db_rows[0]['brand_id'];

                    if ($category_id != $db_category_id) {
                        RexPage::addError('Строка '.$i.'. Указан товар, находящийся в другой категории -'.$category_id.'- -'.$db_category_id.'-');
                        continue;
                    }

                    if ($brand_id != $db_brand_id) {
                        RexPage::addError('Строка '.$i.'. Указан товар, относящийся к другому бренду');
                        continue;
                    }
                }

                //временная проверка --start--
                if (!$product_id) {
                    RexPage::addError('Строка '.$i.'. Товар "'.$name.'" не найден в БД!');
                    continue;
                } 
                //временная проверка --finish--
                
                if (isset($last_product_id) && $last_product_id == $product_id) {
                    $no_update = true;
                } else {
                    $no_update = false;
                }

                if ($no_update) {
                    //continue;
                } else {

                    $productEntity = new ProductEntity();
                    if ($product_id) {
                        $last_product_id = $product_id;
                        $productEntity->get($product_id);
                        if (!$productEntity->id) {
                            RexPage::addError('Строка '.$i.'. Ошибка при получении сущности с id '.$product_id);
                            continue;
                        }
                    }                   
                    //echo $productEntity->id.' - ';
                    foreach ($field_translator as $column => $translator) {
                        $field = is_array($translator) ? $translator[0] : $translator;
                        $value = trim($sheet->getCell($column.$i)->getValue());
                        if (!$value && $value !== '0') {
                            RexPage::addError('Строка '.$i.'. В столбце '.$column.' не указано значение параметра '.$field);
                            continue;
                        }
                        //if (is_string($value) && $value == '1') $value = intval($value) ;
                        if (is_array($translator) && isset($translator[1]) && isset($translator[1][$value])) {
                            $value = $translator[1][$value];
                        }
                        $productEntity->$field = $value;
                       // echo $productEntity->$field." - (".gettype($productEntity->$field).")";
                    }
                    
                    if ($value_translator[trim($sheet->getCell('L'.$i)->getValue())]) {
                        $productEntity->sale = trim($sheet->getCell($sale_column.$i)->getValue());     
                    } else {
                        $productEntity->sale = 0;
                    }
                    
                    if ($productEntity->id) {
                        if ($productEntity->update()) {
                            RexPage::addMessage('Строка '.$i.'. Товар успешно найден и обновлен');
                        } else {
                            RexPage::addError('Строка '.$i.'. Ошибка обновления товара');
                        }
                    } else {
                        $productEntity->name = $name;
                        $productEntity->category_id = $category_id;
                        $productEntity->brand_id = $brand_id;
                        if ($productEntity->create()) {
                            RexPage::addMessage('Строка '.$i.'. Товар успешно добавлен');
                        } else {
                            RexPage::addError('Cтрока '.$i.'. Ошибка обновления товара');
                        }
                    }
                }               

                $aSkustring = explode('##', $sheet->getCell('D'.$i)->getValue());
                $aSku = array();
                $aAttributes = array();

                foreach ($aSkustring as $key => $value) {

                    $aTmp = explode('::', $value);
                    $aSku[trim($aTmp[0])] = trim($aTmp[1]);
                    if (!isset($attributes[trim($aTmp[0])])) {
                        RexPage::addError('Строка '.$i.'. В столбце '.$column.' несуществующий атрибут '.trim($aTmp[0])); 
                        continue;
                    } else {
                        $sqlx = 'SELECT 
                                  a2p.id,
                                  attr.name AS attribute_name,
                                  attv.name AS attribute_value 
                                FROM
                                  attr2prod a2p 
                                  LEFT JOIN attribute attr 
                                    ON a2p.`attribute_id` = attr.id
                                  LEFT JOIN attribute attv
                                    ON a2p.`value` = attv.`id`
                                  WHERE attr.`name` = "'.trim($aTmp[0]).'" AND attv.name = "'.trim($aTmp[1]).'" AND product_id = '.$product_id;

                        $query_result = XDatabase::getAll($sqlx);
                        if (!sizeof($query_result)) {
                            RexPage::addError('Строка '.$i.'. Товар с id = '.$product_id.' не имеет sku-элемент: '.trim($aTmp[0]).'::'.trim($aTmp[1]));
                        } else {
                            array_push($aAttributes,$query_result[0]['id']);
                        }                    
                    }
                }
                
                asort($aAttributes);
                $string = implode(',', $aAttributes);
                $sqly = 'SELECT 
                            sku_id 
                         FROM 
                            (SELECT 
                            sku_id,
                              GROUP_CONCAT(attr2prod_id) AS attribute_string
                            FROM
                              sku_element 
                            GROUP BY sku_id
                            ORDER BY sku_id, attr2prod_id ASC
                            ) AS tbl
                         WHERE tbl.attribute_string = "'.$string.'"';
                $sku_id = XDatabase::getOne($sqly);
                $skuEntity = new SkuEntity();
                $skuEntity->get(intval($sku_id));
                
                if(trim($sheet->getCell($instock_column.$i)->getValue())){
                   $sku_in_stock = $value_translator[trim($sheet->getCell($instock_column.$i)->getValue())];    
                } else {
                   RexPage::addError('Строка '.$i.'. Ошибка обновления sku_id = '.$sku_id);   
                }
  
                
                if ($skuEntity->id) {
                    if ($skuEntity->price != $price || $skuEntity->quantity != $sku_in_stock || $skuEntity->price_opt != $price_opt) {
                        $skuEntity->price = $price;
                        $skuEntity->price_opt = $price_opt;
                        $skuEntity->quantity = $sku_in_stock;
                        if ($skuEntity->update()) {
                            RexPage::addMessage('Строка '.$i.'. sku_id = '.$sku_id.' успешно найден и обновлен');
                        } else {
                            RexPage::addError('Строка '.$i.'. Ошибка обновления sku_id = '.$sku_id);
                        }
                    } 
                }
            }  

            if (!$process) {
                RexPage::addError('Загружен пустой или некорректный файл');
            }
        } else {
            RexDisplay::assign('show_form', 1);
        }
    }
}