<?php
/**
 * Class Brand2TechManager
 *
 * Entity of SiteEntities
 *
 * @author   petroved
 * @access   public
 * @created  Thu Nov 14 10:05:33 EET 2013
 */
class Brand2TechManager extends \RexFramework\DBManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'Brand2TechEntity:volley.standart:1.0',
        'TechnologyEntity:volley.standart:1.0',
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0'
    );

	function __construct()
	{
		parent::__construct('brand2tech', 'id');
	}

	function getBrandsByTechnologyId($id)
	{
		if (!is_numeric($id)) {
			return false;
		}
		$id = intval($id);
		$sql = 'SELECT
					brand_id
				FROM
					brand2tech
				WHERE
					technology_id = '.$id;
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			return array();
		} else {
			return $res;
		}
	}

	function getTechnologiesByBrand($brand_id)
	{
		if(!is_numeric($brand_id)) {
			return false;
		}
		$brand_id = intval($brand_id);
		if ($brand_id) {
            return XDatabase::getAll('SELECT name, id
            												FROM technology
            												WHERE id IN (
            													SELECT
	                                      technology_id
	                                    FROM
	                                      brand2tech
	                                    WHERE brand_id = '.$brand_id.')'
            												);
        }
    return false;
  }

}