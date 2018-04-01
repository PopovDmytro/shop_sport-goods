<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBDatetime as RexDBDatetime;
use \RexDBText as RexDBText;

/**
 * Class CommentEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CommentEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "comment";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('user_id', new RexDBInt());
        static::add('content', new RexDBText());
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('product_id', new RexDBInt());
        static::add('status', new RexDBInt());
    }
}