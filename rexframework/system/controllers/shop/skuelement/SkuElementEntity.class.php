<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBFloat as RexDBFloat;
use \XDatabase as XDatabase;

/**
 * Class SkuElementEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SkuElementEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "sku_element";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('sku_id', new RexDBInt());
        static::add('attr2prod_id', new RexDBInt());
    }
    
}