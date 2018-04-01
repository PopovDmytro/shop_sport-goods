<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexRoute as RexRoute;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \Request as Request;
use \XSession as XSession;
use \XDatabase as XDatabase;
use \RexLang as RexLang;
use \PHPSender as PHPSender;


/**
 * Class OrderController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  OrderController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class OrderController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\UserEntity:shop.standart:1.0',
        'RexShop\OrderEntity:shop.standart:1.0',
        'RexShop\OrderManager:shop.standart:1.0',
        'RexFramework\UserManager:standart:1.0'
    );
    
	function getDefault()
	{
        $user = XSession::get('user');
		if (!$user or $user->id < 1) {
			exit;
		}
		
		$orderManager = RexFactory::manager('order');
		$orderManager->getByWhere('`user_id` ='.intval($user->id).' ORDER BY `id` DESC');
		$orderList = $orderManager->getCollection();
		if ($orderList and sizeof($orderList) > 0) {
			
			foreach ($orderList as $key => $order) {
				$prod2OrderManager = RexFactory::manager('prod2Order');
				$prod2OrderManager->getProductList($order['id']);
				$productList = $prod2OrderManager->getCollection('object');
				
				if ($productList and sizeof($productList) > 0) {
					$orderList[$key]['productList'] = array();
					foreach ($productList as $product_key => $prod2Order) {

						//product
						$productEntity = RexFactory::entity('product');
						if ($productEntity->get($prod2Order->product_id)) {
                            if ($prod2Order->sku) {
                                $skuEntity = RexFactory::entity('sku');
                                $skuEntity->get($prod2Order->sku);
                                if ($skuEntity->price) {
                                    $productEntity->price = $skuEntity->price;    
                                }
                                $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                                                                    htmlspecialchars('<td class="cart-attr-l"><b>'),
                                                                    htmlspecialchars(':&nbsp;</b>'),
                                                                    htmlspecialchars('<td class="cart-attr-r">'),
                                                                    htmlspecialchars('</td>'));
                                $orderList[$key]['productList'][$product_key]['sku'] = $skuName;
                            }
							$orderList[$key]['productList'][$product_key]['product'] = $productEntity;
						} else {
							continue;
						}
						
						//prod2Order
						$orderList[$key]['productList'][$product_key]['prod2Order'] = $prod2Order;

                        //imagesku
                        $orderList[$key]['productList'][$product_key]['imagessku']  = $orderManager->getImage($prod2Order->sku);
                        
						//image
						$pimageManager = RexFactory::manager('pImage');	
						$pimageManager->getByWhere('product_id = '.intval($productEntity->id).' order by sorder limit 1');
						$list = $pimageManager->getCollection('object');
						if (sizeof($list) == 1) {
							$orderList[$key]['productList'][$product_key]['image'] = $list[0];
						} else {
							$orderList[$key]['productList'][$product_key]['image'] = false;
						}
						
						$attributeList = array();
						if (strlen(trim($prod2Order->attributes)) > 0) {
							
							$tmp = explode(';', $prod2Order->attributes);					
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
						$orderList[$key]['productList'][$product_key]['attributes'] = $attributeList;
						
					}
				} else {
					$orderList[$key]['productList'] = false;
				}
			}
            
			foreach ($orderList as $key => $orderValue)
			{
				switch ($orderList[$key]['status']) {
					case 1 :
						$orderList[$key]['status'] = RexLang::get('order.status.new');
						break;
					case 2 :
						$orderList[$key]['status'] = RexLang::get('order.status.checked');
						break;
					case 3 :
						$orderList[$key]['status'] = RexLang::get('order.status.paid');
						break;
					case 4 :
						$orderList[$key]['status'] = RexLang::get('order.status.closed');
						break;
					case 5 :
						$orderList[$key]['status'] = RexLang::get('order.status.reopened');
						break;
					case 6 :
						$orderList[$key]['status'] = RexLang::get('order.status.purchased');
						break;
					case 7 :
						$orderList[$key]['status'] = RexLang::get('order.status.sent_to_ukraine');
						break;
				}			
			}
            		
			foreach ($orderList as $key => $item){
                if ($item['productList']) {
                    foreach ($item['productList'] as $key_prod => $item_prod){
                        $pCatalog = RexFactory::entity('pCatalog');
                        $pCatalog->get($item_prod['product']->category_id);
                        
                        $orderList[$key]['productList'][$key_prod]['img_alias'] = $pCatalog->alias;
                    }
                }
			}
			
			RexDisplay::assign('orderList', $orderList);  
		}
	}
	
	function getAdd()
	{
        $user = XSession::get('user');
		
		$cart 	= Request::get('cart', false);		
		$order 	= Request::get('order', false);
		
		if ($cart != false and isset($cart['submit'])) {
			unset($cart['submit']);
			$orderEntity = RexFactory::entity('order');
			
			if ($order != false and isset($order['comment']) and strlen(trim($order['comment'])) > 0) {	
				$orderEntity->comment = trim(strip_tags($order['comment']));
			}

			if ($order != false and isset($order['name']) and strlen(trim($order['name'])) > 0) {	
				$orderEntity->name = $order['name'];
			}
			
			if ($order != false and isset($order['phone']) and strlen(trim($order['phone'])) > 0) {	
				$orderEntity->phone = $order['phone'];
			}
            
			if (sizeof($cart) > 0) {
				if ((!$user or $user->id < 1) and (!isset($order['phone']) or strlen(trim($order['phone'])) < 4)) {
					RexPage::addError(Rexlang::get('order.error.incorrect_user'));
				} else {	
					if ($user and $user->id > 0) {
						$orderEntity->user_id = $user->id;	
					} else {
						$orderEntity->user_id = 0;
					}					
					
					$orderEntity->status 	= 1;
					if (!$orderEntity->create()) {
						RexPage::addError(Rexlang::get('order.error.error_create'));
					} else {
						$total_sum = 0;
                        foreach ($cart as $key => $cartData) {		
                            if (intval($cartData['count']) > 9999) {
                                $cartData['count'] = 9999;
                            }			
                            $prod2OrderEntity = RexFactory::entity('prod2Order');
							$prod2OrderEntity->order_id     = $orderEntity->id;
							$product_id = intval($cartData['product_id']);
                            $prod2OrderEntity->product_id 	= $product_id;
							$prod2OrderEntity->attributes 	= $cartData['attributes'];
                            $prod2OrderEntity->count        = $cartData['count'];
                            $product_sku = intval($cartData['sku']);
                            if ($product_sku) {
                                $price = $prod2OrderEntity->getProductPriceBySku($product_sku);    
                            } else {
                                $price = $prod2OrderEntity->getProductPriceByProductId($product_id);
                            }							
                            $prod2OrderEntity->price    = $price;
                            $prod2OrderEntity->sku 		= intval($cartData['sku']);
							if ($prod2OrderEntity->count < 1) {
								$prod2OrderEntity->count = 1;
							}
                            $total_sum += $prod2OrderEntity->count * $price;
							if (!$prod2OrderEntity->create()) {
								RexPage::addError(sprintf(Rexlang::get('order.error.error_add_product'), intval($cartData['product_id'])));
							}
						}
                        $orderEntity->price = $total_sum;
                        $orderEntity->update();
					}
				}
			} else {
				RexPage::addError(RexLang::get('order.error.empty_order'));
			}
		} else {
			RexPage::addError(RexLang::get('order.error.empty_params'));
		}
		
		if (!RexPage::isError()) {
			//clear cart
			$manager = RexFactory::manager('cart');
            $manager->delete();
            
			//main to admin
			/*$headers = "From: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/plain; charset=UTF-8\r\n";
		    $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
		    $headers .= "X-Mailer: PHP/" . phpversion();  */

		    if ($orderEntity->user_id > 0) {
                if (RexSettings::get('phpsender_order') == 'true') {
                    $userEntity = RexFactory::entity('user');
                    $userEntity->get($orderEntity->user_id);
                    if ($userEntity->phone) {
                        PHPSender::sendSms($userEntity->phone, 'Вы оформили заказ № '.$orderEntity->id);
                    }
                    PHPSender::sendSms(RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'), 'Пользователь '.$userEntity->login.' ('.$userEntity->id.') оформил заказ № '.$orderEntity->id);    
                }
                
                $message = sprintf(RexLang::get('order.email.new_order_message_login'), $orderEntity->id, $orderEntity->user_id);
                $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);
                
                RexDisplay::assign('pismomes', $message);
            
                $html = RexDisplay::fetch('mail/pismo.order.admin.tpl');
                $userManager = RexFactory::manager('user');
                $userManager->getMail($html, RexSettings::get('contact_email'), sprintf(RexLang::get('order.email.new_order_subject'), RexConfig::get('Project', 'sysname')));
             
                RexRoute::location('order');
                exit;
		    }
			
            if (RexSettings::get('phpsender_order') == 'true') {
                PHPSender::sendSms($orderEntity->phone, 'Вы оформили заказ № '.$orderEntity->id);
                PHPSender::sendSms(RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'), 'Пользователь '.$orderEntity->name.' (тел. '.$orderEntity->phone.') оформил заказ № '.$orderEntity->id);    
            }
            
            $message = sprintf(RexLang::get('order.email.new_order_message_login'), $orderEntity->id, $orderEntity->name, $orderEntity->phone);
            $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);
                
            RexDisplay::assign('pismomes', $message);
        
            $html = RexDisplay::fetch('mail/pismo.order.admin.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, RexSettings::get('contact_email'), sprintf(RexLang::get('order.email.new_order_subject'), RexConfig::get('Project', 'sysname')));
             		    

			RexPage::addMessage(RexLang::get('order.order_congratulation'));
		}
	}
}