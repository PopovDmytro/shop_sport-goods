<?php
namespace RexFramework;

if (!class_exists('Entity')) {
    require_once CORE_DIR . 'Entity.class.php';
}

use \Entity as Entity;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

/**
* Class DBEntity
*
* db entity
*
* @author   Fatal
*
* @access   public
*
* @package  XFramework
*
* @created  Wed May 03 09:04:37 EEST 2006
*/
class DBEntity extends Entity
{
    public static $assemble = 'standart';
    public static $version = 1.0;

    /**
    * error
    *
    * @var PEAR_Error
    */
    var $_error;

    /**
    * Constructor of DBEntity
    *
    * @param   integer $aID - unique ID
    * @access  public
    */
    public function __construct($aID = null)
    {
        if (is_string($aID) or is_numeric($aID)) {
            $this->get($aID);
        }

        parent::__construct($aID);
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
    public function getError()
    {
        return $this->_error;
    }

    /**
    * get Entity
    *
    * @access  public
    *
    * @param   integer $aID - unique ID
    *
    * @return  bool
    */
    public function get($aID = null)
    {
        if (!$aID) {
            return false;
        } else {

            $sSQL = "SELECT * FROM ".$this->__table." WHERE ".$this->__uid." = ?";

            $res = XDatabase::getRow($sSQL, $aID, array("integer"));
            if (PEAR::isError($res)) {
                $this->_error = $res;
                return false;
            }
        }


        if ($res) {
            foreach ($res as $name => $value) {
                $this->{$name} = $value;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
    * create Entity
    *
    * @access  public
    *
    * @return  bool
    */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $res = XDatabase::queryInsert($this->__table, $this);

        if (PEAR::isError($res)) {
            $this->_error = $res;
            return false;
        } else {
            $this->{$this->__uid} = XDatabase::getLastInsertID($this->__table);
            return true;
        }
    }

    /**
    * update Entity
    *
    * @access  public
    *
    * @return  bool
    */
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }
        $values = $this->getArray();
        /*$sql = 'UPDATE '.$this->__table.' SET '.
        implode(', ', array_map(create_function('$k, $v', 'return \'`\'.$k.\'` = "\'.addslashes($v).\'"\';'),
        array_keys($values), array_values($values))).' WHERE '.$this->__uid.' = "'.addslashes($this->{$this->__uid}).'";';
        echo '-='.$sql.'=-';
        $res = XDatabase::query($sql);*/
        $res = XDatabase::queryUpdate(    $this->__table,
            $this,
            $this->__uid ." = '".addslashes($this->{$this->__uid})."'", 'integer');
        if (PEAR::isError($res)) {
            $this->_error = $res;
            return false;
        } else {
            return true;
        }
    }

    /**
    * delete Entity
    *
    * @access  public
    *
    * @return  bool
    */
    public function delete()
    {
        $uidName = $this->__uid;
        $SQL = "    DELETE FROM
        ".$this->__table."
        WHERE
        ".$uidName." = ?";

        $res = XDatabase::query($SQL, array($this->$uidName));
        if (PEAR::isError($res)) {
            $this->_error = $res;
            return false;
        } else {
            return true;
        }
    }

    /**
    * get Entity by any fields
    *
    * @access  public
    *
    * @param   array $aFields
    *
    * @return  bool
    */
    public function getByFields($aFields = null)
    {

        if (!$aFields) {
            return false;
        } else {

            $subSQL = "";
            foreach ($aFields as $aKey => $aValue) {
                $subSQL .= " ".$aKey." = '". addslashes($aValue) ."' AND";
            }
            $subSQL = substr($subSQL, 0, strlen($subSQL) - 3);

            $sSQL = "SELECT * FROM ".$this->__table." WHERE ".$subSQL;
            //sys::dump($sSQL);
            $res = XDatabase::getAll($sSQL);

            if (PEAR::isError($res)) {
                $this->_error = $res;
                return false;
            } else {

                if (sizeof($res) > 1) {
                    return false;
                }

                if (sizeof($res) == 1) {
                    $tmp = $res[0];
                    $res = $tmp;
                } else {
                    return false;
                }
            }
        }


        if ($res) {
            foreach ($res as $name => $value) {
                $this->{$name} = $value;
            }
            return true;
        } else {
            return false;
        }
    }


    /**
    * get Entity by any fields
    *
    * @access  public
    *
    * @return  bool
    */
    public function getByWhere($aWhere = null)
    {

        if (!$aWhere) {
            return false;
        } else {

            $sSQL = "SELECT * FROM ".$this->__table." WHERE ".$aWhere;

            $res = XDatabase::getAll($sSQL);

            if (PEAR::isError($res)) {
                $this->_error = $res;
                return false;
            } else {

                if (sizeof($res) > 1) {
                    return false;
                }

                if (sizeof($res) == 1) {
                    $tmp = $res[0];
                    $res = $tmp;
                } else {
                    return false;
                }
            }
        }

        if ($res) {
            foreach ($res as $name => $value) {
                $this->{$name} = $value;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
    * get Field
    *
    * @access  public
    *
    * @param   integer $aID - unique ID
    */
    public function getField($aID, $aFieldName)
    {
        if (!$aID) {
            return false;
        } else {

            $sSQL = "SELECT * FROM ".$this->__table." WHERE ".$this->__uid." = ?";

            $res = XDatabase::getRow($sSQL, $aID, array("integer"));
            if (PEAR::isError($res)) {
                $this->_error = $res;
                return false;
            }
        }

        if ($res and isset($res[$aFieldName])) {
            return $res[$aFieldName];
        } else {
            return false;
        }
    }
}