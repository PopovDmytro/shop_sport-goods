<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexRoute as RexRoute;
use \RexSettings as RexSettings;
use \RexResponse as RexResponse;
use \Request as Request;
use \XSession as XSession;

/**
 * Class CartController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  CartController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class CartController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\CartEntity:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0'
    );
	
	function getDefault()
	{
		$cart = Request::get('cart', false);
        
		if ($cart != false and isset($cart['submit'])) {
			unset($cart['submit']);
			
			if (sizeof($cart) > 0) {
				$manager = RexFactory::manager('cart');
				$manager->_collection = array();
				
				foreach ($cart as $key => $cartData) {
					$isset = false;
                    
					foreach ($manager->_collection as $cartCollection) {
						if ($cartCollection->product_id == $cartData['product_id']) {
							$isset = true;
						}
					}
                    
					if ($isset) {
						continue;
					}

					$cartEntity = RexFactory::entity('cart');
					$cartEntity->count = intval($cartData['count']);
					
                    if ($cartEntity->count < 1) {
						$cartEntity->count = 1;
					}
					unset($cartData['count']);
					
					$cartEntity->product_id = $cartData['product_id'];
					unset($cartData['product_id']);
					
					$cartEntity->attributes = $cartData['attributes'];
					$manager->_collection[] = $cartEntity;
				}
				
				$manager->setData();
			} else {
				$manager = RexFactory::manager('cart');
				$manager->getData();							
			}
		} else {
			$manager = RexFactory::manager('cart');
			$manager->getData();			
		}

		$total = 0;
        
		foreach ($manager->_collection as $key => $cart) {
			$productEntity = RexFactory::entity('product');
			if ($productEntity->getByFields(array('id' => $cart->product_id))) {
                
                $skuName = '';
                if ($cart->sku) {
                    $skuEntity = RexFactory::entity('sku');
                    $skuEntity->get($cart->sku);
                    if ($skuEntity->price) {
                        $productEntity->price = $skuEntity->price;    
                    }
                    $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                                                        htmlspecialchars('<td class="cart-attr-l">'),
                                                        htmlspecialchars(':</td>'),
                                                        '',
                                                        htmlspecialchars('</td>'));
                }
				
                if ($productEntity->sale) {
                    $productEntity->price = round($productEntity->price*(1 - $productEntity->sale/100), 2); 
                }
                
				//image
				$pimageManager = RexFactory::manager('pImage');
                $list = array();
                
                if ($cart->sku) {
                    $list = $pimageManager->getCartImage($cart->sku);
                }
                
                if (!$list) {
                    $pimageManager->getByWhere('product_id = '.intval($productEntity->id).' order by sorder limit 1');
                    $list = $pimageManager->getCollection('object');    
                }
				

				if (sizeof($list) == 1) {
					$image = $list[0];
				} else {
					$image = false;
				}
                
                

				$attributeList = array();
				if (strlen(trim($cart->attributes)) > 0) {
					$tmp = explode(';', $cart->attributes);					
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
                
                $pCatalog = RexFactory::entity('pCatalog');
                $pCatalog->get($productEntity->category_id);
                
				$manager->_collection[$key] = array(
                    'cart'=>$cart,
                    'product'=>$productEntity,
                    'image'=>$image,
                    'attributes'=>$attributeList,
                    'pcatalog_alias' => $pCatalog->alias,
                    'sku' => $skuName
                );
			} else {
				unset($manager->_collection[$key]);
			}
		}
		
		$cartList = $manager->_collection;
		RexDisplay::assign('cartList', $manager->_collection);
	}
	
	function getAdd()
	{
		$cartData = Request::get('cart', false);
        
		if ($cartData !== false and isset($cartData['submit'])) {
			unset($cartData['submit']);
			
           // \sys::dump();
			$manager = RexFactory::manager('cart');
			$manager->getData();
            
			$isset = false;
			foreach ($manager->_collection as $cartCollection) {
				if ($cartCollection->product_id == $cartData['product_id'] && $cartCollection->sku == $cartData['sku']) {
					$isset = true;
				}
			}
			if (!$isset) {
				
				$cartEntity = RexFactory::entity('cart');
                $cartData['count'] = intval($cartData['count']);
                if ($cartData['count'] > 9999) {
                    $cartData['count'] = 9999;
                }
				$cartEntity->count = $cartData['count'];
				if ($cartEntity->count < 1) {
					$cartEntity->count = 1;
				}
				unset($cartData['count']);
				
				$cartEntity->product_id = $cartData['product_id'];
				unset($cartData['product_id']);
                
                if (isset($cartData['sku'])) {
                    $cartEntity->sku = $cartData['sku'];    
                }
                unset($cartData['sku']);
				
				foreach ($cartData as $attributeID => $attributeValue) {
					$cartEntity->attributes .= $attributeID.':'.$attributeValue.';';
				}
				$cartEntity->attributes = trim($cartEntity->attributes, ';');
				
				$manager->_collection[] = $cartEntity;
				$manager->setData();
			}
            if (RexResponse::isRequest()) {
                RexResponse::init();
                RexResponse::response('ok');
            }
            RexRoute::location('cart');
		}
	}
	
	function getClear()
	{
		$id = Request::get('id', false);
        $sku = Request::get('sku', false);
        
        $manager = RexFactory::manager('cart');
        if ($id === false) {        
            $manager->delete();
        } else {
            $manager->getData();
            if ($manager->_collection and sizeof($manager->_collection) > 0) {
                foreach ($manager->_collection as $key => $cartCollection) {
                    if ($cartCollection->product_id == $id) {
                        if ($sku && $cartCollection->sku != $sku) {
                            continue;
                        }
                        unset($manager->_collection[$key]);
                        //break;
                    }
                }
                $manager->setData();
            }
        }

        RexRoute::location('cart');
	}
    
    
	function getCart() //smarty func
    {
        $user = XSession::get('user');
        RexDisplay::assign('who_i', $user);   
        $cart = Request::get('cart', false);
        if ($cart != false and isset($cart['submit'])) {
            unset($cart['submit']);
            
            if (isset($cart['product_id']) && isset($cart['count']) && !isset($cart[0])) {
                $cart[0] = array(
                    'product_id' => $cart['product_id'],
                    'count' => $cart['count']
                );
                if (isset($cart['attributes'])) {
                    $cart[0]['attributes'] = $cart['attributes'];
                    unset($cart['attributes']);
                }
                unset($cart['product_id']);
                unset($cart['count']);
                if (isset($cart['sku'])) {
                    unset($cart['sku']);    
                }
            }
            
            if (sizeof($cart) > 0) {
                
                $manager = RexFactory::manager('cart');
                //2. creating array
                $manager->_collection = array();
                
                foreach ($cart as $key => $cartData) {
                    $isset = false;
                    foreach ($manager->_collection as $cartCollection) {
                        if ($cartCollection->product_id == $cartData['product_id']) {
                            $isset = true;
                        }
                    }
                    if ($isset) {
                        continue;
                    }

                    $cartEntity = RexFactory::entity('cart');
                    $cartEntity->count = intval($cartData['count']);
                    if ($cartEntity->count < 1) {
                        $cartEntity->count = 1;
                    }
                    unset($cartData['count']);
                    
                    $cartEntity->product_id = $cartData['product_id'];
                    unset($cartData['product_id']);
                    
                    if (isset($cartData['attributes'])) {
                        $cartEntity->attributes = $cartData['attributes'];
                    }

                    $manager->_collection[] = $cartEntity;
                }
                
                //$manager->setData();
            } else {
                $manager = RexFactory::manager('cart');
                $manager->getData();                            
            }
        } else {
            $manager = RexFactory::manager('cart');
            $manager->getData();            
        }

        foreach ($manager->_collection as $key => $cart) {
            $productEntity = RexFactory::entity('product');
            if ($productEntity->getByFields(array('id' => $cart->product_id))) {
                
                //image
                $pimageManager = RexFactory::manager('pImage');    
                $pimageManager->getByWhere('`product_id` = '.intval($productEntity->id).' ORDER BY `sorder` LIMIT 1');
                $list = $pimageManager->getCollection('object');

                if (sizeof($list) == 1) {
                    $image = $list[0];
                } else {
                    $image = false;
                }

                $attributeList = array();
                if (strlen(trim($cart->attributes)) > 0) {
                    $tmp = explode(';', $cart->attributes);                    
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

                $manager->_collection[$key] = array('cart'=>$cart, 'product'=>$productEntity, 'image'=>$image, 'attributes'=>$attributeList);
            } else {
                unset($manager->_collection[$key]);
            }
        }
        
        $cartList = $manager->_collection;
        
        $product_id_arr = array();
        $sumcart = 0;
        $count_cart = 0;

        foreach ($cartList as $key => $item) {
            $product_id_arr[] = $item['cart']->product_id;
            if ($item['cart']->sku) {
                $skuEntity = RexFactory::entity('sku');
                $skuEntity->get($item['cart']->sku);
                if ($skuEntity->price) {
                    $item['product']->price = $skuEntity->price;    
                }
                if ($skuEntity->sale and $skuEntity->sale != 0){
                    $item['product']->price = $item['product']->price * (1 - $skuEntity->sale/100);
                }
            } 
            /*if ($item['product']->sale) {
                $item['product']->price = round($item['product']->price*(1 - $item['product']->sale/100), 1); 
            }*/
            $sumcart += ($item['product']->price * $item['cart']->count)*$item['cart']->count; 
            $count_cart += $item['cart']->count;
        }
        
        RexDisplay::assign('productListCart', $product_id_arr);
        RexDisplay::assign('dolar_rate', RexSettings::get('dolar_rate'));
        RexDisplay::assign('filter_kurs', RexSettings::get('filter_kurs'));
        RexDisplay::assign('cart_sum', $sumcart);
        RexDisplay::assign('cart_cnt', $count_cart);
        
        if (RexResponse::isRequest()) {
            RexResponse::init();
            $template = RexDisplay::fetch('cart/cart.header.tpl');
            RexResponse::response($template);
        }
    }
}
