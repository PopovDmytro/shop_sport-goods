<?php

class OrderManager extends \RexShop\OrderManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'OrderEntity:volley.standart:1.0',
        'RexShop\OrderManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );

    function __construct()
    {
        parent::__construct('rexorder', 'id');
    }

    function _processFilter($key, $value)
    {
        if ($key == 'status' && ($value == '0' || $value == '13')) {
            //return ' AND t.`status` IN (1, 2) ';
            return '';
        } elseif ($key == 'status' && $value == 3) {
            return ' AND t.`status` = 3';
        } elseif ($key == 'status' && $value == 12) {
            return ' AND t.`status` IN (1, 2, 4, 5, 7, 8, 9, 10, 11) ';
        }
        if ($key == 'date_from' && $value) {
            return ' AND DATE_FORMAT(t.`date_create`, "%Y-%m-%d") >= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_to' && $value) {
            return ' AND DATE_FORMAT(t.`date_create`, "%Y-%m-%d") <= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_from_dc' && $value) {
            return ' AND `date_create` >= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_to_dc' && $value) {
            return ' AND `date_create` <= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_from_ds' && $value) {
            return ' AND `date_sent` >= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_to_ds' && $value) {
            return ' AND `date_sent` <= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_from_dr' && $value) {
            return ' AND `date_received` >= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'date_to_dr' && $value) {
            return ' AND `date_received` <= "'.strftime('%F', strtotime($value)).'"';
        } elseif ($key == 'user_id' && $value) {
            return ' AND uoi.`user_id` = '.$value;
        } elseif ($key == 'search' && $value) {
            return ' AND `name` LIKE "%'.$value.'%"';
        } elseif ($key == 'ordertype' && $value) {
            return ' AND `type` = "'.$value.'"';
        } elseif ($key == 'transtype' && $value) {
            return '';
        }

        return false;
    }

    function getList($filters, $fields, $mod = false) {
        $order_by = $this->_uid;
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = ' LEFT JOIN `user` u ON t.`user_id` = u.`id` ';

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
            0 => XDatabase::getAll('SELECT t.*, u.`email` '.$sql.$sql_limit),
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

    function getOrderAmount()
    {
        $sql = 'Select count(id) from rexorder';
        return XDatabase::getOne($sql);
    }

    public function getStatistic($userID = false)
    {
        $sql = 'SELECT
                  SUM(th.price_opt) AS zakup,
                  SUM(th.sale_price) AS total,
                  COUNT(th.id) AS orders
                FROM
                (
                SELECT
                  o.id,
                  o.price_opt,
                  SUM(p2o.price * p2o.`count`) AS full_price,
                  FLOOR(SUM((p2o.price - (p2o.price - (p2o.price - p2o.price * p2o.discount / 100))) * p2o.`count`) -
                  SUM((p2o.price - (p2o.price - (p2o.price - p2o.price * p2o.discount / 100))) * p2o.`count`) * o.sale / 100) AS sale_price
                FROM
                  rexorder o
                  INNER JOIN prod2order p2o
                    ON o.id = p2o.`order_id`
                GROUP BY o.id) AS th';
        return XDatabase::getRow($sql);
    }

    public function getSale($userID)
    {
        $sql = 'SELECT
                  SUM(th.full_price) AS undiscounted,
                  SUM(th.last_price) AS discounted
                FROM
                  (SELECT
                    SUM(p2o.price * p2o.`count`) AS full_price,
                    FLOOR(SUM(p2o.price * IF(p2o.discount > 0, (100 - p2o.discount) / 100, 1) * p2o.`count`)  * IF(o.sale > 0, (100 - o.sale) / 100, 1)) AS last_price
                  FROM
                    rexorder o
                    INNER JOIN prod2order p2o
                      ON o.id = p2o.`order_id`
                  WHERE o.user_id = '.intval($userID).'
                    AND o.`status` = 3
                  GROUP BY o.id
                  ORDER BY o.id ASC) AS th';

        return XDatabase::getRow($sql);
    }

    public function getAllSale($userID)
    {
        $sql = 'SELECT
                  SUM(th.full_price) AS undiscounted,
                  SUM(th.last_price) AS discounted
                FROM
                  (SELECT
                    SUM(p2o.price * p2o.`count`) AS full_price,
                    FLOOR(SUM(p2o.price * IF(p2o.discount > 0, (100 - p2o.discount) / 100, 1) * p2o.`count`)  * IF(o.sale > 0, (100 - o.sale) / 100, 1)) AS last_price
                  FROM
                    rexorder o
                    INNER JOIN prod2order p2o
                      ON o.id = p2o.`order_id`
                  WHERE o.user_id = '.intval($userID).'
                  GROUP BY o.id
                  ORDER BY o.id ASC) AS th';

        return XDatabase::getRow($sql);
    }

    public function getTotalOrderSum($userID)
    {
        $sql = 'SELECT SUM(th.price * IF(th.discount > 0, (100 - th.discount) / 100, 1)) AS total
                FROM
                (SELECT
                  o.sale AS discount,
                  FLOOR(SUM((p2o.price - p2o.price*p2o.discount/100) *p2o.count)) AS price
                FROM
                  rexorder o
                  LEFT JOIN prod2order p2o
                    ON o.id = p2o.`order_id`
                WHERE o.user_id = '.intval($userID).'
                  AND o.status IN (2, 3, 5)
                GROUP BY o.id) AS th';
        return XDatabase::getOne($sql);
    }

    public function getOrderValues($orderID)
    {
       /* $sql = 'SELECT
                  sale,
                  //SUM(p2o.price * p2o.`count`) AS full_price,//
                  SUM(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`) AS full_price,
                  FLOOR(SUM((p2o.price - (p2o.price - (p2o.price  - p2o.price * p2o.discount / 100)))* p2o.`count`) -
                  SUM((p2o.price - (p2o.price - (p2o.price  - p2o.price * p2o.discount / 100)))* p2o.`count`) * o.sale / 100) AS sale_price
                FROM
                  rexorder o
                  INNER JOIN prod2order p2o
                    ON o.id = p2o.`order_id`
                WHERE o.id = '.intval($orderID);
        //echo $sql;
        return XDatabase::getRow($sql); */
       $sql = 'SELECT
                  o.sale,
                  SUM(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`) AS full_price,
                  SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) AS sale_price
                FROM
                  rexorder o
                  INNER JOIN prod2order p2o
                    ON o.id = p2o.`order_id`
                WHERE o.id = '.intval($orderID);
                  
        return XDatabase::getRow($sql);
     
    }

    public function getOrderCount($userID)
    {
        $sql = 'SELECT  COUNT(id) AS `count` FROM rexorder WHERE user_id ='.$userID.' AND `status` = 3';//IN (2, 3, 5)';
        return XDatabase::getOne($sql);
    }
    public function getAllOrderCount($userID)
    {
        $sql = 'SELECT  COUNT(id) AS `count` FROM rexorder WHERE user_id =' . $userID;
        return XDatabase::getOne($sql);
    }


    public function getTotalScoreByFilters($filters)
    {
        if (isset($filters['date_radio']) && $filters['date_radio'] == 'all') {
            $date_from = '';
            $date_to = '';
        } else {
            $date_from = (isset($filters['date_from']) && !$filters['date_from']=='') ? 'AND o.date_create > CAST("'.strftime('%Y-%m-%d', strtotime($filters['date_from'])).'" AS DATETIME) ' : '';
            $date_to = (isset($filters['date_to']) && !$filters['date_to']=='') ? 'AND o.date_create < CAST("'.strftime('%Y-%m-%d', strtotime($filters['date_to'])).'" AS DATETIME) ' : '';
        }

        $status = (isset($filters['status']) && !$filters['status']=='') ? 'AND o.status = '.$filters['status'].' ' : '';

        $sql = 'SELECT
                  SUM(th.price_opt) AS zakup,
                  SUM(th.sale_price) AS total,
                  COUNT(th.id) AS orders
                FROM
                  (SELECT
                    o.id,
                    o.price_opt,
                    SUM(p2o.price * p2o.`count`) AS full_price,
                    FLOOR(SUM((p2o.price - (p2o.price - (p2o.price - p2o.price * p2o.discount / 100))) * p2o.`count`) -
                    SUM((p2o.price - (p2o.price - (p2o.price - p2o.price * p2o.discount / 100))) * p2o.`count`) * o.sale / 100) AS sale_price
                  FROM
                    rexorder o
                    INNER JOIN prod2order p2o
                      ON o.id = p2o.`order_id`
                      WHERE 1=1 '.$date_from.$date_to.$status.'
                  GROUP BY o.id) AS th';

        $res = XDatabase::getRow($sql);
        return $res;
    }

    public function getOrderList($uniqID, $isSingle = false)
    {

        $customWhere = ' WHERE o.`user_id` = '.intval($uniqID).' ';
        if ($isSingle) {
            $customWhere =  'WHERE o.`id` = '.intval($uniqID).' ';
        }
        $sql = 'SELECT
                  o.*,
                  SUM(p2o.price * p2o.`count`) AS full_price,
                  FLOOR(SUM((p2o.price - p2o.price * (p2o.discount / 100)) * p2o.`count`)  -
                  SUM((p2o.price - p2o.price * (p2o.discount / 100)) * p2o.`count`) * o.sale/100) AS sale_price
                FROM
                  rexorder o
                  INNER JOIN prod2order p2o
                    ON o.`id` = p2o.`order_id`
                '.$customWhere.'
                GROUP BY o.id
                ORDER BY p2o.`id` DESC';

        return XDatabase::getAll($sql);
    }

    public function getProductValues($orderID, $productID, $skuID = false)
    {
        $sku = $skuID ? ' AND sku = '.$skuID : '';
        $sql = 'SELECT
                  price, discount,
                  FLOOR(price - price * discount / 100) AS user_price
                FROM
                  prod2order
                WHERE order_id = '.intval($orderID).'
                  AND product_id = '.intval($productID).$sku;
        return XDatabase::getRow($sql);
    }

    public function getUserSale($userID)
    {
        $discount = 0;

        $s = $this->getSale(intval($userID));
        $aDiscount =array();
        $aDiscount = XDatabase::getAll('SELECT * FROM discount ORDER BY payed_sum ASC');
        if(is_array($aDiscount)){
            foreach ($aDiscount as $key => $value) {
                if ($s['discounted'] >= $value['payed_sum']) {
                    $discount = $value['discount'];
                } else {
                    break;
                }
            }
        }


        if (!$discount) {
            $orders = XDatabase::getOne('SELECT COUNT(id) AS orders FROM rexorder WHERE user_id = '.intval($userID).' AND `status` IN (2, 3, 5)');
            if ($orders >= 1) {
                $discount = 5;
            } else {
                $discount = 0;
            }
        }

        return $discount;

    }

    public function getListOrderInfo ($orderId)
    {
        $sql = 'SELECT
                  r.`name`,
                  r.`name_single`,
                  r.`phone`,
                  r.`delivery`,
                  r.`city` AS city_name,
                  r.`fillials` AS fillials_name
                FROM
                  rexorder r
                WHERE r.`id` ='.$orderId ;
        return XDatabase::getRow($sql);
    }

    public function getOrderItems($orderID)
    {
        $sql = 'SELECT p2o.`product_id` as id, p.name, p2o.`count`, p2o.`discount`, p2o.price as full_price, FLOOR(p2o.price - (IF(p.`is_common_sale`, p2o.`discount`, 0) / 100) * p2o.price) AS price, s.`sku_article`, s.`id` AS sku_id, p.`is_common_sale`
                FROM rexorder o  INNER JOIN prod2order p2o ON o.id = p2o.`order_id` INNER JOIN product p
                ON p2o.`product_id` = p.`id` LEFT JOIN sku s  ON p2o.`sku` = s.id WHERE o.id = ' . intval($orderID);

        $prodList = XDatabase::getAll($sql);
        $i = 0;
        $sql = 'SELECT atp.`attribute_id`, a.`name`, skue.`sku_id`, sku.`sku_article`, sku.price as full_price, FLOOR(sku.`price` - (IFNULL(sku.`sale`, p.`sale`) / 100) * sku.`price`) AS price FROM `attr2prod` atp INNER JOIN `attribute` AS a ON atp.`value` = a.`id` INNER JOIN `sku_element` AS skue ON skue.`attr2prod_id` = atp.`id` INNER JOIN `sku` ON sku.`id` = skue.`sku_id` INNER JOIN product p ON atp.product_id = p.id WHERE ( sku.`quantity` > 0 AND atp.`attribute_id` = 1';

        foreach ($prodList as $item) {
            $queryAppend = '';
            if ($item['sku_id']) {
                $skuList = $this->getSkuListByColorSku(intval($item['id']), intval($item['sku_id']));
                $queryAppend .= ' OR (atp.`attribute_id` IN (2, 150, 188, 265) AND sku.`id` IN (' . $skuList . '))';
            }
            $queryAppend .= ') AND atp.`product_id` = ' . intval($item['id']) . ' GROUP BY a.`name` ';
            $prodList[$i]['attr'] = XDatabase::getAll($sql . $queryAppend);
            ++$i;
        }
        return $prodList;
    }

    public function getCountNewOrders()
    {
        $sql = 'SELECT COUNT(*) FROM rexorder WHERE `is_readed` = 0';
        return XDatabase::getOne($sql);
    }

    public function updateUnreadOrder()
    {
        $sql = 'UPDATE rexorder SET `is_readed` = 1  WHERE `is_readed` = 0';
        return XDatabase::getOne($sql);
    }

    public function getStatsByDate($dateFrom, $dateTo)
    {
        $whereCond = 'WHERE o.`date_update`  BETWEEN "' . $dateFrom . '" AND "' . $dateTo . '"';

        $queryMake = function($aggregation, $customWhere = false) use ($whereCond) {
            if ($customWhere) {
                $customWhere = ' AND ' . $customWhere;
            }

            return 'SELECT ' . $aggregation . ' FROM rexorder o ' . $whereCond . $customWhere;
        };

        $dateDayDiff  = abs(strtotime($dateFrom) - strtotime($dateTo));
        $dateDayDiff  = floor($dateDayDiff / (60 * 60 * 24));
        $groupBy      = 'YEAR(o.`date_update`), MONTH(o.`date_update`) ASC';
        $isMonthRange = $dateDayDiff <= 31;
        if ($isMonthRange) {
            $groupBy = 'CONCAT(YEAR(o.`date_update`), "/", WEEK(o.`date_update`))';
        }
        /*
           $query    = 'SELECT COUNT(o.id) AS total, o.date_update,
        SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) AS sale_price,
        SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) - SUM(s.`price_opt`) * p2o.count AS net_profit FROM rexorder o INNER JOIN prod2order p2o ON o.`id` = p2o.`order_id` INNER JOIN sku s ON p2o.`sku` = s.`id` ' . $whereCond . ' AND o.status = 3 GROUP BY ' . $groupBy . ' ORDER BY o.`date_update` ASC';

         */


        $query    = 'SELECT COUNT(o.id) AS total, o.date_update,
        SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) AS sale_price, 
        SUM(s.`price_opt` * p2o.count) AS purchase,
        SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) - SUM(s.`price_opt` * p2o.count) AS net_profit FROM rexorder o INNER JOIN prod2order p2o ON o.`id` = p2o.`order_id` INNER JOIN sku s ON p2o.`sku` = s.`id` ' . $whereCond . ' AND o.status = 3 GROUP BY ' . $groupBy . ' ORDER BY o.`date_update` ASC';

        $sumStats = XDatabase::getAll($query);

        $list = array(array('', 'Доход','Расход', 'Чистая прибыль'));

        foreach ($sumStats as $key => $item) {
            $dateFormat = date('F, Y', strtotime($item['date_update']));
            if ($isMonthRange) {
                $dateFormat = ($key + 1) . '-я неделя';
            }

            $list[] = array($dateFormat, intval($item['sale_price']),-intval($item['purchase']), intval($item['net_profit']));
        }

        $stats = array(
            'total_count'       => XDatabase::getOne($queryMake('COUNT(id) AS total_count')),
            'processed_admin'   => XDatabase::getOne($queryMake('COUNT(id) AS processed_admin', '`user_id` = 0')),
            'processed_store'   => XDatabase::getOne($queryMake('COUNT(id) AS processed_store', '`user_id` > 0')),
            'closed_orders'     => XDatabase::getOne($queryMake('COUNT(id) AS closed_orders', '`status` = 3')),
            'canceled_orders'   => XDatabase::getOne($queryMake('COUNT(id) AS canceled_orders', '`status` = 6')),
        );

        if (!isset($stats['total_count']) || !$stats['total_count']) {
            RexResponse::error('Не найдено ни одного заказа!');
        }

        $stats = array(
            'first' => array(
                'chart_type' => 'PieChart',
                'title' => 'Количество заказов всего - ' . $stats['total_count'],
                'data'  => array(
                    array('title', ''),
                    array('Оформлены в админке', intval($stats['processed_admin'])),
                    array('Оформлены в магазине', intval($stats['processed_store'])),
                )
            ),
            'second' => array(
                'chart_type'  => 'MaterialColumn',
                'title' => 'Количество завершенных заказов: ' . intval($stats['closed_orders']),
                'data'  => $list
            ),
            'thrid' => array(
                'chart_type'  => 'PieChart',
                'title' => 'Соотношение между завершенными и отмененным заказами',
                'data'  => array(
                    array('title', ''),
                    array('Отмененные', intval($stats['canceled_orders'])),
                    array('Завершенные', intval($stats['closed_orders'])),
                )
            )
        );

        return $stats;
    }

    public function getDetails($orderID)
    {
        if (!$orderID) {
            return false;
        }

        $prod2OrderManager  = RexFactory::manager('prod2Order');
        $prod2OrderManager->getByWhere('order_id = ' . $orderID);
        $productList        = $prod2OrderManager->getCollection();
        $dataList = array();
        if (count($productList)) {
            foreach ($productList as $product_key => $prod2Order) {
                $productEntity = RexFactory::entity('product');
                if ($productEntity->get($prod2Order['product_id'])) {
                    if ($prod2Order['sku']) {
                        $skuEntity = RexFactory::entity('sku');
                        $skuEntity->get($prod2Order['sku']);
                        $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                            htmlspecialchars('<td class="cart-attr-l">'),
                            htmlspecialchars(''),
                            htmlspecialchars('<td class="cart-attr-r">'),
                            htmlspecialchars('</td>'));
                        $dataList[$product_key]['sku']         = $skuName;
                        $dataList[$product_key]['skuprice']    = $skuEntity->price;
                        $dataList[$product_key]['sku_article'] = $skuEntity->sku_article;
                        $dataList[$product_key]['prices']      = $this->getProductValues($orderID, $productEntity->id, $skuEntity->id);
                    } else {
                        $dataList[$product_key]['prices'] = $this->getProductValues($orderID, $productEntity->id);
                    }
                    $dataList[$product_key]['product'] = $productEntity;
                } else {
                    continue;
                }

                $categoryEntity = RexFactory::entity('pCatalog');
                if ($categoryEntity->get($productEntity->category_id)) {
                    $dataList[$product_key]['category'] = $categoryEntity;
                } else {
                    continue;
                }

                $dataList[$product_key]['prod2Order']   = $prod2Order;
                $dataList[$product_key]['imagesku']     = $this->getImage($prod2Order['sku']);
                $pimageManager = RexFactory::manager('pImage');
                $pimageManager->getImageByProductOrSku($productEntity->id, $prod2Order['sku']);
                $list          = $pimageManager->getCollection();

                if (count($list) == 1) {
                    $dataList[$product_key]['image'] = $list[0];
                } else {
                    $dataList[$product_key]['image'] = false;
                }

                $attributeList = array();
                if (strlen(trim($prod2Order['attributes'])) > 0) {
                    $tmp = explode(';', $prod2Order['attributes']);

                    foreach ($tmp as $data) {
                        $data = explode(':', $data);

                        $arrtibuteEntity = RexFactory::entity('attribute');
                        $arrtibuteEntity->get($data[0]);
                        $attributeList[$data[0]]['key'] = $arrtibuteEntity;

                        $arrtibuteEntity = RexFactory::entity('attribute');
                        $arrtibuteEntity->get($data[1]);
                        $attributeList[$data[0]]['value'] = $arrtibuteEntity;
                    }
                }
                $dataList[$product_key]['attributes'] = $attributeList;
            }
        } else {
            $dataList = false;
        }

        return $dataList;
    }

    public function getTextStatus($statusID)
    {
        $statuses = $this->getOrderStatuses();

        return isset($statuses[$statusID]) ? $statuses[$statusID]['name'] : 'Не определен';
    }

    public function getOrderStatuses()
    {
        return array(
            1 => array(
                'name' => 'Новый',
                'color' => '#FF0066'
            ),
            2 => array(
                'name' => 'Оплачен',
                'color' => '#336633'
            ),
            3 => array(
                'name' => 'Завершен',
                'color' => '#999999'
            ),
            4 => array(
                'name' => 'Оплачивается',
                'color' => '#54A1C3'
            ),
            5 => array(
                'name' => 'Доставляется',
                'color' => '#000'
            ),
            6 => array(
                'name' => 'Отменен',
                'color' => '#F6CE0A'
            ),
            7 => array(
                'name' => 'Доставлен',
                'color' => '#0905F6'
            ),
            8 => array(
                'name' => 'Обрабатывается',
                'color' => '#92d050'
            ),
            9 => array(
                'name' => 'СРОЧНО',
                'color' => '#ff0066'
            ),
            10 => array(
                'name' => 'Возврат',
                'color' => '#ff0000'
            ),
            11 => array(
                'name' => 'Обмен',
                'color' => '#ffff00'
            )
        );
    }

    public function getSkuListByColorSku($productID, $skuID)
    {
        $sql = 'SELECT
                  s.id
                FROM
                  sku s
                  INNER JOIN sku_element se
                    ON s.id = se.sku_id
                  INNER JOIN attr2prod a2p
                    ON se.attr2prod_id = a2p.id
                    AND a2p.value =
                    (SELECT
                      atp2.`value`
                    FROM
                      sku_element AS skue2
                      INNER JOIN attr2prod AS atp2
                        ON atp2.`id` = skue2.`attr2prod_id`
                    WHERE atp2.`product_id` = "' . $productID . '"
                      AND skue2.`sku_id` = "' . $skuID . '"
                      AND atp2.`attribute_id` = 1
                    LIMIT 1)
                WHERE s.product_id = "' . $productID . '"
                  AND s.`quantity` > 0 ';
        $skuList = XDatabase::getAll($sql);
        $skuListStr = '';

        foreach ($skuList as $item) {
            $skuListStr .= $item['id'] . ', ';
        }
        $skuListStr = trim($skuListStr, ', ');
        return $skuListStr;
    }

    public function getOrdersInfo($filters, $fields, $dateFrom, $dateTo){
        $inpage = $filters['inpage'];
        $page   = $filters['page'];

        $sql_select_orders_head = "SELECT 
                                     DISTINCT o.id";
        $sql_select_orders      = " FROM
                                      rexorder o 
                                    INNER JOIN prod2order p2o 
                                      ON p2o.order_id = o.id 
                                    INNER JOIN product p 
                                      ON p.id = p2o.product_id 
                                    WHERE o.date_update BETWEEN '{$dateFrom}' 
                                      AND '{$dateTo}' 
                                      AND o.status = '3'
                                    ORDER BY o.id DESC";
        $sql_limit = " LIMIT " . ( $page - 1 ) * $inpage . ", " . $inpage;
        $selected_orders = XDatabase::getCol( $sql_select_orders_head . $sql_select_orders . $sql_limit );

        if ( !empty($selected_orders) ) {
            $sql = "SELECT
                  p2o.order_id,
                  p.name,
                  IF(
                    u.id IS NOT NULL,
                    CONCAT(u.lastname, ' ', u.name),
                    o.name
                  ) AS user_name,
                  s.sku_article AS product_article,
                  p2o.count,
                  p2o.price,
                  IF( s.id, s.price_opt, p.price_opt ) AS price_opt,
                  p2o.discount,
                  o.sale
                FROM
                  rexorder o 
                  INNER JOIN prod2order p2o 
                    ON p2o.order_id = o.id 
                  INNER JOIN product p 
                    ON p.id = p2o.product_id 
                  INNER JOIN sku s 
                    ON p2o.sku = s.id 
                  LEFT JOIN user u 
                    ON u.id = o.user_id   
                WHERE o.date_update BETWEEN '{$dateFrom}' 
                  AND '{$dateTo}' 
                  AND o.status = '3' 
                  AND p2o.order_id IN ( " . implode(", ", $selected_orders) . " )";

            $order_products = XDatabase::getAll($sql);

            $orders = array();
            foreach ($order_products as $order_product) {
                $orders[$order_product['order_id']]['id']           = $order_product['order_id'];
                $orders[$order_product['order_id']]['sale']         = $order_product['sale'];
                $orders[$order_product['order_id']]['user_name']    = $order_product['user_name'];

                $product['name']            = $order_product['name'];
                $product['count']           = $order_product['count'];
                $product['product_article'] = $order_product['product_article'];
                $product['price']           = $order_product['discount'] ? round( $order_product['price'] * ( 1 - $order_product['discount'] / 100 ) ) : $order_product['price'];
                $product['total_price']     = $product['price'] * $order_product['count'];
                $product['price_opt']       = $order_product['price_opt'];
                $product['total_price_opt'] = $order_product['price_opt'] * $order_product['count'];

                if (isset($orders[$order_product['order_id']]['total_price'])) {
                    $orders[$order_product['order_id']]['total_price'] += $product['total_price'];
                    $orders[$order_product['order_id']]['total_price_opt'] += $product['total_price_opt'];
                } else {
                    $orders[$order_product['order_id']]['total_price'] = $product['total_price'];
                    $orders[$order_product['order_id']]['total_price_opt'] = $product['total_price_opt'];
                }
                $orders[$order_product['order_id']]['total_sale_price'] = round( $orders[$order_product['order_id']]['total_price'] * ((100 - $orders[$order_product['order_id']]['sale']) / 100) );

                $orders[$order_product['order_id']]['products'][] = $product;
            }

            krsort( $orders );
            return array(
                0 => $orders,
                1 => XDatabase::getOne('SELECT COUNT(DISTINCT (o.id)) ' . $sql_select_orders));
        } else {
            RexResponse::error('По выбранным датам нет данных о заказах!');
        }
    }

    function  getTotalsByPeriod( $dateFrom, $dateTo ) {
        $sql = "SELECT 
                  COUNT(DISTINCT o.id) AS total,
                  SUM(
                    ROUND(
                      FLOOR(
                        p2o.price - p2o.price * p2o.discount / 100
                      ) * p2o.`count` - FLOOR(
                        (
                          p2o.price - p2o.price * p2o.discount / 100
                        )
                      ) * p2o.`count` * (o.sale / 100)
                    )
                  ) AS sale_price,
                  SUM(s.`price_opt` * p2o.count) AS purchase,
                  SUM(
                    ROUND(
                      FLOOR(
                        p2o.price - p2o.price * p2o.discount / 100
                      ) * p2o.`count` - FLOOR(
                        (
                          p2o.price - p2o.price * p2o.discount / 100
                        )
                      ) * p2o.`count` * (o.sale / 100)
                    )
                  ) - SUM(s.`price_opt` * p2o.count) AS net_profit 
                FROM
                  rexorder o 
                  INNER JOIN prod2order p2o 
                    ON o.`id` = p2o.`order_id` 
                  INNER JOIN sku s 
                    ON p2o.`sku` = s.`id` 
                WHERE o.`date_update` BETWEEN '{$dateFrom}' 
                  AND '{$dateTo}' 
                  AND o.status = 3 
                ORDER BY o.`date_update` ASC ";
        return XDatabase::getAll( $sql );
    }
}