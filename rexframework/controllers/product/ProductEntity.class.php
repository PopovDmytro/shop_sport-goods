<?php

class ProductEntity extends RexDBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    
    protected static $__table = "product";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('sorder', new RexDBInt());
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('title', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('keywords', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('description', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('content', new RexDBText());
        static::add('category_id', new RexDBInt());
        static::add('price', new RexDBFloat());
        static::add('price_opt', new RexDBFloat());
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
        static::add('weight', new RexDBString());
        static::add('unit', new RexDBString());
        static::add('is_common_price', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
        static::add('is_common_sale', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
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

    function create()
    {
        $this->sorder = 0;

        parent::create();
        $this->setSorder();
        parent::update();

        return true;
    }

    function setSorder()
    {
        $sql = 'SELECT MAX(`sorder`) FROM ' . $this->__table;
        $this->sorder = XDatabase::getOne($sql);

        if (!$this->sorder or $this->sorder < 1) {
            $this->sorder = 1;
        } else {
            $this->sorder++;
        }
    }
}