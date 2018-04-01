<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class CommentManager
 *
 * Manager of Comment
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class CommentManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\CommentEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('comment', 'id');
	}
	
	function getLatest($aCount=2, $aProductID=false, $aUser=false)
	{
		if (!$aCount or $aCount < 1) {
			$aCount = 10;
		}
		
		if (!$aProductID or ($aProductID < 1 and $aProductID != 'all')) {
			return false;
		}

		if (!$aUser) {
			
			if ($aProductID == 'all') {
				$sql = 'SELECT c.*, u.* FROM `comment` AS c, `user` AS u WHERE c.`user_id` = u.`id` AND c.`status` = 2 ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aCount));				
			} else {
				$sql = 'SELECT c.*, u.* FROM `comment` AS c, `user` AS u WHERE c.`product_id` = ? AND c.`user_id` = u.`id` AND c.`status` = 2 ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aProductID, $aCount));
			}
		} else {
			
			if ($aProductID == 'all') {
				$sql = 'SELECT c.*, u.* FROM `comment` AS c, `user` AS u WHERE c.`user_id` = u.`id` AND (c.`status` = 2 OR (c.`status` = 1 AND c.`user_id` = ?)) ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aUser, $aCount));					
			} else {
				$sql = 'SELECT c.*, u.* FROM `comment` AS c, `user` AS u WHERE c.`product_id` = ? AND c.`user_id` = u.`id` AND (c.`status` = 2 OR (c.`status` = 1 AND c.`user_id` = ?)) ORDER BY c.`date_create` DESC LIMIT ?';
                $res = XDatabase::getAll($sql, array($aProductID, $aUser, $aCount));	
			}
		}

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
}