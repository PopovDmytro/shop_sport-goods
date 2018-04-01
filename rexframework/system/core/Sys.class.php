<?php

    /**
    * Class Sys
    *
    * Class with system useful functions
    *
    * @access   public
    * @package  XFramework
    * @version  0.1
    */
    class Sys
    {
        static $entities = array();

        /**
        * Function for dump variables
        *
        * @access public
        */
        public static function dump()
        {
            include_once ('dBug.class.php');

            $id = rand();

            $init = debug_backtrace();

            $msg = "";
            $lvl = sizeof($init);
            if ($lvl >= 1) {
                //	 		$msg .= "&nbsp;&nbsp;was called from :<BR>";
                for ($k = $lvl-1; $k >= 0; $k--) {
                    $msg .= "&nbsp;[#".$k."] ";

                    if (isset($init[$k]["class"]))
                        $msg .= @$init[$k]["class"]."::";

                    if (isset($init[$k]["function"]))
                        $msg .= @$init[$k]["function"]."() called at ";

                    if (isset($init[$k]["file"]) && $init[$k]["line"]) {
                        $msg .= "[/".basename(@$init[$k]["file"]).":".@$init[$k]["line"]."]<BR>";
                    }
                }
            }

            // get variable name
            $arrFile = $init[0];
            $arrLines = file($arrFile["file"]);
            $code = $arrLines[($arrFile["line"]-1)];

            //find call to Sys::dump
            preg_match('/\bSys::dump\s*\(\s*(.+)\s*\);/i', $code, $arrMatches);

            echo	"<div class='debug'>
            <ul>
            <li id=\"".$id."\">".
            "<a name=\"".$id."\"></a><a onClick=\"javascript: show_item(".$id.");\" href=\"#".$id."\">".
            "<b>Dump was called: </b><br/>".
            $msg .
            "<br/>".
            "<b>Variables: </b> ".
            @$arrMatches[1] .
            "<br/>".
            "</a>".
            "<ul>\n";

            foreach (func_get_args() as $var) {
                echo "<li>";

                new dBug($var);

                echo "</li>\n";
            }
            echo	"</ul></li></ul></div><HR>";
        }

        /**
        * Function for dump variables and die
        *
        * @access public
        */
        public static function dumpd()
        {
            $args = func_get_args();
            call_user_func_array(array(__CLASS__, 'dump'), $args);
            die();
        }

        /**
        * getCookie
        *
        * Get cookie var
        *
        * @class   System
        * @access  public
        * @param   string     $aKey  Cookie key
        * @return  mixed
        */
        public static function getCookie($aKey)
        {
            $res = null;
            if (isset($_COOKIE[$aKey])) $res = $_COOKIE[$aKey];
            return $res;
        }

        /**
        * setCookie
        *
        * Sets cookie var
        *
        * @class
        * @access  public
        * @param   string    $aName   Var name
        * @param   mixed     $aValue  Var value
        * @param   integer   $aExpire expire time
        * @param   string    $aPath
        * @param   string    $aDomain
        * @param   bool      $aSecure
        * @return  void
        */
        public static function setCookie($aName, $aValue = null, $aExpire = null, $aPath = null, $aDomain = null, $aSecure = null)
        {
            setcookie($aName, $aValue, $aExpire, $aPath, $aDomain, $aSecure);
        }

        /**
        * inludeClassesRec
        *
        * Include classes recursively from direcory
        *
        * @class   Sys
        * @access  public
        * @param   string     $path
        * @param   bool       $debug
        * @param   array      $exclude
        * @return  void
        */
        public static function inludeClassesRec($path, $debug = false, $exclude = null, $base_dir = '', $concurent_dir = '')
        {
            if ($base_dir || $concurent_dir) {
                $base_dir = realpath($base_dir);
                $concurent_dir = realpath($concurent_dir);
            }

            if (!file_exists($path)) {
                return;
            }
            if (!is_dir($path)) {
                die("Can't include classes from '$path' because it is not a directory.");
            }

            $dir  = dir($path);

            // load all components from folder
            while (($entry = $dir->read()) !== false) {

                if (!$exclude) {
                    $exclude = array();
                }

                if (is_dir($path.DIRECTORY_SEPARATOR.$entry) && ($entry != ".") && ($entry != "..") && (!in_array($entry, $exclude))) {

                    Sys::inludeClassesRec($path.DIRECTORY_SEPARATOR.$entry, $debug, $exclude, $base_dir, $concurent_dir);
                }
            }
            Sys::includeClasses($path, $debug, $base_dir, $concurent_dir);
        }

        /**
        * includeClasses
        *
        * Include all classes from directory
        *
        * @class   System
        * @access  public
        * @param   string     $dir  Directory
        * @param   bool       $debug  Debug option
        * @return  bool       true if success
        */
        public static function includeClasses($dir, $debug = false, $base_dir = '', $concurent_dir = '')
        {
            if (!$base_dir) {
                $base_dir = $dir;
            }
            $base_dir = realpath($base_dir);
            $concurent_dir = realpath($concurent_dir);

            if (is_dir($dir)) {
                if (file_exists($dir."/include.php")) {
                    if ($debug) {
                        echo "'include.php' was founded in '$dir'. loading classes from this file...";
                    }

                    require_once($dir."/include.php");

                    if ($debug) {
                        echo "OK<br>";
                    }
                }

                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (substr($file, -10) == '.class.php') {
                            if ($debug) {
                                echo "including '".$dir."/".$file."'... ";
                            }

                            $path_class = substr(realpath($dir.'/'.$file), strlen($base_dir));
                            if (!$concurent_dir || !is_file($concurent_dir.$path_class) && $file != 'Sys.class.php') {
                                require_once($dir."/".$file);

                                if (substr($file, -16) == 'Entity.class.php') {
                                    $entity_name = substr($file, 0, -16);
                                    if ($entity_name && substr($entity_name, -2) != 'DB') {
                                    	$class_name = $entity_name.'Entity';
                                        static::$entities[] = $class_name;
                                    	if (is_subclass_of($class_name, 'RexDBEntity')) {
                                    		$class_name::setClass($class_name);
                                    	}
                                    }
                                }

                                if ($debug) {Sys::dump(1);
                                    echo "OK<br>";
                                }
                            }
                        }
                    }
                    closedir($dh);
                }
            } else {
                return false;
            }

            return true;
        }

        public static function includeRexClasses()
        {
            if (defined('EXTERNAL')) {
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/system', false, array('.svn'), APPLICATION_ROOT, EXTERNAL_ROOT);
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/core', false, array('.svn'), APPLICATION_ROOT, EXTERNAL_ROOT);
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/controllers/rexframework', false, array('.svn'),
                    APPLICATION_ROOT.'classes/controllers/rexframework',
                    EXTERNAL_ROOT.'classes/controllers');
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/controllers/custom', false, array('.svn'),
                    APPLICATION_ROOT.'classes/controllers/custom',
                    EXTERNAL_ROOT.'classes/controllers');
                if (defined('EXTERNAL')) {
                    Sys::inludeClassesRec(EXTERNAL_ROOT.'classes/system', false, array('.svn'));
                    Sys::inludeClassesRec(EXTERNAL_ROOT.'classes/core', false, array('.svn'));
                    Sys::inludeClassesRec(EXTERNAL_ROOT.'classes/controllers', false, array('.svn'));
                }
            } else {
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/system', false, array('.svn'));
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/core', false, array('.svn'));
                Sys::inludeClassesRec(APPLICATION_ROOT.'classes/controllers', false, array('.svn'));
            }
        }

        /**
        * Init RexDBEntity field specs
        */
        public static function initEntitiesFieldSpec()
        {
            $check_struct = array();
            foreach (Sys::$entities as $entity_name) {
                if (class_exists($entity_name)) {
                    $entity = new $entity_name();
                    if (defined('REXDB_CHECK_STRUCT_ON_STARTUP') && REXDB_CHECK_STRUCT_ON_STARTUP && $entity instanceof RexDBEntity) {
                        $check_struct[] = $entity;
                    }
                } else {
                    throw new Exception('Check "'.$entity_name.'", that filename and entity name are the same');
                }
            }
            foreach ($check_struct as $entity) {
                $entity::checkDbStruct();
            }
        }
        
        static function show404error($redirectUrl = false, $setHeader = true)
        {
            if ($setHeader) {
                header('HTTP/1.1 404 Not Found');
            }
            if ($redirectUrl !== false and strlen(trim($redirectUrl)) > 0) {
                header( 'Location: '.$redirectUrl ) ;
                exit;
            }
        }
    }
?>