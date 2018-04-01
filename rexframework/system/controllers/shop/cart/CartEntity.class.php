<?php
namespace RexShop;

/**
 * Class CartEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CartEntity extends \RexObject
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
	var $product_id;
    var $count;
	var $sku;
    var $attributes;
	var $num;
	 
	 /**
	  * set Entity
	  */
	 function set($aParams = null) 
	 {
	 	if ($aParams) {
			foreach ($aParams as $name => $value) {
				if (array_key_exists($name, get_object_vars($this))) {
					$this->{$name} = $value;
				}
			}
		}
		
		return true;
	 }
}