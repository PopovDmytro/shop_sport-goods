<?php
namespace RexFramework;

use \XDatabase as XDatabase;
use \RexObject as RexObject;
use \RexFactory as RexFactory;
use \RexDBList as RexDBList;
use \PEAR as PEAR;

/**
* Class DBManager
*
* DB entity manager
*
* @author   Fatal
*
* @access   public
*
* @package  REXFramework
*
* @created  Fri Apr 28 11:32:41 EEST 2006
*/
class DBManager extends RexObject
{
    public static $assemble = 'standart';
    public static $version = 1.0;
    public static $needed = array(
        'DBEntity:standart:1.0'
    );
    
    /**
    * table name
    *
    * @var string
    */
    var $_table;

    /**
    * table UID
    *
    * @var string
    */
    var $_uid;


    /**
    * error
    *
    * @var PEAR_Error
    */
    var $_error;

    var $_collection;
    var $_count;

    /**
    * Constructor of DBManager
    *
    * @param   string $atable Table Name
    * @param   string $aUid   UID in table
    * @access  public
    */
    function __construct($aTable = '', $aUid = '')
    {
        $this->_table    = $aTable;
        $this->_uid        = $aUid;
    }

    /**
    * getError
    *
    * Returns PEAR error
    *
    * @access public
    *
    * @return PEAR_Error
    */
    function getError()
    {
        return $this->_error;
    }

    /**
    * _checkForObject
    *
    * Check object
    *
    * @access  private
    * @param   $this->_entity     $obj
    * @return  bool
    */
    /*function _checkForObject($obj = null)
    {
    if ($obj == null or !is_a($obj, $this->_entity)) {
    return false;
    } else {
    return true;
    }
    }*/

    /**
    * get
    *
    * return entity
    *
    * @access  public
    *
    * @param   int     $id  UID
    *
    * @return  $this->_entity $obj
    */
    function get($aNum=false)
    {
        if ($aNum !== false) {
            $sSQL = "SELECT * FROM `".$this->_table."` LIMIT 0, ".intval($aNum);
        } else {
            $sSQL = "SELECT * FROM ".$this->_table."`";
        }
        $res = XDatabase::getAll($sSQL);

        if (PEAR::isError($res)) {
            $this->_error = $res;
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
    }

    /**
    * getCount
    *
    * return collection
    *
    * @access  public
    *
    * @param   int     $id  UID
    *
    * @return  $this->_entity $obj
    */
    function getCount()
    {
        $sSQL = "SELECT count(*) FROM ".$this->_table;

        $res = XDatabase::getOne($sSQL);

        if (PEAR::isError($res)) {
            $this->_error = $res;
            $this->_count= false;
        } else {
            $this->_count = $res;
        }

        return $this->_count;
    }

    /**
    * getByFields
    *
    * return entity
    *
    * @access  public
    *
    * @param   array  $aFields
    *
    * @return  bool
    */
    function getByFields($aFields = null)
    {
        if (!$aFields) {
            $this->_collection = array();
        } else {

            $subSQL = "";
            foreach ($aFields as $aKey => $aValue) {
                $subSQL .= " ".$aKey." = ". XDatabase::quote($aValue) ." AND";
            }
            $subSQL = substr($subSQL, 0, strlen($subSQL) - 3);

            $sSQL = "SELECT * FROM ".$this->_table." WHERE ".$subSQL;

            $res = XDatabase::getAll($sSQL);

            if (PEAR::isError($res)) {
                $this->_error = $res;
                $this->_collection = array();
            } else {
                $this->_collection = $res;
            }
        }
    }

    /**
    * getCollection
    *
    * return _collection
    *
    * @access  public
    *
    * @param   array  $aFields
    *
    * @return  array
    */
    function getCollection($aType=false)
    {
        if (!$aType) {
            //
        } elseif ($aType == 'object') {
            $mod_arr = explode('\\', get_class($this));
            $mod = array_pop($mod_arr);
            if (substr($mod, -7) == 'Manager') {
                $mod = substr($mod, 0, -7);
            }
            $mod = lcfirst($mod);
            
            $realCollection = array();
            
            if ($this->_collection) {
                foreach ($this->_collection as $params) {
                    $entity = RexFactory::entity($mod);
                    $entity->set($params);
                    
                    $realCollection[] = $entity;
                }
                if (sizeof($realCollection) > 0) {
                    $this->_collection = $realCollection;
                }
            } else {
                $this->_collection = array();
            }
        }

        return $this->_collection;

    }

    /**
    * getByWhere
    *
    * return entity
    *
    * @access  public
    *
    * @param   array  $aWhere
    *
    * @return  bool
    */
    function getByWhere($aWhere = null)
    {
        if (!$aWhere) {
            $this->_collection = array();
        } else {

            $sSQL = "SELECT * FROM ".$this->_table." WHERE ".$aWhere;

            $res = XDatabase::getAll($sSQL);

            if (PEAR::isError($res)) {
                $this->_error = $res;
                $this->_collection = array();
            } else {
                $this->_collection = $res;
            }
        }
    }

    /**
    * create
    *
    * Creates new entity
    *
    * @access  public
    *
    * @param   $this->_entity     $obj
    *
    * @return  bool
    */
    /*function create(&$obj)
    {

    $res = XDatabase::queryInsert($this->_table, $obj);

    if (PEAR::isError($res)) {
    $this->_error = $res;
    return false;
    } else {
    $obj->{$this->_uid} = XDatabase::getLastInsertID($this->_table);
    return true;
    }
    }*/

    /**
    * update
    *
    * Update record in DB
    *
    * @access  public
    *
    * @param   $this->_entity     $obj
    *
    * @return  bool
    */
    /*function update(&$obj)
    {

    $res = XDatabase::queryUpdate(    $this->_table,
    $obj,
    $this->_uid ." = ".XDatabase::quote($obj->{$this->_uid}, 'integer'));

    if (PEAR::isError($res)) {
    $this->_error = $res;
    return false;
    } else {
    return true;
    }
    }*/



    /**
    * delete
    *
    * @access  public
    *
    * @return  bool
    */
    function deleteByFields($aFields)
    {
        if (!$aFields) {
            return false;
        } else {
            $subSQL = "";

            foreach ($aFields as $aKey => $aValue) {
                $subSQL .= " ".$aKey." = ". /*XDatabase::quote*/($aValue) ." AND";
            }

            $subSQL = substr($subSQL, 0, strlen($subSQL) - 3);

            $sSQL = "DELETE FROM ".$this->_table." WHERE ".$subSQL;

            $res = XDatabase::query($sSQL);

            if (PEAR::isError($res)) {
                $this->_error = $res;
                return false;
            } else {
                return true;
            }
        }
    }

    function _processFilter($key, $value)
    {
        return false;
    }

    /**
    * Get db table rows by filter
    *
    * @param array $filters
    * @return array
    */
    function getList($filters, $fields, $mod = false) {
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = '';
        
        $mod_arr = explode('\\', get_class($this));
        if (!$mod) {
            $mod = array_pop($mod_arr);
            if (substr($mod, -7) == 'Manager') {
                $mod = substr($mod, 0, -7);
            }
            $mod = lcfirst($mod);
        }
        
        $entity = RexFactory::entity($mod, false);
        $manager = RexFactory::manager($mod);
        $order_by = $manager->_uid;
        foreach ($filters as $key => $value) {
            $result = $manager->_processFilter($key, $value);
            
            if ($result !== false) {
                if (!is_array($result))
                    $sql .= $result;
                else {
                    $sql .= $result[0];
                    $sql_join .= $result[1];
                }
            } else {
                switch ($key) {
                    case 'page':
                        $page = $value;
                        break;
                    case 'inpage':
                        $inpage = $value;
                        break;
                    case 'order_by':
                        $order_by = $value;
                        break;
                    case 'order_dir':
                        $order_dir = $value;
                        break;
                    case 'search':
                        $value = trim($value);
                        if ($value) {
                            $ors = array();
                            if ($fields && sizeof($fields)) {
                                foreach ($fields as $field => $spec) {
                                    if ($field{0} != '_' && $field && intval($field).'' != $field) {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            } elseif ($entity) {
                                foreach ($entity as $field => $field_value) {
                                    if ($field{0} != '_') {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            }
                            if ($ors) {
                                $sql .= ' AND ('.implode(' OR ', $ors).')';
                            }
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`'.$key.'` = "'.addslashes($value).'"';
                        }
                }
            }
        }
        $sql_limit =' ORDER BY `t`.`'.$order_by.'` '.$order_dir.'
        LIMIT '.($page * $inpage - $inpage).', '.$inpage.'  ;';

        if ($entity && is_subclass_of($entity, 'RexDBEntity') && !$sql_join) {
            $list = new RexDBList($mod);
            
            $list->getByWhere(str_replace('`t`.', '', $sql));
            $count = sizeof($list);
            $list->setOrderBy($order_by.' '.$order_dir);
            $list->setLimit(($page * $inpage - $inpage).', '.$inpage);
            
            return array(
                0 => $list,
                1 => $count
            );
        }
        
        $sql = 'FROM '.$manager->_table.' AS `t` '.$sql_join.' WHERE '.$sql;
        
        return array(
            0 => XDatabase::getAll('SELECT t.* '.$sql.$sql_limit),
            1 => XDatabase::getOne('SELECT COUNT(*) '.$sql));
    }
    
    function truncate($str, $len, $end = '...', $encode = 'utf-8') {
        if (mb_strlen($str, $encode) > $len) {
            $str = mb_substr($str, 0, $len - mb_strlen($end, $encode), $encode).$end;
        }
        
        return $str;
    }
}