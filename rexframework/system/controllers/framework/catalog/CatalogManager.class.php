<?php
namespace RexFramework;

use \XDatabase as XDatabase;
use \RexFactory as RexFactory;

/**
 * Class CatalogManager
 *
 * Manager of Catalog
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CatalogManager extends DBManager
{    
    var $struct = array();
    
    function __construct($aTable, $aPrimary)
    {
        parent::__construct($aTable, $aPrimary);
    }
    
    function getLatest($aCount=3)
    {
        if (!$aCount or $aCount < 1) {
            $aCount = 3;
        }

        $sql = 'SELECT * FROM '.$this->_table.' WHERE `active` = 1 ORDER BY UNIX_TIMESTAMP(`date_create`) DESC LIMIT ?';
        $res = XDatabase::getAll($sql, array($aCount));
        if (!$res or sizeof($res) < 1) {
            $this->_collection = false;
        } else {
            $this->_collection = $res;
        }
    }
    
    function getRand($aCount=3)
    {
        if (!$aCount or $aCount < 1) {
            $aCount = 3;
        }

        $sql = 'SELECT * FROM '.$this->_table.' WHERE `active` = 1 ORDER BY RAND() LIMIT ?';
        $res = XDatabase::getAll($sql, array($aCount));
        if (!$res or sizeof($res) < 1) {
            $this->_collection = false;
        } else {
            $this->_collection = $res;
        }
    }
    
    function recalculateGorder($catalogEntity, $aID, $aCounter = 300)
    {
        if ($aCounter > 299) {
            return false;
        }
        
        if (!$aID) {
            return false;
        }
        
        if ($catalogEntity->get($aID)) {

            if ($catalogEntity->pid < 1) {
                $clearGorder = '';
            } else {
                $clearGorder = intval($catalogEntity->getField($catalogEntity->pid, 'gorder'));    
                $clearGorder = $catalogEntity->_drawZero($clearGorder).$clearGorder;
                $clearGorder = substr($clearGorder, 0, ($catalogEntity->level)*3);
            }

            $clearGorder .= $catalogEntity->_drawZeroLevel($catalogEntity->sorder).$catalogEntity->sorder;            
            $catalogEntity->gorder = intval($clearGorder.$catalogEntity->_drawZero($clearGorder));
            $catalogEntity->update(true);
        }
        
        $this->getByFields(array('pid'=>$aID));
        $list = $this->getCollection();

        if ($list and sizeof($list) > 0) {
            foreach ($list as $category) {
                if (isset($category['id']) and intval($category['id']) > 0) {
                    $this->recalculateGorder($catalogEntity, $category['id'], ++$aCounter);
                }
            }
        }
    }
    
    function getSubCategoriesList($aID, $aCounter=300, $aSubLevel=false)
    {
        if ($aCounter > 299) {
            return false;
        }

        $this->getByFields(array('pid' => $aID));
        
        $list = $this->getCollection();
        if (sizeof($list) > 0) {
            foreach ($list as $category) {
                $this->struct[] = $category['id'];
                if (!$aSubLevel) {
                    $this->getSubCategoriesList($category['id'], ++$aCounter, $aSubLevel);
                }
            }
        }
    }
    
    function getUpList($aCategoryID, $entity, $aIsset=true)
    {
        if (!$entity->get($aCategoryID) or sizeof($this->_collection) > 9) {
            return false;
        }

        if ($aIsset !== false) {
            $this->_collection[] = $entity->id;
        }

        if ($entity->pid > 0) {    
            return $this->getUpList($entity->pid, $entity);
        } else {
            return true;
        }
    }
}