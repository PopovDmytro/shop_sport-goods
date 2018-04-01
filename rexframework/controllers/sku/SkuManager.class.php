<?php

class SkuManager extends \RexShop\SkuManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'SkuEntity:volley.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0'
    );
    
    function __construct()
    {
        parent::__construct('sku', 'id');
    }
    
    public function getAtrListByColor($productID, $attr2prodID)
    {           
        $sql = 'SELECT 
                  se1.sku_id,
                  a2p.attribute_id,
                  (SELECT 
                    `name` 
                  FROM
                    attribute
                  WHERE id = a2p.attribute_id) AS `name`,
                  (SELECT 
                    `name` 
                  FROM
                    attribute
                  WHERE id = a2p.value) AS `value` 
                FROM
                  sku s 
                  INNER JOIN sku_element se 
                    ON s.`id` = se.`sku_id` 
                    AND se.`attr2prod_id` = '.$attr2prodID.' 
                  INNER JOIN sku_element se1 
                    ON s.`id` = se1.`sku_id` 
                    AND se1.`attr2prod_id` <> '.$attr2prodID.' 
                  INNER JOIN attr2prod a2p 
                    ON se1.`attr2prod_id` = a2p.id 
                WHERE s.product_id = '.$productID.' 
                AND s.`quantity` > 0 
                GROUP BY se1.id'; 
        return XDatabase::getAll($sql);
    }
}