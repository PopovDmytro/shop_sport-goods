<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexDBList as RexDBList;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

/**
 * Class OrderManager
 *
 * Manager of Order
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class OrderManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\OrderEntity:shop.standart:1.0'
    );
    
    function __construct()
    {
        parent::__construct('rexorder', 'id');
    }  
     
    function _processFilter($key, $value)
    {
        if ($key == 'status' && ($value == '0' || $value == '')) {
            return ' AND t.`status` IN (1, 2) ';
        } elseif ($key == 'status' && $value == 3) {
            return ' AND t.`status` = 3';
        }
        
        return false;
    }    
    
    function getList($filters, $fields, $mod = false) {
        $order_by = $this->_uid;
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = '';
        
        $mod = 'order';
        
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
                            $pattern = '/^([0-9]+)-*([0-9]*)/';
                            preg_match($pattern, $filters['search'], $matches);
                            if ($matches) {
                                $sql_join .= ' INNER JOIN prod2order po ON t.id = po.`order_id` ';
                                $sql .= ' AND po.`product_id` = '.$matches[1];
                                if (isset($matches[2]) && $matches[2]) {
                                    $sql .= ' AND po.sku = '.$matches[2];    
                                }   
                            } else {          
                                $ors = array();
                                if ($fields && sizeof($fields)) {
                                    foreach ($fields as $field => $spec) {
                                        if ($field{0} != '_' && $field && intval($field).'' != $field) {
                                            $ors[] = '`t`.`'.$field.'` LIKE "%'.($value).'%"';
                                        }
                                    }
                                } elseif ($entity) {
                                    foreach ($entity as $field => $field_value) {
                                        if ($field{0} != '_') {
                                            $ors[] = '`t`.`'.$field.'` LIKE "%'.($value).'%"';
                                        }
                                    }
                                }
                                if ($ors) {
                                    $sql .= ' AND ('.implode(' OR ', $ors).')';
                                }
                            }
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`'.$key.'` = "'.($value).'"';
                        }
                }
            }
        }
        
        $sql_limit =' ORDER BY `t`.`'.$order_by.'` '.$order_dir.'
        LIMIT '.($page * $inpage - $inpage).', '.$inpage.'  ;';
        
        $sql = 'FROM '.$this->_table.' AS `t` '.$sql_join.' WHERE '.$sql;
        
        return array(
            0 => XDatabase::getAll('SELECT t.* '.$sql.$sql_limit),
            1 => XDatabase::getOne('SELECT COUNT(*) '.$sql));
    }     
    
    function getImage($skuid)
    {
        $sql = 'SELECT 
              pimg.`id`, pimg.`image`
            FROM sku_element AS sku 
              LEFT JOIN pimage pimg ON sku.attr2prod_id  = pimg.`attribute_id`  
            WHERE pimg.id IS NOT NULL AND sku.sku_id ='.$skuid;
            
        return XDatabase::getRow($sql);    
    }
}