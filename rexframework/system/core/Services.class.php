<?php

define('SERVICES_TYPE_NUMERIC',			1);
define('SERVICES_TYPE_ALPHA',			2);
define('SERVICES_TYPE_ALPHANUMERIC',	3);
define('SERVICES_TYPE_ALPHAUP',			4);
define('SERVICES_TYPE_ALPHALOW',		5);
define('SERVICES_TYPE_SPECIAL',			6);
define('SERVICES_TYPE_ALL',				7);

/**
 * Class Services
 *
 * Services class with static methods
 *
 * @access   public
 * @package  XFramework
 */
class Services
{
	/**
	 * random
	 *
	 * Generate random string
	 *
	 * @author  fatal
	 * @class   Services
	 * @access  public
	 * @param   int     $aLenght  lenght string
	 * @return  string
	 */
	function random($aLenght = 8, $aType = SERVICES_TYPE_ALPHANUMERIC)
	{
		/**
		 * TYPE:
		 * any           = alphanumeric + special
		 * aplphanumeric = numeric + alphaAll
		 * alpha		 = alphaUp + alphaLow
		 */
		$arrChars  = array();
		$arrChars[SERVICES_TYPE_SPECIAL]  = array();
		$arrChars[SERVICES_TYPE_NUMERIC]  = array();
		$arrChars[SERVICES_TYPE_ALPHAUP]  = array();
		$arrChars[SERVICES_TYPE_ALPHALOW] = array();
		$_arrChars = array();
		
		for ($i = 32; $i < 48; $i++) {
			array_push($arrChars[SERVICES_TYPE_SPECIAL], chr($i)); // !"#.../
		}

		for ($i = 58; $i < 65; $i++) {
			array_push($arrChars[SERVICES_TYPE_SPECIAL], chr($i)); // :;<=>?@
		}

		for ($i = 91; $i < 97; $i++) {
			array_push($arrChars[SERVICES_TYPE_SPECIAL], chr($i)); // [\]^_`
		}

		for ($i = 48; $i < 58; $i++) {
			array_push($arrChars[SERVICES_TYPE_NUMERIC], chr($i)); // 0-9
		}
		
		for ($i = 65; $i < 91; $i++) {
			array_push($arrChars[SERVICES_TYPE_ALPHAUP], chr($i)); // A-Z
		}
		
		for ($i = 97; $i < 122; $i++) {
			array_push($arrChars[SERVICES_TYPE_ALPHALOW], chr($i)); // a-z
		}
		
		
		switch ($aType) {
			case SERVICES_TYPE_ALL:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHAUP]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHALOW]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_NUMERIC]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_SPECIAL]);
				break;
			case SERVICES_TYPE_ALPHANUMERIC:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHAUP]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHALOW]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_NUMERIC]);
				break;
			case SERVICES_TYPE_NUMERIC:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_NUMERIC]);
				break;
			case SERVICES_TYPE_ALPHA:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHAUP]);
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHALOW]);
				break;
			case SERVICES_TYPE_ALPHAUP:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHAUP]);
				break;
			case SERVICES_TYPE_ALPHALOW:
				$_arrChars = array_merge($_arrChars, $arrChars[SERVICES_TYPE_ALPHALOW]);
				break;
			default:
				break;
		}
		
		$max_elements = count($_arrChars) - 1;
		$new_pass = '';
		for ($i = 0; $i<$aLenght; $i++) { 
			// for version under 4.2.0
			//srand((double)microtime()*1000000);
			$new_pass .= $_arrChars[rand(0, $max_elements)];
		}
		return $new_pass;
	}
	
	/**
	 * getGenerateTime
	 *
	 * function_description
	 *
	 * @author  fatal
	 * @class   Services
	 * @access  public
	 * @global  $GenerateTime
	 * @param   bool $aPrint
	 * @return  string time
	 */
	function getGenerateTime($aComment = "", $aPrint = false)
	{
		global $GenerateTime;
		global $GenerateTimeString;
		
		$back = debug_backtrace();
		
		if (!isset($GenerateTime)) {
			list($msec, $sec) = explode(chr(32), microtime());
			$GenerateTime["start"]   = $sec + $msec;
			$GenerateTime["section"] = $sec + $msec;
			if ($aPrint) {
				print ("Execute time: " . sprintf("%01.4f", 0) ." sec. / " . sprintf("%01.4f", 0) . " sec. / (".$aComment.") / [".basename(@$back[0]["file"]).":".@$back[0]["line"]."]<br/>\n");
			} else {
				$GenerateTimeString = "Execute time: " . sprintf("%01.4f", 0) ." sec. / " . sprintf("%01.4f", 0) . " sec. / (".$aComment.") / [".basename(@$back[0]["file"]).":".@$back[0]["line"]."]<br/>\n";
				return true;
			}
		} else {
			$start = $GenerateTime["section"];
			list($msec, $sec) = explode(chr(32), microtime());
			$GenerateTime["section"] = $sec + $msec;

			$GenerateTimeString .= "Execute time: ".sprintf("%01.4f", round($GenerateTime["section"] - $GenerateTime["start"], 4)) ." sec. / ".
							sprintf("%01.4f", round($GenerateTime["section"] - $start, 4)) ." sec. / (".$aComment.") / [".basename(@$back[0]["file"]).":".@$back[0]["line"]."]<br/>\n";
			if ($aPrint) {			
				print $GenerateTimeString;
			} else {
				return $GenerateTimeString;
			}
		}
	}
	
	/**
	 * getMemoryUsage
	 *
	 * @author  fatal
	 * 
	 * @access  public
	 * 
	 * @return  rettype  return
	 */
	 function getMemoryUsage($aPrint = false) {
		if ( !function_exists('memory_get_usage') ) {
			if ( substr(PHP_OS,0,3) == 'WIN') {
				//if ( substr( PHP_OS, 0, 3 ) == 'WIN' );
				$output = array();
				exec( 'tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output );
				if ($aPrint) {
					print preg_replace( '/[\D]/', '', $output[5] ) * 1024;
				} else {
					return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
				}
			} else {
				$pid = getmypid();
				exec("ps -eo%mem,rss,pid | grep $pid", $output);
				$output = explode("  ", $output[0]);
				
				
				$memory = number_format(@$output[1] * 1024, 0, '.', ' ') . ' bytes';
				
				if ($aPrint) {
					print $memory;
				} else {
					return $memory;
				}
			}
		} else {			
			$memory = number_format(memory_get_usage(), 0, '.', ' ') . ' bytes';
			
			if ($aPrint) {
				print $memory;
			} else {
				return $memory;
			}
		}
	}
	
	/**
	 * MD5
	 *
	 * Generate MD5 value
	 *
	 * @class   
	 * @access  public
	 * @param   string     $value  
	 * @return  string  
	 */
	function MD5($value)
	{
		return md5($value);
	}
}

?>