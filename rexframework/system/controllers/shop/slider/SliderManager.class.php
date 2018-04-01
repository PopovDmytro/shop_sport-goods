<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class SliderManager
 *
 * Manager of Slider
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SliderManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\SliderEntity:shop.standart:1.0'
    );

	function __construct()
	{
		parent::__construct('slider', 'id');
	}

	function getMainSlider()
	{
		$sql = 'SELECT * FROM `slider` WHERE `showbanner` = 1 ORDER BY `sorder` DESC ';
		$res = XDatabase::getAll($sql);
		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}

	function getLatestSliders($aCount=3)
	{
		if (!$aCount or $aCount < 1) {
			$aCount = 3;
		}

		$sql = 'SELECT * FROM `slider` ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT ?';
		$res = XDatabase::getAll($sql, array($aCount));
		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}

	function getArchive($aStart, $aCount)
	{
		$sql = 'SELECT * FROM `slider` ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT '.$aStart.', '.$aCount;
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
}