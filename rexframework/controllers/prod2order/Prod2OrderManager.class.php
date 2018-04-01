<?php

class Prod2OrderManager extends \RexShop\Prod2OrderManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\Prod2OrderEntity:shop.standart:1.0',
        'RexShop\Prod2OrderManager:shop.standart:1.0',
    );
    
	function __construct()
	{
		parent::__construct('prod2order', 'id');
	}
	
    function getProductDiscount($productID)
    {
        $sql = 'SELECT sale FROM product WHERE id = '.intval($productID);
        return XDatabase::getOne($sql);
    }
}