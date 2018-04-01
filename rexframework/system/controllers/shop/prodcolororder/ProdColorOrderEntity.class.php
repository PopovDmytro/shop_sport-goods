<?php
namespace RexShop;

use \XDatabase as XDatabase;
use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;

class ProdColorOrderEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "prod_color_order";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id',           new RexDBInt());
        static::add('product_id',   new RexDBInt());
        static::add('attribute_id', new RexDBInt());
        static::add('sorder',       new RexDBInt());
    }

    function getByAttributeIdAndProduct($attribute_id, $product_id)
    {
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `attribute_id` = ? AND product_id = ?';
        return XDatabase::getRow($sql, array($attribute_id, $product_id));
    }

    function setSorder()
    {
        $sql = 'SELECT MAX(`sorder`) FROM '.$this->__table.' WHERE `id` <> ? AND `product_id` = ?';
        $this->sorder = XDatabase::getOne($sql, array($this->id, $this->product_id));

        if (!$this->sorder or $this->sorder < 1) {
            $this->sorder = 1;
        } else {
            $this->sorder++;
        }
    }

    function getUP($aSorder)
    {
        $product_id = func_get_arg(1);
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `product_id` = '.$product_id.' AND `sorder` < ? ORDER BY `sorder` DESC LIMIT 1';

        $res = XDatabase::getRow($sql, array($aSorder));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }

    function getDown($aSorder)
    {
        $product_id = func_get_arg(1);
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `product_id` = '.$product_id.' AND `sorder` > ? ORDER BY `sorder` ASC LIMIT 1';
        $res = XDatabase::getRow($sql, array($aSorder));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }
}