<?php
class PCatalogManager extends \RexShop\PCatalogManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\CatalogManager:standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
    );
    
    function getAllCategoriesExcept($aCategoryID, $aProductID)
    {
        if ($aProductID) {
            return XDatabase::getAll('SELECT 
                                      p.id,
                                      p.`name`,
                                      (SELECT p1.name FROM pcatalog p1 WHERE p1.id = p.pid) AS pname,
                                      IF (pc.`product_id` IS NOT NULL, 1, 0) AS exist 
                                    FROM
                                      pcatalog p 
                                      LEFT JOIN prod2cat pc 
                                        ON pc.`product_id` = '.$aProductID.' 
                                        AND pc.`category_id` = p.id 
                                    WHERE p.id <> '.$aCategoryID);
        }
         
        return XDatabase::getAll('SELECT 
                                      p.id,
                                      p.`name`,
                                      (SELECT p1.name FROM pcatalog p1 WHERE p1.id = p.pid) AS pname
                                    FROM
                                      pcatalog p
                                    WHERE p.id <> '.$aCategoryID);
    }
    
}