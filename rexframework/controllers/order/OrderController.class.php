<?php

class OrderController extends \RexShop\OrderController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\OrderController:shop.standart:1.0',
        'Prod2OrderEntity:volley.standart:1.0',
        'SkuEntity:volley.standart:1.0',
        'RexShop\UserEntity:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0',
        'RexShop\Prod2OrderEntity:shop.standart:1.0',
        'RexShop\Prod2OrderManager:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'OrderEntity:volley.standart:1.0',
        'UserEntity:volley.standart:1.0',
        'CityEntity:volley.standart:1.0',
        'OrderManager:volley.standart:1.0',
        'RexFramework\UserManager:standart:1.0'
    );

    function getDefault()
    {
        $user = XSession::get('user');
        if (!$user or $user->id < 1) {
            exit;
        }

        $orderManager = RexFactory::manager('order');
        $sale = $orderManager->getSale(intval($user->id));
        $count = $orderManager->getOrderCount(intval($user->id));

        RexDisplay::assign('sale', $sale);
        RexDisplay::assign('o_count', $count);
        RexDisplay::assign('userSale', $this->manager->getUserSale($user->id));

        $orderList = $orderManager->getOrderList($user->id);

        if ($orderList and sizeof($orderList) > 0) {

            $this->getToOrderList($orderList, $orderManager);

            RexDisplay::assign('orderList', $orderList);
        }
    }

    function getToOrderList(Array &$orderList, $orderManager)
    {

        foreach ($orderList as $key => $order) {
            $prod2OrderManager = RexFactory::manager('prod2Order');
            $prod2OrderManager->getProductList($order['id']);
            $productList = $prod2OrderManager->getCollection('object');

            if ($productList and sizeof($productList) > 0) {
                $orderList[$key]['productList'] = array();
                foreach ($productList as $product_key => $prod2Order) {
                    //product
                    $productEntity = RexFactory::entity('product');
                    if ($productEntity->get($prod2Order->product_id)) {
                        if ($prod2Order->sku) {
                            $skuEntity = RexFactory::entity('sku');
                            $skuEntity->get($prod2Order->sku);
                            if ($skuEntity->price) {
                                $productEntity->price = $skuEntity->price;
                            }
                            $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                                htmlspecialchars('<td class="cart-attr-l"><b>'),
                                htmlspecialchars(':&nbsp;</b>'),
                                htmlspecialchars('<td class="cart-attr-r">'),
                                htmlspecialchars('</td>'));
                            $orderList[$key]['productList'][$product_key]['sku'] = $skuName;
                            $orderList[$key]['productList'][$product_key]['skuEntity'] = $skuEntity;
                            $orderList[$key]['productList'][$product_key]['prices'] = $orderManager->getProductValues($order['id'], $productEntity->id, $skuEntity->id);
                        } else {
                            $orderList[$key]['productList'][$product_key]['prices'] = $orderManager->getProductValues($order['id'], $productEntity->id);
                        }
                        //image alias
                        $pCatalog = RexFactory::entity('pCatalog');
                        $pCatalog->get($productEntity->category_id);
                        $orderList[$key]['productList'][$product_key]['img_alias'] = $pCatalog->alias;
                        $orderList[$key]['productList'][$product_key]['product'] = $productEntity;


                    } else {
                        continue;
                    }

                    //prod2Order
                    $orderList[$key]['productList'][$product_key]['total_price'] = 0;
                    $orderList[$key]['productList'][$product_key]['prod2Order'] = $prod2Order;
                    $orderList[$key]['productList'][$product_key]['total_price'] += $prod2Order->price*$prod2Order->count;
                    //imagesku
                    $orderList[$key]['productList'][$product_key]['imagessku']  = $orderManager->getImage($prod2Order->sku);

                    //image
                    $pimageManager = RexFactory::manager('pImage');
                    $pimageManager->getByWhere('product_id = '.intval($productEntity->id).' order by sorder limit 1');
                    $list = $pimageManager->getCollection('object');
                    if (sizeof($list) == 1) {
                        $orderList[$key]['productList'][$product_key]['image'] = $list[0];
                    } else {
                        $orderList[$key]['productList'][$product_key]['image'] = false;
                    }

                    $attributeList = array();
                    if (strlen(trim($prod2Order->attributes)) > 0) {

                        $tmp = explode(';', $prod2Order->attributes);
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
                    $orderList[$key]['productList'][$product_key]['attributes'] = $attributeList;

                }
            } else {
                $orderList[$key]['productList'] = false;
            }

            $orderList[$key]['status'] = $this->manager->getTextStatus($orderList[$key]['status']);
        }
        return $orderList;
    }

    function getQuickOrder()
    {
        RexResponse::init();
        $user  = XSession::get('user');
        $qOrder = $_POST;

        if ($qOrder['rex_request'] and $qOrder['rex_request'] == '1') {

            $orderEntity = RexFactory::entity('order');
            $orderEntity->phone = $qOrder['quick_order']['phone'];
            if ($user and $user->id > 0) {
                $orderEntity->user_id = $user->id;
            } else {
                $orderEntity->user_id = 0;
            }
            $orderEntity->status = 1;

            if (!$orderEntity->create()) {
                RexResponse::response(Rexlang::get('order.error.error_create'));
            } else {

                $product_id = intval($qOrder['cart']['product_id']);
                $prod2OrderEntity = RexFactory::entity('prod2Order');
                $prod2OrderEntity->order_id = intval($orderEntity->id);
                $prod2OrderEntity->product_id = $product_id;

                if (intval($qOrder['cart']['count']) > 9999) {
                    $qOrder['cart']['count'] = 9999;
                }
                $prod2OrderEntity->count = $qOrder['cart']['count'];

                if ($prod2OrderEntity->count < 1) {
                    $prod2OrderEntity->count = 1;
                }

                $prod2OrderEntity->discount = $prod2OrderEntity->getProductSaleByProductId($product_id);
                if (isset($qOrder['cart']['sku'])) {
                    $skuEntity = new SkuEntity();

                    $prod2OrderEntity->sku      = $qOrder['cart']['sku'];
                    $prod2OrderEntity->price    = $prod2OrderEntity->getProductPriceBySku($qOrder['cart']['sku']);
                    $prod2OrderEntity->discount = $skuEntity->getSaleById($qOrder['cart']['sku']);
                } else {
                    $prod2OrderEntity->price = $prod2OrderEntity->getProductPriceByProductId($product_id);
                }

                if (!$prod2OrderEntity->create()) {
                    RexResponse::response(sprintf(Rexlang::get('order.error.error_add_product'), $product_id));
                } else {
                    $orderEntity->price_opt = $prod2OrderEntity->count * $prod2OrderEntity->price;
                    $orderEntity->update();
                    RexResponse::response(RexLang::get('qorder.congratulation'));
                }
            }
        }
    }

    function getAdd()
    {
        $user  = XSession::get('user');
        $cart  = Request::get('cart', false);
        $order = Request::get('order', false);
        $email = false;

        if ($cart != false and isset($cart['submit'])) {
            unset($cart['submit']);
            $orderEntity = RexFactory::entity('order');
            $orderEntity->comment = '';

            if ($order != false and isset($order['confirm']) and strlen(trim($order['confirm'])) > 0) {
                $orderEntity->comment = trim(RexLang::get('order.do_not_call_to_confirm'));
            }

            if ($order != false and isset($order['comment']) and strlen(trim($order['comment'])) > 0) {
                $orderEntity->comment .= trim(strip_tags($order['comment']));
            }

            if ($order != false and isset($order['name']) and strlen(trim($order['name'])) > 0) {
                $name = trim($order['name']);
                $orderEntity->name_single = $name;  
            }
            
            if ($order != false and isset($order['lastname']) and strlen(trim($order['lastname'])) > 0) {
                $lastname = trim($order['lastname']);
                $orderEntity->name = $lastname;  
            }
            
            if ($order != false and isset($order['email']) and strlen(trim($order['email'])) > 0) {
                $email = trim($order['email']);
            }
            
            if ($order != false and isset($order['phone']) and strlen(trim($order['phone'])) > 0) {
                $orderEntity->phone = $order['phone'];
            }

            if ($order != false and isset($order['city']) and $order['city'] != 'Введите ваш город') {
                $orderEntity->city = $order['city'];
            }

            if ($order != false and isset($order['fillials']) and $order['fillials'] != 'Адрес отделения транспортной компании') {
                $orderEntity->fillials = $order['fillials'];
            }
            
            if ($order != false and isset($order['delivery']) and $order['delivery'] != 'Выберите способ оплаты и доставки') {
                $orderEntity->delivery = $order['delivery'];
            } else {
                RexPage::addError(Rexlang::get('order.error.empty_delivery'));
            }

            $customer = RexFactory::entity('user');
            $customer->getByWhere('email = "' . $order['email'] . '" LIMIT 1');

            if (sizeof($cart) > 0 and !empty($cart)) {
                if ((!$user or $user->id < 1) and (!isset($order['phone']) or strlen(trim($order['phone'])) < 4)) {
                    RexPage::addError(Rexlang::get('order.error.incorrect_user'));
                } else {
                    if ($user and $user->id > 0) {
                        $orderEntity->user_id = $user->id;
                    } else {
                        $orderEntity->user_id = 0;
                    }

                    $orderEntity->status = 1;

                    if (!$orderEntity->create()) {
                        RexPage::addError(Rexlang::get('order.error.error_create'));

                    } else {
                        $total_sum = 0;
                        $zakup_sum = 0;
                        foreach ($cart as $key => $cartData) {
                            if (intval($cartData['count']) > 9999) {
                                $cartData['count'] = 9999;
                            }
                            $prod2OrderEntity = RexFactory::entity('prod2Order');
                            $prod2OrderEntity->order_id = $orderEntity->id;
                            $product_id = intval($cartData['product_id']);
                            $prod2OrderEntity->product_id = $product_id;
                            $prod2OrderEntity->attributes = $cartData['attributes'];
                            $prod2OrderEntity->count = $cartData['count'];

                            if (isset($cartData['sku'])) {
                                $product_sku = intval($cartData['sku']);
                            } elseif (isset($cartData['sku_color'])) {
                                $product_sku = intval($cartData['sku_color']);
                            } else {
                                $product_sku = 0;
                            }
                            if ($product_sku != 0) {
                                $skuEntity = RexFactory::entity('sku');
                                $price_zakup = $prod2OrderEntity->getProductOptPriceBySku($product_sku);
                                $price = $skuEntity->getPriceBySkuId($product_sku);
                                $sale = $skuEntity->getSaleById($product_sku);

                            } else {
                                $price_zakup = $prod2OrderEntity->getProductOptPriceByProductId($product_id);
                                $price = $prod2OrderEntity->getProductPriceByProductId($product_id);
                                $sale = $prod2OrderEntity->getProductSaleByProductId($product_id);
                            }

                            $prod2OrderEntity->sku = $product_sku;
                            $prod2OrderEntity->price = $price;
                            $prod2OrderEntity->discount = $sale;

                            if ($prod2OrderEntity->count < 1) {
                                $prod2OrderEntity->count = 1;
                            }

                            $zakup_sum += $prod2OrderEntity->count * $price_zakup;

                            if (!$prod2OrderEntity->create()) {
                                RexPage::addError(sprintf(Rexlang::get('order.error.error_add_product'), intval($cartData['product_id'])));
                            }
                        }

                        $orderEntity->price_opt = $zakup_sum;

                        $userEntity = RexFactory::entity('user');
                        if (!$customer->id) {
                            $userEntity->name = $name;
                            $userEntity->lastname = $lastname;
                            $login = 'user' . uniqid();
                            $password = uniqid();
                            $userEntity->clear_password = $password;
                            $userEntity->password = md5($password);
                            $userEntity->login = $login;
                            if ($email) {
                                $userEntity->email = $email;
                                $userEntity->is_registered = 4;
                                $userEntity->delivery = 1;
                            } else {
                                $userEntity->email = $login . '@noemail.com';
                                $userEntity->is_registered = 0;
                                $userEntity->delivery = 0;
                            }
                            $userEntity->phone = $order['phone'];
                            $userEntity->role = 'user';
                            if (isset($order['fillials']) and $order['fillials'] != 'Адрес отделения транспортной компании') {
                                $userEntity->fillials = $order['fillials'];
                            }
                            if (isset($order['city']) and $order['city'] != 'Введите ваш город') {
                                $userEntity->city = $order['city'];
                            }
                            if (!$userEntity->create()) {
                                RexPage::addError(sprintf(Rexlang::get('order.error.user_error')));
                            }


                            $orderEntity->user_id = $userEntity->id;
                        } else {
                            $orderEntity->user_id = $customer->id;
                        }

                        $userEntity->getByWhere('id =' . $orderEntity->user_id);
                        $orderSum = $this->manager->getTotalOrderSum($orderEntity->user_id);

                        if ($orderEntity->user_id > 0) {
                            $orderEntity->sale = $this->manager->getUserSale($orderEntity->user_id);
                        }

                        if ($userEntity->id and (!isset($orderEntity->phone) or intval($orderEntity->phone) <= 0)) {
                            if (strlen(trim($userEntity->phone)) > 0) {
                                $orderEntity->phone = $userEntity->phone;
                            } else {
                                $userEntity->phone = $order['phone'];
                            }
                        }

                        if ($userEntity->id) {
                            if ($userEntity->city == 0 && isset($order['city']) && $order['city'] != 'Введите ваш город') {
                                $userEntity->city = $order['city'];
                            }
                            if ($userEntity->fillials == 0 && isset($order['fillials']) && $order['fillials'] != 'Адрес отделения транспортной компании') {
                                $userEntity->fillials = $order['fillials'];
                            }

                            if (isset($name)) {
                                $userEntity->name = $name;
                            }

                            if (isset($lastname)) {
                                $userEntity->lastname = $lastname;
                            }
                            if (strlen(trim($order['phone'])) > 0) {
                                $userEntity->phone = $order['phone'];
                            }

                            if (strlen(trim($order['email'])) > 0) {
                                $userEntity->email = $order['email'];
                            }
                        }

                        $userEntity->update();
                        $orderEntity->update();

                        /*try {
                            $orderEntity->update();
                        } catch (Exception $e) {
                            var_dump(XDatabase::getError());exit;
                        }*/
                    }
                }
            } else {
                RexPage::addError(RexLang::get('order.error.empty_order'));
            }
        } else {
            RexPage::addError(RexLang::get('order.error.empty_params'));
        }

        if (!RexPage::isError()) {
            //clear cart
            $manager = RexFactory::manager('cart');
            $manager->delete();

            //main to admin
            /*$headers = "From: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();  */
            if ($orderEntity->user_id > 0) {
                if (RexSettings::get('phpsender_order') == 'true') {    
                    $userEntity = RexFactory::entity('user');
                    $userEntity->get($orderEntity->user_id);

                    $noOrders       = 0;
                    $hasFirstOrder  = 1;
                    $smsSent        = 2;

                    // if user already sent first order
                    if ($userEntity->has_first_order == $smsSent) {
                        /* Предыдущий текст: 'Вы оформили заказ № '.$orderEntity->id.'. В ближайшее время с Вами свяжется менеджер заказа.' */
                        PHPSender::sendSms($userEntity->phone, RexLang::get('order.sms.info_after_order'));
                    }
                    PHPSender::sendSms(RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'), 'Пользователь '.$userEntity->name.' '.$userEntity->lastname.' ('.$userEntity->id.') оформил заказ № '.$orderEntity->id);
                }  

                $message = sprintf(RexLang::get('order.email.new_order_message_login'), $orderEntity->id, $userEntity->login);
                $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);

                RexDisplay::assign('pismomes', $message);

                $html = RexDisplay::fetch('mail/pismo.order.admin.tpl');
                $userManager = RexFactory::manager('user');
                //$userManager->getMail($html, RexSettings::get('contact_email'), sprintf(RexLang::get('order.email.new_order_subject'), RexConfig::get('Project', 'sysname')));

            }     

            /*if (RexSettings::get('phpsender_order') == 'true') {
                PHPSender::sendSms($orderEntity->phone, 'Вы оформили заказ № '.$orderEntity->id.'. В ближайшее время с Вами свяжется менеджер заказа.');
                PHPSender::sendSms(RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'), 'Пользователь '.$orderEntity->name.' (тел. '.$orderEntity->phone.') оформил заказ № '.$orderEntity->id);
            }*/

            $message = sprintf(RexLang::get('order.email.new_order_message_login'), $orderEntity->id, $orderEntity->name, $orderEntity->phone);
            $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);

            RexDisplay::assign('pismomes', $message);

            $html = RexDisplay::fetch('mail/pismo.order.admin.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, RexSettings::get('contact_email'), sprintf(RexLang::get('order.email.new_order_subject'), RexConfig::get('Project', 'sysname')));


            $orderList = $this->manager->getOrderList($orderEntity->id, true);

            $messageForUser = $this->getToOrderList($orderList, $this->manager);
            $messageForUser = str_replace(array('\n', '\r'), array("<br />", "<br />"), $messageForUser);

            RexDisplay::assign('order', $messageForUser[0]);
            RexDisplay::assign('pismo', RexConfig::get('Project', 'http_domain'));  // for url`s

            $htmlForUser = RexDisplay::fetch('mail/pismo.order.user.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($htmlForUser, $email, sprintf(RexLang::get('order.email.new_order_subject'), RexConfig::get('Project', 'sysname')));

            RexRoute::location(['mod' => 'order', 'act' => 'success']);
        }
    }

    public function getSuccess()
    {
        RexPage::addMessage(RexLang::get('order.order_congratulation'));
        RexDisplay::assign('delayedRedirect', RexRoute::getUrl(['mod' => 'home', 'act' => 'default']));
    }


    // /usr/bin/php -f /home/users/volleymag/dev.volleymag.com.ua/htdocs/cron/check_first_order.php
    public function getCheckFirstOrder()
    {
        if (PHP_SAPI !== 'cli') {
            sys::show404error('/404.html');
            return true;
        }

        $noOrders       = 0;
        $hasFirstOrder  = 1;
        $smsSent        = 2;

        $usersListSql = 'SELECT * FROM `user` WHERE has_first_order = '.$hasFirstOrder;

        $userList = XDatabase::getAll($usersListSql);

        foreach ($userList as $userItem) {
            $userEntity = RexFactory::entity('user');
            $userEntity = $userEntity->get($userItem['id']);

            if (!$userEntity) {
                continue;
            }

            $_SERVER['SERVER_NAME'] = 'www.volleymag.com.ua';

            $validate = PHPSender::validateNumber($userEntity->phone);

            if (!$validate) {
                $userEntity->has_first_order = $noOrders;
                try {
                    $userEntity->update();
                } catch(\Exception $e){}
                continue;
            }

            $res = PHPSender::sendSms($userEntity->phone, RexLang::get('order.sms.info_after_order'));

            $userEntity->has_first_order = !$res ? $noOrders : $smsSent;

            try {
                $userEntity->update();
            } catch(\Exception $e){}
        }
        echo 'done';
        exit;
    }
    /*public function getEdit()
    {

        echo 123; exit;
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

        RexDisplay::assign('entity', $entity);

        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->edit_dialog_width, $this->edit_dialog_height);
        }
    }*/

}
