<?php
namespace RexFramework;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;

/**
 * Class StaticPageEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class StaticPageEntity extends RexDBEntity
{
    protected static $__table = "staticpage";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('alias', new RexDBString());
        static::add('content', new RexDBText());
        static::add('title', new RexDBString());
        static::add('keywords', new RexDBString());
        static::add('description', new RexDBString());
        static::add('active', new RexDBInt(0, 1, 1));
        static::add('youtube', new RexDBText());
    }    
}