<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \PEAR as PEAR;
use \PHPExcel as PHPExcel;
use \PHPExcel_Writer_Excel5 as PHPExcel_Writer_Excel5;
use \PHPExcel_Style_Alignment as PHPExcel_Style_Alignment;
use \PHPExcel_Style_Border as PHPExcel_Style_Border;
use RexSettings as RexSettings;

/**
 * Class ProductManager
 *
 * Manager of Product
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ProductManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\AttributeEntity:shop.standart:1.0'
    );

	var $images;
	var $categories;
	var $brands;
    var $_count;
    var $_productListIDs;

	function __construct()
	{
		parent::__construct('product', 'id');
	}

    public function getList($filters, $fields, $mod = false)
    {
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = '';
        $sql_sku = '';

        $mod_arr = explode('\\', get_class($this));
        if (!$mod) {
            $mod = array_pop($mod_arr);
            if (substr($mod, -7) == 'Manager') {
                $mod = substr($mod, 0, -7);
            }
            $mod = lcfirst($mod);
        }

        $entity = RexFactory::entity($mod, false);
        $manager = RexFactory::manager($mod);
        $order_by = $manager->_uid;

        foreach ($filters as $key => $value) {
            $result = $manager->_processFilter($key, $value);

            if ($result !== false) {
                if (!is_array($result))
                    $sql .= $result/*.")"*/;
                else {
                    $sql .= $result[0];
                    $sql_join .= $result[1];
                }
            } else {
                switch ($key) {
                    case 'page':
                        $page = $value;
                        break;
                    case 'inpage':
                        $inpage = $value;
                        break;
                    case 'order_by':
                        $order_by = $value;
                        break;
                    case 'order_dir':
                        $order_dir = $value;
                        break;
                    case 'search':
                        $value = trim($value);
                        if ($value) {
                            $sql_sku = 'LEFT JOIN sku s ON
                        				t.`id` = s.`product_id`';
                            $sql .= ' AND (t.name LIKE "%' . addslashes($value) . '%"
                            		OR	`sku_article` LIKE "%' . addslashes($value) . '%")';
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`'.$key.'` = "'.addslashes($value).'"';
                        }
                }
            }
        }

        $sql_limit =' ORDER BY `t`.`'.$order_by.'` '.$order_dir.'
        LIMIT '.($page * $inpage - $inpage).', '.$inpage.'  ;';

        $sql = 'FROM '.$manager->_table.' AS `t` '.$sql_sku.' '.$sql_join.' WHERE '.$sql;

        return array(
            0 => XDatabase::getAll('SELECT DISTINCT t.* '.$sql.$sql_limit),
            1 => count(XDatabase::getAll('SELECT DISTINCT t.* '.$sql))
        );
    }

    function _processFilter($key, $value)
    {
        $sql = '';
        $join = '';

        if ($key == 'filter' && $value) {
            switch ($value) {
                case 'noimg':
                    $join .= ' LEFT JOIN `pimage` pim ON t.`id` = pim.`product_id`';
                    $sql .= ' AND ISNULL(pim.`id`)';
                    break;
                case 'noattr':
                    $join .= ' LEFT JOIN `attr2prod` ap ON t.`id` = ap.`product_id`';
                    $sql .= ' AND ISNULL(ap.`id`)';
                    break;
                case 'noactive':
                    $sql .= '  AND `t`.active = 2';
                    break;
                case 'top':
                    $sql .= ' AND `t`.bestseller = 1';
                    break;
                case 'new':
                    $sql .= ' AND `t`.new = 1';
                    break;
                case 'sale':
                    $sql .= ' AND `t`.event = 1';
                    break;
                case 'active':
                    $sql .= ' AND `t`.active = 1';
                default :
                    $sql .= ' AND 1=1 ';
                    break;
            }

            return array($sql, $join);
        } elseif ($key == 'pcatalog' && $value) {
            $manager = RexFactory::manager('pCatalog');
            $manager->getSubCategoriesList(intval($value), 3);
            $categoryList = $manager->struct;
            if ($categoryList and sizeof($categoryList) > 0) {
                $manager->getByWhere('id in ('.implode(',', $categoryList).') order by gorder');
                $fullList = $categoryList;
                $fullList[] = $value;
            } else {
                $fullList = array($value);
            }

            $sql .= ' AND `t`.`category_id` IN ('.implode(',', $fullList).')';

            return $sql;
        } elseif ($key == 'id' && $value) {
            return ' OR `t`.`id` = '.$value;
        }

        return false;
    }

	function getLatest($aCount)
	{
		$sql = 'SELECT
                    p.*,
                    p1.`id` AS image_id,
                    p1.`image` AS image_ext
                FROM
                    `product` AS p
                LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
				WHERE
                    ISNULL(p2.`id`) AND
					p.`active` = 1
				GROUP BY p.`id`
				ORDER BY p.`price` ASC
				LIMIT '.intval($aCount);
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByCategory($aCategory, $aStart, $aCount)
	{
		$sql = 'SELECT
                    p.*,
                    p1.`id` AS image_id,
                    p1.`image` AS image_ext
                FROM
                    `product` AS p
                LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
				WHERE
                    ISNULL(p2.`id`) AND
					p.`category_id` = ?
					p.`active` = 1
				GROUP BY p.`id`
				ORDER BY p.`price` ASC
				LIMIT '.intval($aStart).', '.intval($aCount);
		$res = XDatabase::getAll($sql, array($aCategory));

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;
			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByCategoryList($aCategoryList, $aStart, $aCount, $aBrandID=false, $aAttributeID=false, $aAttributeValue=false)
	{
		if ($aBrandID !== false) {
			$aBrandID = 'AND `brand_id` = '.$aBrandID;
		} else {
			$aBrandID = '';
		}

		if ($aAttributeID !== false) {
			$sql = 'SELECT
						p.*,
                        p1.`id` AS image_id,
                        p1.`image` AS image_ext
					FROM
						`attr2prod`, `product` AS p
					LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
					WHERE
                        ISNULL(p2.`id`) AND
						p.`category_id` IN ('.implode(',', $aCategoryList).') AND
						p.`active`=1 AND

						p.`id` = attr2prod.`product_id` AND
						attr2prod.`attribute_id` = ? AND
						attr2prod.`value` = ?

						'.$aBrandID.'


					GROUP BY p.`id`
					ORDER BY p.`price` ASC
					LIMIT '.intval($aStart).', '.intval($aCount);
			$res = XDatabase::getAll($sql, array($aAttributeID, $aAttributeValue));
		} else {
			$sql = 'SELECT
                        p.*,
                        p1.`id` AS image_id,
                        p1.`image` AS image_ext
                    FROM
                        `product` AS p
                    LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                    WHERE
                        ISNULL(p2.`id`) AND
                        p.`category_id` IN ('.implode(',', $aCategoryList).') AND
                        p.`active` = 1
                        '.$aBrandID.'
                        ORDER BY p.`price` ASC
                    LIMIT '.intval($aStart).', '.intval($aCount);
            $res = XDatabase::getAll($sql);
		}

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';

				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByYML($aCategoryList, $aStart, $aCount)
	{

			$sql = 'SELECT
                        p.*,
                        p1.`id` AS image_id,
                        p1.`image` AS image_ext
                    FROM
                        `product` AS p
                    LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                    WHERE
                        ISNULL(p2.`id`) AND
						p.`category_id` IN ('.implode(',', $aCategoryList).') AND
						p.`active` = 1 AND
						`yml` > 0
					GROUP BY p.`id`
					LIMIT '.intval($aStart).', '.intval($aCount);
			$res = XDatabase::getAll($sql);


		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByFeaturedList($aCategoryList, $aStart, $aCount, $aFeaturedField)
	{
		if (!$aCategoryList) {
			$aCategoryList = '';
		} else {
			$aCategoryList = 'AND p.`category_id` IN ('.implode(',', $aCategoryList).')';
		}
			/*if($aFeaturedField == 'homepage')
				$orderSql = 'order by product.price asc';
			else*/
				$orderSql = 'ORDER BY RAND()';
			$sql = 'SELECT
                        p.*,
                        p1.`id` AS image_id,
                        p1.`image` AS image_ext,
                        (SELECT
                          GROUP_CONCAT(
                            at.name
                            ORDER BY at.id DESC SEPARATOR ", "
                          )
                        FROM
                          attr2prod a2p
                          INNER JOIN attribute `at`
                            ON a2p.`value` = at.id
                        WHERE a2p.product_id = p.id
                          AND a2p.`attribute_id` = 145
                        GROUP BY a2p.`attribute_id`) AS sex
                    FROM
                        `product` AS p
                    LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                    WHERE
                        ISNULL(p2.`id`) AND
						p.`active` = 1

						'.$aCategoryList.'
						AND '.$aFeaturedField.' > 0

					GROUP BY p.`id`
					'.$orderSql.'
					LIMIT '.intval($aStart).', '.intval($aCount);
                    //var_dump($sql);exit;
			$res = XDatabase::getAll($sql);
			
		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getCompare($aCompare)
	{
		foreach ($aCompare as $info) {
            $sql = 'SELECT
                      der.*,
                      GROUP_CONCAT(
                        se.`attr2prod_id`
                        ORDER BY se.`attr2prod_id` ASC SEPARATOR ","
                      ) AS sku_line
                    FROM
                      (SELECT
                        p.id AS id,
                        p.category_id as category_id,
                        p.name as name,
                        p.content as content,
                        p.price*(1-p.sale/100) AS price,
                        s.id AS sku_id,
                        s.`price`*(1-p.sale/100) AS sku_price,
                        s.`quantity` AS sku_quantity,
                        pc.alias AS pcatalog_alias,
                        COALESCE(pm.id, pmg.id, pmg2.id) AS image_id,
                        COALESCE(pm.image, pmg.image, pmg2.image) AS image_ext
                      FROM
                        product p
                        LEFT JOIN pcatalog pc
                          ON p.`category_id` = pc.id
                        LEFT JOIN sku s
                          ON s.id = '.$info['sid'].'
                        LEFT JOIN sku_element se
                          ON s.id = se.`sku_id`
                        LEFT JOIN pimage pm
                          ON pm.product_id = p.id
                          AND IF (
                            se.`attr2prod_id` IS NOT NULL,
                            pm.`attribute_id` = se.`attr2prod_id`,
                            1
                          )
                        LEFT JOIN pimage pmg
                          ON pmg.product_id = p.id
                          AND pmg.attribute_id = 0
                          LEFT JOIN pimage pmg2 ON pmg2.id = (SELECT pi.id FROM pimage `pi` WHERE p.id = pi.product_id ORDER BY pi.sorder ASC LIMIT 1)
                      WHERE p.id = '.$info['pid'].'
                      GROUP BY se.`sku_id`
                      ORDER BY IF (
                          pm.`attribute_id` IS NOT NULL,
                          pm.`attribute_id`,
                          pmg.sorder
                        ) DESC
                      LIMIT 1) AS der
                      LEFT JOIN sku_element se
                        ON der.sku_id = se.sku_id';
            $res = XDatabase::getAll($sql);
            $answer[] = $res[0];
        }


		if (!$answer or sizeof($answer) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $answer;

			/*foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}*/
		}
	}

	function getByPrice($aCategoryList, $aStart, $aCount, $aPrice)
	{
		if (!$aCategoryList) {
			$aCategoryList = '';
		} else {
			$aCategoryList = 'AND p.`category_id` IN ('.implode(',', $aCategoryList).')';
		}
		$sql = 'SELECT
                    p.*,
                    p1.`id` AS image_id,
                    p1.`image` AS image_ext
                FROM
                    `product` AS p
                LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                WHERE
                    ISNULL(p2.`id`) AND
					p.`active` = 1
					'.$aCategoryList.'
					AND '.$aPrice.'
				GROUP BY p.`id`
				ORDER BY p.`price` ASC
				LIMIT '.intval($aStart).', '.intval($aCount);
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getCountByPrice($aCategoryList, $aPrice)
	{

		if (!$aCategoryList) {
			$aCategoryList = '';
		} else {
			$aCategoryList = 'AND product.`category_id` IN ('.implode(',', $aCategoryList).')';
		}

		$sql = 'SELECT COUNT(DISTINCT product.`id`) FROM `product` WHERE
		product.`active` = 1
		'.$aCategoryList.'
		AND '.$aPrice.'
		';
		$res = XDatabase::getOne($sql);

		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res;
		}
		return $this->_count;
	}

	function getByCategorySearch($aStart, $aCount, $aQuery, $aSku, $aCategory)
	{
	/*  $sql_category = 'INNER JOIN pcatalog c ON p.category_id = c.id';
        if ($aCategory) {
            $sql_category = 'INNER JOIN
                                (SELECT
                                  p1.id , p1.name, p1.alias , p1.name_single
                                FROM
                                  pcatalog p1
                                  LEFT JOIN pcatalog p2
                                    ON p2.id = p1.pid
                                  LEFT JOIN pcatalog p3
                                    ON p3.id = p2.pid
                                  LEFT JOIN pcatalog p4
                                    ON p4.id = p3.pid

                                WHERE (
                                    p1.id = '.$aCategory.'
                                    OR p1.pid = '.$aCategory.'
                                    OR p2.pid = '.$aCategory.'
                                    OR p3.pid = '.$aCategory.'
                                    OR p4.pid = '.$aCategory.'
                                  )
                                  AND p1.active) AS c
                                ON p.category_id = c.id';
        }

        if ($aSku) {
            $sql = 'SELECT
                          p.*,
                          p.`name` as product_name,
                          b.`name` as brand_name,
                          p1.`id` AS image_id,
                          p1.`image` AS image_ext,
                          c.`name_single` as category_name,
                          c.`alias` as category_alias,
                          p.`id` as product_id
                        FROM
                          `product` AS p
                          LEFT JOIN sku s
                            ON s.`product_id` = p.`id`
                          INNER JOIN brand b ON p.brand_id = b.id
                          '.$sql_category.'
                          LEFT JOIN `pimage` p1
                            ON p1.`product_id` = p.`id` AND p1.id = (SELECT p2.id FROM pimage p2 WHERE p2.`product_id` = p.`id` ORDER BY p2.`sorder` ASC LIMIT 1)
                        WHERE (p.`name` LIKE "%'.addslashes($aQuery).'%"
                          OR b.name LIKE "%'.addslashes($aQuery).'%"
                          OR c.name LIKE "%'.addslashes($aQuery).'%"
                          OR s.sku_article LIKE "%'.addslashes($aQuery).'%") AND p.`active` = 1
                        GROUP BY p.`id`
                        ORDER BY p.`price` ASC
                        LIMIT '.intval($aStart).', '.intval($aCount);
        } else {
            $sql = 'SELECT
                          p.*,
                          p.`name` as product_name,
                          b.`name` as brand_name,
                          p1.`id` AS image_id,
                          p1.`image` AS image_ext,
                          c.`name_single` as category_name,
                          c.`alias` as category_alias,
                          p.`id` as product_id
                        FROM
                          `product` AS p
                          LEFT JOIN sku s
                            ON s.`product_id` = p.`id`
                          INNER JOIN brand b ON p.brand_id = b.id
                          '.$sql_category.'
                          LEFT JOIN `pimage` p1
                            ON p1.`product_id` = p.`id` AND p1.id = (SELECT p2.id FROM pimage p2 WHERE p2.`product_id` = p.`id` ORDER BY p2.`sorder` ASC LIMIT 1)
                        WHERE (p.`name` LIKE "%'.addslashes($aQuery).'%"
                          OR b.name LIKE "%'.addslashes($aQuery).'%"
                          OR c.name LIKE "%'.addslashes($aQuery).'%") AND p.`active` = 1
                        GROUP BY p.`id`
                        ORDER BY p.`price` ASC
                        LIMIT '.intval($aStart).', '.intval($aCount);
        } */
        
        $sql_category = 'INNER JOIN pcatalog c ON p.category_id = c.id';
        if ($aCategory) {
            $sql_category = 'INNER JOIN
                                (SELECT
                                  p1.id , p1.name, p1.alias , p1.name_single
                                FROM
                                  pcatalog p1
                                  LEFT JOIN pcatalog p2
                                    ON p2.id = p1.pid
                                  LEFT JOIN pcatalog p3
                                    ON p3.id = p2.pid
                                  LEFT JOIN pcatalog p4
                                    ON p4.id = p3.pid

                                WHERE (
                                    p1.id = '.$aCategory.'
                                    OR p1.pid = '.$aCategory.'
                                    OR p2.pid = '.$aCategory.'
                                    OR p3.pid = '.$aCategory.'
                                    OR p4.pid = '.$aCategory.'
                                  )
                                  AND p1.active) AS c
                                ON p.category_id = c.id';
        }

        if ($aSku) {
            $sql = 'SELECT
                          p.*,
                          p.`price` as price,
                          p.`name` as product_name,
                          b.`name` as brand_name,
                          p1.`id` AS image_id,
                          p1.`image` AS image_ext,
                          c.`name_single` as category_name,
                          c.`alias` as category_alias,
                          p.`id` as product_id
                        FROM
                          `product` AS p
                          INNER JOIN brand b ON p.brand_id = b.id
                          '.$sql_category.'
                          LEFT JOIN sku s ON s.`product_id` = p.`id`
                          LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id` AND p1.id = (SELECT p2.id FROM pimage p2 WHERE p2.`product_id` = p.`id` ORDER BY p2.`sorder` ASC LIMIT 1)
                        WHERE (p.`name` LIKE "%'.addslashes($aQuery).'%"
                          OR b.name LIKE "%'.addslashes($aQuery).'%"
                          OR c.name LIKE "%'.addslashes($aQuery).'%"
                          OR s.sku_article LIKE "%'.addslashes($aQuery).'%") AND p.`active` = 1
                        GROUP BY p.`id`
                        ORDER BY p.`price` ASC
                        LIMIT '.intval($aStart).', '.intval($aCount);
        } else {
            $sql = 'SELECT
                          p.*,
                          p.`price` as price,
                          p.`name` as product_name,
                          b.`name` as brand_name,
                          p1.`id` AS image_id,
                          p1.`image` AS image_ext,
                          c.`name_single` as category_name,
                          c.`alias` as category_alias,
                          p.`id` as product_id
                        FROM
                          `product` AS p
                          INNER JOIN brand b ON p.brand_id = b.id
                          '.$sql_category.'
                          LEFT JOIN sku s ON s.`product_id` = p.`id`
                          LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id` AND p1.id = (
                            SELECT  pimg.`id` 
                                FROM
                                  sku s 
                                  INNER JOIN sku_element se 
                                    ON s.`id` = se.`sku_id` 
                                  INNER JOIN pimage pimg 
                                    ON pimg.`attribute_id` = se.`attr2prod_id` 
                                  INNER JOIN attr2prod a2p 
                                    ON se.`attr2prod_id` = a2p.`id`  
                                WHERE s.product_id = p.`id`
                                  AND s.`quantity` > 0 
                                LIMIT 1)
                        WHERE (p.`name` LIKE "%'.addslashes($aQuery).'%"
                          OR b.name LIKE "%'.addslashes($aQuery).'%"
                          OR c.name LIKE "%'.addslashes($aQuery).'%"
                          OR s.sku_article LIKE "%'.addslashes($aQuery).'%") AND p.`active` = 1
                        GROUP BY p.`id`
                        ORDER BY p.`price` ASC
                        LIMIT '.intval($aStart).', '.intval($aCount);
        }
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

            $images = array();

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;

                $brand = RexFactory::entity('brand');
                $brand->get($product['brand_id']);
                $this->brands[$product['id']] = $brand;

                if (intval($product['image_id']) > 0) {
                    $images[] = $product['image_id'];
                }
			}

			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

    function getCountCotegorySearch($aQuery, $aSku, $aCategory)
    {
        $sql_category = 'INNER JOIN pcatalog c ON p.category_id = c.id';
        if ($aCategory) {
            $sql_category = 'INNER JOIN
                                (SELECT
                                  p1.id , p1.name, p1.alias , p1.name_single
                                FROM
                                  pcatalog p1
                                  LEFT JOIN pcatalog p2
                                    ON p2.id = p1.pid
                                  LEFT JOIN pcatalog p3
                                    ON p3.id = p2.pid
                                  LEFT JOIN pcatalog p4
                                    ON p4.id = p3.pid
                                WHERE (
                                    p1.id = '.$aCategory.'
                                    OR p1.pid = '.$aCategory.'
                                    OR p2.pid = '.$aCategory.'
                                    OR p3.pid = '.$aCategory.'
                                    OR p4.pid = '.$aCategory.'
                                  )
                                  AND p1.active) AS c
                                ON p.category_id = c.id';
        }

        if (!$aSku) {
            $sql = 'SELECT
                          COUNT(p.id)
                        FROM
                          `product` AS p
                          INNER JOIN brand b ON p.brand_id = b.id
                          '.$sql_category.'
                          LEFT JOIN `pimage` p1
                            ON p1.`product_id` = p.`id` AND p1.id = (SELECT p2.id FROM pimage p2 WHERE p2.`product_id` = p.`id` ORDER BY p2.`sorder` ASC LIMIT 1)
                        WHERE (p.`name` LIKE "%'.addslashes($aQuery).'%"
                          OR b.name LIKE "%'.addslashes($aQuery).'%"
                          OR c.name LIKE "%'.addslashes($aQuery).'%") AND p.`active` = 1';
        } else {
            $sql = 'SELECT
                          COUNT(p.id)
                        FROM
                          `product` AS p
                          INNER JOIN brand b ON p.brand_id = b.id
                          INNER JOIN pcatalog c ON p.category_id = c.id
                          LEFT JOIN `pimage` p1
                            ON p1.`product_id` = p.`id` AND p1.id = (SELECT p2.id FROM pimage p2 WHERE p2.`product_id` = p.`id` ORDER BY p2.`sorder` ASC LIMIT 1)
                        WHERE p.`id` = '.$aQuery.'
                          AND p.`active` = 1';
        }

        $res = XDatabase::getOne($sql);

        return $res;
    }

	function getByCategoryAttributeList($aCategoryList, $aStart, $aCount, $aSelect, $aHaving, $aWhere = '', $brand_filters = '')
	{
		$sql = 'SELECT
				    p.*,
                    brand.name AS brand_name,
				    p1.`id` AS image_id,
				    p1.`image` AS image_ext

					'.($aSelect ? ','.$aSelect : '').'

					FROM
						`attr2prod`, `product` AS p
					LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                    LEFT JOIN brand ON brand.id = p.brand_id
					WHERE
                        ISNULL(p2.`id`) AND
                        '.$brand_filters.'
						p.`category_id` IN ('.implode(',', $aCategoryList).') AND
						p.`active` = 1 AND
						attr2prod.`product_id` = p.`id` '.($aWhere ? 'AND '.$aWhere : '').'

					GROUP BY p.`id`

					'.($aHaving ? 'HAVING '.$aHaving : '').'

					ORDER BY p.`price` ASC
					LIMIT '.intval($aStart).', '.intval($aCount);

		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				    $sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByIDs($aIDs)
	{
		$sql = 'SELECT
                    p.*,
                    p1.`id` AS image_id,
                    p1.`image` AS image_ext
                FROM
                    `product` AS p
                LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                WHERE
                    ISNULL(p2.`id`) AND
					p.`id` IN ('.$aIDs.') AND
					p.`active` = 1
				GROUP BY p.`id`
				ORDER BY p.`price` ASC';
		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}
	}

	function getByCategoryCount($aCategoryList, $aBrandID=false, $aAttributeID=false, $aAttributeValue=false)
	{
		if ($aBrandID !== false) {
			$aBrandID = 'AND `brand_id` = '.$aBrandID;
		} else {
			$aBrandID = '';
		}

		if ($aAttributeID !== false) {
			$sql = 'SELECT COUNT(DISTINCT product.`id`) FROM `attr2prod`, `product` WHERE product.`category_id` IN ('.implode(',', $aCategoryList).') AND product.`active` = 1 AND

					product.`id` = attr2prod.`product_id` AND
					attr2prod.`attribute_id` = ? AND
					attr2prod.`value` = ?

			'.$aBrandID;
			$res = XDatabase::getOne($sql, array($aAttributeID, $aAttributeValue));
		} else {
			$sql = 'SELECT COUNT(DISTINCT product.`id`) FROM `product` WHERE product.`category_id` IN ('.implode(',', $aCategoryList).') AND product.`active` = 1 '.$aBrandID;
			$res = XDatabase::getOne($sql);
		}

		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res;
		}
		return $this->_count;
	}

	function getByCategorySearchCount($aQuery)
	{
        $sql = 'SELECT
                    COUNT(p.`id`)
                FROM
                    `pcatalog`,
                    `brand`,
                    `product` AS p
                LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                WHERE
                    ISNULL(p2.`id`) AND
                    p.`category_id` = pcatalog.`id` AND
                    p.`brand_id` = brand.`id` AND
                    (
                        p.`name` LIKE "%'.addslashes($aQuery).'%" OR
                        pcatalog.`name_single` LIKE "%'.addslashes($aQuery).'%" OR
                        brand.`name` LIKE "%'.addslashes($aQuery).'%"
                    ) AND

                    p.`active` = 1';

			$res = XDatabase::getOne($sql);

		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res;
		}
		return $this->_count;
	}

    function getByCategoryListPrices($aCategoryList)
    {
        $kurs = RexSettings::get('dolar_rate');
        $valuta = RexSettings::get('filter_kurs');
        if ($valuta == '$'){
            $sql = 'SELECT
                    MAX(DISTINCT product.price) AS max_price,
                    MIN(DISTINCT product.price) AS min_price
                FROM `product` WHERE product.`category_id` IN ('.implode(',', $aCategoryList).') AND product.`active` = 1';
        }else{
            $sql = 'SELECT
                    MAX(DISTINCT product.price) AS max_price,
                    MIN(DISTINCT product.price) AS min_price
                FROM `product` WHERE product.`category_id` IN ('.implode(',', $aCategoryList).') AND product.`active` = 1';
        }

        $res = XDatabase::getAll($sql);
        if (PEAR::isError($res)) {
            $this->_error = $res;
             return array('rangefrom' => 0, 'rangeto' => 0);
        } else {
            return array('rangefrom' => sizeof($res) > 0 ? $res[0]['min_price'] : XDatabase::getOne('select min(`price`) from `product` where `active` = 1'),
                         'rangeto' => sizeof($res) > 0 ? $res[0]['max_price'] : XDatabase::getOne('select max(`price`) from `product` where `active` = 1')
                );
        }
    }

    function getByCategoryAttributeListPrices($aCategoryList, $aSelect = '', $aHaving = '', $aWhere = '', $brand_filters = '')
    {
        $kurs = RexSettings::get('dolar_rate');
        $valuta = RexSettings::get('filter_kurs');

        if ($valuta == '$'){
            $sql = 'SELECT
                    MAX(DISTINCT p.price) AS max_price,
                    MIN(DISTINCT p.price) AS min_price

                    '.($aSelect ? ','.$aSelect : '').'

                    FROM
                        `attr2prod`, `product` AS p
                    LEFT JOIN `pimage` ON pimage.`product_id` = p.`id`
                    WHERE
                        '.$brand_filters.'
                        '.($aWhere ? $aWhere.' AND ' : '').'
                        p.`category_id` IN ('.implode(',', $aCategoryList).') AND
                        p.`active` = 1 AND
                        attr2prod.`product_id` = p.`id`

                    /*GROUP BY p.`id`*/

                    '.($aHaving ? 'HAVING '.$aHaving : '');
        } else {
            $sql = 'SELECT
                    MAX(DISTINCT p.price) AS max_price,
                    MIN(DISTINCT p.price) AS min_price

                    '.($aSelect ? ','.$aSelect : '').'

                    FROM
                        `attr2prod`, `product` AS p
                    LEFT JOIN `pimage` ON pimage.`product_id` = p.`id`
                    WHERE
                        '.$brand_filters.'
                        '.($aWhere ? $aWhere.' AND ' : '').'
                        p.`category_id` IN ('.implode(',', $aCategoryList).') AND
                        p.`active` = 1 AND
                        attr2prod.`product_id` = p.`id`

                   /* GROUP BY p.`id`*/

                    '.($aHaving ? 'HAVING '.$aHaving : '');
        }

        $res = XDatabase::getAll($sql);

        if (PEAR::isError($res)) {
            $this->_error = $res;
            return array('rangefrom' => 0, 'rangeto' => 0);
        } else {
            if ($valuta == '$'){
                $s_min = 'SELECT MIN(p.`price`) FROM `product` AS p WHERE '.($aWhere ? $aWhere.' AND ' : '').' p.`category_id` IN ('.implode(',', $aCategoryList).') AND p.`active` = 1';
                $s_max = 'SELECT MAX(p.`price`) FROM `product` AS p WHERE '.($aWhere ? $aWhere.' AND ' : '').' p.`category_id` IN ('.implode(',', $aCategoryList).') AND p.`active` = 1';
            } else {
                $s_min = 'SELECT MIN(p.`price`) FROM `product` AS p WHERE '.($aWhere ? $aWhere.' AND ' : '').' p.`category_id` IN ('.implode(',', $aCategoryList).') AND p.`active` = 1';
                $s_max = 'SELECT MAX(p.`price`) FROM `product` AS p WHERE '.($aWhere ? $aWhere.' AND ' : '').' p.`category_id` IN ('.implode(',', $aCategoryList).') AND p.`active` = 1';
            }

            return array('rangefrom' => sizeof($res) > 0 ? $res[0]['min_price'] : XDatabase::getOne($s_min),
                         'rangeto' => sizeof($res) > 0 ? $res[0]['max_price'] : XDatabase::getOne($s_max)

            );
        }
        return $this->_count;
    }

	function getByCategoryAttributeListCount($aCategoryList, $aSelect, $aHaving, $aWhere = '')
	{
		$sql = 'SELECT
					COUNT(DISTINCT p.id) AS counter

					'.($aSelect ? ','.$aSelect : '').'

					FROM
						`attr2prod`, `product` AS p
					LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                    LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
                    WHERE
                        ISNULL(p2.`id`) AND
						p.`category_id` IN ('.implode(',', $aCategoryList).') AND
						p.`active` = 1 AND
						attr2prod.`product_id` = p.`id` '.($aWhere ? 'and '.$aWhere : '').'

					GROUP BY p.`id`

					'.($aHaving ? 'HAVING '.$aHaving : '');

		$res = XDatabase::getRow($sql);

		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res['counter'];
		}
		return $this->_count;
	}



	function getByCategoryListCount($aCategoryList)
	{
		$sql = 'SELECT COUNT(DISTINCT product.`id`) FROM `product` WHERE product.`category_id` IN ('.implode(',', $aCategoryList).') AND product.`active` = 1';
		$res = XDatabase::getOne($sql);
        if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res;
		}
		return $this->_count;
	}

	/*function getLatestByCat($aCategoryID=false, $aWithoutID=false)
	{
		$sql = '';
		if ($aCategoryID) {
			$sql .= 'and product.main_category_id = '.intval($aCategoryID);
		}
		if ($aWithoutID) {
			$sql .= ' and product.id <> '.intval($aWithoutID);
		}

		$sql = 'select
					product.*,
					pimage.id as image_id,
					pimage.image as image_ext
				from
					product
				left join pimage on pimage.product_id = product.id
				where
					1=1
					'.$sql.'
				group by product.id desc
				limit 0, 3';
		$res = XDatabase::getAll($sql);
		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}

			$images = implode(',', $images);
			$sql = 'select product_id, id, image from pimage where id in ('.$images.')';
			$res = XDatabase::getAssoc($sql);
			if (!$res) {
				$this->images = array();
			} else {
				$this->images = $res;
			}

		}
	}*/

	/*function getLatestByTime($aCategoryID=false, $aWithoutID=false)
    {
        $sql = '';
        if ($aCategoryID) {
            $sql .= 'and product.category_id = '.intval($aCategoryID);
        }
        if ($aWithoutID) {
            $sql .= ' and product.id <> '.intval($aWithoutID);
        }

        $sql = 'select
                    product.*,
                    pimage.id as image_id,
                    pimage.image as image_ext
                from
                    product
                left join pimage on pimage.product_id = product.id
                where
                    1=1
                    '.$sql.'
                group by product.date_update desc
                limit 0, 20';
        $res = XDatabase::getAll($sql);
        if (!$res or sizeof($res) < 1) {
            $this->_collection = array();
        } else {
            $this->_collection = $res;

            $images = array();
            foreach ($this->_collection as $product) {
                if (intval($product['image_id']) > 0) {
                    $images[] = $product['image_id'];
                }
            }

            $images = implode(',', $images);
            $sql = 'select product_id, id, image from pimage where id in ('.$images.')';
            $res = XDatabase::getAssoc($sql);
            if (!$res) {
                $this->images = array();
            } else {
                $this->images = $res;
            }

        }
    }*/

	/*function getMostPopular($aCategoryID=false, $aWithoutID=false)
	{
		$sql = '';
		if ($aCategoryID) {
			$sql .= 'and product.category_id = '.intval($aCategoryID);
		}
		if ($aWithoutID) {
			$sql .= ' and product.id <> '.intval($aWithoutID);
		}

		$sql = 'select
					product.*,
					pimage.id as image_id,
					pimage.image as image_ext,
					prod
				from
					product
				left join pimage on pimage.product_id = product.id
				where
					1=1
					'.$sql.'
				group by product.date_update desc
				limit 0, 20';
		$res = XDatabase::getAll($sql);
		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}

			$images = implode(',', $images);
			$sql = 'select product_id, id, image from pimage where id in ('.$images.')';
			$res = XDatabase::getAssoc($sql);
			if (!$res) {
				$this->images = array();
			} else {
				$this->images = $res;
			}

		}
	}*/

	/*function getRandom()
	{
		$sql = 'SELECT
					product.*,
					pimage.id as image_id,
					pimage.image as image_ext
				FROM
					product
				LEFT JOIN pimage on pimage.product_id = product.id
				WHERE
					product.active=1
				GROUP BY product.id
				ORDER BY RAND(TO_DAYS(NOW()))
				LIMIT 1';

		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}

			$images = implode(',', $images);
			$sql = 'select product_id, id, image from pimage where id in ('.$images.')';
			$res = XDatabase::getAssoc($sql);
			if (!$res) {
				$this->images = array();
			} else {
				$this->images = $res;
			}

		}
	}*/

	/*function getLastVisited()
	{
		$sql = 'SELECT
					product.*,
					pimage.id as image_id,
					pimage.image as image_ext
				FROM
					product
				LEFT JOIN pimage on pimage.product_id = product.id
				WHERE
					product.active=1
				ORDER BY product.visited DESC
				LIMIT 1';

		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}

			$images = implode(',', $images);
			$sql = 'select product_id, id, image from pimage where id in ('.$images.')';
			$res = XDatabase::getAssoc($sql);
			if (!$res) {
				$this->images = array();
			} else {
				$this->images = $res;
			}

		}
	}*/

	/*function getLastThreeProd($aCategoryID=false, $aWithoutID=false)
	{
		$sql = 'SELECT
					product.*,
					pimage.id as image_id,
					pimage.image as image_ext
				FROM
					product
				LEFT JOIN pimage on pimage.product_id = product.id
				WHERE
					product.active=1
				ORDER BY product.date_update DESC
				LIMIT 3';

		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}

			$images = implode(',', $images);
			$sql = 'select product_id, id, image from pimage where id in ('.$images.')';
			$res = XDatabase::getAssoc($sql);

			if (!$res) {
				$this->images = array();
			} else {
				$this->images = $res;
			}

		}
	}*/

	function getArchive($aFeaturedField, $aStart, $aCount)
	{
		$sql = 'SELECT
                    p.*,
                    p1.`id` AS image_id,
                    p1.`image` AS image_ext
				FROM
					`product` AS p
				LEFT JOIN `pimage` p1 ON p1.`product_id` = p.`id`
                LEFT JOIN `pimage` p2 ON p1.`product_id` = p2.`product_id` AND p1.`sorder` > p2.`sorder`
				WHERE
					ISNULL(p2.`id`) AND p.`active` = 1'.($aFeaturedField ? ' AND p.`'.$aFeaturedField.'` > 0' : '').'
				GROUP BY p.`id`
				ORDER BY p.`price` ASC
				LIMIT '.intval($aStart).', '.intval($aCount);

		$res = XDatabase::getAll($sql);

		if (!$res or sizeof($res) < 1) {
			$this->_collection = array();
		} else {
			$this->_collection = $res;

			foreach ($this->_collection as $product) {
				$pCatalog = RexFactory::entity('pCatalog');
				$pCatalog->get($product['category_id']);
				$this->categories[$product['id']] = $pCatalog;
			}

			foreach ($this->_collection as $product) {
				$brand = RexFactory::entity('brand');
				$brand->get($product['brand_id']);
				$this->brands[$product['id']] = $brand;
			}

			$images = array();
			foreach ($this->_collection as $product) {
				if (intval($product['image_id']) > 0) {
					$images[] = $product['image_id'];
				}
			}
			if (sizeof($images) > 0) {
				$images = implode(',', $images);
				$sql = 'SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';
				$res = XDatabase::getAssoc($sql);
				if (!$res) {
					$this->images = array();
				} else {
					$this->images = $res;
				}
			}
		}

	}

	function getCountByFeature($aFeature)
	{
		$sql = 'SELECT COUNT(DISTINCT product.`id`) FROM `product` WHERE

		product.`active` = 1 AND

		'.$aFeature.' > "0"';

		$res = XDatabase::getOne($sql);

		if (PEAR::isError($res)) {
			$this->_error = $res;
			 $this->_count= false;
		} else {
			$this->_count = $res;
		}
		return $this->_count;
	}

    //function

    function exportProductList(&$products = array(), $filename = false)
    {
        $data_start_row = 2;
        $category_column = 0;
        $brand_column = 1;
        $name_column = 2;
        $value_translator = array(
            1 => 'да',
            0 => 'нет');

        //слолбец, название столбца, ширина, транслятор
        $field_translator = array(
            'category' => array('A', 'Категория', 35),
            'brand' => array('B', 'Бренд', 15),
            'name' => array('C', 'Наименование на сайте', 32),
            'sku_string' => array('D', 'Атрибуты товара', 32),
            'price_opt' => array('E', 'Оптовая цена', 10),
            'price' => array('F', 'Цена', 10),
            'sale' => array('G', "Скидка, %", 10),
            'last_price' => array('H', "Цена со\nскидкой", 10),
            'in_stock' => array('I', "В наличии\n(да/нет)", 10, $value_translator),
            'bestseller' => array('J', "Лидер продаж", 10, $value_translator),
            'new' => array('K', "Новинка", 10, $value_translator),
            'event' => array('L', "Акционный", 10, $value_translator),
        );

/*        $sql = 'SELECT
                    p.*,
                    IF (ppc.id, CONCAT(ppc.name, " /// ", pc.name), pc.name) AS category,
                    b.name AS brand
                FROM product p
                INNER JOIN `pcatalog` pc ON pc.`id` = p.`category_id`
                LEFT JOIN `pcatalog` ppc ON ppc.`id` = pc.`pid`
                INNER JOIN `brand` b ON b.`id` = p.`brand_id`
                ORDER BY `category`, p.`name`';    */

        $sql = '  SELECT
                    p.id,
                    pc.name AS category,
                    b.name AS brand,
                    p.name,
                    GROUP_CONCAT(CONCAT(a1.name, "::", a2.name) SEPARATOR "##") AS sku_string,
                    s.`price_opt`,
                    s.`price`,
                    p.sale,
                    FLOOR(s.`price` - s.`price` * p.sale / 100) AS last_price,
                    s.`quantity` AS in_stock,
                    p.`bestseller`,
                    p.`new`,
                    p.`event`
                  FROM
                    product p
                    LEFT JOIN sku s
                      ON p.id = s.`product_id`
                    LEFT JOIN sku_element se
                      ON s.id = se.`sku_id`
                    LEFT JOIN attr2prod a2p
                      ON se.`attr2prod_id` = a2p.`id`
                    LEFT JOIN attribute a1
                      ON a2p.`attribute_id` = a1.`id`
                    LEFT JOIN attribute a2
                      ON a2p.`value` = a2.`id`
                    INNER JOIN `brand` b
                      ON b.`id` = p.`brand_id`
                    INNER JOIN `pcatalog` pc
                      ON pc.`id` = p.`category_id`
                  GROUP BY s.id';

        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        $sheet = $phpExcel->getActiveSheet();
        $sheet->setTitle('MegaOpt price');
        $sheet->getRowDimension(1)->setRowHeight(50);
        
        foreach ($field_translator as $translator) {
            $sheet->getCell($translator[0].'1')->setValue($translator[1]);
            $sheet->getColumnDimension($translator[0])->setWidth($translator[2]);
            $style = $sheet->getStyle($translator[0].'1');
            $style->getFont()->setBold(true);
            $style->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $style->getBorders()->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        } 

        if (!$products) {
            $products = XDatabase::getAll($sql);
        }
        if (!$products) {
            $products = array();
        }
        $sproducts = sizeof($products);
        for ($i = 0; $i < $sproducts; ++$i) {
            $product = $products[$i];
            foreach ($field_translator as $field => $translator) {  
                if (array_key_exists($field, $product)) {
                    $value = $product[$field];
                    if (isset($translator[3]) && isset($translator[3][$value])) {
                        $value = $translator[3][$value];
                    }
                } else {
                    $value = '';
                }
                $sheet->getCell($translator[0].($data_start_row + $i))->setValue($value);
                //break;
            }
        }

        $sheet->getCell('R1')->setValue('FORMAT_FLAG1');

        $writer = new PHPExcel_Writer_Excel5($phpExcel);
        if (!$filename) {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.settings('site_name').' '.date('Y-m-d').'.xls"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } else {
            $writer->save($filename);
        }
    }

    function getGoogleSiteMapProduct($aStart, $aCount)
    {
        $sql = 'SELECT p.`id`, p.`name`, p.`date_update`, pc.`alias` AS category_alias FROM `product` AS p
                LEFT JOIN `pcatalog` AS pc ON pc.`id` = p.`category_id`
                WHERE  p.`active` = 1
                ORDER BY p.`id`
                limit '.intval($aStart).', '.intval($aCount);

        $res = XDatabase::getAll($sql);

        if (!$res or sizeof($res) < 1) {
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
    }

    function getByCategoryListFilter($aCategoryList, $aStart, $aCount, $aFilter)
    {
        $price_where = '';

        if (isset($aFilter['rangefrom']) && isset($aFilter['rangeto'])) {
            $kurs = RexSettings::get('dolar_rate');
            $valuta = RexSettings::get('filter_kurs');

            if (($aFilter['rangefrom'] || $aFilter['rangeto']) && $aFilter['rangefrom'] <= $aFilter['rangeto']) {

                if ($valuta == 'грн'){
                    $price_where = ' AND ((p.price - p.price*p.sale/100) BETWEEN '.$aFilter['rangefrom']/$kurs.' AND '.$aFilter['rangeto']/$kurs.')';
                } elseif ($valuta == '$'){
                    $price_where = ' AND ((p.price - p.price*p.sale/100) BETWEEN '.$aFilter['rangefrom'].' AND '.$aFilter['rangeto'].')';
                }

            }
        }

        if (isset($aFilter['brand']) && count($aFilter['brand']) > 0) {
            $brand_where = 'AND (';
            $brandArray = array();

            foreach ($aFilter['brand'] as $brandID) {
                $brandArray[] = 'p.`brand_id` = '.$brandID;
            }

            $brand_where .= implode(' OR ', $brandArray).' AND EXISTS (SELECT 1 FROM `brand2cat` WHERE category_id IN ('.implode(',', $aCategoryList).'))) ';
        } else {
          $brand_where = '';
        }

        $attr_join = '';
        $compare_where = '';

        if (isset($aFilter['attribute']) && count($aFilter['attribute']) > 0) {
            $countKey = 0;

            $attr_id_sizes = array(2, 150, 188, 265);
            foreach ($aFilter['attribute'] as $attrID => $values) {
                $countKey += 1;
				in_array($attrID, $attr_id_sizes) ? $attr_join .= 'LEFT ' : $attr_join .= 'INNER ';
                $attr_join .= ' JOIN attr2prod a'.$countKey.'
                                ON p.id = a'.$countKey.'.`product_id` AND ';
                $attributeEntity = RexFactory::entity('attribute');
                $attributeEntity->get($attrID);
                $countValues = 0;

                switch ($attributeEntity->type_id) {
                    case 9:

                        foreach ($values as $key => $val) {

                            $filterVal[] = $val;

                        }

                        break;
                    default:
                        $attr_join .= 'a'.$countKey.'.`attribute_id` = '.$attrID.' AND (';
                }

                foreach ($values as $key => $val) {

                    switch ($attributeEntity->type_id) {

                        case 9:
                            if ($countValues > 0) {
                                $countKey += 1;
                                $attr_join .= 'LEFT JOIN attr2prod a'.$countKey.'
                                ON p.id = a'.$countKey.'.`product_id` AND ';
                                $compare_where .= 'AND LEAST(
                                                        CONVERT(a'.($countKey-1).'.`value`, UNSIGNED INTEGER),
                                                        CONVERT(a'.$countKey.'.`value`, UNSIGNED INTEGER)
                                                      ) <= GREATEST('.implode(', ', $filterVal).')
                                                      AND LEAST('.implode(', ', $filterVal).') <= GREATEST(
                                                        CONVERT(a'.($countKey-1).'.`value`, UNSIGNED INTEGER),
                                                        CONVERT(a'.$countKey.'.`value`, UNSIGNED INTEGER)
                                                      )';
                            }
                            $attr_join .= 'a'.$countKey.'.`attribute_id` = '.$key.' ';
                            break;
                        case 6:
                        case 7:
                        case 4:
                            if ($countValues > 0) {
                                $attr_join .= ' OR ';
                            }
                            $attr_join .= ' a'.$countKey.'.`value` = '.$val;
                            break;
                        case 5:
                            $attr_join .= ' a'.$countKey.'.`value` = '.$val;
                            break;
                        case 3:
                        case 2:
                            if ($countValues > 0) {
                                $attr_join .= ' OR ';
                            }
                            $attr_join .= ' a'.$countKey.'.`value` = "'.$val.'"';
                            break;
                    }

                    $countValues += 1;
                }

                if ($attributeEntity->type_id != 9) {
                    $attr_join .= ') AND (SELECT COALESCE(SUM(s.quantity), 1) FROM sku s INNER JOIN sku_element se ON s.id = se.`sku_id` WHERE se.attr2prod_id = a'.$countKey.'.id) > 0 ';
                }
            }

        }

        $multiply_cat = XDatabase::getAll('SELECT product_id FROM prod2cat WHERE category_id IN ('.implode(',', $aCategoryList).')');

        if ($multiply_cat) {
            foreach ($multiply_cat as $category) {
                $multiply_cat_array[] = $category['product_id'];
            }
            $multiply_cat_string = '('.implode(', ', $multiply_cat_array).')';
        }
        $order=' ORDER BY p.`sorder` DESC ';
        if (isset($aFilter['price_order'])) {
           $order=' ORDER BY new_price '.$aFilter['price_order'];
        }
        $sql = 'SELECT
                  p.*,
                  p1.`id` AS image_id,
                  p1.`image` AS image_ext,
                  pcatalog.`name_single`,
                  p.price-p.price*p.sale/100 as `new_price`,
                      (SELECT
                      GROUP_CONCAT(
                        at.name
                        ORDER BY at.id DESC SEPARATOR ", "
                      )
                    FROM
                      attr2prod a2p
                      INNER JOIN attribute `at`
                        ON a2p.`value` = at.id
                    WHERE a2p.product_id = p.id
                      AND a2p.`attribute_id` = 145
                    GROUP BY a2p.`attribute_id`) AS sex
                FROM
                  `product` p
                  LEFT JOIN `pimage` p1
                    ON p1.`id` =
                    (SELECT
                      p2.`id`
                    FROM
                    	pimage p2 FORCE INDEX (product_id),
				    	attr2prod a2p2,
				    	sku s,
			    	    sku_element se
				    WHERE p2.`product_id` = p.`id`
					    AND a2p2.`product_id` = p2.`product_id`
						AND p2.`attribute_id` = a2p2.`id`
						AND se.`attr2prod_id` = a2p2.`id`
				        AND s.`id` = se.`sku_id`
				        AND s.`quantity` != 0
                    ORDER BY p2.`sorder` ASC
                    LIMIT 1)
                    '.$attr_join.'
                  LEFT JOIN `pcatalog`
     				ON pcatalog.`id` = p.`category_id`
                WHERE '.(isset($multiply_cat_string) ? '(p.`category_id` IN ('.implode(',', $aCategoryList).') OR p.id IN '.$multiply_cat_string.')' : 'p.`category_id` IN ('.implode(',', $aCategoryList).')').'
                  AND p.`active` = 1
                    '.$price_where.'
                    '.$compare_where.'
                    '.$brand_where.'
                    GROUP BY p.`id`'.
            $order.'                 
                    LIMIT '.intval($aStart).', '.intval($aCount);

        $res = XDatabase::getAll($sql);

        if (!$res or count($res) < 1) {
            $this->_collection = array();
            $this->_count = 0;
            $this->_productListIDs = null;
        } else {
            $this->_collection = $res;
            $sql = 'SELECT
                  COUNT(*) FROM
                  (SELECT
                      p.`id`
                    FROM
                      `product` p
                        '.$attr_join.'
                    WHERE '.(isset($multiply_cat_string) ? '(p.`category_id` IN ('.implode(',', $aCategoryList).') OR p.id IN '.$multiply_cat_string.')' : 'p.`category_id` IN ('.implode(',', $aCategoryList).')').'
                      AND p.`active` = 1
                        '.$price_where.'
                        '.$compare_where.'
                        '.$brand_where.'
                        GROUP BY p.`id`) AS cnt';

            $res = XDatabase::getOne($sql);

            $this->_count = $res;

            $sql = 'SELECT
                  prod.`id` FROM
                  (SELECT
                      p.`id`
                    FROM
                      `product` p
                        '.$attr_join.'
                    WHERE p.`category_id` IN ('.implode(',', $aCategoryList).')
                      AND p.`active` = 1
                        '.$price_where.'
                        '.$compare_where.'
                        '.$brand_where.'
                        GROUP BY p.`id`) AS prod';

            $res = XDatabase::getAll($sql);

            if ($res && count($res) > 0) {
                foreach ($res as $k => $v) {
                    $this->_productListIDs[] = $v['id'];
                }   
            }
            foreach ($this->_collection as $product) {
                $pCatalog = RexFactory::entity('pCatalog');
                $pCatalog->get($product['category_id']);
                $this->categories[$product['id']] = $pCatalog;
            }

            foreach ($this->_collection as $product) {
                $brand = RexFactory::entity('brand');
                $brand->get($product['brand_id']);
                $this->brands[$product['id']] = $brand;
            }

            $images = array();
            foreach ($this->_collection as $product) {
                if (intval($product['image_id']) > 0) {
                    $images[] = $product['image_id'];
                }
            }
            if (count($images) > 0) {
                $images = implode(',', $images);
                $sql = ' SELECT `product_id`, `id`, `image` FROM `pimage` WHERE `id` IN ('.$images.')';

                $res = XDatabase::getAssoc($sql);
                if (!$res) {
                    $this->images = array();
                } else {
                    $this->images = $res;
                }
            }
        }
    }

    function getByCategoryListFilterPrices($aCategoryList, $aFilter)
    {
        $price_where = '';

        $kurs = RexSettings::get('dolar_rate');
        $valuta = RexSettings::get('filter_kurs');

        if ($valuta == '$'){
            $sql_select = 'p.`price` AS price';
            $sql_select = 'IF (p.`sale`, p.`price`*(1-p.`sale`/100), p.`price`) AS price';
        }else{
            $sql_select = 'p.`price`*'.$kurs.' AS price';
            $sql_select = 'IF (p.`sale`, p.`price`*'.$kurs.'*(1-p.`sale`/100), p.`price`*'.$kurs.') AS price';
        }

        if (isset($aFilter['brand']) && count($aFilter['brand']) > 0) {
            $brand_where = 'AND (';
            $brandArray = array();
            foreach ($aFilter['brand'] as $brandID) {
                $brandArray[] = 'p.`brand_id` = '.$brandID;
            }

            $brand_where .= implode(' OR ', $brandArray).' AND EXISTS (SELECT 1 FROM `brand2cat` WHERE category_id IN ('.implode(',', $aCategoryList).')))';
        } else {
          $brand_where = '';
        }

        $attr_join = '';
        $compare_where = '';

        if (isset($aFilter['attribute']) && count($aFilter['attribute']) > 0) {
            $countKey = 0;

            foreach ($aFilter['attribute'] as $attrID => $values) {
                $countKey += 1;
                $attr_join .= 'LEFT JOIN attr2prod a'.$countKey.'
                                ON p.id = a'.$countKey.'.`product_id` AND ';
                $attributeEntity = RexFactory::entity('attribute');
                $attributeEntity->get($attrID);
                $countValues = 0;

                switch ($attributeEntity->type_id) {
                    case 9:

                        foreach ($values as $key => $val) {

                            $filterVal[] = $val;

                        }

                        break;
                    default:
                        $attr_join .= 'a'.$countKey.'.`attribute_id` = '.$attrID.' AND (';
                }

                foreach ($values as $key => $val) {

                    switch ($attributeEntity->type_id) {

                        case 9:
                            if ($countValues > 0) {
                                $countKey += 1;
                                $attr_join .= 'LEFT JOIN attr2prod a'.$countKey.'
                                ON p.id = a'.$countKey.'.`product_id` AND ';
                                $compare_where .= 'AND LEAST(
                                                        CONVERT(a'.($countKey-1).'.`value`, UNSIGNED INTEGER),
                                                        CONVERT(a'.$countKey.'.`value`, UNSIGNED INTEGER)
                                                      ) <= GREATEST('.implode(', ', $filterVal).')
                                                      AND LEAST('.implode(', ', $filterVal).') <= GREATEST(
                                                        CONVERT(a'.($countKey-1).'.`value`, UNSIGNED INTEGER),
                                                        CONVERT(a'.$countKey.'.`value`, UNSIGNED INTEGER)
                                                      )';
                            }
                            $attr_join .= 'a'.$countKey.'.`attribute_id` = '.$key.' ';
                            break;
                        case 6:
                        case 7:
                        case 4:
                            if ($countValues > 0) {
                                $attr_join .= ' OR ';
                            }
                            $attr_join .= ' a'.$countKey.'.`value` = '.$val;
                            break;
                        case 5:
                            $attr_join .= ' a'.$countKey.'.`value` = '.$val;
                            break;
                        case 3:
                        case 2:
                            if ($countValues > 0) {
                                $attr_join .= ' OR ';
                            }
                            $attr_join .= ' a'.$countKey.'.`value` = "'.$val.'"';
                            break;
                    }

                    $countValues += 1;
                }

                if ($attributeEntity->type_id != 9) {
                    $attr_join .= ') ';
                }
            }
        }

        $sql = 'SELECT
                  '.$sql_select.'
                FROM
                  `product` p
                   '.$attr_join.'
                WHERE (p.`category_id` IN ('.implode(',', $aCategoryList).') OR EXISTS (SELECT 1 FROM prod2cat WHERE category_id IN ('.implode(',', $aCategoryList).') AND p.id = product_id))
                  AND p.`active` = 1
                    '.$price_where.'
                    '.$compare_where.'
                    '.$brand_where.'
                    GROUP BY p.`id`';

        $res = XDatabase::getAll($sql);
        if (PEAR::isError($res)) {
            $this->_error = $res;
             return array('rangefrom' => 0, 'rangeto' => 0);
        } elseif (count($res) <= 1) {
            return false;
        } elseif (count($res) > 1) {
            $price['rangefrom'] = $res[0]['price'];
            $price['rangeto'] = $res[0]['price'];

            foreach ($res as $productPrice) {

                if ($productPrice['price'] > $price['rangeto']) {
                    $price['rangeto'] = $productPrice['price'];
                } elseif ($productPrice['price'] < $price['rangefrom']) {
                    $price['rangefrom'] = $productPrice['price'];
                }
            }

            if ($price['rangefrom'] == $price['rangeto']) {
                return false;
            }

            return $price;
        }
    }

    function setProd2Cat($product_id, $values = false)
    {
        XDatabase::query('DELETE FROM prod2cat WHERE product_id = '.$product_id);

        if ($values) {
            XDatabase::query('INSERT INTO prod2cat VALUES '.$values);
        }
    }

}