<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \XDatabase as XDatabase;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;
use \RexDBDatetime as RexDBDatetime;

/**
 * Class SliderEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SliderEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';

    protected static $__table = "slider";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('url', new RexDBString(REXDB_FIELD_NOTNULL, '', 255));
        static::add('text', new RexDBText());
        static::add('date', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('banner', new RexDBString());
        static::add('sorder', new RexDBInt(0, 0, 1));
        static::add('showbanner', new RexDBInt());
    }

    function create() 
    {
        parent::create();
        $this->setSorder();
        parent::update();
        return true;
    }
    
    function update($aNoOrder = false, $aSetSorder = false) 
    {
        parent::update();        
        
        if ($aSetSorder === true) {
            $this->setSorder();    
        }
    
        return true;
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