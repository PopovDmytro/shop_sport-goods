<?php

class ProductAdminController extends \RexShop\ProductAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'ProductEntity:volley.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexShop\QRCodeEntity:shop.standart:1.0',
        'RexShop\QRCodeManager:shop.standart:1.0',
        'RexShop\ProductAdminController:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'Prod2TechManager:volley.standart:1.0',
        'Prod2TechEntity:volley.standart:1.0'
    );

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Название</b>', array($this, '_DGName')),
            //'name' => 'Название',
            array('<b>Дата обновления</b>', array($this, '_DGDate'), array('width' => 110)),
            //array('<b>Атач</b>', array($this, '_DGAttach'), array('width' => 40)),
            array('<b>Атрибуты</b>', array($this, '_DGAttribute'), array('width' => 60)),
            array('<b>Артикулы</b>', array($this, '_DGSku'), array('width' => 60)),
            array('<b>Фото</b>', array($this, '_DGPhoto'), array('width' => 70, 'align' => 'center')),
            array('<b>Изображения</b>', array($this, '_DGImage'), array('width' => 80)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }

    function _DGName($param)
    {
        $catalogAlias = RexFactory::entity('pcatalog');
        $catalogAlias->getByWhere('id ='.$param['record']['category_id']);
        //\sys::dump($catalogAlias->alias);
        $field = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/product/'.$catalogAlias->alias.'/'.$param['record']['id'].'.html" target= _blank>'.$param['record']['name'].'</a>';
        return $field;
    }

    function _DGPhoto($param)
    {
        $pimageManager = RexFactory::manager('pImage');
        $pimageManager->getByWhere('product_id = '.$param['record']['id'].' limit 1');
        $arr = $pimageManager->getCollection();
        if (isset($arr[0])) {
            $id = $arr[0]['id'];
        } else {
            return '<img src="/content/images/pimage/list_mini.jpg">';
        }
        if ($id) {
            $pimageEntity = RexFactory::entity('pImage');
            $pimageEntity->get($id);
            return '<a style="position:relative; display:block;" id="imgfull" class="gallery rex-tooltip-disable" href="'.XImage::getImg(array('name' => 'pImage', 'type' => 'main', 'id' => $pimageEntity->id, 'ext' => $pimageEntity->image)).'"><img id="imageFull" src="'.XImage::getImg(array('name' => 'pImage', 'type' => 'icon', 'id' => $pimageEntity->id, 'ext' => $pimageEntity->image)).'" /></a><script> prev_id = '.$pimageEntity->id.' </script>';
        }
    }

    protected function _getActionParams($param)
    {
        $arr = parent::_getActionParams($param);

        $class  = 'itemeactive';
        $title  = 'default.active';
        $img    = 'ui-icon-circle-check';

        if ($param['record']['active'] == 1) {
            $class  = 'itemdeactive';
            $title  = 'default.deactive';
            $img    = 'ui-icon-cancel';
        }

        $arr[] = array(
            'title'     => RexLang::get($title),
            'item_id'   => $param['record'][$this->entity->__uid],
            'class'     => $class,
            'allow'     => 'edit',
            'img'       => $img
        );

        return $arr;
    }

    function getActivate()
    {
        RexResponse::init();

        $id = Request::get('id');

        $productEntity = RexFactory::entity('product');
        $productEntity->get($id);
        if (!$productEntity->id) {
            RexResponse::error('Продукт с ID = ' . $id . ' не найден!');
        }

        $productEntity->active = $this->task;
        if (!$productEntity->update()) {
            RexResponse::error('Ошибка при обновлении товара с ID = ' . $productEntity->id);
        }

        RexResponse::response('ok');
    }

    function getEdit()
    {
        // RexResponse::init();

        // RexDisplay::assign('is_multiselect', true);

        // $technologyList = new RexDBList('technology');
        // RexDisplay::assign('technologyList', $technologyList);

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            exit(111);
        }

        parent::getEdit();

    }

    function getAdd()
    {
        RexResponse::init();

        RexDisplay::assign('is_multiselect', true);



        // $technologyList = new RexDBList('technology');
        // RexDisplay::assign('technologyList', $technologyList);

        parent::getAdd();
    }

    protected function _createEntity($entity, $arr)
    {
        $arr['active'] = isset($arr['active']) ? 1 : 2;
        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }

        try {

            $skusManager = RexFactory::manager('sku');
            $skusManager->getByFields(array('product_id' => $entity->id));
            $productSkus = $skusManager->getCollection();

            // if (is_array($productSkus) && count($productSkus) > 0) {
            //     foreach ($productSkus as $sku) {
            //         // обновление цены закупки и цены продажи - для всех скусов она устанавливается такой как на товаре
            //         $skuEntity = RexFactory::entity('sku');
            //         $skuEntity->get($sku['id']);
            //         $skuEntity->price_opt = $entity->price_opt;
            //         $skuEntity->price = $entity->price;
            //         $skuEntity->update();
            //     }
            // }

            $technologies = Request::get('technologies', false);

            if ($technologies and sizeof($technologies) > 0) {
                foreach ($technologies as $key => $technology_id) {
                    $prod2TechEntity = RexFactory::entity('prod2Tech');
                    $prod2TechEntity->product_id = $entity->id;
                    $prod2TechEntity->technology_id = intval($technology_id);
                    if (!$prod2TechEntity->create()) {
                        return array(
                            'mod' => $this->mod,
                            'error_no' => ExcJs::ErrorAfterCreate,
                            'message' => 'Unable to create Product',
                            'dialog_uin' => RexResponse::getDialogUin(),
                            'task' => $entity->id
                        );
                    }
                }
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
        $arr['active'] = isset($arr['active']) ? 1 : 2;

        $update = parent::_updateEntity($entity, $arr);

        if ($update !== true) {
            return $update;
        }

        try {

            $skusManager = RexFactory::manager('sku');
            $skusManager->getByFields(array('product_id' => $entity->id));
            $productSkus = $skusManager->getCollection();

            if (is_array($productSkus) && count($productSkus) > 0) {
                foreach ($productSkus as $sku) {
                    // обновление цены закупки и цены продажи - для всех скусов она устанавливается такой как на товаре
                    $skuEntity = RexFactory::entity('sku');
                    $skuEntity->get($sku['id']);
                    $skuEntity->price_opt = $entity->price_opt;
                    $skuEntity->price = $entity->price;
                    $skuEntity->update();
                }
            }


            //prod2tech - technologies
            $prod2TechManager = RexFactory::manager('prod2Tech');
            $prod2TechActive = $prod2TechManager->getTechByProdId($entity->id);
            //var_dump($prod2TechActive);

            // $prod2TechManager->deleteByFields(array('product_id'=>$entity->id));
            // $prod2TechManager->getByFields(array('product_id'=>$entity->id));

            // transform multidimensional array to one-demensional array
            foreach($prod2TechActive as &$techInfo) {
                $techInfo = $techInfo['id'];
            }

            $technologies = Request::get('technologies', false);

            foreach ($prod2TechActive as $key => $technology_id) {
                if ($technologies and sizeof($technologies) > 0) {
                    if(in_array($technology_id, $technologies)) {
                        $technologies = array_flip($technologies);
                        unset ($technologies[$technology_id]) ;
                        $technologies = array_flip($technologies);
                    } else {
                        $prod2TechManager = RexFactory::manager('prod2Tech');
                        $prod2TechManager->deleteByFields(array('product_id' => $entity->id, 'technology_id'=>$technology_id));
                    }
                } else {
                    $prod2TechManager = RexFactory::manager('prod2Tech');
                    $prod2TechManager->deleteByFields(array('product_id' => $entity->id, 'technology_id'=>$technology_id));
                }
            }

            if ($technologies and sizeof($technologies) > 0) {
                foreach ($technologies as $key => $technology_id) {
                    $prod2TechEntity = RexFactory::entity('prod2Tech');
                    $prod2TechEntity->product_id = $entity->id;
                    $prod2TechEntity->technology_id = intval($technology_id);
                    if (!$prod2TechEntity->create()) {
                        return 'Unable to update Product';
                    }
                }
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function _deleteEntity($entity)
    {
        $id  = $entity->id;
        $prod2TechManager = RexFactory::manager('prod2Tech');
        $prod2TechManager->deleteByFields(array('product_id' => $entity->id));

        $delete = parent::_deleteEntity($entity);

        if ($delete !== true) {
            return $delete;
        }

        return true;
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);
        if (!isset($filters['page'])) {
            $page  = Request::get('page', false);

            if ($page) {
                $arr['page'] = 1;
            } else {
                $arr['page'] = XSession::get('product_dg_page');
                if (!$arr['page']) {
                    $arr['page'] = 1;
                }
            }
        }

        if (!isset($filters['filter'])) $arr['filter'] = false;
        if (!isset($filters['pcatalog'])) $arr['pcatalog'] = false;
        if (isset($filters['pcatalog']) && $filters['pcatalog']) {
            $arr['inpage'] = 1000;
        }

        if (isset($arr['pcatalog']) && $arr['pcatalog']) {
            $arr['order_by']  = 'sorder';
            $arr['order_dir'] = 'DESC';
        }

        if (isset($filters['search'])) {
            $pattern = '/([0-9]+)[-0-9]*/';
            preg_match($pattern, $filters['search'], $matches);
            if ($matches) {
                $arr['id'] = $matches[1];
            }
        }

        XSession::set('product_dg_page', $arr['page']);

        return $arr;
    }

    public function getGenerateSorder()
    {
        $products = XDatabase::getAll('SELECT id, sorder FROM `product` WHERE ISNULL(sorder) OR !LENGTH(sorder)');
        if (!$products) {
            return false;
        }

        foreach ($products as $product) {
            $this->entity->get($product['id']);
            $maxSorder = XDatabase::getOne('SELECT MAX(`sorder`) FROM product');
            $maxSorder++;
            $this->entity->sorder = $maxSorder;
            $this->entity->update();
        }
        exit;
    }

    public function getReplaceSorder()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();

        $currentProductID = Request::get('curr', false);
        $nextProductID    = Request::get('next', false);
        $prevProductID    = Request::get('prev', false);

        if (!$currentProductID) {
            RexResponse::error('Не указаны данные для изменения порядка продуктов!');
        }

        $this->entity->get($currentProductID);
        $nextProductEntity = RexFactory::entity('product')->get($nextProductID);
        $prevProductEntity = RexFactory::entity('product')->get($prevProductID);
        if (!$this->entity->id) {
            RexResponse::error('Продукты по указанным ID (' . $currentProductID . ',' . $nextProductID . ') не найдены!');
        }

        $minID = $this->entity->sorder;
        $maxID = $prevProductEntity->sorder;
        $orderDir = 'ASC';
        if ($minID > $maxID) {
            $minID = $nextProductEntity->sorder;
            $maxID = $this->entity->sorder;
            $orderDir = 'DESC';
        }

        $shiftProducts = XDatabase::getCol('SELECT id FROM product WHERE id != ' . $this->entity->id . ' AND sorder >= ' . $minID . ' AND sorder <= ' . $maxID . ' AND category_id = ' . $this->entity->category_id . ' ORDER BY sorder ' . $orderDir);

        foreach ($shiftProducts as $productID) {
            $shiftProductEntity         = RexFactory::entity('product')->get($productID);
            $buffOrder                  = $this->entity->sorder;
            $this->entity->sorder       = $shiftProductEntity->sorder;
            XDatabase::query('UPDATE product SET sorder = ? WHERE id = ?', array($this->entity->sorder, $this->entity->id));
            $shiftProductEntity->sorder = $buffOrder;
            XDatabase::query('UPDATE product SET sorder = ? WHERE id = ?', array($shiftProductEntity->sorder, $shiftProductEntity->id));
        }

        RexResponse::response('ok');
    }

    function getAutocompletprod()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s-\/\'\"\(\)\[\]\`\,]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      p.`id` AS product_id,
                      p.`name` AS product_name,
                      pi.`id` AS image_id,
                      pi.`image` AS image_ext,
                      s.`sku_article`,
                      ROUND(s.`price` - (IFNULL(s.`sale`, p.`sale`) / 100) * s.`price`) AS price,
                      s.`price_opt`,
                      s.`id` AS sku_id,
                      IFNULL(s.`sale`, p.`sale`) AS product_sale,
                      s.`price` AS full_price
                    FROM
                      product p
                      LEFT JOIN sku s
                        ON s.`product_id` = p.`id`
                      LEFT JOIN pimage `pi`
                        ON pi.`product_id` = p.`id`
                    WHERE (p.`name` LIKE "%'.addslashes($query).'%"
                        OR s.`sku_article` LIKE "%'.addslashes($query).'%")
                        AND p.`active` = 1
                    GROUP BY p.`id`
                    LIMIT 30');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['product_id'].'='.$value['product_name'].'='.XImage::getImg(array('type' => 'list', 'name' => 'pImage', 'id' => $value['image_id'], 'ext' => $value['image_ext'])).'='.$value['sku_article'].'='.$value['price'].'='.$value['price_opt'].'='.$value['product_sale'].'='.$value['sku_id'].'='.$value['full_price']."\n";
            }
        }
        exit;
    }
    function _DGDate($param)
    {
        return date('Y-m-d H:i:s', strtotime($param['record']['date_update']));
    }


    public function getCopy()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();

        if (!RexPage::allow($this->datagrid_mod, 'edit')) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Permission error');
            } else {
                RexPage::addError('Permission error', $mod);
            }
        }

        $entity = $this->entity;
        if (!$this->task or $this->task < 1 or !$entity->get($this->task) || !$entity->{$entity->__uid}) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Wrong id');
            } else {
                RexPage::addError('Wrong id', $mod);
            }
        }

        $productEntity = new ProductEntity();

        $productEntity->set([
            'name' => $entity->name,
            'sorder' => $entity->sorder,
            'title' => $entity->title,
            'keywords' => $entity->keywords,
            'description' => $entity->description,
            'content' => $entity->content,
            'category_id' => $entity->category_id,
            'price' => $entity->price,
            'price_opt' => $entity->price_opt,
            'price_old' => $entity->price_old,
            'quantity' => $entity->quantity,
            'active' => 0,
            'code' => $entity->code,
            'visited' => $entity->visited,
            'in_stock' => $entity->in_stock,
            'brand_id' => $entity->brand_id,
            'bestseller' => $entity->bestseller,
            'new' => $entity->new,
            'event' => $entity->event,
            'homepage' => $entity->homepage,
            'yml' => $entity->yml,
            'sale' => $entity->sale,
            'weight' => $entity->weight,
            'unit' => $entity->unit,
            'is_common_price' => $entity->is_common_price,
            'is_common_sale' => $entity->is_common_sale,
            'youtube' => $entity->youtube
        ]);


        if (!$productEntity->create()) {
            RexResponse::error('Unable to create ' . ucfirst($this->datagrid_mod));
        }

        $pimageManager = RexFactory::manager('pImage');
        $pimageManager->getByWhere('product_id = ' . $this->task);
        $data = [];
        $collections = $pimageManager->getCollection();
        if (!empty($collections)) {
            foreach ($collections as $collection) {
                $collection['product_id'] = $productEntity->id;
                unset($collection['id']);
                $entity = RexFactory::entity('pImage');
                $entity->set($collection);
                $entity->create();
                unset($entity);
            }
        }

        $collections = XDatabase::getAll('SELECT `attribute_id`,`value` FROM `attr2prod` WHERE product_id = ' . $this->task);
        if (!empty($collections)) {
            foreach ($collections as $collection) {
                XDatabase::query("INSERT INTO `attr2prod`( `attribute_id`, `product_id`, `value`) VALUES (" . $collection['attribute_id'] . "," . $productEntity->id . "," . $collection['value'] . ")");

            }
        }
        $collections = XDatabase::getAll('SELECT `category_id` FROM `prod2cat` WHERE product_id = ' . $this->task);
        if (!empty($collections)) {
            foreach ($collections as $collection) {
                XDatabase::query("INSERT INTO `prod2cat`( `category_id` , `product_id`) VALUES (" . $collection['category_id'] . "," . $productEntity->id . ")");

            }
        }

        RexResponse::response($productEntity->id);
    }
}