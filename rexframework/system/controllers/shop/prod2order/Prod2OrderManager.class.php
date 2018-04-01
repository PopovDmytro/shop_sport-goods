<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class Prod2OrderManager
 *
 * Manager of Prod2Order
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Prod2OrderManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\Prod2OrderEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('prod2order', 'id');
	}
	
	function getProductList($aOrderID)
	{
		$sql = 'SELECT 
					* 
				FROM 
					`prod2order` AS p2o, `product` AS p 
				WHERE 
					p2o.`product_id` = p.`id` AND p2o.`order_id` = ?';
		$res = XDatabase::getAll($sql, array($aOrderID));

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
	
	function getProductPopularList()
	{
		$sql = 'SELECT `product_id`, COUNT(`product_id`) AS counter 
				FROM `prod2order` 
				GROUP BY `product_id` 
				ORDER BY `counter` DESC';
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}

    function getAttrByProduct($skuid)
    {
        $sql = 'SELECT 
          ske.`id`,
          ske.`attr2prod_id`,apr.`attribute_id`
        FROM
          sku 
          LEFT JOIN sku_element ske 
            ON sku.id = ske.`sku_id` 
          LEFT JOIN attr2prod apr 
            ON apr.id = ske.`attr2prod_id` 
        WHERE sku.id = '.$skuid;
        
        return XDatabase::getAll($sql);
    }
}