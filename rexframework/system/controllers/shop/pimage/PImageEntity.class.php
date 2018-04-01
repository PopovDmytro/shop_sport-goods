<?php
namespace RexShop;

use \XDatabase as XDatabase;
use \RexDBInt as RexDBInt;

/**
 * Class PImageEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class PImageEntity extends \RexFramework\ImageEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ImageEntity:standart:1.0'
    );
    
    protected static $__table = "pimage";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        parent::initTypes();
        
        static::add('product_id', new RexDBInt());
        static::add('attribute_id', new RexDBInt());
        static::add('main', new RexDBInt(REXDB_FIELD_NOTNULL, 0, 1));
		static::add('color_sorder', new RexDBInt());
    }
    
	function setSorder()
	{
		$sql = 'SELECT MAX(`sorder`) FROM '.$this->__table.' WHERE `id` <> ?';
		$this->sorder = XDatabase::getOne($sql, array($this->id));
        
		if (!$this->sorder or $this->sorder < 1) {
			$this->sorder = 1;
		} else {
			$this->sorder++;
		}
	}

	function getUP($aSorder) 
	{
        $product_id = func_get_arg(1);
        $attribute_id = func_get_arg(2);

		$orderCol = 'sorder';
		$andWhere = '';
		$andWhereBind = array();
		if ($attribute_id) {
			$orderCol = 'color_sorder';
			$andWhere = ' AND attribute_id = ? ';
			$andWhereBind = array($attribute_id);
		}

		$sql = 'SELECT * FROM '.$this->__table.' WHERE `product_id` = '.$product_id.$andWhere.' AND `'.$orderCol.'` < ? ORDER BY `'.$orderCol.'` DESC LIMIT 1';

		$res = XDatabase::getRow($sql, array_merge($andWhereBind, array($aSorder)));
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
		$attribute_id = func_get_arg(2);

		$orderCol = 'sorder';
		$andWhere = '';
		$andWhereBind = array();
		if ($attribute_id) {
			$orderCol = 'color_sorder';
			$andWhere = ' AND attribute_id = ? ';
			$andWhereBind = array($attribute_id);
		}

		$sql = 'SELECT * FROM '.$this->__table.' WHERE `product_id` = '.$product_id.$andWhere.' AND `'.$orderCol.'` > ? ORDER BY `'.$orderCol.'` ASC LIMIT 1';
		$res = XDatabase::getRow($sql, array_merge($andWhereBind, array($aSorder)));
		if (!$res) {
			return false;
		} else {
			$this->set($res);
			return true;
		}
	}
}