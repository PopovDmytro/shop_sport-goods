<?php
class SkuAdminController extends \RexShop\SkuAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
        'RexShop\SkuElementEntity:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexShop\SkuElementManager:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\SkuAdminController:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );

    function getDefault()
    {
        $attr = Request::get('attr', false);
        RexDisplay::assign('attr', $attr);

        $product_id = Request::get('product_id', false);
        RexDisplay::assign('product_id', $product_id);

        $productEntity = RexFactory::entity('product');
        $productEntity->get($product_id);
        RexDisplay::assign('product_entity', $productEntity);

        $in_parent = Request::get('in_parent', false);
        if ($in_parent) {
            RexDisplay::assign('in_parent', $in_parent);

        }

        $this->_checkSkuses();

        parent::getDefault();
    }

    protected function _getFields($fields)
    {
        $attr = Request::get('attr', false);

        $headers = array(
            array('<b>Наличие</b>', array($this, '_DGPresence'), array('width' => 10)),
            array('<b>Имя</b>', array($this, '_DGName'), array('width' => 200)),
            array('<b>Артикул</b>', array($this, '_DGSkuArticle'), array('width' => 30))
        );

        if ($attr == 'color') {
            $headers = array_merge($headers, array(
                array('<b>Цена (закупка)</b>', array($this, '_DGOpt'), array('width' => 60)),
                array('<b>Цена (розница)</b>', array($this, '_DGPrice'), array('width' => 60)),
                array('<b>Скидка</b>', array($this, '_DGSale'), array('width' => 60)),
                array('<b>Цена со скидкой</b>', array($this, '_DGSalePrice'), array('width' => 60)),
            ));
        }

        return $headers;
    }
    
    protected function _getData($filters, $fields)
    {
        $res = $this->manager->getList($filters, $fields);

        return $res;
    }

    protected $default_dialog_width = 950;

    function _DGOpt($param) {
        return '<input type="text" class="sku-price" data-add-opt="' . $param['record']['color_id'] . '" ' . self::addAutoSaveAttrs('price_opt', $param['record']['id']) . ' name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][price_opt]" value="'.$param['record']['price_opt'].'" />';
    }

    function _DGSkuArticle($param) {
        return (isset($param['record']['color_id']) ? '<input type="text" ' . self::addAutoSaveAttrs('sku_article', $param['record']['id']) . ' data-add-opt="' . $param['record']['color_id'] . '" class="sku-price" name="sku['.$param['record']['color_id'].'][sku_article]" value="'.$param['record']['sku_article'].'" />' : $param['record']['sku_article']);
    }

    function _DGPrice($param)
    {
        return '<input type="text" data-add-opt="' . $param['record']['color_id'] . '" ' . self::addAutoSaveAttrs('price', $param['record']['id']) . ' class="sku-price" name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][price]" value="'.$param['record']['price'].'" />';
    }

    function _DGQuantity($param)
    {
        return '<input type="text"  data-add-opt="' . $param['record']['color_id'] . '" ' . self::addAutoSaveAttrs('quantity', $param['record']['id']) . ' class="sku-quantity" name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][quantity]" value="'.$param['record']['quantity'].'" />';
    }

    function _DGPresence($param)
    {
        $attr = Request::get('attr', false);
        $hiddenId = '';
        if ($attr != 'color') {
            $hiddenId = '<input type="hidden" name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][id]" value="'.$param['record']['id'].'" />';
        }

        return $hiddenId.'<input id="cbox-'.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'" type="checkbox" data-color-id="'.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'" data-field="quantity" data-entity-id="' . $param['record']['id'] . '" class="sku-presence" name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][quantity]"'.($param['record']['quantity'] ? ' checked="checked"' : '').'/>';
    }

    function _DGSale($param)
    {
        $param['record']['sale'] = $param['record']['sale'] === null ? 0 : $param['record']['sale'];
        return '<input type="text"  data-add-opt="' . $param['record']['color_id'] . '" ' . self::addAutoSaveAttrs('sale', $param['record']['id']) . ' class="sku-sale" name="sku['.(isset($param['record']['color_id']) ? $param['record']['color_id'] : $param['record']['id']).'][sale]" value="'.$param['record']['sale'].'" />';
    }

    function _DGSalePrice($param)
    {
        $price = $param['record']['price'] - ($param['record']['price'] * ($param['record']['sale'] / 100));
        return '<input type="text" class="sku-sale-price" readonly="readonly" value="'.$price.'" />';
    }

    protected static function addAutoSaveAttrs($fieldName, $entityID)
    {
        return 'data-entity="sku" data-field="' . $fieldName . '" data-autosave="true" data-entity-id="' . $entityID . '"';
    }
    
    protected function _getFilters($filters)
    {
        $filters = parent::_getFilters($filters);
        $attr = Request::get('attr', false);
        $allowedAttributes = array('color' => true, 'sizes' => true);

        if ($attr && isset($allowedAttributes[$attr]) && $allowedAttributes[$attr]) {
            $filters['attr'] = $attr;
        }

        return $filters;
    }

    function getColorAutoSave()
    {
        if (!RexResponse::isRequest() || $this->act === 'add') {
            return false;
        }

        RexResponse::init();

        $productId  = Request::get('product_id', false);
        $entityId   = Request::get('entity_id', false);
        $colorId    = Request::get('color_id', false);
        $value      = Request::get('value', false);
        $attr       = Request::get('attr', false);

        if ($attr && $attr == 'color') {
            $oldRow = $this->manager->getOldColorSku($productId, $colorId);
            if ($oldRow) {
                if ($value != $oldRow['quantity']) {
                    $this->manager->saveSkuQuantityByAttrID($productId, $colorId, $value);
                }
                RexResponse::response('success');
                exit;
            }
            $this->manager->saveSkuQuantityByAttrID($productId, $colorId, $value);
        } else {
            $skuEntity = RexFactory::entity('sku');
            $skuEntity->get($entityId);
            $sku['quantity'] = $value;

            $skuEntity->set($sku);
            $skuEntity->update();
        }

        RexResponse::response('success');
    }

    function getSaveForm()
    {
        RexResponse::init();

        $skus = Request::get('sku', false);
        $entity = Request::get('entity', false);

        $attr = Request::get('attr', false);
        $attr = $attr === 'false' ? false : $attr;

        if ($skus) {
            foreach ($skus as $key => $sku) {
                if ($attr == 'color' && !isset($sku['price_opt'])) {
                    continue;
                }

                if ($attr && $attr == 'color') {
                    $oldRow = $this->manager->getOldColorSku($this->task, $key);

                    if ($oldRow) {
                        $skuNewQuantity = 0;
                        if (isset($sku['quantity'])) {
                            $skuNewQuantity = 1;
                        } else {
                            $skuNewQuantity = 0;
                        }

                        if ($skuNewQuantity != $oldRow['quantity']) {
                            $this->manager->saveSkuByAttrID($this->task, $key, $sku);
                        }
                        continue;
                    }
                    $this->manager->saveSkuByAttrID($this->task, $key, $sku);
                } else {
                    $skuEntity = RexFactory::entity('sku');
                    $skuEntity->get($key);
                    if (isset($sku['quantity'])) {
                        $sku['quantity'] = 1;
                    } else {
                        $sku['quantity'] = 0;
                    }

                    unset($sku['id']);
                    $skuEntity->set($sku);
                    $skuEntity->update();
                }
            }
        }

        if ($attr == 'color') {
            if (!$entity['price']) {
                $entity['price'] = 0;
            }
            if (!$entity['price_opt']) {
                $entity['price_opt'] = 0;
            }

            if ($entity) {
                $entity['event']            = isset($entity['event']) ? 1 : 0;
                $entity['is_common_price']  = isset($entity['is_common_price']) ? 1 : 0;
                $entity['is_common_sale']   = isset($entity['is_common_sale']) ? 1 : 0;
                $entity['sale']             = isset($entity['sale']) && is_numeric($entity['sale']) ? $entity['sale'] : 0;
                $productEntity = RexFactory::entity('product');
                $productEntity->get($this->task);
                $productEntity->set($entity);
                $productEntity->update();
            }
        }

        RexResponse::response('ok');
    }

    private function _checkSkuses()
    {
        $filter = Request::get('filters');
        $product_id = isset($filter['product_id']) ? $filter['product_id'] : Request::get('product_id');

        $productEntity = RexFactory::entity('product');
        $productEntity->get($product_id);
        

        $pCatalogManager = RexFactory::manager('pCatalog');
        $pCatalogManager->getUpList($productEntity->category_id, RexFactory::entity('pCatalog'));
        $categories = array_reverse($pCatalogManager->getCollection());
        

        $productAttributes = array();
        $attr2ProdManager = RexFactory::manager('attr2Prod');
        foreach ($categories as $category_id) {
            $attr2ProdManager->getOnlyForSale($product_id, $category_id);
            if ($attr2ProdManager->_collection) {
                $productAttributes = array_merge($productAttributes, $attr2ProdManager->getCollection());
            }
        }

        if (is_array($productAttributes) && count($productAttributes) > 0) {
            $arrayAttribute = array();

            foreach ($productAttributes as $attribute) {
                $arrayAttribute[intval($attribute['attribute_id'])][] = $attribute['id'];
                sort($arrayAttribute[$attribute['attribute_id']]);
            }

            ksort($arrayAttribute);

            $arrayAttribute = array_values($arrayAttribute);

            $countArray = 0;
            $arraySorted = array();

            $this->_generateArray($arrayAttribute, $countArray, $arraySorted);
            unset($arraySorted['current']);

            $skusManager = RexFactory::manager('sku');
            $skusManager->getByFields(array('product_id' => $productEntity->id));
            $productSkus = $skusManager->getCollection();

            if (is_array($productSkus) && count($productSkus) > 0) {
                $arraySku = array();
                foreach ($productSkus as $sku) {

                    $skuElementManager = RexFactory::manager('skuElement');
                    $skuElementManager->getByFields(array('sku_id' => $sku['id']));
                    $skuElements = $skuElementManager->getCollection();

                    foreach ($skuElements as $skuElement) {
                        $arraySku[$sku['id']][] = $skuElement['attr2prod_id'];
                    }

                    if (isset($arraySku[$sku['id']])) {
                        sort($arraySku[$sku['id']]);
                    }
                }

                foreach ($arraySku as $key => $skuElements) {
                    $issetElement = 0;
                    foreach ($arraySorted as $arrayElements) {

                        if (!array_diff($skuElements, $arrayElements) && count($skuElements) == count($arrayElements)) {
                            $issetElement = 1;
                        }
                    }

                    if ($issetElement != 1) {
                        $skuEntity = RexFactory::entity('sku');
                        $skuEntity->get($key);
                        $skuEntity->delete();
                    }
                }

                foreach ($arraySorted as $arrayElements) {
                    $issetElement = 0;
                    foreach ($arraySku as $skuElements) {
                        if (!array_diff($arrayElements, $skuElements) && count($skuElements) == count($arrayElements)) {
                            $issetElement = 1;
                        }
                    }

                    if ($issetElement != 1) {
                        $skuEntity = RexFactory::entity('sku');
                        $skuEntity->product_id = $productEntity->id;
                        $skuEntity->price = $productEntity->price;
                        $skuEntity->price_opt = $productEntity->price_opt;
                        $skuEntity->sku_article = $productEntity->id;
                        $skuEntity->quantity = $productEntity->quantity;
                        $skuEntity->create();

                        foreach ($arrayElements as $element) {
                            $skuElementEntity = RexFactory::entity('skuElement');
                            $skuElementEntity->sku_id = $skuEntity->id;
                            $skuElementEntity->attr2prod_id = $element;
                            $skuElementEntity->create();
                        }
                    }
                }
            } else {
                foreach ($arraySorted as $arrayElements) {
                    $skuEntity = RexFactory::entity('sku');
                    $skuEntity->product_id = $productEntity->id;
                    $skuEntity->price = $productEntity->price;
                    $skuEntity->price_opt = $productEntity->price_opt;
                    $skuEntity->sku_article = $productEntity->id;
                    $skuEntity->quantity = $productEntity->quantity;
                    $skuEntity->create();

                    foreach ($arrayElements as $element) {
                        $skuElementEntity = RexFactory::entity('skuElement');
                        $skuElementEntity->sku_id = $skuEntity->id;
                        $skuElementEntity->attr2prod_id = $element;
                        $skuElementEntity->create();
                    }
                }
            }
        }
    }

    private function _generateArray($arrayAttribute, &$countArray, &$arraySorted, $aStart = 0)
    {
        if ($aStart == count($arrayAttribute)) {
            return;
        }           
        for ($i = 0; $i < count($arrayAttribute[$aStart]); $i++) {
            $arraySorted['current'][$aStart] = $arrayAttribute[$aStart][$i];
            if (isset($arrayAttribute[$aStart+1])) {
                $this->_generateArray($arrayAttribute, $countArray, $arraySorted, $aStart+1);
            } else {
                $arraySorted[$countArray] = $arraySorted['current'];
                $countArray++;
            }
        }
    }

    function getFieldAutoSave() {
        $field = Request::get('field', false);
        if ($field && in_array($field['name'], array('sku_article', 'quantity', 'price_opt', 'price', 'sale'))) {
            RexResponse::init();
            $entities = Request::get('entity_id', false);
            $entities = is_array($entities) ? $entities : array($entities);
            foreach ($entities as $entityID) {
                $this->entity->get($entityID);
                if ($this->entity->id) {
                    $field['add_opt'] = isset($field['add_opt']) ? $field['add_opt'] : false;
                    $this->manager->updateColorSkuArticles($this->entity->product_id, $field, $field['add_opt']);
                }
            }

            RexResponse::response('success');
        } else {
            parent::getFieldAutoSave();
        }
    }
}