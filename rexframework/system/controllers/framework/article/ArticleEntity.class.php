<?php
namespace RexFramework;

use \RexDBEntity as RexDBEntity;
use \XDatabase as XDatabase;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBDatetime as RexDBDatetime;
use \RexDBText as RexDBText;

/**
 * Class ArticleEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ArticleEntity extends RexDBEntity
{
    protected static $__table = "article";
    protected static $__uid = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('sorder', new RexDBInt());
        static::add('name', new RexDBString());
        static::add('content', new RexDBText(0, null, 16777215));
        static::add('alias', new RexDBString());
        static::add('title', new RexDBString());
        static::add('keywords', new RexDBString());
        static::add('description', new RexDBString());
        static::add('active', new RexDBInt(0, 1, 1));
        static::add('date', new RexDBDatetime());
        static::add('type_id', new RexDBInt());
        static::add('icon', new RexDBString());
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