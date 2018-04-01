<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexResponse as RexResponse; 
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexRoute as RexRoute;
use \RexSettings as RexSettings;
use \RexConfig as RexConfig;
use \RexRunner as RexRunner;
use \XDatabase as XDatabase;
use \XSession as XSession;
use \Request as Request;
use \PagerObj as PagerObj;
use \RexLang as RexLang;
use \Sys as Sys;

/**
 * Class ProductController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  ProductController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class ProductController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\SkuEntity:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'RexShop\SkuElementManager:shop.standart:1.0',
        'PagerObj:standart:1.0'
    );
    
    /**
	 * getDefault
	 *
	 * @author  Fatal
	 * @class   ProductController
	 * @access  public
	 * @return  void
	 */
	public function getDefault()
	{
        $this->entity = RexFactory::entity('product');
		if (!$this->entity->getByWhere('id = '.$this->task)) {
            sys::show404error('/404.html');
            exit;
        }
        
        if ($this->entity->active != 1) {
            sys::show404error('/404.html');
            exit;
        }
        
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
        
		RexPage::setTitle($this->entity->name);
		RexPage::setDescription($this->entity->description);
		RexPage::setKeywords($this->entity->keywords);
		
		//images
		$pimageManager = RexFactory::manager('pImage');	
		$pimageManager->getByWhere('`product_id` = '.intval($this->entity->id).' ORDER BY `sorder`');
		$list = $pimageManager->getCollection();
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
				RexDisplay::assign('relatedList', $relatedList);
				RexDisplay::assign('rimageList', $rimageList);
				RexDisplay::assign('rCategoryList', $rCategoryList);
				RexDisplay::assign('rBrandList', $rBrandList);
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
		$attr2ProdManager->draw(false);
		RexDisplay::assign('attributes', $attr2ProdManager->fetched);
        //\sys::dump($attr2ProdManager->fetched);exit;
        
        $skuManager = RexFactory::manager('sku');
        $skuManager->getSkusFront($this->entity->id, $sale);
        
        $skus = $skuManager->skus;
        
        $compare = XSession::get('compare', false);
        
        $cartManager = RexFactory::manager('cart');
        $cartManager->getData();
        
        //\sys::dump($skus);   exit;
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
       //\sys::dump($skuManager->attributes); exit;
        RexDisplay::assign('skus', $skus);  
        RexDisplay::assign('totalQuantity', $skuManager->total_quantity);
        //\Sys::dump($skus); exit;
        
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
        
	}

	function getArchive()
	{
		if (!$this->task or $this->task == 'default') {
			$this->task = 1;
		}
		
		$feature = Request::get('feature');
		
		$pagerObj = new PagerObj('pager_'.$this->mod, RexSettings::get('per_page'), $this->task);

		$this->manager = RexFactory::manager('product');
		$this->manager->getArchive($feature, $pagerObj->getFrom(), $pagerObj->getPerPage());
		
		$product_archive = $this->manager->getCollection();
		$imageList = $this->manager->images;
		$categoryList = $this->manager->categories;
		$brandList = $this->manager->brands;
		
		if (sizeof($product_archive) > 0) {
			RexDisplay::assign('productList', $product_archive);
			RexDisplay::assign('imageList', $imageList);
			RexDisplay::assign('categoryList', $categoryList);
			RexDisplay::assign('brandList', $brandList);
			RexDisplay::assign('feature', $feature);
		}
		
		$pagerObj->setCount($this->manager->getCountByFeature($feature));
		$pagerObj->generatePages();
		RexDisplay::assign('pager', $pagerObj);
		
		switch ($feature){
			case 'new': $title = RexLang::get('catalog.archive.new.title'); $desc = RexLang::get('catalog.archive.new.description'); break;
			case 'event': $title = RexLang::get('catalog.archive.event.title'); $desc = RexLang::get('catalog.archive.event.description'); break;
			case 'bestseller': $title = RexLang::get('catalog.archive.bestseller.title'); $desc = RexLang::get('catalog.archive.bestseller.description'); break;
			default: break;
		}
		
		RexPage::setTitle($title);
		RexPage::setDescription($desc);
	}
	
	function _random($aParams)
	{		
		$productManager = RexFactory::manager('product');
		$productManager->getRandom();
		
		RexDisplay::assign($aParams['saveto'], $productManager->getCollection());
		RexDisplay::assign($aParams['saveto_images'], $productManager->images);
	}
	
	function _lastvisited($aParams)
	{		
		$productManager = RexFactory::manager('product');
		$productManager->getLastVisited();
		
		RexDisplay::assign($aParams['saveto'], $productManager->getCollection());
		RexDisplay::assign($aParams['saveto_images'], $productManager->images);
	}
	
	function _new($aParams)
	{		
		$productManager = RexFactory::manager('product');
		$productManager->getLastThreeProd();
		
		RexDisplay::assign($aParams['saveto'], $productManager->getCollection());
		RexDisplay::assign($aParams['saveto_images'], $productManager->images);
	}
    
    function getCompareList()
    {
        $compare = XSession::get('compare');
        
        if ($compare) {
            foreach ($compare as $key => $info) {
                if (!is_array($info) || !isset($info['pid']) || !$this->entity->get($info['pid'])) {
                    unset($compare[$key]);
                }
            }
            XSession::set('compare', $compare);
            RexDisplay::assign('compare', $compare);
            RexDisplay::assign('compare_count', count($compare));
        }
    }

	function getAjaxCompare()
	{
        RexResponse::init();
        
        $aProductID = Request::get('product_id', 0);
        $aSkuID = Request::get('sku', 0);
        
		$newProduct = RexFactory::entity('product');
		if (!$newProduct->get($aProductID)) {
            RexResponse::error(RexLang::get('catalog.ajaxcompare.error.unknown_product'));
		}
		
		$compare = XSession::get('compare', false);
        
        if (sizeof($compare) > 3) {
            RexResponse::error(RexLang::get('catalog.ajaxcompare.error.max_compare'));
        }
        
		if (!$compare) {
			XSession::set('compare', array(array('pid' => $aProductID, 'sid' => $aSkuID)));	
		} else {			
            foreach ($compare as $info) {
				if ($aProductID == $info['pid'] && $aSkuID == $info['sid']) {
                    RexResponse::error(RexLang::get('catalog.ajaxcompare.error.already_exist'));
				}
			}
			
            $compare[] = array('pid' => $aProductID, 'sid' => $aSkuID);
			XSession::set('compare', $compare);
		}
        
		RexResponse::response('ok'); 
	}
	
	function getCompareClear()
	{
		XSession::set('compare', '');
		if ($_SERVER['HTTP_REFERER']) {
			header('Location: '.$_SERVER['HTTP_REFERER']);
            exit;
		} else {
            RexRoute::location('home');
		}
	}
	
	function getCompare()
	{
        RexPage::setTitle('Сравнение товаров – интернет-магазин спортивных товаров Волеймаг');
		//RexPage::setTitle(RexLang::get('catalog.compare.title'));
		
		$compare = XSession::get('compare', false);
        
		if (!$compare) {
			return false;
		}
		
		if (sizeof($compare) < 1) {
			return false;
		}
		
		$this->manager = RexFactory::manager('product');
        
		$this->manager->getCompare($compare);
		if (!$this->manager->_collection or sizeof($this->manager->_collection) < 1) {
			return false;
		}
		
		$productList = $this->manager->getCollection();
		
		RexDisplay::assign('productList', $productList);
		
		$attr2ProdManager = RexFactory::manager('attr2Prod');
		$attr2ProdManager->productList = $productList;
		$attr2ProdManager->drawMulti();
		RexDisplay::assign('attributes', $attr2ProdManager->fetched);
	}
}