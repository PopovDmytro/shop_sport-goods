<?php
namespace RexShop;

use \RexDisplay as RexDisplay;
use \RexFactory as RexFactory;
use \RexResponse as RexResponse;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;

/**
 * Class SkuAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  SkuAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SkuAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
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
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );

    protected $add_dialog_width = 550;
    protected $add_dialog_height = 450;
    protected $edit_dialog_width = 550;
    protected $edit_dialog_height = 450;
    protected $default_dialog_width = 800;
    protected $default_dialog_height = 750;

    protected function _getFields($fields)
    {
        return array(
            array('<b>Name</b>', array($this, '_DGName'), array('width' => 200)),
            array('<b>Price</b>', array($this, '_DGPrice'), array('width' => 60)),
            array('<b>Quantity</b>', array($this, '_DGQuantity'), array('width' => 30))
        );
    }

    function _DGName($param)
    {
        return $param['record']['name'];
    }

    function _DGPrice($param)
    {
        return '<input type="text" class="sku-price" name="sku['.$param['record']['id'].'][price]" value="'.$param['record']['price'].'" />';
    }

    function _DGQuantity($param)
    {
        return '<input type="text" class="sku-quantity" name="sku['.$param['record']['id'].'][quantity]" value="'.$param['record']['quantity'].'" />';
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);

        $product_id = Request::get('product_id', false);

        if ($product_id) {
            $arr['active'] = 1;
            $arr['product_id'] = $product_id;
            $arr['inpage'] = 1000;
        } else {
            $product_id = $arr['product_id'];
        }

        RexDisplay::assign('product_id', $product_id);

        $arr['order_by'] = 'id';
        $arr['order_dir'] = 'ASC';

        return $arr;
    }

    function getDefault()
    {
        // $product_id = Request::get('product_id', false);
        // RexDisplay::assign('product_id', $product_id);

        // $in_parent = Request::get('in_parent', false);
        // if ($in_parent) {
        //     RexDisplay::assign('in_parent', $in_parent);

        // }

        // проверка созданных артикулов
        // $this->_checkSkuses();

        parent::getDefault();
    }

    function getSaveForm()
    {
        RexResponse::init();

        $skus = Request::get('sku', false);

        if ($skus) {
            foreach ($skus as $key => $sku) {
                $skuEntity = RexFactory::entity('sku');
                $skuEntity->get($key);
                $skuEntity->quantity = $sku['quantity'];
                $skuEntity->price = $sku['price'];
                $skuEntity->update();
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
            foreach ($productAttributes as $attribute) {
                $arrayAttribute[$attribute['attribute_id']][] = $attribute['id'];
                sort($arrayAttribute[$attribute['attribute_id']]);
            }
            sort($arrayAttribute);
            $countArray = 0;
            $arraySorted = array();

            $this->_generateArray($arrayAttribute, $countArray, $arraySorted);

            $skusManager = RexFactory::manager('sku');
            $skusManager->getByFields(array('product_id' => $productEntity->id));
            $productSkus = $skusManager->getCollection();

            if (is_array($productSkus) && count($productSkus) > 0) {
                foreach ($productSkus as $sku) {
                    $skuElementManager = RexFactory::manager('skuElement');
                    $skuElementManager->getByFields(array('sku_id' => $sku['id']));
                    $skuElements = $skuElementManager->getCollection();
                    foreach ($skuElements as $skuElement) {
                        $arraySku[$sku['id']][] = $skuElement['attr2prod_id'];
                    }
                    sort($arraySku[$sku['id']]);
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
                        $skuEntity->quantity = 1;
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
                    $skuEntity->quantity = 1;
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
        unset($arraySorted['current']);
    }
}