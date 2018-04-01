<?php
namespace RexShop;

use \RexDBInt as RexDBInt;

/**
 * Class AttributeEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class AttributeEntity extends \RexFramework\CatalogEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\CatalogEntity:standart:1.0'
    );
    
    protected static $__table = "attribute";
    protected static $__uid = "id";
    
    protected static function initTypes()
    {
        parent::initTypes();
        
        static::add('type_id', new RexDBInt());
        static::add('filtered', new RexDBInt(0, 0, 1));
        static::add('filtered_form', new RexDBInt(0, 0, 1));
        static::add('is_picture', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
    }
}