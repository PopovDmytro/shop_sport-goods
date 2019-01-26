<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;
use \RexDBDatetime as RexDBDatetime;

/**
 * Class NewsEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class NewsEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "news";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('alias', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('content', new RexDBText());
        static::add('date', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('title', new RexDBString());
        static::add('keywords', new RexDBString());
        static::add('description', new RexDBString());
        static::add('icon', new RexDBString());
        static::add('youtube', new RexDBText());
    }
}