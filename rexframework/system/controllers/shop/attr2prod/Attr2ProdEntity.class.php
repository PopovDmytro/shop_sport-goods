<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;

/**
 * Class Attr2ProdEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Attr2ProdEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "attr2prod";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('attribute_id', new RexDBInt());
        static::add('product_id', new RexDBInt());
        static::add('value', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
    }
}