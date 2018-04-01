<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class Brand2CatManager
 *
 * Manager of Brand2Cat
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Brand2CatManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\Brand2CatEntity:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('brand2cat', 'id');
	}
	
	function getBrandsByCatList($aCategoryList)
	{
		$list = array();
		foreach ($aCategoryList as $category_id) {
			//get main categories tree
			$manager = RexFactory::manager('pCatalog');
			$manager->getUpList($category_id, RexFactory::entity('pCatalog'));
			$list = array_merge($list, array_reverse($manager->getCollection()));

		}		
		$list = array_unique($list); //all categories ID for product
		if (sizeof($list) > 0) {
			$list = implode(',', $list);
			$brandList = XDatabase::getAssoc('select brand_id, 1 from brand2cat where category_id in ('.$list.')');
			if ($brandList and sizeof($brandList) > 0) {
				
				$brandList = array_keys($brandList);
				$brandList = implode(',', $brandList);
				
				$brandManager = RexFactory::manager('brand');
				$brandManager->getByWhere('id in ('.$brandList.')');
				$brandList = $brandManager->getCollection('object');
				if ($brandList and sizeof($brandList) > 0) {
					return $brandList;
				}
			}
		}
		
		return array();
	}
}