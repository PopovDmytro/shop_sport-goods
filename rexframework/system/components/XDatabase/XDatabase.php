<?php
    require_once 'PEAR.php';
    require_once 'MDB2.php';
    class XDatabase {

        var $_MDB2;

        var $_DSN;

        var $_prefix;

        var $_error;

        var $_log;



        function XDatabase($aProperty)
        {

            global $XDatabase;
            if (!$XDatabase) $XDatabase = $this;

            $XDatabase->connect($aProperty);
        }
        function _XDatabase() 
        {

        }
        public static function connect($aProperty)
        {

            global $XDatabase;
            if (!$XDatabase) { 
                new XDatabase($aProperty);
                return;
            }
            if (!empty($aProperty['prefix'])) {
                $XDatabase->_prefix = $aProperty['prefix'];
            } 

            if (isset($aProperty['prefix'])) {
                unset($aProperty['prefix']);
            }




            $XDatabase->_DSN = MDB2::parseDSN($aProperty);
            $XDatabase->_MDB2 = MDB2::connect($XDatabase->_DSN, true);
            //$XDatabase->_MDB2->setCharset('utf8');

            XDatabase::_checkDBResult($XDatabase->_MDB2);
            return;
        }

        public static function getDSN($asString = false) 
        {
            global $XDatabase;
            if (!$XDatabase or !isset($XDatabase->_DSN)) {
                return null;
            } else {
                if ($asString) { 
                    return	@$XDatabase->_DSN['phptype'] . "://" .
                    @$XDatabase->_DSN['username'] . ":" .
                    @$XDatabase->_DSN['password'] . "@" .
                    @$XDatabase->_DSN['protocol'] . "+" .
                    @$XDatabase->_DSN['hostspec'] . "/" .
                    @$XDatabase->_DSN['database'];
                } else {
                    return $XDatabase->_DSN;
                }
            }
        }

        public static function parseDSN($aDSN = null) 
        {
            return MDB2::parseDSN($aDSN);
        }
        public static function replacePrefix($aQuery) 
        {
            global $XDatabase;
            if (!empty($XDatabase->_prefix)) {
                return str_replace('#__', $XDatabase->_prefix, $aQuery);
            } else {
                return $aQuery;
            }
        }


        public static function _checkDBResult($aRes)
        {
            global $XDatabase;  
            if (MDB2::isError($aRes)) {
                $XDatabase->_error = $aRes;
                $aRes = null;
                return false;
            } else {
                $XDatabase->_error = null;
                return true;
            }
        }



        function _addLogRecord($aRes) 
        {
            global $XDatabase;

            $init = debug_backtrace();

            $lvl = count($init);
            $msg = "";

            for ($k = $lvl-2; $k >= 0; $k--) {
                $msg .= "&nbsp;[#".$k."] ";

                if (isset($init[$k]["class"]))
                    $msg .= @$init[$k]["class"]."::";

                if (isset($init[$k]["function"]))
                    $msg .= @$init[$k]["function"]."() called at ";

                $msg .= "[/".basename($init[$k]["file"]).":".$init[$k]["line"]."]<BR>";
            }


            $XDatabase->_log[] = array(	"backtace"	=> $msg,
                "object"	=> $aRes);
        }

        public static function getLogs() 
        {
            global $XDatabase;

            return $XDatabase->_log;
        }

        public static function getOne($aQuery,
            $aParam = null,
            $aInputType = null,
            $aOutputType = null,
            $aColnum = 0)
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended', null, false);
                $res = $XDatabase->_MDB2->extended->getOne($aQuery, $aOutputType, $aParam, $aInputType, 0);
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }
        public static function getRow($aQuery,
            $aParam = null,
            $aInputType = null,
            $aOutputType = null,
            $aIsAssoc = true)
        {
            global $XDatabase;

            $res = null; 

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended', null, false);
                if ($aIsAssoc)
                    $res = $XDatabase->_MDB2->extended->getRow($aQuery, $aOutputType, $aParam, $aInputType, MDB2_FETCHMODE_ASSOC);
                else 
                    $res = $XDatabase->_MDB2->extended->getRow($aQuery, $aOutputType, $aParam, $aInputType);
                XDatabase::_checkDBResult($res);
            } 
            return $res;
        }
        public static function getCol($aQuery,
            $aParam = null,
            $aInputType = null,
            $aOutputType = null,
            $aColnum = 0)
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended', null, false);
                $res = $res = $XDatabase->_MDB2->extended->getCol($aQuery, $aOutputType, $aParam, $aInputType, $aColnum);
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }
        public static function getAll($aQuery,
            $aParam = null,
            $aInputType = null,
            $aOutputType = null,
            $aRekey = false,
            $aForceArray = false,
            $aGroup = false,
            $aIsAssoc = true)
        {
            global $XDatabase;
            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended', null, false);

                if ($aIsAssoc) {
                    $res = $XDatabase->_MDB2->extended->getAll(
                        $aQuery,
                        $aOutputType,
                        $aParam,
                        $aInputType,
                        MDB2_FETCHMODE_ASSOC,
                        $aRekey,
                        $aForceArray,
                        $aGroup);
                } else {
                    $res = $XDatabase->_MDB2->extended->getAll(
                        $aQuery,
                        $aOutputType,
                        $aParam,
                        $aInputType,
                        MDB2_FETCHMODE_DEFAULT,
                        $aRekey,
                        $aForceArray,
                        $aGroup); 
                }

                XDatabase::_checkDBResult($res);
            }
            return $res;
        }


        public static function getStored($aQuery, $aParam = null) {
            global $XDatabase, $errorList;
            $res = null;
            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Function');

                $res = $XDatabase->_MDB2->executeStoredProc($aQuery, $aParam);

                if ($XDatabase->_MDB2->isError($res)) {
                    Sys::dump($res);
                    $errorList = RexLang::get('default.error_database');
                    return false;
                }

                $result = $res->fetchAll(MDB2_FETCHMODE_ASSOC, false, false, false);		

                while ($res->nextResult()) {
                    $result[] = $res->fetchAll(MDB2_FETCHMODE_ASSOC, false, false, false);        	
                }
                $res->free();

            }
            return $result;
        }

        public static function setStored($aQuery, $aParam = null) {
            global $XDatabase;
            $res = null;
            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Function');
                $res = $XDatabase->_MDB2->executeStoredProc($aQuery, $aParam);

                $result = $res->fetchOne(0);

                while ($res->nextResult()) {
                    $res->free();
                }

            }
            return $result;
        }
        public static function getAssoc(	$aQuery,
            $aParam = null,
            $aInputType = null,
            $aOutputType = null,
            $aIsAssoc = true,
            $aForceArray = false,
            $aGroup = false)
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended', null, false);
                if ($aIsAssoc) {
                    $res = $XDatabase->_MDB2->extended->getAssoc($aQuery, $aOutputType, $aParam, $aInputType, MDB2_FETCHMODE_ASSOC, $aForceArray, $aGroup);
                } else {
                    $res = $XDatabase->_MDB2->extended->getAssoc($aQuery, $aOutputType, $aParam, $aInputType, MDB2_FETCHMODE_DEFAULT, $aForceArray, $aGroup);
                }
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }


        public static function getLastInsertID($aTable = null, $aField = null) 
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aTable = $XDatabase->replacePrefix($aTable);

                $res = $XDatabase->_MDB2->lastInsertID($aTable, $aField);

                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function query($aQuery, $aParam = null, $aTypes = null)
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);

                if (!$aParam) {
                    $res = $XDatabase->_MDB2->exec($aQuery);
                    XDatabase::_checkDBResult($res);
                } else {
                    $data = array($aParam);
                    $statement = $XDatabase->_MDB2->prepare($aQuery, $aTypes, MDB2_PREPARE_MANIP);
                    if (!XDatabase::_checkDBResult($statement)) {
                        return false;
                    }
                    $XDatabase->_MDB2->loadModule('Extended');
                    $res = $XDatabase->_MDB2->extended->executeMultiple($statement, $data); 
                    $statement->free();

                    XDatabase::_checkDBResult($res);
                }
            }
            return $res;
        }

        public static function queryInsert($aTable, $aFieldsValues, $aTypes = null) 
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {

                $aTable = $XDatabase->replacePrefix($aTable); 

                $array = array();
                foreach ($aFieldsValues as $aKey => $aField) {
                    if ( is_array($aField) or is_object($aField) or $aKey[0] == '_' ) { 
                        continue;
                    }
                    $array[$aKey] = $aField;
                }

                $XDatabase->_MDB2->loadModule('Extended');
                $res = $XDatabase->_MDB2->extended->autoExecute($aTable, $array, MDB2_AUTOQUERY_INSERT, null, $aTypes);
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function queryUpdate($aTable, $aFieldsValues, $aWhere = null, $aTypes = null) 
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {

                $aTable = $XDatabase->replacePrefix($aTable);
                $array = array();
                foreach ($aFieldsValues as $aKey => $aField) {
                    if ( is_array($aField) or is_object($aField) or $aKey[0] == '_' ) { 
                        continue;
                    }
                    $array[$aKey] = $aField;
                }
                $XDatabase->_MDB2->loadModule('Extended');
                $res = $XDatabase->_MDB2->extended->autoExecute($aTable, $array, MDB2_AUTOQUERY_UPDATE, $aWhere, $aTypes);
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function queryMultiple($aQuery, $aData) 
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);

                $statement = $XDatabase->_MDB2->prepare($aQuery);
                if (!XDatabase::_checkDBResult($statement)) {
                    return false;
                }

                $XDatabase->_MDB2->loadModule('Extended');
                $res = $XDatabase->_MDB2->extended->executeMultiple($statement, $aData); 
                $statement->free();

                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function queryLimit(
            $aQuery,
            $aTypes,
            $aCount, 
            $aFrom = 0,
            $aResultClass = true)
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aQuery = $XDatabase->replacePrefix($aQuery);
                $XDatabase->_MDB2->loadModule('Extended');
                $res = $XDatabase->_MDB2->extended->limitQuery($aQuery, $aTypes, $aCount, $aFrom, $aResultClass);

                $res = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function prepare($aQuery, $aParam = null, $aTypes = null)
        {
            global $XDatabase;

            $res = null;

            return $res;	
        }

        public static function quote($aValue, $aType = null, $aQuote = true)
        {
            global $XDatabase;
            if (!XDatabase::isError()) {
                return $XDatabase->_MDB2->quote($aValue, $aType, $aQuote);
            }
        }

        public static function getFields($aTable) 
        {
            global $XDatabase;

            $res = null;

            if (!XDatabase::isError()) {
                $aTable = $XDatabase->replacePrefix($aTable);

                $XDatabase->_MDB2->loadModule('Manager', null, true);
                $res = $XDatabase->_MDB2->manager->listTableFields($aTable);

                XDatabase::_checkDBResult($res);
            }
            return $res;
        }

        public static function getVariable($aName = null)
        {
            $variables = array();
            if ( $res = XDatabase::getAll("SHOW VARIABLES;") ) {
                foreach ($res as $val) {
                    $variables[$val['variable_name']] = $val['value'];
                }
            }
            if (sizeof($variables) > 0) {
                if ($aName) {
                    return isset($variables[$aName]) ? $variables[$aName] : null;
                } else {
                    return $variables;
                }
            } else {
                return null;
            }
        }

        public static function getError($aIsPrint = false)
        {
            global $XDatabase;

            if ($aIsPrint) {
                if (PEAR::isError($XDatabase->_error)) {
                    return $XDatabase->_error->getMessage();
                } else {
                    return null;
                }
            } else {
                return $XDatabase->_error;
            }
        }
        public static function isError()
        {
            global $XDatabase;

            if ($XDatabase->_error) {
                return true;
            } else {
                return false;
            }
        }
        public static function resetError()
        {
            global $XDatabase;

            $XDatabase->_error = null;
        }


        public static function getObject() 
        {
            global $XDatabase;
            if (!$XDatabase or !isset($XDatabase->_MDB2)) {
                return null;
            } else {
                return $XDatabase->_MDB2;
            }
        }

        public static function setObject($aMDB2) 
        { 
            global $XDatabase;
            if (is_object($aMDB2) && is_subclass_of($aMDB2, 'MDB2_Driver_Common')) {
                $XDatabase->_MDB2 = $aMDB2;
                return true;
            } else {
                return false;
            }
        }
    }
?>