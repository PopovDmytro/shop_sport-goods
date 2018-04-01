<?php

class OrderAdminController extends \RexShop\OrderAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\OrderEntity:shop.standart:1.0',
        'RexShop\OrderManager:shop.standart:1.0',
        'RexShop\Prod2OrderManager:shop.standart:1.0',
        'RexShop\Prod2OrderEntity:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'UserEntity:volley.standart:1.0',
        'CityEntity:volley.standart:1.0',
        'CityManager:volley.standart:1.0',
        'FillialsEntity:volley.standart:1.0',
        'FillialsManager:volley.standart:1.0',
        'RexShop\OrderAdmin:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
    );

    protected $add_dialog_width = 700;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 700;
    protected $edit_dialog_height = 424;

    protected function _getFields($fields)
    {
        return array(
            array('<table id="order_table" cellspacing="0" cellpadding="0"><tr><td class="id_order"><b>Номер заказа</b></td></tr><tr><td><br/>Добавлен<br/>Изменен</td></tr></table>', array($this, '_DGDateCreate'), array('width' => 60)),
            array('<table id="order_table_new" cellspacing="0" cellpadding="0"><tr><td class="status">Статус</td><td class="accepted">Принял</td></tr><tr><td colspan="2" class="td">ФИО<br/>Номер телефона<br/>Город<br/>Способ оплаты</td></tr><tr class="user_bind"><td colspan="2">Привязка к профилю</td></tr></table>', array($this, '_DGUser'), array('width' => 190)),
            array('<b>Комментарий</b>', array($this, '_DGCom'), array('align' => 'center', 'width' => 90)),
            array('', array($this, '_DGActions'), array('class' => 'order-actions-column')),
//            array('Привязка к профилю', array($this, '_DGUserBind'), array('class' => 'order-user-bind-column')),
            array('&nbsp;', array($this, 'getComment')),
        );
    }

    protected function _getActionParams($param)
    {
        $arr = array(
            array(
                'title' => RexLang::get('default.edit'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemedit',
                'allow' => 'edit',
                'img' => 'ui-icon-pencil'
            ),
            array(
                'title' => RexLang::get('default.delete'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemdelete',
                'allow' => 'delete',
                'img' => 'ui-icon-trash'
            ), array(
                'title' => RexLang::get('default.sendsms'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemsend',
                'allow' => 'send',
                'img' => 'ui-icon-mail-closed'
            ), array(
                'title' => RexLang::get('default.prod2order'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemprod2order',
                'url' => 'index.php?mod=prod2Order&task='.$param['record'][$this->entity->__uid],
                'allow' => 'prod2order',
                'img' => 'ui-icon-prod2order'
            )
        );

        return $arr;
    }

    function _DGCom($param)
    {
        $field = "";
        if ($param['record']['comment'] and $param['record']['admin_comment']) {
            $field .= '<b>От пользователя:</b><br/>'.$param['record']['comment'].'<br/><br/><b>От модератора:</b><br/>'.$param['record']['admin_comment'];
        } elseif ($param['record']['admin_comment']) {
            $field .= '<b>От модератора:</b><br/>'.$param['record']['admin_comment'];
        } elseif ($param['record']['comment']) {
            $field .= '<b>От пользователя:</b><br/>'.$param['record']['comment'];
        } else {
            $field .= 'нет';
        }
        
        return $field;
    }

    function _DGId($param)
    {
        return $param['record']['id']."<input class='hidden' type='hidden' value='".$param['record']['id']."'>";
    }

    function _DGDateCreate($param)
    {
        $field = '<table id="order_table" cellspacing="0" cellpadding="0"><tr><td class="id_order"><b>№ '.$param['record']['id'].'</b></td></tr><tr><td><br/>'.date('Y-m-d', strtotime($param['record']['date_create'])).'<br/>'.date('H:i:s', strtotime($param['record']['date_update'])).'</td></tr></table>';
        return $field;
    }

    function _DGDateUpdate($param)
    {
        return date('Y-m-d', strtotime($param['record']['date_update']));
    }

    function _DGUser($param)
    {
        $userEntity = RexFactory::entity('user');
        $userEntity->get($param['record']['user_id']);
        $city = false;

        if ($param['record']['city']) {
            $city = $param['record']['city'];
        }

        // if ($param['record']['city']) {
        //     // $cityEntity = RexFactory::entity('city');
        //     // $cityEntity->getByWhere('id ='.$param['record']['city']);
        //     // $city = $cityEntity->name;
        //     $city = $userEntity->city;
        // }
        $fillials = false;
        if ($param['record']['fillials']) {
            $fillials = $param['record']['fillials'];
        }

        // if ($param['record']['fillials']) {
        //     $fillialsEntity = RexFactory::entity('fillials');
        //     $fillialsEntity->getByWhere('id ='.$param['record']['fillials']);
        //     $fillials = $fillialsEntity->name;
        // }

        $statusList = $this->manager->getOrderStatuses();
        $currentStatusColor = isset($statusList[$param['record']['status']]) ? $statusList[$param['record']['status']]['color'] : '#000';
        $status = '<select class="order-status" data-order-id="' . $param['record']['id'] . '" style="color:' . $currentStatusColor . '">';
        foreach ($statusList as $statusID => $statusData) {
            $status .= '<option style="color:' . $statusData['color'] . '" value="' . $statusID . '" ' . ($statusID == $param['record']['status'] ? 'selected="selected"' : '') . ' data-color="' . $statusData['color'] . '" >' . $statusData['name'] . '</option>';
        }

        $status .= '</select>';

        $userEntity->get($param['record']['user_id']);
        // $fillialsEntity = RexFactory::entity('fillials');
        // $fillialsEntity->getByWhere('id ='.$param['record']['fillials']);
        // $fillials = $fillialsEntity->name;
        RexDisplay::assign('fillials', $fillials);
        // $cityEntity = RexFactory::entity('city');
        // $cityEntity->getByWhere('id ='.$param['record']['city']);
        // $city = $cityEntity->name;
        
        if ($param['record']['sale'] != 0) {
           $field = '<table id="order_table_new" cellspacing="0" cellpadding="0"><tr><td class="status">'.$status.'</td><td class="accepted">'.$param['record']['operator'].'</td></tr><tr><td colspan="2" style="position:relative;" class="td">'.$param['record']['name'].' '.($param['record']['name_single'] ? $param['record']['name_single']: '').'<br/>Телефон: '.$param['record']['phone'].'<br/>Город: '.$city.'<br/>Филиал: '.$fillials.'<br/>Способ оплаты: '.$param['record']['delivery'].'<div class="order_sale">'.$param['record']['sale'].'%</div></td></tr>';
        } else {
            $field = '<table id="order_table_new" cellspacing="0" cellpadding="0"><tr><td class="status">'.$status.'</td><td class="accepted">'.$param['record']['operator'].'</td></tr><tr><td colspan="2" class="td">'.$param['record']['name'].' '.($param['record']['name_single'] ? $param['record']['name_single']: '').'<br/>Телефон: '.$param['record']['phone'].'<br/>Город: '.$city.'<br/>Филиал: '.$fillials.'<br/>Способ оплаты: '.$param['record']['delivery'].'</td></tr>';
        }

        $field .= '<tr class="user_bind"><td colspan="2"><input type="checkbox" data-order-id="'.$param['record']['id'].'" class="bing-order-action" data-user-id="'.$param['record']['user_id'].'" '.($param['record']['user_id'] != 0 ? 'checked' : '').' '.($param['record']['user_id'] == 0 ? 'disabled' : '').'>
        <input type="text" class="order-email-bind" data-order-id="'.$param['record']['id'].'" value="'.$param['record']['email'].'" '.($param['record']['user_id'] != 0 ? 'disabled' : '').'></td>
            </tr></table>';
        
        return $field;

    }

    function _DGStatus($param)
    {
        $statusList     = $this->manager->getOrderStatuses();
        $currentStatus  = isset($statusList[$param['record']['status']]) ? $statusList[$param['record']['status']] : false;
        if (!$currentStatus) {
            return '<span style="color:#000">Статус не определен</span>';
        }

        return '<span style="color:' . $currentStatus['color'] . '">' . $currentStatus['name'] . '</span>';
    }

    function _DGUserBind($param)
    {
        return '<input type="checkbox" data-order-id="'.$param['record']['id'].'" class="bing-order-action" data-user-id="'.$param['record']['user_id'].'" '.($param['record']['user_id'] != 0 ? 'checked' : '').' '.($param['record']['user_id'] == 0 ? 'disabled' : '').'> '.
                '<input type="text" class="order-email-bind" data-order-id="'.$param['record']['id'].'" value="'.$param['record']['email'].'" '.($param['record']['user_id'] != 0 ? 'disabled' : '').'>';
    }

    public function getUpdateOrderStatus()
    {
        if (!RexResponse::isRequest()) {
            exit();
        }

        RexResponse::init();

        $orderID  = Request::get('order_id', false);
        $statusID = Request::get('status_id', false);
        if (!$orderID || $statusID === false) {
            RexResponse::response(array('message' => 'Не верно указаны параметры!'));
        }

        $statuses = $this->manager->getOrderStatuses();
        if (!isset($statuses[$statusID])) {
            RexResponse::response(array('message' => 'Не верное значение для статуса заказа (' . $statusID . ')!'));
        }

        $this->entity->get($orderID);
        if (!$this->entity->id) {
            RexResponse::response(array('message' => 'Заказ с ID ' . $orderID . ' не найден!'));
        }

        $this->entity->status = $statusID;
        if (!$this->entity->update()) {
            RexResponse::response(array('message' => 'Ошибка при обновлении статуса заказа с ID ' . $this->entity->id));
        }

        $statusEnded = 3;

        if ($this->entity->status == $statusEnded) {
            $this->checkFirstOrderOnClose($this->entity);
        }

        RexResponse::response(array('message' => 'success', 'page' => XSession::get('product_dg_page')));
    }

    function checkFirstOrderOnClose ($entity)
    {
        $noOrders       = 0;
        $hasFirstOrder  = 1;
        $smsSent        = 2;

        $userEntity = RexFactory::entity('user');
        $userEntity->get($entity->user_id);

        if ($userEntity && $userEntity->has_first_order == $noOrders) {
            $userOrdersSql = 'SELECT COUNT(*) FROM `rexorder` WHERE user_id = ?';

            $userOrderCount = XDatabase::getOne($userOrdersSql, array($userEntity->id));
            if ($userOrderCount == 1) {
                $userEntity->has_first_order = $hasFirstOrder;
                $userEntity->update();
            }

            if ($userEntity->phone && PHPSender::validateNumber($userEntity->phone)) {
                $sunrise = "10:00:00";
                $sunset = "18:00:00";
                $dateCurrent = DateTime::createFromFormat('H:i:s', date('H:i:s'));
                $dateSunrise = DateTime::createFromFormat('H:i:s', $sunrise);
                $dateSunSet  = DateTime::createFromFormat('H:i:s', $sunset);

                if ($dateCurrent > $dateSunrise && $dateCurrent < $dateSunSet)
                {
                    PHPSender::sendSms($userEntity->phone, RexLang::get('order.sms.info_after_order'));
                    $userEntity->has_first_order = $smsSent;
                    $userEntity->update();
                }
            }
        }
    }

    function getComment($param)
    {
        $prices = $this->manager->getOrderValues($param['record']['id']);
        $orderInfo = $this->manager->getListOrderInfo($param['record']['id']);
        $field = '';

        $dataList = $this->manager->getDetails($param['record']['id']);

        RexDisplay::assign('order_values', $prices);
        RexDisplay::assign('id', $param['record']['id']);
        RexDisplay::assign('orderInfo', $orderInfo);
        RexDisplay::assign('comment', $param['record']['comment']);
        if ($dataList) {
            RexDisplay::assign('dataList', $dataList);
            $list = RexDisplay::fetch('order/list.inc.tpl');
            $field .= $list;   
            RexDisplay::assign('prod2Order', $list);
        }
        return $field;
    }

    protected function _deleteEntity($entity)
    {
        if (XDatabase::query('DELETE FROM `prod2order` WHERE `order_id` = ?', array($entity->id))) {
            return parent::_deleteEntity($entity);
        }
        return false;
    }

    function _DGProducts($param)
    {
        return '<a href="index.php?mod=prod2Order&task='.$param['record']['id'].'">товары<a>';
    }

    protected function _getFilters($filters)
    {
        if (!isset($filters['page'])) {
            $filters['page'] = XSession::get('product_dg_page');
            if (!$filters['page']) {
                $filters['page'] = 1;
            }
        }

        if (!isset($filters['status'])) {
            $orderStatus  = Request::get('order_status', false);

            if ($orderStatus) {
                $filters['status'] = $orderStatus;
            } else {
                $filters['status'] = XSession::get('order_status');
            }
        }

        if (!$filters['status']) {
            $filters['status'] = 12;
        }

        if (!isset($filters['search'])) $filters['search'] = '';
        if (!isset($filters['order_by'])) $filters['order_by'] = $this->entity->__uid;
        if (!isset($filters['order_dir'])) $filters['order_dir'] = 'DESC';
        if (!isset($filters['page'])) $filters['page'] = 1;
        if (!isset($filters['inpage'])) $filters['inpage'] = $this->inpage;

        $order_id = Request::get('order_id', false);
        if ($order_id) {
            $filters['id'] = $order_id;
        }

        if (isset($filters['date_radio']) && $filters['date_radio'] == 'all') {
            unset($filters['date_radio']);
            unset($filters['date_from']);
            unset($filters['date_to']);
        }

        if (isset($filters['user_id']) && !$filters['user_id']) {
            unset($filters['user_id']);
        }

        if (isset($filters['type']) && !$filters['type']) {
            unset($filters['type']);
        }

        if (isset($filters['is_arrival']) && $filters['is_arrival'] == 'all') {
            unset($filters['is_arrival']);
        }

        XSession::set('product_dg_page', $filters['page']);
        XSession::set('order_status', $filters['status']);

        RexDisplay::assign('order_status', $filters['status']);

        return $filters;
    }

    function getDefault()
    {
        parent::getDefault();
        $orderManager = RexFactory::manager('order');
        $this->manager->updateUnreadOrder();
        RexDisplay::assign('statistic', $orderManager->getStatistic());
        RexPage::setTitle('Заказы');
    }

    function getTotalizator()
    {
        RexResponse::init();
        $filters = Request::get('filters', array());
        $score = $this->manager->getTotalScoreByFilters($filters);
        RexResponse::response($score);
    }

    protected function _updateEntity($entity, $arr)
    {
        $orderEntity = Rexfactory::entity('order');
        $orderEntity->getByWhere('id ='.$entity->id);
        $entity->set($arr);
        if (!$entity->update()) {
            return 'Unable to update '.ucfirst($this->datagrid_mod);
        }
        return true;
    }

    public function getEdit()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();

        if (!RexPage::allow($this->datagrid_mod, 'edit')) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Permission error');
            } else {
                RexPage::addError('Permission error', $mod);
            }
        }

        $entity = $this->entity;
        if (!$this->task or $this->task < 1 or !$entity->get($this->task) || !$entity->{$entity->__uid}) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Wrong id');
            } else {
                RexPage::addError('Wrong id', $mod);
            }
        }
        
        $name = explode(' ', $entity->name);

        if (isset($name[0])){
            RexDisplay::assign('single_name', $name[0]);    
        }
        if (isset($name[1])){
            RexDisplay::assign('last_name', $name[1]);    
        }
        
        RexDisplay::assign('discount', $entity->sale);
        RexDisplay::assign('entity', $entity);
        RexDisplay::assign('dolar', 1);

        $managerRole = RexFactory::manager('user');
        $managerRole->getByWhere('1=1');
        RexDisplay::assign('role', $managerRole->getCollection());

        $userFillials = RexFactory::manager('fillials');
        $userFillials->getByWhere('1 = 1');
        RexDisplay::assign('fillials', $userFillials->getCollection());

        $cityManager = RexFactory::manager('city');
        $cityManager->getByWhere('1 = 1');
        RexDisplay::assign('cities', $cityManager->getCollection());

        $entityCity = RexFactory::entity('city');
        $entityCity->getByWhere("name = '".$entity->city."'");
        // $cityFillials = RexFactory::entity('city');
        // $cityFillials->getByWhere('id ='.$entity->city);
        RexDisplay::assign('city_id', $entityCity->id);

        RexDisplay::assign('filial', $entity->fillials);

        $productList = $this->manager->getOrderItems($this->task);

        RexDisplay::assign('productList', $productList);
        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->edit_dialog_width, $this->edit_dialog_height);
        }
    }

    function getAdd()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();
        $arr = Request::get('entity', array());
        $orderItems = array();
        if ($arr) {
            if (isset($arr['user_id'])) {
                $mOrder = RexFactory::manager('order');
                $arr['sale'] = $mOrder->getUserSale($arr['user_id']);
            }

            $entity = $this->entity;
            if ($arr['exist_id']) {
                if (!RexPage::allow($this->datagrid_mod, 'edit')) {
                    RexResponse::error('Permission error');
                }
                $entity->get($arr['exist_id']);

                $validate = $this->_validate($arr, $entity);
                if ($validate !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($validate);
                    } else {
                        RexPage::addError($validate, $mod);
                    }
                }
                if (!$entity->{$entity->__uid}) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error('Wrong '.ucfirst($this->datagrid_mod).' id');
                    } else {
                        RexPage::addError('Wrong '.ucfirst($this->datagrid_mod).' id', $this->mod);
                    }
                }

                $update = $this->_updateEntity($entity, $arr);
                if ($update !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($update);
                    } else {
                        RexPage::addError($update, $mod);
                    }
                }

                $statusEnded = 3;

                if ($entity->status == $statusEnded) {
                    $this->checkFirstOrderOnClose($entity);
                }

                $prod2OrderManager = RexFactory::manager('prod2Order');
                $prod2OrderManager->deleteByFields(array(
                    'order_id' => $arr['exist_id']
                ));

                if (isset($arr['order_items'])) {
                    foreach ($arr['order_items'] as $product) {
                        $prod2OrderEntity = RexFactory::entity('prod2Order');
                        $prod2OrderEntity->getByFields(array(
                            'order_id'   => $arr['exist_id'],
                            'product_id' => $product['product_id'],
                            'sku'        => $product['sku']
                        ));

                        $prod2OrderEntity->order_id = $arr['exist_id'];
                        $prod2OrderEntity->product_id = $product['product_id'];
                        $prod2OrderEntity->count = $product['count'];
                        $prod2OrderEntity->discount = $product['discount'];
                        $prod2OrderEntity->price = $product['price'];
                        if ($product['sku'] == ""){
                            $product['sku'] = 0;
                        }
                        $prod2OrderEntity->sku = $product['sku'];

                        $prod2OrderEntity = $prod2OrderEntity->id ? $prod2OrderEntity->update() : $prod2OrderEntity->create();
                        if (!$prod2OrderEntity) {
                            RexResponse::error('Unable to update prod2Order');
                        }
                    }
                }
            } else {
                if (!RexPage::allow($this->datagrid_mod, 'add')) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error('Permission error');
                    } else {
                        RexPage::addError('Permission error', $mod);
                    }
                }
                $validate = $this->_validate($arr);
                if ($validate !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($validate);
                    } else {
                        RexPage::addError($validate, $mod);
                    }
                }

                $create = $this->_createEntity($entity, $arr);
                if ($create !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($create);
                    } else {
                        RexPage::addError($create, $mod);
                    }
                }

                if (isset($arr['order_items'])) {
                    foreach ($arr['order_items'] as $product) {
                        $prod2OrderEntity = RexFactory::entity('prod2Order');
                        $product['order_id'] = $entity->id;
                        $my_create = $this->_createEntity($prod2OrderEntity, $product);
                        if ($my_create !== true) {
                            if (RexResponse::isRequest()) {
                                RexResponse::error($my_create);
                            } else {
                                RexPage::addError($my_create, $mod);
                            }
                        }
                    }
                }
            }

            if (RexResponse::isRequest()) {
                RexResponse::response($entity->id);
            } else {
                RexRoute::location(array('mod' => $mod));
            }
        }

        $userFillials = RexFactory::manager('fillials');
        $userFillials->getByWhere('1 = 1');
        RexDisplay::assign('fillials', $userFillials->getCollection());

        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->add_dialog_width, $this->add_dialog_height);
        }
    }

    function getAutocompleteUser()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      u.`id` AS user_id,
                      u.`lastname` AS user_name,
                      u.`name` AS name_single,
                      u.`phone` AS user_phone,
                      u.`city` AS user_city,
                      u.`fillials` AS user_fillials,
                      c.`name` AS city_name
                    FROM
                      `user` u LEFT JOIN city c ON c.`name` = u.`city`
                    WHERE u.`lastname` LIKE "%'.addslashes($query).'%"
                    GROUP BY u.`id`
                    LIMIT 30 ');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['user_name'].'='.$value['user_id'].'='.$value['user_phone'].'='.$value['user_city'].'='.$value['user_fillials'].'='.$value['city_name'].'='.$value['name_single']."\n";
            }
        }
        exit;
    }
    
    function getAutocompleteUser2()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      u.`id` AS user_id,
                      u.`name` AS user_name,
                      u.`lastname` AS last_name2,
                      u.`phone` AS user_phone,
                      u.`city` AS user_city,
                      u.`fillials` AS user_fillials,
                      c.`name` AS city_name
                    FROM
                      `user` u LEFT JOIN city c ON c.`name` = u.`city`
                    WHERE u.`name` LIKE "%'.addslashes($query).'%"
                    GROUP BY u.`id`
                    LIMIT 30 ');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['user_name'].'='.$value['user_id'].'='.$value['user_phone'].'='.$value['user_city'].'='.$value['user_fillials'].'='.$value['city_name'].'='.$value['last_name2']."\n";
            }
        }
        exit;
    }

    function getAutocompletePhone()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      u.`id` AS user_id,
                      u.`name` AS user_name,
                      u.`login` AS user_login,
                      u.`phone` AS user_phone,
                      u.`city` AS user_city,
                      u.`fillials` AS user_fillials,
                      c.`name` AS city_name
                    FROM
                      `user` u
                      LEFT JOIN city c
                        ON c.`id` = u.`city`
                    WHERE u.`phone` LIKE "%'.addslashes($query).'%"
                    GROUP BY u.`id`
                    LIMIT 30 ');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['user_name'].'='.$value['user_id'].'='.$value['user_phone'].'='.$value['user_city'].'='.$value['user_fillials'].'='.$value['city_name'].'='.$value['user_login']."\n";
            }
        }
        exit;
    }

    function getAutocompleteEmail()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);

        $query = preg_replace('#[^a-zа-я0-9\s\.\@]#isu', '', $query);

        $res = XDatabase::getAll('SELECT id, email FROM `user` WHERE email LIKE "%'.addslashes($query).'%" LIMIT 30 ');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['id'].'='.$value['email']."\n";
            }
        }
        exit;
    }

    function getAutocomplete()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      c.`id` AS city_id,
                      c.`name` AS city_name
                    FROM
                      city c
                    WHERE c.`name` LIKE "%'.addslashes($query).'%"
                    GROUP BY c.`id`
                    LIMIT 30');

        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['city_name'].'='.$value['city_id']."\n";
            }
        }
        exit;
    }

    function getSendsms()
    {
        RexResponse::init();
        $text = Request::get('text', false);
        $ord_id = Request::get('ord_id', false);

        if ($ord_id && $text) {
            $orderEntity = RexFactory::entity('order');
            $orderEntity->get($ord_id);
            $phone = 0;

            if ($orderEntity->phone && mb_strlen($orderEntity->phone, 'UTF-8') > 7) {
                $phone = $orderEntity->phone;
            } elseif ($orderEntity->user_id && $orderEntity->user_id > 0) {
                $userEntity = RexFactory::entity('user');
                $userEntity->get($orderEntity->user_id);
                if (isset($userEntity->id) && $userEntity->phone) {
                    $phone = $userEntity->phone;
                }
            }

            if ($phone) {
                PHPSender::sendSms($phone, $text);
                RexResponse::response('SMS успешно отправлено');
            } else {
                RexResponse::response('No phone number');
            }
        }
        RexResponse::response('Error!!!');
    }

    function getNew()
    {
        RexResponse::init();
        $count = $this->manager->getCountNewOrders();
        RexResponse::response($count ? $count : 'ok');
    }

    public function getLinkedOrders()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();

        $userID = Request::get('user_id');
        if (!$userID) {
            RexResponse::error('Пользователь не найден!');
        }

        $orders = XDatabase::getAll('SELECT `id`, `status`, CONCAT(`name_single`, " ", `name`) AS `user_name`, phone FROM rexorder WHERE `status` != 3 AND user_id = 0 ORDER BY date_create DESC');

        if (!$orders) {
            RexResponse::error('Не найдено ни одного заказ созданного менеджером!');
        }

        foreach ($orders as &$order) {
            $orderManager = RexFactory::manager('order');
            $order['text_status'] = $orderManager->getTextStatus($order['status']);
            unset($order['status']);
        }
        unset($order);

        RexDisplay::assign('linkedOrders', $orders);
        RexDisplay::assign('userID', $userID);
        $content = RexDisplay::fetch('/order/user_linked_order.inc.tpl');

        RexResponse::responseDialog($content, 700, 300);
    }

    public function getAddLinedOrders()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();

        $linkedOrders = Request::get('linked_orders', false);
        $userID = Request::get('user_id', false);

        if (!$linkedOrders || !$userID) {
            RexResponse::error('Не выбраны заказы или не указан пользователь!');
        }

        $updateQuery = 'UPDATE rexorder SET user_id = ' . intval($userID) . ' WHERE id IN (' . implode(',', $linkedOrders) . ')';
        XDatabase::query($updateQuery);
        if (XDatabase::isError()) {
            RexResponse::error('Ошибка прикрепления заказов');
        }

        RexResponse::response('ok');
    }

    public function getAttrList()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();

        $productId = Request::get('product_id', false);
        $skuId = Request::get('sku_id', false);

        $attrByProdQuery = 'SELECT a.`name`, skue.`sku_id`, sku.`sku_article`,sku.`sale` as ssale,   ROUND(sku.`price` - ( sku.`sale` / 100) * sku.`price`) AS price, sku.`price` as full_price FROM `attr2prod` atp INNER JOIN `attribute` AS a ON atp.`value` = a.`id` INNER JOIN `sku_element` AS skue ON skue.`attr2prod_id` = atp.`id` INNER JOIN `sku` ON sku.`id` = skue.`sku_id` INNER JOIN `product` p
    ON atp.`product_id` = p.id WHERE sku.`quantity` > 0 ';

        if (!$skuId || $skuId == '') {
            $selectList = '<select name="cart[1][sku]" class="sku-by-color" data-prodid="' . $productId . '"><option value="1">Не выбран</option>';
            $type = 'color';
            $attrByProdQuery .= ' AND atp.`attribute_id` = "1" ';
        } else {
            $skuId = str_replace(' ', '', $skuId);
            $skuList = $this->manager->getSkuListByColorSku($productId, $skuId);
            $selectList = '<select name="cart[2][sku]" class="sku-by-size" data-prodid="' . $productId . '"><option value="1">Не выбран</option>';
            $type = 'size';
            $attrByProdQuery .= ' AND atp.`attribute_id` IN (2, 150, 188, 265) AND sku.`id` IN (' . $skuList . ')';
        }

        $attrByProdQuery .= ' AND atp.`product_id` = "' . $productId . '" GROUP BY a.`name`';

        $attrByProd = XDatabase::getAll($attrByProdQuery);
        if ($attrByProd && !empty($attrByProd)) {
            foreach ($attrByProd as $value) {
                $selectList .= '<option name="entity[order_items][' . $productId . '][' . $type . ']" value="' . $value['sku_id'] . '" data-article="' . $value['sku_article'] . '" data-price="' . $value['price'] . '" data-sale="' . $value['ssale'] .'" data-full-price="' . $value['full_price'] . '">' . $value['name'] . '</option>';
            }
            $selectList .= '</select>';
        } else {
            $selectList = '&nbsp';
        }

        RexResponse::response($selectList);
    }

    public function getChangeOrderBind()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();

        $userID     = Request::get('user_id', false);
        $orderID    = Request::get('order_id', false);
        $bindStatus = Request::get('bind_status', 0);

        if (!$userID || !$orderID) {
            RexResponse::error('Не указанны идентификаторы пользователя и заказа!');
        }

        $userEntity = RexFactory::entity('user');
        $userEntity->get(intval($userID));

        if (!$userEntity->id) {
            RexResponse::error('Пользователь с ID - ' . $userID . ' не найден!');
        }

        $this->entity->get(intval($orderID));
        if (!$this->entity->id) {
            RexResponse::error('Заказ с ID ' . $orderID . ' не найден!');
        }

        $this->entity->user_id = $bindStatus ? $userEntity->id : 0;
        if (!$this->entity->update()) {
            RexResponse::error('Ошибка при обновлении записи!');
        }

        RexResponse::response('ok');
    }
}