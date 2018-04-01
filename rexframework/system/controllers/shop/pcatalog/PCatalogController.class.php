<?php
namespace RexShop;

use \RexResponse as RexResponse;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \Request as Request;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \XDatabase as XDatabase;
use \PagerObj as PagerObj;
use \RexRoute as RexRoute;
use \RexLang as RexLang;
use \RexDBList as RexDBList;
use \Sys as Sys;
use \XSession as XSession;
use \XImage as XImage;

/**
 * Class PCatalogController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PCatalogController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PCatalogController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'PagerObj:standart:1.0'
    );
	
    function getDefault()
    {    
        $rexRequest = false;
        
        if (RexResponse::isRequest()) {
            $rexRequest = true;
            RexResponse::init();
        }
        
        $valuta = RexSettings::get('filter_kurs');
        RexDisplay::assign('valuta', $valuta);
        
        $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        RexDisplay::assign('parseUrl', $parseUrl);
        
        $this->entity = RexFactory::entity('pCatalog');
        if (!$this->entity->getByFields(array('alias' => $this->task))) {
            exit;
        }
        
        RexDisplay::assign('pcatalog', $this->entity);
        
        $clearfilter = Request::get('clearfilter', false);
        
        if ($clearfilter) {
            $url = RexRoute::getUrl('pCatalog', 'default', $this->entity->alias);
            header('location: '.$url);
            exit;
        }
        
        $filter = Request::get('filter', false);
        
        $brand_alias = Request::get('brand_alias', false);
        if ($brand_alias) {
            $brandEntity = RexFactory::entity('brand');
            $brandEntity->getByFields(array('alias' => $brand_alias));
            if ($brandEntity->id) {
                $filter['brand'][] = $brandEntity->id;   
                RexDisplay::assign('brandName', $brandEntity->name);
                RexDisplay::assign('brand', $brandEntity);             
            }
        }
        
        RexDisplay::assign('filterNow', $filter);
        
        // generate list of filters
        //\sys::dump($filter); exit;
        if ($filter && count($filter) > 0) {
        
            foreach ($filter as $filterKey => $filterValue) {
                
                if ($filterKey == 'rangefrom' && $filter['rangefrom'] > 0 && $filter['rangeto'] > 0) {
                    $filterSelected['price']['name'] = 'Цена';
                    $filterSelected['price']['value'] = 'От '.$filter['rangefrom'].' '.$valuta.' до '.$filter['rangeto'].' '.$valuta;
                } elseif ($filterKey == 'attribute' && is_array($filter['attribute']) && count($filter['attribute']) > 0) {
                    
                    foreach ($filterValue as $attrKey => $arrayValues) {
                        $attributeEntity = RexFactory::entity('attribute');
                        $attributeEntity->get($attrKey);
                        $filterSelected['attr_'.$attrKey]['name'] = $attributeEntity->name;
                        
                        if (9 == $attributeEntity->type_id) {
                            $attributeEntityNew = RexFactory::entity('attribute');
                            $attributeEntityNew->getByWhere('pid = '.$attributeEntity->id.' ORDER BY `name` DESC LIMIT 1');
                            $filterSelected['attr_'.$attrKey]['val_name'] = $attributeEntityNew->name;
                        }
                        
                        foreach ($arrayValues as $attrValue) {
                            
                            switch ($attributeEntity->type_id) {
                                case 9:
                                    
                                    if (isset($filterSelected['attr_'.$attrKey]['val_min'])) {
                                        
                                        if ($filterSelected['attr_'.$attrKey]['val_min'] > $attrValue) {
                                            $filterSelected['attr_'.$attrKey]['val_max'] = $filterSelected['attr_'.$attrKey]['val_min'];    
                                            $filterSelected['attr_'.$attrKey]['val_min'] = $attrValue;    
                                        } else {
                                            $filterSelected['attr_'.$attrKey]['val_max'] = $attrValue;                                        
                                        }
                                        
                                    } else {
                                        $filterSelected['attr_'.$attrKey]['val_min'] = $attrValue;    
                                    }
                                    
                                    break;
                                case 7:
                                case 6:
                                    $attributeEntityNew = RexFactory::entity('attribute');
                                    $attributeEntityNew->get($attrValue);
                                    $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = $attributeEntityNew->name;
                                    break;
                                    
                                case 4:
                                case 3:
                                case 2:
                                    $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = $attrValue;    
                                    break;
                                    
                                case 5:
                                    if (1 === intval($attrValue)) {
                                        $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = 'Да';    
                                    } elseif (0 === intval($attrValue)) {
                                        $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = 'Нет';
                                    } else {
                                        unset($filterSelected['attr_'.$attrKey]); 
                                    }
                                    break;
                                   
                            }
                            
                        }
                
                    }
                } elseif ($filterKey == 'brand') {
                    $filterSelected[$filterKey]['name'] = 'Производитель';
                    
                    foreach ($filterValue as $brandID) {
                        $brandEntity = RexFactory::entity('brand');
                        $brandEntity->getByWhere('id = "'.$brandID.'"');
                        $filterSelected[$filterKey]['value'][$brandID] = $brandEntity->name;
                    }
                        
                }
            }
            if (isset($filterSelected)) {
                RexDisplay:assign('filterSelected', $filterSelected);
            }
        }
        
        // fetch filters templates
        $checked_list = array();
        
        if (isset($filter['attribute'])) {
            foreach ($filter['attribute'] as $attrID => $values) {
                $attributeEntity = RexFactory::entity('attribute');
                $attributeEntity->get($attrID);
                
                foreach ($values as $val) {
                    if (intval($val) === 3 && $attributeEntity->type_id == 5) {
                        unset($filter['attribute'][$attrID]);
                        continue;
                    }
                    $checked_list[md5($attrID.'_'.$val)] = 1;    
                }
            }
        }
        
        RexDisplay::assign('checked_list', $checked_list);
        
        /*$brand = Request::get('brand', false);
        if ($brand) {
            $filter['brand'] = $brand;
        }     */
        
        if ($this->entity->title) {
            RexPage::setTitle($this->entity->title);
        }elseif ($this->entity->name){
            RexPage::setTitle($this->entity->name);
        } else {
            RexPage::setTitle(RexSettings::get('site_slogan'));
        }
        
        RexPage::setDescription($this->entity->description);
        RexPage::setKeywords($this->entity->keywords);
        
        //get all subcategories
        $this->manager = RexFactory::manager('pCatalog');
        $this->manager->getSubCategoriesList($this->entity->id, 1);
        $categoryList = $this->manager->struct;
        if ($categoryList and sizeof($categoryList) > 0) {
            $this->manager->getByWhere('id in ('.implode(',', $categoryList).') order by gorder');
            RexDisplay::assign('subcategories', $this->manager->getCollection());
            $fullList = $categoryList;
            $fullList[] = $this->entity->id;
        } else {
            $fullList = array($this->entity->id);
        }
        
        //brand list
        $brand2Cat = RexFactory::manager('brand2Cat');
        $brand2Cat->getByWhere('category_id in ('.implode(',', $fullList).')');
        if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
            $brand2CatData = '';
            foreach ($brand2Cat->getCollection() as $b2c) {
                $brand2CatData .= $b2c['brand_id'].',';
            }
            $brand2CatData = trim($brand2CatData, ',');
            $brandManager = RexFactory::manager('brand');
            $brandManager->getByWhere('id in ('.$brand2CatData.') order by name');
            if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                $res = $brandManager->getCollection();
                
                if (isset($filter['brand'])) {
                        
                    foreach ($filter['brand'] as $brandID) {
                        
                        foreach ($res as &$brandArray) {
                        
                            if ($brandArray['id'] == $brandID) {
                                $brandArray['selected'] = 1;
                            }
                        }
                    }
                }
               
                RexDisplay::assign('categoryBrandList', $res);
            }
        } elseif ($this->entity->pid > 0) {
            $brand2Cat = RexFactory::manager('brand2Cat');
            $brand2Cat->getByWhere('category_id = '.$this->entity->pid);                
            if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
                $brand2CatData = '';
                foreach ($brand2Cat->getCollection() as $b2c) {
                    $brand2CatData .= $b2c['brand_id'].',';
                }
                $brand2CatData = trim($brand2CatData, ',');
                $brandManager = RexFactory::manager('brand');
                $brandManager->getByWhere('id in ('.$brand2CatData.') order by name');
                if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                    $res = $brandManager->getCollection();
                    
                    if (isset($filter['brand'])) {
                        
                        foreach ($filter['brand'] as $brandID) {
                            
                            foreach ($res as &$brandArray) {
                            
                                if ($brandArray['id'] == $brandID) {
                                    $brandArray['selected'] = 1;
                                }
                            }
                        }
                    }
                    
                    RexDisplay::assign('categoryBrandList', $res);
                }
            }
        }
        
        //show product list
        $page = Request::get('page', 1);
        $perPage = RexSettings::get('per_page');
         
        $pagerObj = new PagerObj('pager', $perPage, $page);
        
        $productManager = RexFactory::manager('product');
        $productManager->getByCategoryListFilter($fullList, $pagerObj->getFrom(), $pagerObj->getPerPage(), $filter);    
                
        $productList = $productManager->getCollection();
        $imageList = $productManager->images;
        $categoryList = $productManager->categories;
        $brandList = $productManager->brands;
        
        if (sizeof($productList) > 0) {
            foreach($productList as $kk => $vv)  { 
                $productList[$kk]['content'] = strip_tags($vv['content']);
            }
            
            RexDisplay::assign('productList', $productList);
            RexDisplay::assign('imageList', $imageList);
            RexDisplay::assign('categoryList', $categoryList);
            RexDisplay::assign('brandList', $brandList);
            
            $pagerObj->setCount($productManager->_count);
            $pagerObj->generatePages();
  
            RexDisplay::assign($pagerObj->name, $pagerObj);
            
            $count_more = $productManager->_count - ($page * $perPage);
            $count_next = $count_more > $perPage ? $perPage : $count_more;
            
            if ($rexRequest) {
                $reponse['count_next'] = $count_next;  
            }
            
            RexDisplay::assign('count_next', $count_next);
        }
        
        $fetched = $this->manager->getFilters($this->entity->id, $productManager->_productListIDs);
        if ($fetched !== false) {
            RexDisplay::assign('filter_form', $fetched);
        }
        RexDisplay::assign('instant_filter', RexSettings::get('filter_instant') == 'true');
        
        // calculate pricerange
        $pricerange = $productManager->getByCategoryListFilterPrices($fullList, $filter);
       
        if ($pricerange) {
        
            if (isset($filter['rangefrom']) && isset($filter['rangeto']) && $filter['rangefrom'] <= $filter['rangeto']) {
                $pricerange['pricefrom'] = $filter['rangefrom'];     
                $pricerange['priceto'] = $filter['rangeto'];  
            } else {
                $pricerange['pricefrom'] = $pricerange['rangefrom'];
                $pricerange['priceto'] = $pricerange['rangeto'];
            }
        
            RexDisplay::assign('pricerange', $pricerange);
            
        } else if (!$pricerange && isset($filter['rangefrom']) && isset($filter['rangeto'])) {
            $pricetemp['pricefrom'] = $filter['rangefrom'];     
            $pricetemp['priceto'] = $filter['rangeto'];    
            unset($filter['rangeto']);
            unset($filter['rangefrom']);
            
            $priceget = $productManager->getByCategoryListFilterPrices($fullList, $filter);
            
            if ($priceget) {
                $pricerange = array_merge($priceget, $pricetemp);
                RexDisplay::assign('pricerange', $pricerange);
            }
        }
        
        //get main categories tree
        $this->manager = RexFactory::manager('pCatalog');
        $this->manager->getUpList($this->entity->id, RexFactory::entity('pCatalog'));
        $navCategoryList = array_reverse($this->manager->getCollection());        
        if (sizeof($navCategoryList) > 0) {
            $this->manager->getByWhere('`id` IN ('.implode(',', $navCategoryList).') ORDER BY `gorder`');
            RexDisplay::assign('navCategoryList', $this->manager->getCollection());
        }
        //RexPage::setTitle(RexSettings::get('site_slogan'));
        
        if ($rexRequest) {
            $reponse['content'] = RexDisplay::fetch('pcatalog/product.list.inc.tpl'); 
            RexResponse::response($reponse);
        }
    }
	
	function getList() //smarty func
	{
        RexDisplay::assign('valuta', RexSettings::get('dolar_rate', 1));   
        RexDisplay::assign('filter_kurs', RexSettings::get('filter_kurs', '$'));
		$pCatalogManager = RexFactory::manager('pCatalog');
        $pCatalogManager->getByWhere('`active` = 1 AND `level` = 0 AND is_menu = 1 ORDER BY `gorder` ASC');	
        $res = $pCatalogManager->getCollection();
        
        RexDisplay::assign('mainCategoryList', $res);
        
        if ($res) {
            foreach ($res as &$catalog) {
                $pCatalogManager->getByWhere('`pid` = '.$catalog['id'].' AND `active` = 1 AND `level` = 1 ORDER BY `gorder` ASC');
                $catalog['cat_list'] = $pCatalogManager->getCollection();
            }   
        }
        
		RexDisplay::assign('treeList', $res);
	}
    
    function getMainCategory()
    {
        $this->manager->getByWhere('active = 1 AND is_showmain = 1 ORDER BY RAND() LIMIT 3');
        
        RexPage::setTitle(RexSettings::get('site_slogan'));
        RexDisplay:assign('main_categoryList', $this->manager->_collection);
    }
    
    function getFull()
    {
        $pCatalogManager = RexFactory::manager('pCatalog');
        $pCatalogManager->getByWhere('active = 1 ORDER BY gorder ASC');
        $res = $pCatalogManager->getCollection();
        
        $result = array();
        
        foreach ($res as $key => $catalog) {
            if ($catalog['level'] == 0) {
                $result[$catalog['id']] = $catalog;
                unset($res[$key]);    
            }
        }
        
        $i = 1;
        
        while (count($res) > 0) {
            foreach ($res as $key => $catalog) {
                if ($catalog['level'] == $i) {
                    if (isset($result[$catalog['pid']])) {
                        $result['pid'][$catalog['pid']][] = $catalog;
                    }
                    unset($res[$key]);    
                }
            }
            $i++;            
        }
        
        RexDisplay::assign('catalogList', $result);
    }
	
	function getSearch()
	{     
		$zapros = Request::get('q', false);
        $page = Request::get('page', 1);
         
        if ($_SERVER['REQUEST_URI'] == RexRoute::getUrl(array('route' => 'shop_search')) && $page == 1) {
             RexRoute::location(array('route' => 'shop_search_one', 'q' => $zapros));
        }
        
        //
        $category = Request::get('category', false);
        $zapros = urldecode(urldecode($zapros));
        $q = $zapros;
        
        RexPage::setTitle(RexLang::get('catalog.search.title'));
        
		if (!$q or strlen(trim($q)) < 2) {
			RexRoute::location(array('route' => 'shop_full_list'));
		}
		$q = preg_replace('#[^a-zа-я0-9-\s]#isu', '', $q);
		RexDisplay::assign('q', $q);

        $pattern = '/^([0-9]+)-([0-9]+)$/';
        preg_match($pattern, $q, $matches);
        
        $sku_id = false;
        
        if ($matches) {
            $q = $matches[1];
            $sku_id = $matches[2];
            setcookie('sku_id', $sku_id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        } else {
            $pattern = '/^([0-9]+)$/';
            preg_match($pattern, $q, $matches);
            if ($matches) {
                $sku_id = true;    
            }
        }
        
        if ($category) {
            XSession::set('search_category', $category);    
        } else {
            XSession::remove('search_category');
        }
        
		//show product list
		$pagerObj = new PagerObj('pager', RexSettings::get('per_page'), $page);
        		
		$productManager = RexFactory::manager('product');
		$productManager->getByCategorySearch($pagerObj->getFrom(), $pagerObj->getPerPage(), $q, $sku_id, $category);
		$productList = $productManager->getCollection();
		$imageList = $productManager->images;
		$categoryList = $productManager->categories;
		$brandList = $productManager->brands;

        if (sizeof($productList) == 1 && $page == 1) {
            RexRoute::location(array('mod' => 'product', 'act' => 'default', 'cat_alias' => $categoryList[$productList[0]['id']]->alias, 'task' => $productList[0]['id']));    
        } else if (sizeof($productList) >= 1) {
			RexDisplay::assign('productList', $productList);
			RexDisplay::assign('imageList', $imageList);
			RexDisplay::assign('categoryList', $categoryList);
			RexDisplay::assign('brandList', $brandList);
			$pagerObj->setCount($productManager->getByCategorySearchCount($q));
			$pagerObj->generatePages();
			RexDisplay::assign($pagerObj->name, $pagerObj);
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
        
        $pattern = '/^([0-9]+)-([0-9]+)$/';
        preg_match($pattern, $query, $matches);
        
        $sku_id = false;
        
        if ($matches) {
            $query = $matches[1];
            $sku_id = $matches[2];
            setcookie('sku_id', $sku_id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        } else {
            $pattern = '/^([0-9]+)$/';
            preg_match($pattern, $query, $matches);
            if ($matches) {
                $sku_id = true;    
            }
        }
        
        $productManager = RexFactory::manager('product');
	    $productManager->getByCategorySearch(0, 30, $query, $sku_id, Request::get('category', false));
        $res = $productManager->getCollection();
		
		if ($res and sizeof($res) > 0) {
			foreach ($res as $key=>$value) {
				echo XImage::getImg(array('type' => 'list', 'name' => 'pImage', 'id' => $value['image_id'], 'ext' => $value['image_ext'])).'='.
					$value['category_alias'].'/'.$value['product_id'].'.html='.
						$value['category_name'].' '.$value['brand_name'].' '.$value['product_name']."\n";
			}
		}
		exit;
	}
	
	function getYML()
	{
		$this->manager = RexFactory::manager('pCatalog');
		$this->manager->getByWhere('`yml` > 0');
		if (!$this->manager->_collection or sizeof($this->manager->_collection) < 1) {
			exit;
		}

		$globalList = array();
		foreach ($this->manager->getCollection() as $category) {
			$globalList[] = $category['id'];
			
			//get all subcategories
			$manager = RexFactory::manager('pCatalog');
			$manager->getSubCategoriesList($category['id'], 4);
			$categoryList = $manager->struct;
			if ($categoryList and sizeof($categoryList) > 0) {
				$globalList = array_merge($globalList, $categoryList);
			}
		}

		$productManager = RexFactory::manager('product');
		$productManager->getByYML($globalList, 0, 1000);
		$productList = $productManager->getCollection();
		$imageList = $productManager->images;
		$categoryList = $productManager->categories;
		$brandList = $productManager->brands;
	
		if (sizeof($productList) > 0) {
			$uniqueCategoryList = array();
			foreach ($categoryList as $category) {
				$uniqueCategoryList[$category->id] = $category;
			}
			
			
			$xml = '';
			
			$xml .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$xml .= "<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n";
			$xml .= "<yml_catalog date=\"";
			$xml .= date('Y-m-d H:i');
			$xml .= "\">\n"; 
			$xml .= "<shop>\n";			
			$xml .= "<name>".RexConfig('clear_domain')."</name>\n";
			$xml .= "<company>".RexConfig('name')."</company>\n";
			$xml .= "<url>http://".RexConfig('clear_domain')."/</url>\n";
			
			$xml .= "<currencies>\n";			
			$xml .= "<currency id=\"UAH\" rate=\"1\"/>\n";
			$xml .= "<currency id=\"USD\" rate=\"NBU\"/>";			
			$xml .= "</currencies>\n";
			
			$xml .= "<categories>\n";
			foreach ($uniqueCategoryList as $category) {
				if ($category->pid == 0) {
					$xml .= "<category id=\"".$category->id."\">".$category->name."</category>\n";
				} else {
					$xml .= "<category id=\"".$category->id."\" parentId=\"".$category->pid."\">".$category->name."</category>\n";
				}
			}			
			$xml .= "</categories>\n";
			
			$xml .= "<offers>\n";
			
			$iteration = 1;
			foreach ($productList as $product) {
				
				$url 			= 'http://'.RexConfig('clear_domain').'/product/'.$categoryList[$product['id']]->alias.'/'.$product['id'].'.html';
				
				if (isset($imageList[$product['id']])) {
					$image 			= 'http://'.RexConfig('clear_domain').'/content/images/pimage/'.$imageList[$product['id']]['id'].'_full.'.$imageList[$product['id']]['image'];	
				} else {
					$image 			= '';
				}
				
				$name = $product['name'];
				if (isset($brandList[$product['id']])) {
					$name =	$brandList[$product['id']]->name.' '.$name;
				} else {
					continue;
				}

				$xml .= "<offer id=\"".$iteration."\" type=\"vendor.model\" available=\"true\" bid=\"5\">\n";
				$xml .= "<url>".$url."</url>\n";
				$xml .= "<price>".number_format($product['price'] * RexSettings::get('dolar_rate'), 0, '.', '')."</price>\n";
				$xml .= "<currencyId>UAH</currencyId>\n";
				$xml .= "<categoryId>".$product['category_id']."</categoryId>\n";
				$xml .= "<picture>".$image ."</picture>\n";
			  	$xml .= "<delivery>true</delivery>\n";
				$xml .= "<local_delivery_cost>70</local_delivery_cost>\n";
				$xml .= "<typePrefix>".$categoryList[$product['id']]->name_single."</typePrefix>\n";
				$xml .= "<vendor>".$brandList[$product['id']]->name."</vendor>\n";
				$xml .= "<vendorCode>".$product['name']."</vendorCode>\n";
			  	$xml .= "<model>".$product['name']."</model>\n";
				$xml .= "<description>".$name."</description>\n";

				$xml .= "</offer>";
				
				$iteration++;
			}
			
			
			$xml .= "</offers>\n";
			$xml .= "</shop>\n";
			$xml .= "</yml_catalog>\n";
			
			header ('Content-Type:text/xml'); 
			echo $xml;
		}
		
		exit;
	}
	
    function getBestseller() //smarty func
    {
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 5,
            'type' => 'bestseller' //TODO: определение category_id
        ));
    }
    
    function getFeatured() //smarty func
    {
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 3,
            'type' => 'bestseller'
        ));
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 3,
            'type' => 'new'
        ));
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 3,
            'type' => 'event'
        ));
    }
	
	function _featured($aParams)
	{
		if (isset($aParams['category_id']) and $aParams['category_id'] > 0) {
			$this->entity = RexFactory::entity('pCatalog');
			if (!$this->entity->get($aParams['category_id'])) {
				exit;
			}

			//get all subcategories
			$this->manager = RexFactory::manager('pCatalog');
			$this->manager->getSubCategoriesList($this->entity->id, 1);
			$categoryList = $this->manager->struct;
			if ($categoryList and sizeof($categoryList) > 0) {
				$fullList = $categoryList;
				$fullList[] = $this->entity->id;
			} else {
				$fullList = array($this->entity->id);
			}
		} else {
			$fullList = false;
		}
		
		//show product list
		$productManager = RexFactory::manager('product');
		$productManager->getByFeaturedList($fullList, 0, $aParams['count'], $aParams['type']);
		$productList = $productManager->getCollection();
		$imageList = $productManager->images;
		$categoryList = $productManager->categories;
		$brandList = $productManager->brands;

		if (sizeof($productList) > 0) {
			RexDisplay::assign($aParams['type'].'_productList', 	$productList);
			RexDisplay::assign($aParams['type'].'_imageList', 	$imageList);
			RexDisplay::assign($aParams['type'].'_categoryList', $categoryList);
			RexDisplay::assign($aParams['type'].'_brandList', 	$brandList);
		}
	}
    
}