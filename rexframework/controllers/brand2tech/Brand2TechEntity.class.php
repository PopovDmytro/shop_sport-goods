<?php

/**
 * Class Brand2TechEntity
 *
 * Entity of SiteEntities
 *
 * @author   petroved
 * @access   public
 * @created  Thu Nov 14 10:05:33 EET 2013
 */
class Brand2TechEntity extends \RexFramework\DBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

	var $id;
	var $brand_id;
	var $technology_id;

	var $__table = 'brand2tech';
	var $__uid   = 'id';
}