<?php
namespace RexShop;

use \RexDBString as RexDBString;
use \RexDBInt as RexDBInt;
use \RexDBText as RexDBText;

/**
 * Class UserEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class UserEntity extends \RexFramework\UserEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\UserEntity:standart:1.0'
    );

    protected static $__table = "user";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        parent::initTypes();

        static::add('lastname', new RexDBString());
        static::add('middlename', new RexDBString());
        static::add('city', new RexDBString());
        static::add('address', new RexDBString());
        static::add('zip', new RexDBString());
        static::add('receiver', new RexDBString());
        static::add('delivery', new RexDBInt());
        static::add('notice', new RexDBText());
        static::add('confirm', new RexDBInt(0, 0, 1));
        static::add('avatar', new RexDBString(0, ''));
    }
}