<?php
/**
 * Class TechnologyManager
 *
 * Manager of Technology
 *
 * @author   petroved
 * @access   public
 * @created  Thu Nov 14 10:05:33 EET 2013
 */
class TechnologyManager extends \RexFramework\DBManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'TechnologyEntity:volley.standart:1.0'
    );

	function __construct()
	{
		parent::__construct('technology', 'id');
	}

	// function getByBrands($aBrandList, $aStart, $aCount)
	// {
	// 	$aBrandList = implode(',', $aBrandList);
	// 	$sql = 'SELECT
	// 				technology.*
	// 			FROM
	// 				`brand2tech`,
	// 				`technology`
	// 			WHERE
	// 				brand2tech.`brand_id` IN ('.$aBrandList.') AND
	// 				brand2tech.`technology_id` = technology.`id`
	// 			GROUP BY technology.`id`
	// 			LIMIT '.intval($aStart).', '.intval($aCount);
	// 	$res = XDatabase::getAll($sql);

	// 	if (!$res or sizeof($res) < 1) {
	// 		$this->_collection = array();
	// 	} else {
	// 		$this->_collection = $res;
	// 	}
	// }

	// function getAllTechnologies()
	// {
	// 	$sql = 'SELECT
	// 				`id`, `name`
	// 			FROM
	// 				`technology`
	// 			GROUP BY technology.`id`';
	// 	$res = XDatabase::getAll($sql);

	// 	if (!$res or sizeof($res) < 1) {
	// 		return array();
	// 	} else {
	// 		return $res;
	// 	}
	// }

}