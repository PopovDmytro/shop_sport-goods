<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \Request as Request;
use \RexResponse as RexResponse;
use \XDatabase as XDatabase;

/**
 * Class Attr2ProdAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  Attr2ProdAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class Attr2ProdAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\Attr2ProdEntity:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0'
    );
	
	function getDefault()
	{
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

		$product_id = Request::get('product_id', false);
		if (!$product_id) {
			RexResponse::error('Wrong Product ID');
		}

        $in_parent = Request::get('in_parent', false);
        if ($in_parent) {
            RexDisplay::assign('in_parent', $in_parent);
            
        }
        
		$productEntity = RexFactory::entity('product');
		$productEntity->get($product_id);
		RexDisplay::assign('product', $productEntity);
		

		$attribute = Request::get('attribute', false);
		if ($attribute and isset($attribute['submit'])) {
			unset($attribute['submit']);
			
			$attr2ProdManager = RexFactory::manager($this->mod);
			$attr2ProdArray = $attr2ProdManager->getAllByProductIDForCompare($productEntity->id);
            $colorId = 1;

			foreach ($attribute as $attribute_id => $attribute_value) {
				if (is_array($attribute_value)) {
                    $attribute_value = array_unique($attribute_value);
					foreach ($attribute_value as $value) {
						if (empty($value)) {
							continue;
						}

                        if ($attribute_id == $colorId) {
                            $prodColorOrderEntity = RexFactory::entity('ProdColorOrder');
                            $prodColorOrderEntity = $prodColorOrderEntity->getByAttributeIdAndProduct($value, $productEntity->id);

                            if (!$prodColorOrderEntity) {
                                $prodColorOrderEntity = RexFactory::entity('ProdColorOrder');
                                $prodColorOrderEntity->product_id = (int)$productEntity->id;
                                $prodColorOrderEntity->attribute_id = (int)$value;

                                $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `prod_color_order` WHERE `product_id` = '.$productEntity->id);
                                $prodColorOrderEntity->sorder = $order !== null ? $order + 1 : 0;
                                if(!$prodColorOrderEntity->create()) {
                                    RexResponse::error('Unable to create ProdColorOrder');
                                }
                            }
                        }

                        $strForCompare = trim($attribute_id).'::'.trim($value);
                        $issetAttr = false;

                        if ($attr2ProdArray) {
                            $keySimilar = array_search($strForCompare, $attr2ProdArray);
                            if (is_numeric($keySimilar)) {
                                $issetAttr = true;
                                unset($attr2ProdArray[$keySimilar]);
                            }
                        }

                        if (!$issetAttr) {
                            $attr2ProdEntity = RexFactory::entity($this->mod);
                            $attr2ProdEntity->set(array('product_id' => intval($productEntity->id), 'attribute_id' => intval($attribute_id), 'value' => $value));
                                                
                            if(!$attr2ProdEntity->create()) {
                                RexResponse::error('Unable to update Attr2Prod');
                            }    
                        }
					}
				} else {
					if (empty($attribute_value) && $attribute_value != 0) {
						continue;
					}
                    
					$strForCompare = trim($attribute_id).'::'.trim($attribute_value);
                        $issetAttr = false;
                        
                        if ($attr2ProdArray) {
                            $keySimilar = array_search($strForCompare, $attr2ProdArray);
                            
                            if (is_numeric($keySimilar)) {
                                $issetAttr = true;
                                unset($attr2ProdArray[$keySimilar]);    
                            }
                        }
                        
                        if (!$issetAttr) {
                            $attr2ProdEntity = RexFactory::entity($this->mod);
                            $attr2ProdEntity->set(array('product_id' => intval($productEntity->id), 'attribute_id' => intval($attribute_id), 'value' => $attribute_value));
                                                
                            if(!$attr2ProdEntity->create()) {
                                RexResponse::error('Unable to update Attr2Prod');
                            }    
                        }
				}
			}

            if ($attr2ProdArray) {
                foreach ($attr2ProdArray as $attr2ProdID => $attrString) {
                    $attr2ProdEntity = RexFactory::entity($this->mod);
                    $attr2ProdEntity->getByWhere('id = '.$attr2ProdID);
                    $attr2ProdEntity->delete();
                }
            }
            
            $attr2ProdManager->getDeleteAllNullSkuses();

            $prodColorManger = RexFactory::manager('ProdColorOrder');
            $prodColorManger->deleteByProductId($productEntity->id);

            RexResponse::response('ok');
		}

		//draw
		$attr2ProdManager = RexFactory::manager($this->mod);
		$attr2ProdManager->product = $productEntity;
		$attr2ProdManager->draw(true);
        RexDisplay::assign('form', $attr2ProdManager->fetched);
		
        if (RexResponse::getDialogUin()) {
            $content = RexDisplay::fetch(strtolower($this->mod).'/default.tpl');
            RexResponse::responseDialog($content, 500, 200);
        }
	}
    
    function getFilterAttribute()
    {
        RexResponse::init();
        
        $attribute_id = Request::get('attr_id', false);
        $product_id = Request::get('product_id', false);
        
        RexResponse::response($this->manager->getByAttrID($attribute_id, $product_id));
    }

    public function getRefreshProdColor()
    {
        $productList = XDatabase::getAll('SELECT id FROM `product`');

        $sql = 'SELECT attr2.id AS color_id FROM sku AS `t` INNER JOIN sku_element se ON t.id = se.sku_id '.
            'INNER JOIN attr2prod ap ON se.attr2prod_id = ap.id  '.
            'INNER JOIN attribute attr1 ON ap.`attribute_id` = attr1.id AND attr1.id = 1 '.
            'INNER JOIN attribute attr2 ON ap.value = attr2.`id`  '.
            'WHERE 1 AND `t`.`product_id` = ? '.
            'GROUP BY `se`.attr2prod_id  '.
            'ORDER BY `t`.`id` ASC  '.
            'LIMIT 0, 1000 ';

        foreach ($productList as $product) {
            $productId = $product['id'];
            if (!$productId) {
                continue;
            }

            $attributesList = XDatabase::getAll($sql, array($productId));

            if (!$attributesList || !count($attributesList)) {
                continue;
            }

            foreach ($attributesList as $value) {
                if (empty($value)) {
                    continue;
                }

                $attributeId = $value['color_id'];

                $prodColorOrderEntity = RexFactory::entity('ProdColorOrder');
                $prodColorOrderEntity = $prodColorOrderEntity->getByAttributeIdAndProduct($attributeId, $productId);

                if (!$prodColorOrderEntity) {
                    $prodColorOrderEntity = RexFactory::entity('ProdColorOrder');
                    $prodColorOrderEntity->product_id = (int)$productId;
                    $prodColorOrderEntity->attribute_id = (int)$attributeId;

                    $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `prod_color_order` WHERE `product_id` = ' . $productId);

                    $prodColorOrderEntity->sorder = $order !== null ? $order + 1 : 0;

                    if (!$prodColorOrderEntity->create()) {
                        echo 'something went wrong';
                        RexResponse::error('Unable to create ProdColorOrder');
                    }
                }
            }
        }

        echo 'Done successfully';
        exit;
    }
}