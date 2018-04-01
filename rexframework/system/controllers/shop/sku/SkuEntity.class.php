<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBFloat as RexDBFloat;
use \XDatabase as XDatabase;

/**
 * Class SkuEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SkuEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "sku";
    protected static $__uid   = "id";
    
    var $_clearName;

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('product_id', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED));
        static::add('price', new RexDBFloat());
        static::add('active', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
        static::add('quantity', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 5));
    }
    
    public function getClearName($aSeparator = '', $aBeforeFirst = '', $aAfterFirst = '', $aBeforeLast = '', $aAfterLast = '')
    {
        if (!$aBeforeLast) {
            $aBeforeLast = $aBeforeFirst;
        }
        if (!$aAfterLast) {
            $aAfterLast = $aAfterFirst;
        }
        $sql = 'SELECT 
                  GROUP_CONCAT(
                    \''.$aBeforeFirst.'\',
                    attr1.`name`,
                    \''.$aAfterFirst.'\',
                    \''.$aBeforeLast.'\',
                    attr2.`name`,
                    \''.$aAfterLast.'\'
                     SEPARATOR \''.$aSeparator.'\'
                  ) AS `clear_name` 
                FROM
                  sku s
                  INNER JOIN sku_element se 
                    ON s.id = se.sku_id 
                  INNER JOIN attr2prod ap 
                    ON se.attr2prod_id = ap.id 
                  INNER JOIN attribute attr1 
                    ON ap.`attribute_id` = attr1.id 
                  INNER JOIN attribute attr2 
                    ON ap.value = attr2.`id`
                    WHERE s.id = '.$this->id;
        return $this->_clearName = html_entity_decode(XDatabase::getOne($sql));
    }
    
}