<?php
namespace RexShop;

use \RexDisplay as RexDisplay;
use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class PCatalogManager
 *
 * Manager of PCatalog
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class PCatalogManager extends \RexFramework\CatalogManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\CatalogManager:standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
    );
    
	function __construct()
	{
		parent::__construct('pcatalog', 'id');
	}
	
	function getFilters($aCategoryID, $productsList = false)
	{
		//get all subcategories
		$this->getSubCategoriesList($aCategoryID, 10);
		$categoryList = $this->struct;
        
		if ($categoryList and sizeof($categoryList) > 0) {
			$categoryList[] = $aCategoryID;
		} else {
			$categoryList = array($aCategoryID);
		}

        if ($productsList === false || $productsList === null) {
		    $res = XDatabase::getAll('SELECT 1 AS `show`, attr2prod.`id`, `attribute_id`, `value`
                                        FROM `attr2prod`, `product`
                                        WHERE attr2prod.`product_id` = product.`id`
                                        AND product.`category_id` IN ('.implode(',', $categoryList).')');
        } else {
            $existList = XDatabase::getAll('SELECT attr2prod.`id`
                                            FROM `attr2prod`
                                            WHERE attr2prod.`product_id` IN ('.implode(',', $productsList).')'); 
            $res = XDatabase::getAll('SELECT attr2prod.`id`, `attribute_id`, `value`
                                            FROM `attr2prod`, `product`
                                            WHERE attr2prod.`product_id` = product.`id` AND product.`category_id` IN ('.implode(',', $categoryList).')');
            
            if (count($res) > 0 && count($existList) > 0) {
                
                foreach ($res as $key => $value) {
                    
                    foreach ($existList as $k => $v) {
                        
                        if ($v['id'] == $value['id']) {
                            $res[$key]['show'] = 1;
                        }
                            
                    }
                    
                }
                
            }
            
        }
        
		if (!$res or sizeof($res) < 1) {
			return false;
		}
        
		$uniqueAttributeIDList = array();
		
        foreach ($res as $value) {
			$uniqueAttributeIDList[$value['attribute_id']] = 1;
		}
        		
		if (!$uniqueAttributeIDList or sizeof($uniqueAttributeIDList) < 1) {
			return false;
		}		

		$attributeManager = RexFactory::manager('attribute');
		//$attributeManager->getByWhere('`id` IN ('.implode(',', array_keys($uniqueAttributeIDList)).') AND `filtered_form` = 1 ');//ORDER BY attribute.`gorder`
        
        $attributeManager->getByCategoriesSorderFilter($categoryList);
        /*if (!$productsList) {
            $attributeManager->getByCategoriesSorderFilter($categoryList);
        } else {
            $attributeManager->getByProductsSorderFilter($productsList);
        }*/
		
        if (!$attributeManager->_collection or sizeof($attributeManager->_collection) < 1) {
			return false;
		}
        
		$attributeList = $attributeManager->getCollection('object');
		//print_r($attributeList);
        $attr2ProdManager = RexFactory::manager('attr2Prod');
		//usort($attributeList, array($attr2ProdManager, '_sort'));
		RexDisplay::assign('attributeList', $attributeList);

		$attr2Prod = RexFactory::manager('attr2Prod');
		$attr2Prod->attr2prodList = $res;
        
		foreach ($attributeList as $attribute) {
			$attr2Prod->drawForm($attribute);
		}
		
		return $attr2Prod->fetched;
	}
	
	function getNameKeyList()
	{
		$sql = 'SELECT * FROM `'.$this->_table.'`';
		$res = XDatabase::getAll($sql);
		$new_res = array();
		foreach ($res as $row) {
			$new_res[$row['name']] = $row;
		}
		return $new_res;
	}
    
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
                                    WHERE p.level <> 0 
                                      AND p.id <> '.$aCategoryID);
        }
         
        return XDatabase::getAll('SELECT 
                                      p.id,
                                      p.`name`,
                                      (SELECT p1.name FROM pcatalog p1 WHERE p1.id = p.pid) AS pname
                                    FROM
                                      pcatalog p
                                    WHERE p.level <> 0 
                                      AND p.id <> '.$aCategoryID);
    }
    
    function getPcatalogsForProduct()
    {
        return XDatabase::getAll('SELECT pc1.*, COUNT(pc2.id) as count
                                    FROM pcatalog pc1 LEFT JOIN pcatalog pc2 ON pc1.id = pc2.pid
                                    GROUP BY pc1.id ORDER BY pc1.gorder ASC');
    }
}