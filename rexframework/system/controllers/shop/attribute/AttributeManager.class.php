<?php
namespace RexShop;

use \XDatabase as XDatabase;

/**
 * Class AttributeManager
 *
 * Manager of Attribute
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class AttributeManager extends \RexFramework\CatalogManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\CatalogManager:standart:1.0',
        'RexShop\AttributeEntity:shop.standart:1.0'
    );
    
	/**
	 * Constructor of AttributeManager
	 *
	 * @access  public
	 */
	function __construct()
	{
		parent::__construct('attribute', 'id');
	}
    
    function getByCategoriesSorderFilter($categories)
    {
        $sql = 'SELECT DISTINCT
                    a.* 
                FROM
                    `pcatalog` pc 
                    INNER JOIN `attr2cat` ac 
                        ON ac.`category_id` = pc.`id` 
                        OR ac.`category_id` = pc.`pid` 
                    INNER JOIN `attribute` a 
                        ON a.`id` = ac.`attribute_id` 
                WHERE pc.`id` IN ('.implode(',', $categories).') AND `filtered_form` = 1 
                ORDER BY ac.`sorder` ASC';
        //echo $sql;exit;
        $res = XDatabase::getAll($sql);
        if (\PEAR::isError($res)) {
            $this->_error = $res;
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
    }
    
    function getByProductsSorderFilter($products)
    {
        $sql = 'SELECT DISTINCT
                    a.* 
                FROM
                    `attribute` a 
                    INNER JOIN `attr2prod` ap 
                        ON ap.`attribute_id` = a.`id` 
                        AND ap.`product_id` IN ('.implode(',', $products).') 
                    INNER JOIN `product` p 
                        ON p.`id` = ap.`product_id` 
                    LEFT JOIN `pcatalog` pc 
                        ON pc.`id` = p.`category_id` 
                    INNER JOIN `attr2cat` ac 
                        ON (ac.`category_id` = p.`category_id` 
                        OR ac.`category_id` = pc.`pid`)
                        AND ac.`attribute_id` = ap.`attribute_id` 
                WHERE `filtered_form` = 1 
                ORDER BY ac.`sorder` ASC';
        echo $sql;exit;
        $res = XDatabase::getAll($sql);
        if (\PEAR::isError($res)) {
            $this->_error = $res;
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
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

    function getGenderName($product)
    {
        $sql = 'SELECT 
                  a.name 
                FROM
                  attribute a 
                  LEFT JOIN attr2prod a2p 
                    ON a2p.`value` = a.`id` 
                WHERE a2p.`product_id` = '.$product.' 
                  AND a2p.`attribute_id` = 145';
        $res = XDatabase::getAll($sql);
       
        return $res;
    }
}