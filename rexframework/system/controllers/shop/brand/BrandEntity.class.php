<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBDatetime as RexDBDatetime;
use \RexDBText as RexDBText;

/**
 * Class BrandEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class BrandEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "brand";
    protected static $__uid = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString());
        static::add('date_create', new RexDBDatetime());
        static::add('title', new RexDBString());
        static::add('keywords', new RexDBString());
        static::add('description', new RexDBString());
        static::add('content', new RexDBText());
        static::add('alias', new RexDBString());
        static::add('icon', new RexDBString());
    }
}