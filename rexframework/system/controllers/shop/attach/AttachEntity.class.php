<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBFloat as RexDBFloat;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;
use \RexDBDate as RexDBDate;
use \RexDBDatetime as RexDBDatetime;
use \XDatabase as XDatabase;

/**
 * Class AttachEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class AttachEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "attach";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('product_id', new RexDBInt());
        static::add('filename', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('type', new RexDBString(REXDB_FIELD_NOTNULL, '', 254));
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('download_count',  new RexDBInt(0, 0, 5));
    }
    
}