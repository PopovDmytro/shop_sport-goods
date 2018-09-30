<?php

class ProductController extends \RexShop\ProductController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\ProductController:shop.standart:1.0',
        'RexFramework\ParentController:standart:1.0',
        'ProductEntity:volley.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexShop\SkuElementManager:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0',
        'RexShop\AttributeManager:shop.standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\AttachManager:shop.standart:1.0',
        'PagerObj:standart:1.0',
        'Prod2TechManager:volley.standart:1.0',
        'Prod2TechEntity:volley.standart:1.0'
    );
    
    function getDefault()
    {
        $this->entity = RexFactory::entity('product');   
        if (!$this->entity->getByWhere('id = '.$this->task)) {
            RexDisplay::assign('error', true);
            RexPage::setTitle(RexLang::get('404.title'));
            header('HTTP/1.0 404 Not Found');
            return true;
        }
        //\sys::dump($this->task);exit; 
        if ($this->entity->active != 1) {
            //sys::show404error('/404.html');
            RexDisplay::assign('error', true);
            RexPage::setTitle(RexLang::get('404.title'));
            header('HTTP/1.0 404 Not Found');
            return true;
        }

//        $lastproducts = array($this->entity->id);
//        if(isset($_COOKIE['lastproducts'])) {
//            $lastproducts = array_merge($lastproducts, json_decode($_COOKIE['lastproducts'], true));
//        }
//
//        $lastproducts = array_slice(array_unique ($lastproducts), 0, 11);
//        setcookie('lastproducts',json_encode($lastproducts) , time() + (86400 * 30), '/', RexConfig::get('Project', 'cookie_domain'));
//
//        $lastproductsEntity = RexFactory::entity('product');
//        unset($lastproducts[array_search($this->entity->id,$lastproducts)]);
//        $ids=implode($lastproducts,"','");
//        $sql= "SELECT
//              p.*,
//              pc.`alias` AS palias,
//              pa.`id` AS `pimageid`,
//              pa.`color_sorder`,
//              pa.`image`,
//              s.`sku_article`
//            FROM
//              product AS p
//              INNER JOIN pcatalog AS pc
//                ON pc.`id` = p.`category_id`
//              LEFT JOIN pimage AS pa
//                ON pa.`product_id` = p.`id`
//                 LEFT JOIN `sku` AS s ON s.`product_id`=p.`id`
//            WHERE p.id IN ('$ids')
//            AND s.`active`='1'
//            GROUP BY p.`id`
//            ORDER BY FIELD(p.`id`,'$ids')";
//        RexDisplay::assign('lastproduct',  XDatabase::getAll($sql));

        $this->getWatched();

        $sku_id = Request::get('sku', false);
        
        if ($sku_id) {
            setcookie('sku_id', $sku_id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
            header('Location: /product/'.Request::get('cat_alias').'/'.$this->task.'.html');
            exit;
        }

        if (isset($_COOKIE['sku_id'])) {
            $skuEntity = RexFactory::entity('sku');
            $skuEntity->getByFields(array('product_id' => $this->entity->id, 'id' => $_COOKIE['sku_id']));
            if ($skuEntity->id) {
                $skuElementManager = RexFactory::manager('skuElement');
                $skuElementManager->getByFields(array('sku_id' => $_COOKIE['sku_id']));
                RexDisplay::assign('skuSelected', $skuElementManager->getCollection());
            }
            setcookie('sku_id', '', time(), '/', RexConfig::get('Project', 'cookie_domain'));
        }
        
        $this->entity->visited();
        
        $sale = 1;
        
        if ($this->entity->sale) {
            $sale = 1 - ($this->entity->sale/100);
            $this->entity->price_old = $this->entity->price;
            $this->entity->price = round($this->entity->price*$sale, 0);
            
        } 
        //\sys::dump($this->entity->sale); exit;
        RexDisplay::assign('product', $this->entity);

        RexDisplay::assign('pricegr', $this->entity->price * RexSettings::get('dolar_rate'));
        
        $sql = 'SELECT `pid` FROM `pcatalog`
                         LEFT JOIN `product` ON  product.`category_id` = pcatalog.`id`
                         WHERE product.`id` = ?;';
         
        $podkat_v = XDatabase::getOne($sql, array($this->task));
        RexDisplay::assign('podcat_v', $podkat_v);
        
        if (!$this->entity->title) {
            RexPage::setTitle($this->entity->name);
        } else {
            RexPage::setTitle($this->entity->title);
        }
        RexPage::setDescription($this->entity->description);
        RexPage::setKeywords($this->entity->keywords);
        
        //images old selection
        /*$pimageManager = RexFactory::manager('pImage');
        $pimageManager->getByWhere('`product_id` = '.intval($this->entity->id).' ORDER BY `sorder`');
        $list = $pimageManager->getCollection();*/

        $list = XDatabase::getAll('SELECT
              pimg.* 
            FROM
              sku s 
              INNER JOIN sku_element se 
                ON s.`id` = se.`sku_id` 
              INNER JOIN pimage pimg 
                ON pimg.`attribute_id` = se.`attr2prod_id`
              INNER JOIN attr2prod a2p 
                ON se.`attr2prod_id` = a2p.id 
              LEFT JOIN prod_color_order pco 
                ON pimg.`product_id` = pco.`product_id` AND a2p.`value` = pco.`attribute_id`
            WHERE pimg.product_id = ' . intval($this->entity->id) . ' 
              AND s.`quantity` > 0 
            GROUP BY pimg.id
            ORDER BY pimg.`main` DESC, pco.`sorder`');

        if (sizeof($list) > 0) {
            RexDisplay::assign('imageList', $list);
        }
        
        //Comments
        $_REQUEST['task'] = array(
            'product_id' => $this->entity->id,
            'name' => $this->entity->name
        ) ;
        RexRunner::runController('comment', 'add');
        
        //Related
        $related = XDatabase::getAll('SELECT `related_id`, `product_id` FROM `related` WHERE `product_id` = ? OR `related_id` = ?', array($this->entity->id, $this->entity->id));
        
        if ($related and sizeof($related) > 0) {
            $rel = array();
            foreach ($related as $key => $value) {
                if ($value['related_id'] != $this->entity->id) {
                    $rel[] = $value['related_id'];
                } elseif ($value['product_id'] != $this->entity->id) {
                    $rel[] = $value['product_id'];
                }
                
            }
            $related = implode(',', $rel);
            
            $manager = RexFactory::manager('product');
            $manager->getByIDs($related);
            $relatedList = $manager->getCollection();
            $rimageList = $manager->images;
            $rCategoryList = $manager->categories;
            $rBrandList = $manager->brands;
            if (sizeof($relatedList) > 0) {
                $color = array();
                $skuManager = RexFactory::manager('sku');
                foreach($relatedList as $kk => $vv)  {
                    $relatedList[$kk]['content'] = strip_tags($vv['content']);
                    
                    $skuByColor = $skuManager->getSkusFrontMainPage($vv['id']);
                
                    if ($skuByColor) {
                        $color[$vv['id']] = $skuByColor;
                    }
                }

                //\sys::dump($color);
                RexDisplay::assign('prodColor', $color);
                
                //RexDisplay::assign('relatedList', $relatedList);
                RexDisplay::assign('productList', $relatedList);
                RexDisplay::assign('rimageList', $rimageList);
                RexDisplay::assign('categoryList', $rCategoryList);
                RexDisplay::assign('brandList', $rBrandList);
            }
        }
        
        $this->manager = RexFactory::manager('pCatalog');
        $this->manager->getUpList($this->entity->category_id, RexFactory::entity('pCatalog'));
        $navCategoryList = array_reverse($this->manager->getCollection());
        
        if (sizeof($navCategoryList) > 0) {
            $this->manager->getByWhere('`id` IN ('.implode(',', $navCategoryList).') ORDER BY `gorder`');
            RexDisplay::assign('navCategoryList', $this->manager->getCollection());
        }
        
        $pCatalog = RexFactory::entity('pCatalog');
        $pCatalog->get($this->entity->category_id);
        RexDisplay::assign('pcatalog', $pCatalog);
        
        //draw
        $attr2ProdManager = RexFactory::manager('attr2Prod');
        $attr2ProdManager->product = $this->entity;
        $attr2ProdManager->draw(true);
        RexDisplay::assign('attributes', $attr2ProdManager->fetched);

        $skuManager = RexFactory::manager('sku');
        $skuManager->getSkusFront($this->entity->id, $sale);

        $skus = $skuManager->skus;
        $compare = XSession::get('compare', false);
        
        $cartManager = RexFactory::manager('cart');
        $cartManager->getData();

        if (is_array($skus) && count($skus) > 0) {
            if ($cartManager->_collection and sizeof($cartManager->_collection) > 0) {
                foreach ($cartManager->_collection as $key => $cartCollection) {
                    if ($cartCollection->product_id == $this->task) {
                        foreach ($skus as &$sku) {
                            if ($cartCollection->sku == $sku['id']) {
                                $sku['isset'] = 1;    
                            }
                        }
                    }
                }
            }
            
            if ($compare) {
                foreach ($skus as &$sku) {
                    foreach ($compare as $value) {
                        if ($value['pid'] == $this->task) {
                            if ($sku['id'] == $value['sid']) {
                                $sku['comp_isset'] = 1;    
                            }
                        }
                    }
                }
            } 
        } else {
            if ($cartManager->_collection and sizeof($cartManager->_collection) > 0) {
                foreach ($cartManager->_collection as $key => $cartCollection) {
                    if ($cartCollection->product_id == $this->task) {
                        
                        RexDisplay::assign('isset_cart', true);
                    }
                }
            }
            
            if ($compare) {
                foreach ($compare as $value) {
                    if ($value['pid'] == $this->task) {
                        RexDisplay::assign('isset_compare', true);
                    }
                }
            }    
        }

        RexDisplay::assign('attrForSale', $skuManager->attributes);
        RexDisplay::assign('skus', $skus);

        RexDisplay::assign('totalQuantity', $skuManager->total_quantity);
        if (isset($skus[0]['sku_article'])) {
            RexDisplay::assign('default_sku', $skus[0]['sku_article']); 
        }

        //brand list
        $brand2Cat = RexFactory::manager('brand2Cat');
        $brand2Cat->getByWhere('`category_id` = '.$this->entity->category_id);
        if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
            $brand2CatData = '';
            foreach ($brand2Cat->getCollection() as $b2c) {
                $brand2CatData .= $b2c['brand_id'].',';
            }
            $brand2CatData = trim($brand2CatData, ',');
            $brandManager = RexFactory::manager('brand');
            $brandManager->getByWhere('`id` IN ('.$brand2CatData.') ORDER BY `name`');
            if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                RexDisplay::assign('categoryBrandList', $brandManager->getCollection());
            }
        } elseif ($pCatalog->pid > 0) {
            $brand2Cat = RexFactory::manager('brand2Cat');
            $brand2Cat->getByWhere('`category_id` = '.$pCatalog->pid);                
            if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
                $brand2CatData = '';
                foreach ($brand2Cat->getCollection() as $b2c) {
                    $brand2CatData .= $b2c['brand_id'].',';
                }
                $brand2CatData = trim($brand2CatData, ',');
                $brandManager = RexFactory::manager('brand');
                $brandManager->getByWhere('`id` IN ('.$brand2CatData.') ORDER BY `name`');
                if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                    RexDisplay::assign('categoryBrandList', $brandManager->getCollection());
                }
            }
        }
                     
        $brand = RexFactory::entity('brand');
        $brand->get($this->entity->brand_id);
        RexDisplay::assign('productBrand', $brand);
        
        //get attached files
        $attachManager = RexFactory::manager('attach');
        $attachManager->getByFields(array('product_id' => $this->task));
        $attaches = $attachManager->getCollection();
        
        if ($attaches) {
            RexDisplay::assign('attaches', $attaches);    
        }
        
        $sql = 'SELECT content FROM staticpage WHERE id = 5';
        $res = XDatabase::getOne($sql);
        //\sys::dump($res);exit;
        RexDisplay::assign('content', $res);
        
        $gender = RexFactory::manager('attribute');
        $sex = $gender->getGenderName($this->task);
        
        //\sys::dump($as);exit;
        RexDisplay::assign('sex', $sex);
        $technManager = RexFactory::manager('prod2Tech');
        $technologies = $technManager->getTechByProdId($this->task);
        // var_dump($technologies);
        // exit;
        //\sys::dump($res);exit;
        RexDisplay::assign('technologies', $technologies);

    }

	function getArchive()
	{
		if (!$this->task or $this->task == 'default') {
			$this->task = 1;
		}
        $rexRequest = false;
        if (RexResponse::isRequest()) {
            $rexRequest = true;
            RexResponse::init();
        }
        $rexRequestModal = Request::get('skinDefault', false);

        $feature = Request::get('feature');
        $page = Request::get('page', 1);
		$pagelast = Request::get('pagelast' , false);
        $perpage = RexSettings::get('per_page');

		$pagerObj = new PagerObj('pager_'.$this->mod, $perpage, $this->task);
        $from = $pagerObj->getFrom();
        $count = $pagerObj->getPerPage();
        if ($pagelast && $pagelast != $page) {
            $count = ($pagelast - $page +1) * $perpage; 
            //echo  $count; exit;
        }   
        
		$this->manager = RexFactory::manager('product');
		$this->manager->getArchive($feature, $from, $count);

		$product_archive = $this->manager->getCollection();
		$imageList = $this->manager->images;
        $categoryList = $this->manager->categories;
		$brandList = $this->manager->brands;

		if (sizeof($product_archive) > 0) {
            $color = array();
            $skuManager = RexFactory::manager('sku');
            foreach($product_archive as $kk => $vv)  {
                $skuByColor = $skuManager->getSkusFrontMainPage($vv['id']);
            
                if ($skuByColor) {
                    $color[$vv['id']] = $skuByColor;
                }
            }

            //\sys::dump($color);
            RexDisplay::assign('prodColor', $color);
			RexDisplay::assign('productList', $product_archive);
			RexDisplay::assign('imageList', $imageList);
			RexDisplay::assign('categoryList', $categoryList);
			RexDisplay::assign('brandList', $brandList);
			RexDisplay::assign('feature', $feature);
            
            $pagerObj->setCount($this->manager->getCountByFeature($feature));
            $pagerObj->generatePages();
            //\sys::dump($product_archive);exit;
            RexDisplay::assign('pager', $pagerObj);
            RexDisplay::assign('pager_count', count($pagerObj->pages));

            $perPage = RexSettings::get('per_page');
            //$page = $this->task; 
            $count_more = $this->manager->_count - ($this->task * $perPage);
            $count_next = $count_more > $perPage ? $perPage : $count_more;

            if ($rexRequest) {
                $reponse['count_next'] = $count_next;
            }

            RexDisplay::assign('count_next', $count_next);
		}

        if ($rexRequestModal) {
            $manager = RexFactory::manager('cart');
            $manager->getData();
            $cartList = $manager->_collection;
            $product_id_arr = array();
            foreach ($cartList as $key => $item) {
                $product_id_arr[] = $item->product_id;
            }
            RexDisplay::assign('productListCart', $product_id_arr);
            if ($rexRequestModal === 'list') {
                setcookie('modal', 'list', time() - 3600, '/');
                $response = RexDisplay::fetch('pcatalog/product.list.tpl');
            } else {
                setcookie('modal', 'block', time() + 3600, '/');
                $response = RexDisplay::fetch('pcatalog/product.block.tpl');
            }
            RexResponse::response($response);
        }
        if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
            RexDisplay::assign('modal', true);
        }

        if ($rexRequest && !$rexRequestModal) {
            if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
                $reponse['content'] = RexDisplay::fetch('pcatalog/product.block.inc.tpl');
            } else {
                $reponse['content'] = RexDisplay::fetch('pcatalog/product.list.inc.tpl');
            }
            RexResponse::response($reponse);
        }
        switch ($feature){
            case 'new': $title = RexLang::get('catalog.archive.new.title'); $desc = RexLang::get('catalog.archive.new.description'); break;
            case 'event': $title = RexLang::get('catalog.archive.event.title'); $desc = RexLang::get('catalog.archive.event.description'); break;
            case 'bestseller': $title = RexLang::get('catalog.archive.bestseller.title'); $desc = RexLang::get('catalog.archive.bestseller.description'); break;
            default: break;
        }

        RexDisplay::assign('name_feath', $title);
        RexPage::setTitle($title);
        RexPage::setDescription($desc);
	}

    function getFree()
    {
        $phone = ' ffg8-902-80-68-188';
        $phone = preg_replace('#[^0-9]#', '', $phone);
        $res = preg_match('/^\d+$/', $phone); \sys::dump($res,$phone); exit;
        /*$res = XDatabase::getAll('Select * from pimage limit 70,30');
        //sys::dump($res); exit;
        foreach ($res as $val) {
            if (file_exists(REX_ROOT.'rexframework/files/images/pimage/'.$val['id'].'/main.'.$val['image'])) {
                XImage::putWatermark(REX_ROOT.'rexframework/files/images/pimage/'.$val['id'].'/main.'.$val['image'], HTDOCS.'content/wm.png', 213, 227, 8, 15, 4, 15);
                echo $val['id'].'<br />';
            }
        }   */
    }
    
    function getBrand()
    {
        
        $brand_a = Request::get('brand_a', false);
        //\sys::dump($brand_a);exit;
        $brand = RexFactory::entity('brand');
        $brand->getbyWhere('id ='.$brand_a);
        
        RexDisplay::assign('productB', $brand->content);
        RexDisplay::assign('productBN', $brand->name);
        
        return RexDisplay::fetch('product/brand.tpl');
    }

    function getWatched() {
        $lastproducts = array($this->entity->id);
        if(isset($_COOKIE['lastproducts'])) {
            $lastproducts = array_merge($lastproducts, json_decode($_COOKIE['lastproducts'], true));
        }

        $lastproducts = array_slice(array_unique ($lastproducts), 0, 11);
        setcookie('lastproducts',json_encode($lastproducts) , time() + (86400 * 30), '/', RexConfig::get('Project', 'cookie_domain'));

        $lastproductsEntity = RexFactory::entity('product');
        unset($lastproducts[array_search($this->entity->id,$lastproducts)]);
        $ids=implode($lastproducts,"','");
        $sql= "SELECT 
              p.*,
              pc.`alias` AS palias,
              pa.`id` AS `pimageid`,
              pa.`color_sorder`,
              pa.`image`,
              s.`sku_article`
            FROM
              product AS p 
              INNER JOIN pcatalog AS pc 
                ON pc.`id` = p.`category_id` 
              LEFT JOIN pimage AS pa 
                ON pa.`product_id` = p.`id` 
                 LEFT JOIN `sku` AS s ON s.`product_id`=p.`id`
            WHERE p.id IN ('$ids')
            AND s.`active`='1'
            GROUP BY p.`id`
            ORDER BY FIELD(p.`id`,'$ids')";
        RexDisplay::assign('lastproduct',  XDatabase::getAll($sql));
    }
}