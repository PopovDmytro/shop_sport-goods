<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexConfig as RexConfig;

/**
 * Class CartManager
 *
 * Manager of Cart
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CartManager extends \RexObject
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
	var $_collection = array();
	
	function getData()
	{   
		if (isset($_COOKIE['cart'])) {
			$cart = base64_decode($_COOKIE['cart']); 
		    $cart = gzuncompress($cart);
			$cart = unserialize($cart);
			
			if (sizeof($cart) > 0) {
				$cartList = array();
				foreach ($cart as $key => $cartEntity) {

					if (!isset($cartEntity->product_id)) {
						continue;
					}
					$productEntity = RexFactory::entity('product');
					if (!$productEntity->get(intval($cartEntity->product_id))) {
						continue;
					}
					
					$this->_collection[] = $cartEntity;
				}
			}
		}
	}
	
	function setData()
	{
		$numInset = 0;
        
        foreach ($this->_collection as $entity) {
            if (!$entity->num) {
                $entity->num = $numInset++;    
            }
        }
        
        $decode = serialize($this->_collection);
		$decode = gzcompress( $decode );
		$decode = base64_encode($decode); 

		setcookie('cart', $decode, time() + 18000, '/', RexConfig::get('cookie_domain'));
		return true;
	}
	
	function delete() 
	{
        setcookie('cart', '', time() + 18000, '/', RexConfig::get('cookie_domain'));		
		return true;
	}
}