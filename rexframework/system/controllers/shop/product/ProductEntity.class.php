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
 * Class ProductEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ProductEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "product";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('title', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('keywords', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('description', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('content', new RexDBText());
        static::add('category_id', new RexDBInt());
        static::add('price', new RexDBFloat());
        static::add('price_old', new RexDBFloat());
        static::add('quantity', new RexDBInt());
        static::add('active', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
        static::add('code', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('visited', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('in_stock', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('brand_id', new RexDBInt());
        static::add('bestseller', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('new', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('event', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('homepage', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('yml', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
        static::add('sale', new RexDBInt());
    }
    
	function visited() 
	{
		$this->visited = date('Y-m-d H:i:s');
		$this->update();
	}
    
    function delete()
    {
        $id = $this->id;
        if (parent::delete()) {
            XDatabase::query('delete from `related` where `product_id` = '.$id.' or `related_id` = '.$id);
            XDatabase::query('delete from `attr2prod` where `product_id` = '.$id);
            return true;
        } else {
            return false;
        }
    }
}