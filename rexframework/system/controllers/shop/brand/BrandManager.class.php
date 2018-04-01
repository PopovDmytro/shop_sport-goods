<?php
namespace RexShop;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

/**
 * Class BrandManager
 *
 * Manager of Brand
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class BrandManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\DBManager:standart:1.0',
        'RexShop\BrandEntity:shop.standart:1.0',
    );
    
	function __construct()
	{
		parent::__construct('brand', 'id');
	}
	
	function getByCategories($aCategoryList, $aStart, $aCount)
	{
		$aCategoryList = implode(',', $aCategoryList);
		$sql = 'SELECT 
					brand.*
				FROM 
					`brand2cat`,
					`brand`
				WHERE 
					brand2cat.`category_id` IN ('.$aCategoryList.') AND 
					brand2cat.`brand_id` = brand.`id` 
				GROUP BY brand.`id`
				LIMIT '.intval($aStart).', '.intval($aCount);
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;
		}
	}
	
	function getByShops()
	{
		$sql = 'SELECT 
					brand.*
				FROM 
					`shop2brand`,
					`brand`
				WHERE 
					brand.`id` = shop2brand.`brand_id` 
				GROUP BY brand.`name` ASC';
//				limit '.intval($aStart).', '.intval($aCount);
		$res = XDatabase::getAll($sql);
		
		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;
		}
	}
	
	function getAllBrands()
	{
		$sql = 'SELECT 
					`id`, `name`
				FROM 
					`brand`
				GROUP BY brand.`id`';
		$res = XDatabase::getAll($sql);
		
		if (!$res or sizeof($res) < 1) {
			return array();
		} else {
			return $res;
		}
	}
	
	function getByCategoriesCount($aCategoryList)
	{
		$aCategoryList = implode(',', $aCategoryList);
		$sql = 'SELECT COUNT(DISTINCT brand.`id`) FROM `brand`, `brand2cat` WHERE brand2cat.`category_id` IN ('.$aCategoryList.') AND brand2cat.`brand_id` = brand.`id`';
		$res = XDatabase::getOne($sql);
		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;			
		} else {
			$this->_count = $res;
		}
		return $this->_count;
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
}