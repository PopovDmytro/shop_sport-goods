<?php
namespace RexShop;

use \RexDBString as RexDBString;
use \RexDBInt as RexDBInt;

/**
 * Class PCatalogEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class PCatalogEntity extends \RexFramework\CatalogEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\CatalogEntity:standart:1.0'
    );

    protected static $__table = "pcatalog";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        parent::initTypes();

        static::add('name_single', new RexDBString());
        static::add('yml', new RexDBInt(0, 1, 1));
        static::add('is_menu', new RexDBInt(0, 0, 1));
        static::add('is_showmain', new RexDBInt(0, 0, 1));
    }
}