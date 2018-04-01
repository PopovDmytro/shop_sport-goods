<?php
/**
 * Class Prod2TechManager
 *
 * Entity of SiteEntities
 *
 * @author   petroved
 * @access   public
 * @created  Fri Nov 15 10:05:33 EET 2013
 */
class Prod2TechManager extends \RexFramework\DBManager
{
  public static $assemble = 'volley.standart';
  public static $version = '1.0';
  public static $needed = array(
      'RexFramework\DBManager:standart:1.0',
      'Prod2TechEntity:volley.standart:1.0',
      'TechnologyEntity:volley.standart:1.0',
      'RexShop\BrandEntity:shop.standart:1.0',
      'RexShop\BrandManager:shop.standart:1.0'
  );

	function __construct()
	{
		parent::__construct('prod2tech', 'id');
	}

	function getTechByProdId($id)
	{
		if (!is_numeric($id)) {
			return false;
		}
		$sql = 'SELECT *
			FROM technology
			WHERE id IN (
				SELECT
						technology_id
					FROM
						prod2tech
					WHERE
						product_id = '.$id.'
					)';
		$res = XDatabase::getAll($sql);
		if (!$res or sizeof($res) < 1) {
			return array();
		} else {
			return $res;
		}

	}

	function getProdTechnologiesByBrandId($id)
	{
		if (!is_numeric($id)) {
			return false;
		}
		$sql = 'SELECT
					technology_id
				FROM
					prod2tech
				WHERE
					product_id IN
					(SELECT id FROM product WHERE brand_id = '.$id.'
						)';
		$res = XDatabase::getAll($sql);
		if (!$res or sizeof($res) < 1) {
			return array();
		} else {
			return $res;
		}
	}

}