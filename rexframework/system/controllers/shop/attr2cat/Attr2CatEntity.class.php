<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \XDatabase as XDatabase;

/**
 * Class Attr2CatEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Attr2CatEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "attr2cat";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('attribute_id', new RexDBInt());
        static::add('category_id', new RexDBInt());
        static::add('sorder', new RexDBInt());
        static::add('is_forsale', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
        static::add('is_picture', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 0, 1));
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
        $sql = 'SELECT MAX(`sorder`) FROM '.$this->__table.' WHERE `category_id` = ? AND `id` <> ?';
        $this->sorder = XDatabase::getOne($sql, array($this->category_id, $this->id));

        if (!$this->sorder or $this->sorder < 1) {
            $this->sorder = 1;
        } else {
            $this->sorder++;
        }        
    }
    
    function getUP($aPID, $aSorder) 
    {
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` < ? AND `category_id` = ? ORDER BY `sorder` DESC LIMIT 1';
        $res = XDatabase::getRow($sql, array($aSorder, $aPID));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }
    
    function getDown($aPID, $aSorder) 
    {
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` > ? AND `category_id` = ? ORDER BY `sorder` ASC LIMIT 1';
        $res = XDatabase::getRow($sql, array($aSorder, $aPID));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }
}