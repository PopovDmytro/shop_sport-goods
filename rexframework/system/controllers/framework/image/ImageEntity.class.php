<?php
namespace RexFramework;

use \XDatabase as XDatabase;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBDatetime as RexDBDatetime;
use \RexDBEntity as RexDBEntity;

/**
 * Class ImageEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ImageEntity extends RexDBEntity
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    
    protected static $__table = "image";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('image', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 128));
        static::add('description', new RexDBString());
        static::add('sorder', new RexDBInt());
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
    }
    
    function create() 
    {
        $this->sorder = 0;
        
        parent::create();
        
        $this->setSorder();
        
        return parent::update();
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
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` < ? ORDER BY `sorder` DESC LIMIT 1';
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
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` > ? ORDER BY `sorder` ASC LIMIT 1';
        $res = XDatabase::getRow($sql, array($aSorder));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }
    
}