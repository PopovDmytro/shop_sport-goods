<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class Attr2CatManager
 *
 * Manager of Attr2Cat
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Attr2CatManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\Attr2CatEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('attr2cat', 'id');
	}
	
	function getNotAssigned($aCategoryID)
	{
		$sql = 'SELECT * FROM `attribute` WHERE (SELECT COUNT(*) FROM `attr2cat` WHERE (attr2cat.`attribute_id` = attribute.`id`) AND attr2cat.`category_id` = ?) < 1 AND attribute.`level` = 0 ORDER BY `gorder`';
		$res = XDatabase::getAll($sql, array($aCategoryID));
		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
    
    function getByWhereOrder($aWhere, $aOrder)
    {
        $sql = 'SELECT * FROM `attr2cat` WHERE '.$aWhere.' ORDER BY '.$aOrder;
        $res = XDatabase::getAll($sql);
        if (\PEAR::isError($res)) {
            $this->_error = $res;
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
    }
}