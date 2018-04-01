<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBText as RexDBText;
use \RexDBDatetime as RexDBDatetime;
use \RexDBFloat as RexDBFloat;
use \RexDBString as RexDBString;

/**
 * Class OrderEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class OrderEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "rexorder";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('status', new RexDBInt());
        static::add('user_id', new RexDBInt());
        static::add('comment', new RexDBText());
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        //static::add('price', new RexDBFloat());
        static::add('name', new RexDBString());
        static::add('phone', new RexDBString());
    }
}