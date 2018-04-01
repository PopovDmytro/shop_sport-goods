<?php
namespace RexShop;

/**
 * Class Brand2CatEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Brand2CatEntity extends \RexFramework\DBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
	var $id;
	var $brand_id;
	var $category_id;	
	
	var $__table = 'brand2cat';
	var $__uid   = 'id';
}