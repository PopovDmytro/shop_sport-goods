<?php
namespace RexFramework;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBText as RexDBText;
use \RexDBDatetime as RexDBDatetime;

/**
 * Class ErrorLogEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ErrorLogEntity extends RexDBEntity
{
    protected static $__table = "errorlog";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('log_date', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('server', new RexDBText(0, null, 16777216));
        static::add('request', new RexDBText(0, null, 16777216));
        static::add('session', new RexDBText(0, null, 16777216));
        static::add('files', new RexDBText(0, null, 16777216));
        static::add('cookie', new RexDBText(0, null, 16777216));
        static::add('error_message', new RexDBText(0, null, 16777216));
        static::add('trace', new RexDBText(0, null, 16777216));
        static::add('type', new RexDBInt());
    }    
}