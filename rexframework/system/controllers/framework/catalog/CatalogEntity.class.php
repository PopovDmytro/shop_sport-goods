<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;
use \RexDBDatetime as RexDBDatetime;

define('CATALOG_GORDER_SIGNS', 9);
define('CATALOG_GORDER_LEVEL_SIGNS', 3);

/**
 * Class CatalogEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CatalogEntity extends RexDBEntity
{
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('pid', new RexDBInt());
        static::add('sorder', new RexDBInt());
        static::add('gorder', new RexDBString());
        static::add('level', new RexDBInt());
        static::add('name', new RexDBString());
        static::add('icon', new RexDBString());
        static::add('content', new RexDBText());
        static::add('alias', new RexDBString());
        static::add('title', new RexDBString());
        static::add('keywords', new RexDBString());
        static::add('description', new RexDBString());
        static::add('active', new RexDBInt(0, 1, 1));
        static::add('date_create', new RexDBDatetime());
    }
     
    function create() 
    {
        $this->setLevel();
        $this->sorder = 0;
        $this->gorder = 0;
        
        parent::create();
        $this->setSorder();
        parent::update();

        $manager = new CatalogManager($this->__table, $this->__uid);
        $manager->recalculateGorder($this, $this->id, 1);

        return true;
    }
    
    function update($aNoOrder = false, $aSetSorder = false) 
    {
        parent::update();        
        $this->setLevel();
        
        if ($aSetSorder === true) {
            $this->setSorder();    
        }
        
        parent::update();
    
        if ($aNoOrder === false) {
            $manager = new CatalogManager($this->__table, $this->__uid);
            $manager->recalculateGorder($this, $this->id, 1);
        }
    
        return true;
    }
    
    function delete() 
    {
        $id = $this->{$this->__uid};
        
        $this->recursiceDelete($id);
    
        return true;
    }
    
    function recursiceDelete($id)
    {
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `pid` = '.$id;
        $res = XDatabase::getAll($sql);
        
        if (sizeof($res) > 0) {
            foreach ($res as $value) {
                $this->recursiceDelete($value['id']);
            }
        }
        
        $this->{$this->__uid} = $id;
        parent::delete();
    }
    
    function setLevel()
    {
        $tmp_level = $this->getField($this->pid, 'level');
        
        if ($this->pid == 0 or $tmp_level === false or $tmp_level < 0) {
            $this->level = 0;
        } else {
            $this->level = $tmp_level + 1;
        }
    }
    
    function setPid($aPid)
    {
        $this->pid = $aPid;
    }
    
    function setSorder()
    {
        $sql = 'SELECT MAX(`sorder`) FROM '.$this->__table.' WHERE `pid` = ? AND `id` <> ?';
        $this->sorder = XDatabase::getOne($sql, array($this->pid, $this->id));

        if (!$this->sorder or $this->sorder < 1) {
            $this->sorder = 1;
        } else {
            $this->sorder++;
        }        
    }
    
    function _drawZero($aID)
    {
        $count = CATALOG_GORDER_SIGNS - strlen($aID);
        $zero = '';
        if ($count > 0) {
            for ($i=0; $i<$count; $i++) {
                $zero .= '0';
            }
        }
        
        return $zero;
    }
    
    function _drawZeroLevel($aID)
    {
        $count = CATALOG_GORDER_LEVEL_SIGNS - strlen($aID);
        $zero = '';
        if ($count > 0) {
            for ($i=0; $i<$count; $i++) {
                $zero .= '0';
            }
        }
        
        return $zero;
    }
    
    function getUP($aPID, $aSorder) 
    {
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` < ? AND `pid` = ? ORDER BY `sorder` DESC LIMIT 1';
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
        $sql = 'SELECT * FROM '.$this->__table.' WHERE `sorder` > ? AND `pid` = ? ORDER BY `sorder` ASC LIMIT 1';
        $res = XDatabase::getRow($sql, array($aSorder, $aPID));
        if (!$res) {
            return false;
        } else {
            $this->set($res);
            return true;
        }
    }
    
}