<?php
class CartController extends \RexShop\CartController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\CartEntity:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'SkuManager:volley.standart:1.0',
        'RexShop\CartController:shop.standart:1.0',
        'RexFramework\ParentController:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
        'CityManager:volley.standart:1.0',
        'FillialsManager:volley.standart:1.0',
        'OrderManager:volley.standart:1.0',
        'UserEntity:volley.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0'
    );

    function getDefault()
    {
        $user = XSession::get('user', false);
        $cart = Request::get('cart', false);
        $mOrder = new OrderManager();

        if ($user) {
            $orderSum = $mOrder->getTotalOrderSum($user->id);
            $orderCount = $mOrder->getOrderCount($user->id);
            $sale = $mOrder->getUserSale($user->id);
        } else {
            $sale = 0;
        }

        if ($cart != false and isset($cart['submit'])) {
            unset($cart['submit']);

            if (sizeof($cart) > 0) {
                $manager = RexFactory::manager('cart');
                $manager->_collection = array();

                foreach ($cart as $key => $cartData) {
                    $isset = false;

                    foreach ($manager->_collection as $cartCollection) {
                        if ($cartCollection->product_id == $cartData['product_id']) {
                            $isset = true;
                        }
                    }

                    if ($isset) {
                        continue;
                    }

                    $cartEntity = RexFactory::entity('cart');
                    $cartEntity->count = intval($cartData['count']);

                    if ($cartEntity->count < 1) {
                        $cartEntity->count = 1;
                    }
                    unset($cartData['count']);

                    $cartEntity->product_id = $cartData['product_id'];
                    unset($cartData['product_id']);

                    $cartEntity->attributes = $cartData['attributes'];
                    $manager->_collection[] = $cartEntity;
                }

                $manager->setData();

            } else {
                $manager = RexFactory::manager('cart');
                $manager->getData();
            }
        } else {
            $manager = RexFactory::manager('cart');
            $manager->getData();
        }

        foreach ($manager->_collection as $key => $cart) {
            $productEntity = RexFactory::entity('product');
            if ($productEntity->get($cart->product_id)) {

                $skuName = '';
                $skuEntity = false;

                if ($cart->sku) {
                    $skuEntity = RexFactory::entity('sku');
                    $skuEntity->get($cart->sku);
                    if ($skuEntity->price) {
                        $productEntity->price = round($skuEntity->price - $skuEntity->price*$skuEntity->sale/100, 2);
                    } else {
                        $productEntity->price = round($productEntity->price - ($productEntity->is_common_sale == 1 ? $productEntity->price*$productEntity->sale/100 : 0), 2);
                    }
                    $skuName = $skuEntity->getClearName(htmlspecialchars('</tr><tr>'),
                                                        htmlspecialchars('<td class="cart-attr-l">'),
                                                        htmlspecialchars(':</td>'),
                                                        '',
                                                        htmlspecialchars('</td>'));
                } else {
                    $productEntity->price = round($productEntity->price - ($productEntity->is_common_sale == 1 ? $productEntity->price*$productEntity->sale/100 : 0), 2);
                }

                $attributeList = array();
                if (strlen(trim($cart->attributes)) > 0) {
                    $tmp = explode(';', $cart->attributes);
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

                $atributeListByColor = $skuByColor = array();

                if (isset($attributeList[1])) {
                    $skuManager = RexFactory::manager('sku');
                    $skuByColor = $skuManager->getSkuByAtrID($productEntity->id, $attributeList[1]['value']->id);

                    if ($skuByColor) {
                        if ($skuByColor['sale']) {
                            $productEntity->price = round($skuByColor['price'] - $skuByColor['price'] * $skuByColor['sale'] / 100, 2);
                        } elseif($productEntity->sale && $productEntity->is_common_sale == 1) {
                            $productEntity->price = round($skuByColor['price'] - $skuByColor['price']*$productEntity->sale/100, 2); // price_opt     DO NOT UNCOMMENT!!!
                        }
                        $atributeListByColor = $skuManager->getAtrListByColor($productEntity->id, $skuByColor['attr2prod_id']);

                        if ($atributeListByColor) {
                            RexDisplay::assign('issetUncheckedSku', 1);
                        }
                    }

                    $parseAtributes = explode(';', $cart->attributes);

                    $newAtributes = array();

                    foreach ($parseAtributes as $parseAtribute) {
                        $data = explode(':', $parseAtribute);
                        if ($data[0] == 1) {
                            continue;
                        }
                        $newAtributes[] = $parseAtribute;
                    }

                    $cart->attributes = implode(';', $newAtributes);
                }

                //image
                $pimageManager = RexFactory::manager('pImage');
                $list = array();

                if ($cart->sku || $skuByColor) {
                    $list = $pimageManager->getCartImage($cart->sku ?: $skuByColor['id']);
                }

                if (!$list) {
                    $pimageManager->getByWhere('product_id = '.intval($productEntity->id).' order by sorder limit 1');
                    $list = $pimageManager->getCollection('object');
                }

                if (sizeof($list) == 1) {
                    $image = $list[0];
                } else {
                    $image = false;
                }

                $pCatalog = RexFactory::entity('pCatalog');
                $pCatalog->get($productEntity->category_id);

                $manager->_collection[$key] = array(
                    'cart'=>$cart,
                    'product'=>$productEntity,
                    'image'=>$image,
                    'attributes'=>$attributeList,
                    'pcatalog_alias' => $pCatalog->alias,
                    'sku' => $skuName,
                    'skuEntity' => $skuEntity,
                    'atributeListByColor' => $atributeListByColor,
                    'skuByColor' => $skuByColor
                );
            } else {
                unset($manager->_collection[$key]);
            }
        }

        $managerCity = RexFactory::manager('city');
        $managerCity->getByWhere('1 = 1');
        RexDisplay::assign('city', $managerCity->getCollection());

        $managerFillials = RexFactory::manager('fillials');
        $managerFillials->getByWhere('1 = 1');
        RexDisplay::assign('fillials', $managerFillials->getCollection());

        $managerRole = RexFactory::manager('user');
        $managerRole->getByWhere('1 = 1');
        RexDisplay::assign('role', $managerRole->getCollection());

        $cartList = $manager->_collection;
        RexDisplay::assign('cartList', $manager->_collection);
        RexDisplay::assign('sale', $sale);
        RexPage::setTitle('Корзина – интернет-магазин спортивных товаров Волеймаг');
        if ($user) {
            $userEntity = RexFactory::entity('user');

            $userEntity->get($user->id);
            RexDisplay::assign('user', $userEntity);

            $entityCity = RexFactory::entity('city');
            $entityCity->getByWhere("name = '".$userEntity->city."'");
    // echo "<pre>";var_dump($entityCity);exit;
            RexDisplay::assign('usercity_id', $entityCity->id);
        }

        if (XSession::get('user_exist')) {
            $fromSession = explode(';', XSession::get('user_exist'));
            RexDisplay::assign('odelivery', $fromSession[1]);
            RexDisplay::assign('ocomment', $fromSession[2]);
            RexDisplay::assign('oconfirm', $fromSession[3]);
            XSession::remove('user_exists');
        }

        //sys::dump($manager->_collection);
    }

    function getAdd()
    {
        $cartData = Request::get('cart', false);

        if ($cartData !== false and isset($cartData['submit'])) {
            unset($cartData['submit']);

            $manager = RexFactory::manager('cart');
            $manager->getData();

            $cartEntity = RexFactory::entity('cart');
            $cartData['count'] = intval($cartData['count']);
            if ($cartData['count'] > 9999) {
                $cartData['count'] = 9999;
            }
            $cartEntity->count = $cartData['count'];
            if ($cartEntity->count < 1) {
                $cartEntity->count = 1;
            }
            unset($cartData['count']);

            $cartEntity->product_id = $cartData['product_id'];
            unset($cartData['product_id']);

            $cartEntity->sku = isset($cartData['sku']) ? $cartData['sku'] : 0;
            unset($cartData['sku']);

            foreach ($cartData as $attributeID => $attributeValue) {
                $cartEntity->attributes .= $attributeID.':'.$attributeValue.';';
            }
            $cartEntity->attributes = trim($cartEntity->attributes, ';');
            $cartEntity->num = 0;
            $isset = false;

            array_walk($manager->_collection, function ($cartCollection) use ($cartEntity, &$isset){
                if ($cartCollection->product_id == $cartEntity->product_id &&
                    $cartCollection->sku        == $cartEntity->sku &&
                    $cartCollection->attributes == $cartEntity->attributes) {
                    $isset = true;
                }

                $cartEntity->num = $cartCollection->num >= $cartEntity->num ? ++$cartEntity->num : $cartEntity->num;
            });

            if (!$isset) {
                $manager->_collection[] = $cartEntity;
                $manager->setData();
            }

            if (RexResponse::isRequest()) {
                RexResponse::init();
                RexResponse::response('ok');
            }

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                echo 'ok';
                exit;
            }

//            RexRoute::location('cart');
        }
    }

	function getClear()
	{
		$id = Request::get('id', false);
        $sku = Request::get('sku', false);

        $manager = RexFactory::manager('cart');
        if ($id === false) {
            $manager->delete();
        } else {
            $manager->getData();
            if ($manager->_collection and sizeof($manager->_collection) > 0) {
                foreach ($manager->_collection as $key => $cartCollection) {
                    if ($cartCollection->num == $id) {
                        unset($manager->_collection[$key]);
                        break;
                    }
                }
                $manager->setData();
            }
        }

        RexRoute::location('cart');
	}


	function getCart() //smarty func
    {
        $user = XSession::get('user');
        $mOrder = new OrderManager();

        if ($user){
            $userEntity = RexFactory::entity('user');
            $userEntity->getByWhere('id ='.$user->id);
            $sale = $mOrder->getUserSale($user->id);
        } else {
            $sale = 0;
        }

        RexDisplay::assign('who_i', $user);
        $cart = Request::get('cart', false);

        if ($cart != false and isset($cart['submit'])) {
            unset($cart['submit']);

            if (isset($cart['product_id']) && isset($cart['count']) && !isset($cart[0])) {
                $cart[0] = array(
                    'product_id' => $cart['product_id'],
                    'count' => $cart['count']
                );
                if (isset($cart['attributes'])) {
                    $cart[0]['attributes'] = $cart['attributes'];
                    unset($cart['attributes']);
                }
                unset($cart['product_id']);
                unset($cart['count']);
                if (isset($cart['sku'])) {
                    unset($cart['sku']);
                }
            }

            if (sizeof($cart) > 0) {

                $manager = RexFactory::manager('cart');
                //2. creating array
                $manager->_collection = array();

                foreach ($cart as $key => $cartData) {
                    $isset = false;
                    foreach ($manager->_collection as $cartCollection) {
                        if ($cartCollection->product_id == $cartData['product_id']) {
                            $isset = true;
                        }
                    }
                    if ($isset) {
                        continue;
                    }

                    $cartEntity = RexFactory::entity('cart');
                    $cartEntity->count = intval($cartData['count']);
                    if ($cartEntity->count < 1) {
                        $cartEntity->count = 1;
                    }
                    if (is_array($cartData['count'])) {
                        unset($cartData['count']);
                    }

                    $cartEntity->product_id = $cartData['product_id'];
                    if (is_array($cartData['product_id'])) {
                        unset($cartData['product_id']);
                    }

                    if (isset($cartData['attributes'])) {
                        $cartEntity->attributes = $cartData['attributes'];
                    }

                    $manager->_collection[] = $cartEntity;
                }

                //$manager->setData();
            } else {
                $manager = RexFactory::manager('cart');
                $manager->getData();
            }
        } else {
            $manager = RexFactory::manager('cart');
            $manager->getData();
        }

        foreach ($manager->_collection as $key => $cart) {
            $productEntity = RexFactory::entity('product');
            if ($productEntity->getByFields(array('id' => $cart->product_id))) {

                //image
                $pimageManager = RexFactory::manager('pImage');
                $pimageManager->getByWhere('`product_id` = '.intval($productEntity->id).' ORDER BY `sorder` LIMIT 1');
                $list = $pimageManager->getCollection('object');

                if (sizeof($list) == 1) {
                    $image = $list[0];
                } else {
                    $image = false;
                }

                $attributeList = array();
                if (strlen(trim($cart->attributes)) > 0) {
                    $tmp = explode(';', $cart->attributes);
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

                $manager->_collection[$key] = array('cart'=>$cart, 'product'=>$productEntity, 'image'=>$image, 'attributes'=>$attributeList);
            } else {
                unset($manager->_collection[$key]);
            }
        }

        $cartList = $manager->_collection;

        $product_id_arr = $colorProductCart = array();
        $sumcart = 0;
        $count_cart = 0;

        foreach ($cartList as &$item) {
            $product_id_arr[] = $item['cart']->product_id;
            $colorProductCart[$item['cart']->product_id.':'.$item['cart']->attributes] = 1;
            if ($item['cart']->sku) {
                $skuEntity = RexFactory::entity('sku');
                $skuEntity->get($item['cart']->sku);
                if ($skuEntity->price) {
                    $item['product']->price = $skuEntity->price;
                }
                if ($skuEntity->sale and $skuEntity->sale != 0){
                    $item['product']->price = $item['product']->price * (1 - $skuEntity->sale/100);
                }
            } elseif (isset($item['attributes'][1])) {
                $skuManager = RexFactory::manager('sku');
                $skuByColor = $skuManager->getSkuByAtrID($item['product']->id, $item['attributes'][1]['value']->id);

                if ($skuByColor) {
                    $item['product']->price = $skuByColor['price']; // price_opt
                }
            }
            /*if ($item['product']->sale) {
                $item['product']->price = round($item['product']->price*(1 - $item['product']->sale/100), 1);
            }*/
            $sumcart += $item['product']->price * $item['cart']->count;
            $count_cart += $item['cart']->count;
        }

        RexDisplay::assign('productListCart', $product_id_arr);
        RexDisplay::assign('colorProductCart', $colorProductCart);
        RexDisplay::assign('dolar_rate', RexSettings::get('dolar_rate'));
        RexDisplay::assign('filter_kurs', RexSettings::get('filter_kurs'));
        RexDisplay::assign('cart_sum', floor($sumcart - $sumcart*$sale/100));
        RexDisplay::assign('cart_cnt', $count_cart);

        if (RexResponse::isRequest()) {
            RexResponse::init();
            $template = RexDisplay::fetch('cart/cart.header.tpl');
            RexResponse::response($template);
        }
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
}
