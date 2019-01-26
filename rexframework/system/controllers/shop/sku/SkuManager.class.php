<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexDBList as RexDBList;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

class SkuManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\AttributeEntity:shop.standart:1.0'
    );

    var $attibutes;
    var $skus;
    var $total_quantity = 0;

	function __construct()
	{
		parent::__construct('sku', 'id');
	}

    function getList($filters, $fields, $mod = false) {
        $attributesList = array(
            'color' => ' AND attr1.id = 1'
        );

        $order_by = $this->_uid;
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = 'INNER JOIN sku_element se
                        ON t.id = se.sku_id
                      INNER JOIN attr2prod ap
                        ON se.attr2prod_id = ap.id
                      INNER JOIN attribute attr1
                        ON ap.`attribute_id` = attr1.id '.(isset($filters['attr']) && isset($attributesList[$filters['attr']]) ? $attributesList[$filters['attr']] : '').'
                      INNER JOIN attribute attr2
                        ON ap.value = attr2.`id`';

        $mod = 'sku';

        $entity = RexFactory::entity($mod, false);

        foreach ($filters as $key => $value) {
            $result = $this->_processFilter($key, $value);

            if ($result !== false) {
                if (!is_array($result))
                    $sql .= $result;
                else {
                    $sql .= $result[0];
                    $sql_join .= $result[1];
                }
            } else {
                switch ($key) {
                    case 'page':
                        $page = $value;
                        break;
                    case 'attr':
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
                            $ors = array();
                            if ($fields && sizeof($fields)) {
                                foreach ($fields as $field => $spec) {
                                    if ($field{0} != '_' && $field && intval($field).'' != $field) {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            } elseif ($entity) {
                                foreach ($entity as $field => $field_value) {
                                    if ($field{0} != '_') {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            }
                            if ($ors) {
                                $sql .= ' AND ('.implode(' OR ', $ors).')';
                            }
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`'.$key.'` = "'.addslashes($value).'"';
                        }
                }
            }
        }

        if (isset($filters['attr']) && $filters['attr'] == 'color') {
            $sql_group = ' GROUP BY `se`.attr2prod_id';
            $sql_limit =' ORDER BY `t`.`'.$order_by.'` '.$order_dir.'
            LIMIT '.($page * $inpage - $inpage).', '.$inpage.'  ;';

            $sql = 'FROM '.$this->_table.' AS `t` '.$sql_join.' WHERE '.$sql;
//var_dump('SELECT t.id, t.product_id, t.price, t.price_opt, t.sale, t.sku_article, t.active, MAX(t.quantity) AS quantity, attr2.id AS color_id, CONCAT("<b>", attr1.`name`, "</b>", ": ", attr2.`name`) AS `name` '.$sql.$sql_group.$sql_limit);exit;
            return array(
                0 => XDatabase::getAll('SELECT t.id, t.product_id, t.price, t.price_opt, t.sale, t.sku_article, t.active, MAX(t.quantity) AS quantity, attr2.id AS color_id, CONCAT("<b>", attr1.`name`, "</b>", ": ", attr2.`name`) AS `name` '.$sql.$sql_group.$sql_limit),
                1 => XDatabase::getOne('SELECT COUNT(*) FROM (SELECT t.id '.$sql.$sql_group.') AS derived'));
        } else {
            $order_by = 'gorder';
            $sql_group = ' GROUP BY `t`.id';
            $sql_limit =' ORDER BY `'.$order_by.'` '.$order_dir.' 
            LIMIT '.($page * $inpage - $inpage).', '.$inpage.'  ';       
            $sql = 'FROM '.$this->_table.' AS `t` '.$sql_join.' WHERE '.$sql;
            //echo 'SELECT t.*, GROUP_CONCAT("<b>", attr1.`name`, "</b>", ": ", attr2.`name` ORDER BY attr1.`name` DESC SEPARATOR "<br />") AS `name`, GROUP_CONCAT(attr2.`gorder` ORDER BY attr2.`gorder` ASC SEPARATOR "-") AS `gorder` '.$sql.$sql_group.$sql_limit;exit;
            return array(
                0 => XDatabase::getAll('SELECT t.*, GROUP_CONCAT("<b>", attr1.`name`, "</b>", ": ", attr2.`name` ORDER BY attr1.`name` DESC SEPARATOR "<br />") AS `name`, GROUP_CONCAT(attr2.`gorder` ORDER BY attr2.`gorder` ASC SEPARATOR "-") AS `gorder` '.$sql.$sql_group.$sql_limit),
                1 => XDatabase::getOne('SELECT COUNT(*) FROM (SELECT t.id '.$sql.$sql_group.') AS derived'));
        }

    }
    
    function getOldColorSku($task, $colorId)
    {
        $sql = 'SELECT t.id, t.product_id, MAX(t.quantity) AS quantity, attr2.id AS color_id '.
                'FROM sku AS t '.
                'INNER JOIN sku_element se '.
                    'ON t.id = se.sku_id '.
                'INNER JOIN attr2prod ap '.
                    'ON se.attr2prod_id = ap.id '.
                'INNER JOIN attribute attr1 '.
                    'ON ap.`attribute_id` = attr1.id AND attr1.id = 1 '.
                'INNER JOIN attribute attr2  '.
                    'ON ap.value = attr2.`id` '.
                'WHERE 1 '.
                    'AND  attr2.`id` = '.addslashes($colorId).' '.
                    'AND t.active = "1" '.
                    'AND `t`.`product_id` = "'.addslashes($task).'" ';

        return  XDatabase::getRow($sql);
    }
    
    function getListOld($filters, $fields, $mod = false) {
        $order_by = $this->_uid;
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = 'INNER JOIN sku_element se
                        ON t.id = se.sku_id
                      INNER JOIN attr2prod ap
                        ON se.attr2prod_id = ap.id
                      INNER JOIN attribute attr1
                        ON ap.`attribute_id` = attr1.id
                      INNER JOIN attribute attr2
                        ON ap.value = attr2.`id`';

        $mod = 'sku';

        $entity = RexFactory::entity($mod, false);

        foreach ($filters as $key => $value) {
            $result = $this->_processFilter($key, $value);

            if ($result !== false) {
                if (!is_array($result))
                    $sql .= $result;
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
                            $ors = array();
                            if ($fields && sizeof($fields)) {
                                foreach ($fields as $field => $spec) {
                                    if ($field{0} != '_' && $field && intval($field).'' != $field) {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            } elseif ($entity) {
                                foreach ($entity as $field => $field_value) {
                                    if ($field{0} != '_') {
                                        $ors[] = '`t`.`'.$field.'` LIKE "%'.addslashes($value).'%"';
                                    }
                                }
                            }
                            if ($ors) {
                                $sql .= ' AND ('.implode(' OR ', $ors).')';
                            }
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`'.$key.'` = "'.addslashes($value).'"';
                        }
                }
            }
        }

    }

    function getSkusFront($aProductID)
    {
        $sql = 'SELECT
                  ap.*,
                  s.sku_article,
                  (SELECT
                    attr.`name`
                  FROM
                    attribute attr
                  WHERE attr.id = ap.`value`) AS `name`,

                  (SELECT
                    attr.`gorder`
                  FROM
                    attribute attr
                  WHERE attr.id = ap.`value`) AS `gorder`,
                  CONCAT(p.`id`, ".", p.`image`) AS img_url,
                  p.`id` AS img_id,
                  p.`image` AS img_ext,
                  pco.sorder AS `sorder`
                FROM
                  attr2prod ap

                  INNER JOIN sku_element se
                    ON ap.id = se.attr2prod_id
                  INNER JOIN sku s
                    ON se.sku_id = s.id
                    AND s.quantity > 0
                  LEFT JOIN pimage p
                    ON ap.`product_id` = p.`product_id`
                    AND ap.id = p.attribute_id AND p.`main` = 1
                  LEFT JOIN prod_color_order pco
                    ON ap.value = pco.`attribute_id` AND ap.`product_id` = pco.`product_id`
                WHERE ap.`product_id` = '.$aProductID.'
                GROUP BY ap.id
                ORDER BY ap.`attribute_id`, pco.sorder, gorder,  ap.`id` ASC';

        $res = XDatabase::getAll($sql);
        if ($res && count($res) > 0) {
            foreach ($res as $attr) {
                $attributeEntity = RexFactory::entity('attribute');
                $attributeEntity->get($attr['attribute_id']);
                $arrayAttributes[$attributeEntity->name][] = $attr;

            }
        } else {
            $arrayAttributes = false;
        }

        $this->attributes = $arrayAttributes;

        $sql = 'SELECT * FROM sku WHERE product_id = '.$aProductID;
        $res = XDatabase::getAll($sql);

        if ($res && count($res) > 0) {
            foreach ($res as &$attr) {
                $attr['skus_elem'] = XDatabase::getAll('SELECT attr2prod_id FROM sku_element WHERE sku_id = '.$attr['id'].' ORDER BY attr2prod_id ASC');
            }
        }

        $this->skus = $res;
    }

    public function saveSkuByAttrID($product_id, $attr_id, $values)
    {
        if (isset($values['quantity'])) {
            $values['quantity'] = 1;
        } else {
            $values['quantity'] = 0;
        }

        $sql = 'UPDATE
                  sku s
                  INNER JOIN sku_element se
                    ON se.sku_id = s.id
                  INNER JOIN attr2prod a2p
                    ON se.attr2prod_id = a2p.id
                    AND a2p.value = '.$attr_id.'
                SET s.price = "'.$values['price'].'",
                  s.price_opt = "'.$values['price_opt'].'",
                  s.sku_article = "'.$values['sku_article'].'",
                  s.quantity = "'.$values['quantity'].'", 
                  s.sale = "'.$values['sale'].'"
                WHERE s.product_id = '.$product_id;

        return XDatabase::query($sql);
    }

    public function updateColorSkuArticles($productID, $sku_field, $attributeID)
    {
        $sql = 'UPDATE sku s
                INNER JOIN sku_element se
                 ON se.sku_id = s.id
                INNER JOIN attr2prod a2p
                 ON se.attr2prod_id = a2p.id
                 ' . ($attributeID ? ' AND a2p.value = ' . $attributeID : '') . ' 
                SET s.`' . $sku_field['name'] . '` = "' . $sku_field['value'] . '"
                WHERE s.product_id = ' . $productID;

        return XDatabase::query($sql);
    }

    public function saveSkuQuantityByAttrID($product_id, $attr_id, $quantity)
    {
        $sql = 'UPDATE
                  sku s
                  INNER JOIN sku_element se
                    ON se.sku_id = s.id
                  INNER JOIN attr2prod a2p
                    ON se.attr2prod_id = a2p.id
                    AND a2p.value = '.$attr_id.'
                SET s.quantity = "'.$quantity.'"
                WHERE s.product_id = '.$product_id;
        // echo $sql; exit;
        return XDatabase::query($sql);
    }

    public function getSkusFrontMainPage($aProductID)
    {
        //echo $aProductID; exit;
        $sql = 'SELECT
                  ap.*,
                  s.`sale`,
                  (SELECT
                    attr.`name`
                  FROM
                    attribute attr
                  WHERE attr.id = ap.`value`) AS `name`,
                  (SELECT
                    attr.`gorder`
                  FROM
                    attribute attr
                  WHERE attr.id = ap.`value`) AS `gorder`,
                  CONCAT(p.`id`, ".", p.`image`) AS img_url,
                  p.`id` AS img_id,
                  p.`image` AS img_ext,
                  s.price,
                  s.sku_article
                  , s.id AS skus_id
                FROM
                  attr2prod ap
                  INNER JOIN sku_element se
                    ON ap.id = se.attr2prod_id
                  INNER JOIN sku s
                    ON se.sku_id = s.id
                    AND s.quantity > 0
                  LEFT JOIN pimage p
                    ON ap.`product_id` = p.`product_id`
                    AND ap.id = p.attribute_id
                    AND p.`main` = 1 
                  LEFT JOIN product prod 
                    ON prod.`id` = p.`product_id`  
                  LEFT JOIN prod_color_order pco 
                    ON p.`product_id` = pco.`product_id` AND ap.`value` = pco.`attribute_id`
                WHERE ap.`product_id` = '.$aProductID.' AND ap.`attribute_id` = 1
                GROUP BY ap.id
                ORDER BY p.sorder, gorder, ap.`attribute_id`, ap.`id` ASC';

        return XDatabase::getAll($sql);
    }

    public function getSkuByAtrID($productID, $attrID)
    {
        $sql = 'SELECT
                  s.*,
                  a2p.id AS attr2prod_id
                FROM
                  sku s
                  INNER JOIN sku_element se
                    ON s.id = se.sku_id
                  INNER JOIN attr2prod a2p
                    ON se.attr2prod_id = a2p.id
                    AND a2p.value = '.$attrID.'
                WHERE s.product_id = '.$productID.'
                LIMIT 1';
        return XDatabase::getRow($sql);
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
                GROUP BY se1.id';
        return XDatabase::getAll($sql);
    }
}
