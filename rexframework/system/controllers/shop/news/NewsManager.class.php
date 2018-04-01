<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class NewsManager
 *
 * Manager of News
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class NewsManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\NewsEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('news', 'id');
	}
	
	function getLatestNews($aCount=3)
	{
		if (!$aCount or $aCount < 1) {
			$aCount = 3;
		}

		$sql = 'SELECT * FROM `news` ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT ?';
		$res = XDatabase::getAll($sql, array($aCount));
		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
	
	function getArchive($aStart, $aCount)
	{
		$sql = 'SELECT * FROM `news` ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT '.$aStart.', '.$aCount;
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
}