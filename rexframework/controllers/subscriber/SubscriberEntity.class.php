<?php

namespace RexFramework;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;

/**
 * Class SubscriberEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SubscriberEntity extends RexDBEntity
{
    public static $assemble = 'standart';
    public static $version = '1.0';

    protected static $__table = "subscribers";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('subscriber_email', new RexDBString());
    }
}