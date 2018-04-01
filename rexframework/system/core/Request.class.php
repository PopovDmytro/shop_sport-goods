<?php

define("REQUEST_TYPE_STRING",	1);
define("REQUEST_TYPE_INTEGER",	2);
define("REQUEST_TYPE_FLOAT",	3);
define("REQUEST_TYPE_ARRAY",	4);
define("REQUEST_TYPE_BOOL",		5);

/**
 * Class Request
 *
 * Request class with static methods
 *
 * @access   public
 * @package  XFramework
 * @version  0.1
 */

class Request extends RexObject
{
	 /**
	  * getPost
	  *
	  * Get POST var
	  *
	  * @class   Request
	  * @access  public
	  * @param  string $aKey Variable name
	  * @param  string $aType Variable type
	  * @return  mixed
	  */
	 public static function getPost($aKey, $aType = null)
	 {
	 	if (array_key_exists($aKey, $_POST)) {
	 		return Request::_checkType($_POST[$aKey], $aType);
	 	}
	 	return false;
	 }

	 /**
	  * getGet
	  *
	  * Get GET var
	  *
	  * @class   Request
	  * @access  public
	  * @param  string $aKey Variable name
	  * @param  string $aType Variable type
	  * @return  mixed
	  */
	 public static function getGet($aKey, $aType = null)
	 {
	 	if (array_key_exists($aKey, $_GET)) {
	 		return Request::_checkType($_GET[$aKey], $aType);
	 	}
	 	return false;
	 }

     public static function set($aKey, $aValue)
     {
         $_POST[$aKey] = $aValue;
     }

	 /**
	  * get
	  *
	  * Get POST and GET var
	  *
	  * @class   Request
	  * @access  public
	  * @param  string $aKey Variable name
	  * @param  string $aType Variable type
	  * @param  mixed  $aDefault Default variable value
	  * @return  mixed
	  */
	 public static function get($aKey, $aDefault = null, $aType = null)
	 {
        //return isset($_GET[$aKey]) ? $_GET[$aKey] : (isset($_POST[$aKey]) ? $_POST[$aKey] : $aDefault);
        return isset($_GET[$aKey]) ? $_GET[$aKey] : (isset($_POST[$aKey]) ? $_POST[$aKey] : (isset($_REQUEST[$aKey]) ? $_REQUEST[$aKey] :$aDefault));
	 	/*$res = false;

    	$res = Request::getPost($aKey, $aType);

    	if ($res === false)
    		$res = Request::getGet($aKey, $aType);

    	if ($res === false)
    		$res = $aDefault;

    	return $res;*/
	 }

	 /**
	  * getAll
	  *
	  * Return all GET and POST data
	  *
	  * @class   Request
	  * @access  public
	  * @return  array
	  */
	 public static function getAll()
	 {
	 	return array_merge($_GET, $_POST);
	 }

	 /**
	  * getFile
	  *
	  * Return temporary and real file name
	  *
	  * @class   Request
	  * @access  public
	  * @param   string $aKey Variable name
	  * @return  mixed
	  */
	 public static function getFile($aKey)
	 {
	 	if (isset($_FILES[$aKey]))
    	 	return $_FILES[$aKey];
	 }

	 /**
	  * getAllFiles
	  *
	  * Return temporary and real file name
	  *
	  * @class   Request
	  * @access  public
	  * @param   string $aKey Variable name
	  * @return  mixed
	  */
	 public static function getAllFiles()
	 {
	 	if (isset($_FILES))
    	 	return $_FILES;
	 }

	 /**
	  * _checkType
	  *
	  * check variable type
	  *
	  * @author  fatal
	  * @class   Request
	  * @access  public
	  * @param   mixed		$var   variable
	  * @param   string     $aType  variable type
	  * @return  bool
	  */
	 public static function _checkType($aVar, $aType = null)
	 {
	 	// switch statement for $aType
	 	switch ($aType) {
	 		case REQUEST_TYPE_STRING:
	 			if (!is_string($aVar)) {
	 				return false;
	 			} else {
	 				return (string) $aVar;
	 			}
	 			break;

	 		case REQUEST_TYPE_INTEGER:
	 			if (!preg_match("/^-?[0-9]+$/", $aVar)) {
	 				return false;
	 			} else {
	 				return (int) $aVar;
	 			}
	 			break;

	 		case REQUEST_TYPE_FLOAT:
	 			if (!preg_match("/^-?[0-9]+(\.[0-9]+)?$/", $aVar)) {
	 				return false;
	 			} else {
	 				return (float) $aVar;
	 			}
	 			break;

	 		case REQUEST_TYPE_ARRAY:
	 			if (!is_array($aVar)) {
	 				return false;
	 			} else {
	 				return $aVar;
	 			}
	 		case REQUEST_TYPE_BOOL:
	 			return (bool) $aVar;

	 		default:
	 			return $aVar;
	 	}
	 }
}

?>