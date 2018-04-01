<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexDBList as RexDBList;
use \RexSettings as RexSettings;
use \Request as Request;
use \XDatabase as XDatabase;

/**
 * Class Attr2ProdManager
 *
 * Manager of Attr2Prod
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Attr2ProdManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\Attr2ProdEntity:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );
    
	var $fetched;
	var $product;
	
	var $productList;
	
	var $attr2prodList;
	
	function __construct()
	{
		parent::__construct('attr2prod', 'id');
	}
	
	function draw($aIsAdmin=false)
	{
		$productEntity = $this->product;
		
		$this->fetched = '';
		
	    //get main categories tree
		$pcatelogManager = RexFactory::manager('pCatalog');
		$pcatelogManager->getUpList($productEntity->category_id, RexFactory::entity('pCatalog'));
		$categories = array_reverse($pcatelogManager->getCollection());		
		if (sizeof($categories) < 1) {
			return false;
		}
	    //get attributes
		$attr2CatList = array();
		foreach ($categories as $category_id) {
			$attr2CatManager = RexFactory::manager('attr2Cat');
            if ($aIsAdmin) {
                $attr2CatManager->getByWhereOrder('category_id = '.$category_id, 'sorder ASC');    
            } else {
                $attr2CatManager->getByWhereOrder('is_forsale = 0 AND category_id = '.$category_id, 'sorder ASC');
            }
            if ($attr2CatManager->_collection){
                $collections = $attr2CatManager->getCollection();
                foreach($collections as $collection){
                    $match = false;
                    if (empty($attr2CatList)){
                        $match = true;
                    }else {
                        foreach($attr2CatList as $item){
                            if ($item["attribute_id"] != $collection["attribute_id"] ){
                                $match = true;
                            }
                        }
                    }
                    if ($match){
                        $attr2CatList = array_merge($attr2CatList, $attr2CatManager->getCollection());
                    }
                }
            }
		}

		if (sizeof($attr2CatList) < 1) {
			return false;
		}
		$attributeList = array();
		foreach ($attr2CatList as $value) {
			$attributeEntity = RexFactory::entity('attribute');
			if ($attributeEntity->get($value['attribute_id'])) {
				$attributeList[] = $attributeEntity;
			}
		}
		if (sizeof($attributeList) < 1) {
			return false;
		}

		RexDisplay::assign('attributeList', $attributeList);

        foreach ($attributeList as $attribute) {
			if ($aIsAdmin) {
                $this->drawAdmin($attribute);
			} else {
				$this->drawFront($attribute);
			}
		}

		/*$replaced = preg_replace('#<tr class="group">.+?</tr>#isu', '', $this->fetched);
		if (!preg_match('#<tr[^<>]*>.+?</tr>#isu', $replaced)) {
			$this->fetched = false;
		}*/
	}
	
	function drawMulti()
	{
		$productEntity = $this->productList[0];
		
		$this->fetched = '';
		
	    //get main categories tree
		$pcatelogManager = RexFactory::manager('pCatalog');
		$pcatelogManager->getUpList($productEntity['category_id'], RexFactory::entity('pCatalog'));
		$categories = array_reverse($pcatelogManager->getCollection());		
		if (sizeof($categories) < 1) {
			return false;
		}
	    //get attributes
		$attr2CatList = array();
		foreach ($categories as $category_id) {
			$attr2CatManager = RexFactory::manager('attr2Cat');
			$attr2CatManager->getByFields(array('category_id' => $category_id));
			$attr2CatList = array_merge($attr2CatList, $attr2CatManager->getCollection());
		}
		if (sizeof($attr2CatList) < 1) {
			return false;
		}
		$attributeList = array();
		foreach ($attr2CatList as $value) {
			$attributeEntity = RexFactory::entity('attribute');
            
			if ($attributeEntity->get($value['attribute_id'])) {
				$attributeList[] = $attributeEntity;
			}
		}
        
		if (sizeof($attributeList) < 1) {
			return false;
		}
		usort($attributeList, array($this, '_sort'));
		RexDisplay::assign('attributeList', $attributeList);
		
		foreach ($attributeList as $attribute) {
			$this->drawFrontMulti($attribute);
		}
		
		$replaced = preg_replace('#<tr class="group">.+?</tr>#isu', '', $this->fetched);
		if (!preg_match('#<tr[^<>]*>.+?</tr>#isu', $replaced)) {
			$this->fetched = false;
		}
	}
	
	function drawAdmin($aAttribute)
	{
        switch ($aAttribute->type_id) {
			
			case 1: //group
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_group.inc.tpl');
	
                $list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				foreach ($list as $attribute) {
					$this->drawAdmin($attribute);
				}
				
				$this->fetched .= '<tr><td colspan="2"><hr></tr>';
				break;

			case 2: //text
			case 3: //integer
			case 4: //double
				//value
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_text.inc.tpl');
				break;
				
			case 5: //boolean
				//value
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', false);
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_boolean.inc.tpl');
				break;
				
			case 6: //select
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				RexDisplay::assign('attributeList', $list);
				
				//values
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_select.inc.tpl');

				break;
				
			case 7: //multiselect
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				RexDisplay::assign('attributeList', $list);
				
				//values
                $tmp = new RexDBList('attr2Prod');
                $tmp->getByWhere('`attribute_id` = '.intval($aAttribute->id).' AND `product_id` = '.$this->product->id);
				if (sizeof($tmp) > 0) {					
					$list = array();
					foreach ($tmp as $attribute) {
						$list[$attribute->value] = $attribute;
					}
					RexDisplay::assign('attr2prod', $list);
				} else {
					RexDisplay::assign('attr2prod', '');
				}

				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_multiselect.inc.tpl');
				break;
				
			case 9: // range
                //subfilters
                $list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id.' AND `active` = 1');
                $list->setOrderBy('`name` ASC');
                
                if (sizeof($list) != 3) {
                    return false;
                }
                
                $to_sort = array();
                $list->rewind(0);
                $rangeArr['start'] = intval($list->current()->name);
                $rangeArr['start_id'] = intval($list->current()->id);
                $list->rewind(1);
                $rangeArr['end'] = intval($list->current()->name);
                $rangeArr['end_id'] = intval($list->current()->id);
                $rangeArr['to'] = intval($list->current()->id);
                $list->rewind(2);
                $rangeArr['name'] = $list->current()->name;
                
                RexDisplay::assign('range', $rangeArr);
                //value
                $manager = RexFactory::manager('attr2Prod');
                
                if (is_object($this->product) && is_numeric($this->product->id)) {
                    $manager->getByWhere('(attribute_id = '.$rangeArr['start_id'].' OR attribute_id = '.$rangeArr['end_id'].') AND product_id = '.$this->product->id.' ORDER BY CONVERT(`value`, UNSIGNED INTEGER) ASC');
                    
                    if ($manager->_collection && count($manager->_collection) > 0) {
                        RexDisplay::assign('rangefrom', $manager->_collection[0]['value']);
                        RexDisplay::assign('rangeto', $manager->_collection[1]['value']);
                    } else {
                        RexDisplay::assign('rangefrom', false);
                        RexDisplay::assign('rangeto', false);
                    }
                    
                } else {
                    RexDisplay::assign('rangefrom', false);
                    RexDisplay::assign('rangeto', false);
                }
                    
                RexDisplay::assign('attribute', $aAttribute);
                $this->fetched .= RexDisplay::fetch('attribute/_range.inc.tpl');
                break;
		}
	}
	
	function drawFront($aAttribute)
	{
		switch ($aAttribute->type_id) {
			
			case 1: //group
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_group.inc.tpl');
	
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				
				foreach ($list as $attribute) {
					$this->drawFront($attribute);
				}
				
				break;

			case 2: //text
			case 3: //integer
			case 4: //double
				//value
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_text.inc.tpl');
				break;
				
			case 5: //boolean
				//value
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_boolean.inc.tpl');
				break;
				
			case 6: //select
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}

				RexDisplay::assign('attributeList', $list);
				
				//values
				$entity = RexFactory::entity('attr2Prod');
				if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product->id))) {
					RexDisplay::assign('attr2prod', $entity);	
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_select.inc.tpl');

				break;
				
			case 7: //multiselect
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				RexDisplay::assign('attributeList', $list);
				
				//values
				$tmp = new RexDBList('attr2Prod');
                $tmp->getByWhere('`attribute_id` = '.intval($aAttribute->id).' AND `product_id` = '.$this->product->id);
				if (sizeof($tmp) > 0) {					
					$list = array();
					foreach ($tmp as $attribute) {
						$list[$attribute->value] = $attribute;
					}
					RexDisplay::assign('attr2prod', $list);
				} else {
					RexDisplay::assign('attr2prod', '');
				}
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/_multiselect.inc.tpl');

				break;
				
			case 9: // range
                //subfilters
                $list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id.' AND `active` = 1');
                $list->setOrderBy('`name` ASC');
                
                if (sizeof($list) != 3) {
                    return false;
                }
                
                $to_sort = array();
                $list->rewind(0);
                $to_sort[] = intval($list->current()->name);
                $rangeArr['start_id'] = intval($list->current()->id);
                $list->rewind(1);
                $to_sort[] = intval($list->current()->name);
                $rangeArr['end_id'] = intval($list->current()->id);
                $list->rewind(2);
                $rangeArr['name'] = $list->current()->name;
                sort($to_sort);
                list($start, $end) = $to_sort;
                
                RexDisplay::assign('start', $start);
                RexDisplay::assign('end', $end);
                //value
                $manager = RexFactory::manager('attr2Prod');
                if (is_object($this->product) && is_numeric($this->product->id)) {
                    $manager->getByWhere('(attribute_id = '.$rangeArr['start_id'].' OR attribute_id = '.$rangeArr['end_id'].') AND product_id = '.$this->product->id.' ORDER BY CONVERT(`value`, UNSIGNED INTEGER) ASC');
                    
                    if ($manager->_collection && count($manager->_collection) > 0) {
                        RexDisplay::assign('rangefrom', $manager->_collection[0]['value']);
                        RexDisplay::assign('rangeto', $manager->_collection[1]['value']);
                    } else {
                        RexDisplay::assign('rangefrom', false);
                        RexDisplay::assign('rangeto', false);
                    }
                    
                } else {
                    RexDisplay::assign('rangefrom', false);
                    RexDisplay::assign('rangeto', false);
                }
                
                RexDisplay::assign('rangename', $rangeArr['name']);
                    
                RexDisplay::assign('attribute', $aAttribute);
                $this->fetched .= RexDisplay::fetch('attribute/_range.inc.tpl');
                break;
		}
	}
	
	function _sort($a, $b)
	{
		$a_1 = $a->gorder;
		$a_2 = $b->gorder;
			
		if ($a_1 == $a_2) {			
			return 0;	
		}
		
		if ($a_1 > $a_2) {
			return 1;	
		} else {
			return -1;
		}
	}
	
	function drawForm($aAttribute)
	{
        RexDisplay::assign('filter_reduce', RexSettings::get('filter_reduce') == 'true');
        switch ($aAttribute->type_id) {
			//hz, nujna li tut gruppa voobshe!
			case 1: //group
			/*
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/form/_group.inc.tpl');
	
				$attributeManager = RexFactory::manager('attribute');
				$attributeManager->getByFields(array('pid' => $aAttribute->id));
				$list = $attributeManager->getCollection('object');
				if (sizeof($list) < 1) {
					return false;
				}
				Sys::dump($list);
				usort($list, array($this, '_sort'));
				foreach ($list as $attribute) {
					$this->drawForm($attribute);
				}*/
				break;

			case 2: //text
			case 3: //integer
			case 4: //double
				//value
				$values = array();
                
				foreach ($this->attr2prodList as $value) {					
					
                    if ($aAttribute->id == $value['attribute_id']) {
						$value['value'] = trim($value['value']);
						$values[md5($value['value'])]['value'] = $value['value'];
                        
                        if (isset($value['show'])) {
                            $values[md5($value['value'])]['show'] = $value['show'];    
                        }
                        
					}
                    
				}
                
				/*$values = array_values($values);
				natsort($values); */
				RexDisplay::assign('values', $values);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/form/_text.inc.tpl');
				break;
				
			case 5: //boolean
				//value
				$values = array();
				
                foreach ($this->attr2prodList as $value) {					
					
                    if ($aAttribute->id == $value['attribute_id']) {
						$value['value'] = trim($value['value']);
                        $values[md5($value['value'])]['value'] = $value['value'];
                        
                        /*if (isset($value['show'])) {
                            $values[md5($value['value'])]['show'] = $value['show'];    
                        }*/
                        
					}
                    
				}
				
				ksort($values);
				RexDisplay::assign('values', $values);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/form/_boolean.inc.tpl');
				break;
				
			case 6: //select
				//subattributes
				$values = array();				
				foreach ($this->attr2prodList as $value) {					
					if ($aAttribute->id == $value['attribute_id']) {
						$value['value'] = trim($value['value']);
                        $values[md5($value['value'])]['value'] = $value['value'];
                        
                        if (isset($value['show'])) {
                            $values[md5($value['value'])]['show'] = $value['show'];    
                        }						
					}
				}
				
				$resultValueList = array();
				$subList = array();
				foreach ($values as $key => $value) {
					$subAttribute = RexFactory::entity('attribute');
					if (!$subAttribute->get(intval($value['value']))) {
						continue;
					}
                    
                    if (isset($value['show'])) {
                        $resultValueList[intval($value['value'])] = $value['show'];
                    }
                    
					$subList[] = $subAttribute;
				}
                
				//Sys::dump($subList);
                $attr2ProdManager = RexFactory::manager('attr2Prod');
				usort($subList, array($attr2ProdManager, '_sort'));
				//\Sys::dump($resultValueList);
				RexDisplay::assign('values', $resultValueList);
				RexDisplay::assign('values_sorted', $subList);
				
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/form/_select.inc.tpl');

				break;
				
			case 7: //multiselect
				//subattributes
				$values = array();
				foreach ($this->attr2prodList as $value) {					
					if ($aAttribute->id == $value['attribute_id']) {
						$value['value'] = trim($value['value']);
						$values[md5($value['value'])]['value'] = $value['value'];
                        
                        if (isset($value['show'])) {
                            $values[md5($value['value'])]['show'] = $value['show'];    
                        }						
					}
				}

				$resultValueList = array();
				$subList = array();
				foreach ($values as $key => $value) {
					$subAttribute = RexFactory::entity('attribute');
					if (intval($value['value']) == 0 || !$subAttribute->get(intval($value['value']))) {
                        continue;
                    }
                    
					if (isset($value['show'])) {
                        $resultValueList[intval($value['value'])] = $value['show'];
                    }
                    
					$subList[] = $subAttribute;
				}
				
                $attr2ProdManager = RexFactory::manager('attr2Prod');
				@usort($subList, array($attr2ProdManager, '_sort'));
				RexDisplay::assign('values', $resultValueList);
				RexDisplay::assign('attribute', $aAttribute);
				RexDisplay::assign('values_sorted', $subList);
				$this->fetched .= RexDisplay::fetch('attribute/form/_multiselect.inc.tpl');

				break;
				
			case 9: // range
                //subfilters
                $list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id.' AND `active` = 1');
                $list->setOrderBy('`name` ASC');
                
                if (sizeof($list) != 3) {
                    return false;
                }

                $list->rewind(0);
                $rangeArr['start'] = intval($list->current()->name);
                $rangeArr['start_id'] = intval($list->current()->id);
                $list->rewind(1);
                $rangeArr['end'] = intval($list->current()->name);
                $rangeArr['end_id'] = intval($list->current()->id);
                $list->rewind(2);
                $rangeArr['name'] = $list->current()->name;
                
                if (count($this->attr2prodList) > 0) {
                    
                    //$rangeArr['end'] = $rangeArr['start'] = ($rangeArr['start']+$rangeArr['end'])/2;
                    $countStart = 1;
                    $countEnd = 1;
                    
                    foreach ($this->attr2prodList as $value) {                    
                        
                        if ($rangeArr['start_id'] == $value['attribute_id'] && isset($value['show'])) {
                            
                            if (1 == $countStart) {
                                $rangeArr['start'] = $value['value'];
                                $countStart = 0;     
                            } else {
                                
                                if ($value['value'] < $rangeArr['start']) {
                                    $rangeArr['start'] = $value['value'];   
                                }
                                
                            }
                            
                        } elseif ($rangeArr['end_id'] == $value['attribute_id'] && isset($value['show'])) {
                            
                            if (1 == $countEnd) {
                                $rangeArr['end'] = $value['value'];
                                $countEnd = 0;     
                            } else {
                                
                                if ($value['value'] >= $rangeArr['end']) {
                                    $rangeArr['end'] = $value['value'];    
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
                if ($rangeArr['end'] == $rangeArr['start']) {
                    return;
                }
                
                //\Sys::dump($rangeArr);
                RexDisplay::assign('range', $rangeArr);
                //value
                $manager = RexFactory::manager('attr2Prod');
                
                $filter = Request::get('filter', false);
                
                if (isset($filter['attribute'][$aAttribute->id][$rangeArr['start_id']]) && isset($filter['attribute'][$aAttribute->id][$rangeArr['end_id']])) {
                    $rangeArr['from'] = $filter['attribute'][$aAttribute->id][$rangeArr['start_id']];             
                    $rangeArr['to'] = $filter['attribute'][$aAttribute->id][$rangeArr['end_id']];             
                } else {
                    $rangeArr['from'] = false;
                    $rangeArr['to'] = false;
                }
                
                RexDisplay::assign('rangefrom', $rangeArr['from']);
                RexDisplay::assign('rangeto', $rangeArr['to']);
                
                RexDisplay::assign('instant_filter', RexSettings::get('filter_instant'));    
                RexDisplay::assign('attribute', $aAttribute);
                $this->fetched .= RexDisplay::fetch('attribute/form/_range.inc.tpl');
                break;
		}
	}
	
	function drawFrontMulti($aAttribute)
	{
        switch ($aAttribute->type_id) {
			
			case 1: //group
				RexDisplay::assign('attribute', $aAttribute);
				RexDisplay::assign('colspan', sizeof($this->productList)+1);
				$this->fetched .= RexDisplay::fetch('attribute/multi/_group.inc.tpl');
	
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				foreach ($list as $attribute) {
					$this->drawFrontMulti($attribute);
				}
				break;

			case 2: //text
			case 3: //integer
			case 4: //double
				//value				
				$attr2ProdList = array();
				foreach ($this->productList as $product) {
					$entity = RexFactory::entity('attr2Prod');
					if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $product['id']))) {
						$attr2ProdList[] = $entity;
					} else {
						$attr2ProdList[] = false;
					}				
				}
				RexDisplay::assign('attr2prodList', $attr2ProdList);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/multi/_text.inc.tpl');
				break;
				
			case 5: //boolean
				//value
				$attr2ProdList = array();
				foreach ($this->productList as $product) {
					$entity = RexFactory::entity('attr2Prod');
					if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $product['id']))) {
						$attr2ProdList[] = $entity;
					} else {
						$attr2ProdList[] = false;
					}				
				}
				RexDisplay::assign('attr2prodList', $attr2ProdList);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/multi/_boolean.inc.tpl');
				break;
				
			case 6: //select
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				RexDisplay::assign('attributeList', $list);
				
				//values
				$attr2ProdList = array();
				foreach ($this->productList as $product) {
					$entity = RexFactory::entity('attr2Prod');
					if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $product['id']))) {
						$attr2ProdList[] = $entity;
					} else {
						$attr2ProdList[] = false;
					}				
				}
				RexDisplay::assign('attr2prodList', $attr2ProdList);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/multi/_select.inc.tpl');

				break;
				
			case 7: //multiselect
				//subattributes
				$list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$aAttribute->id);
                $list->setOrderBy('`gorder` ASC');
                
				if (sizeof($list) < 1) {
					return false;
				}
				
				RexDisplay::assign('attributeList', $list);
				
				//values
				$attr2ProdList = false;
				foreach ($this->productList as $product) {
                    $tmp = new RexDBList('attr2Prod');
                    
                    $tmp->getByWhere('`attribute_id` = '.$aAttribute->id.' AND `product_id` = '.$product['id']);
					if ($tmp and sizeof($tmp) > 0) {						
						$list = array();
                        
						foreach ($tmp as $attribute) {
                            
                            if ($product['sku_line']) {
                                $continue = 0;
                                $skus = explode(',', $product['sku_line']);
                                foreach ($skus as $sku) {
                                    if ($sku == $attribute->id) {
                                        $continue = 1;
                                    }
                                }
                                if ($continue == 0) {
                                    continue;
                                }
                            }
                            
							$list[$attribute->value] = $attribute;
						}
                        //if ($list) {
						    $attr2ProdList[] = $list;
                        //}
					}				
				}
                
				RexDisplay::assign('attr2prodList', $attr2ProdList);
				RexDisplay::assign('attribute', $aAttribute);
				$this->fetched .= RexDisplay::fetch('attribute/multi/_multiselect.inc.tpl');
				break;
                
            case 9: // range
                //subfilters
                $list = new RexDBList('attribute');
                $list->getByWhere('`pid` = '.$afilter->id.' AND `active` = 1');
                
                if (sizeof($list) != 2) {
                    return false;
                }
                
                $to_sort = array();
                $list->rewind(0);
                $to_sort[] = intval($list->current()->name);
                $list->rewind(1);
                $to_sort[] = intval($list->current()->name);
                sort($to_sort);
                list($start, $end) = $to_sort;
                
                RexDisplay::assign('start', $start);
                RexDisplay::assign('end', $end);
                //value
                $entity = RexFactory::entity('attr2Prod');
                if (is_object($this->product) && is_numeric($this->product->id)) {
                    if ($entity->getByFields(array('attribute_id' => $aAttribute->id, 'product_id' => $this->product['id']))) {
                        $range = explode(',', $entity->value);
                        RexDisplay::assign('rangefrom', $range[0]);
                        RexDisplay::assign('rangeto', $range[1]);
                    } else {
                        RexDisplay::assign('rangefrom', false);
                        RexDisplay::assign('rangeto', false);
                    }
                } else {
                    RexDisplay::assign('rangefrom', false);
                    RexDisplay::assign('rangeto', false);
                }
                    
                RexDisplay::assign('attribute', $aAttribute);
                $this->fetched .= RexDisplay::fetch('attribute/_range.inc.tpl');
                break;
		}
	}
    
    function getOnlyForSale($aProductID, $aCategoryID)
    {
        $sql = 'SELECT 
                  ap.* 
                FROM
                  attr2prod ap 
                  INNER JOIN attr2cat ac 
                    ON ap.`attribute_id` = ac.`attribute_id` 
                    AND ac.`category_id` = '.$aCategoryID.'
                    AND ac.`is_forsale` = 1 
                WHERE ap.`product_id` = '.$aProductID;
                
        $this->_collection = XDatabase::getAll($sql);
    }
    
    function getColorAttributes($aProductID)
    {
        $sql = 'SELECT 
                  attr.*
                FROM
                  attr2prod ap 
                  INNER JOIN attr2cat ac 
                    ON ap.`attribute_id` = ac.`attribute_id` 
                    AND ac.`is_forsale` = 1 
                  INNER JOIN attribute attr 
                    ON ap.`attribute_id` = attr.id 
                    AND attr.is_picture = 1 
                WHERE ap.`product_id` = '.$aProductID.'
                GROUP BY attr.id';
                
        return XDatabase::getAll($sql);
    }
    
    function getByAttrID($attributeID, $aProductID)
    {
        $sql = 'SELECT 
                  ap.*,
                  attr.name AS child_name
                FROM
                  attr2prod ap 
                  INNER JOIN attr2cat ac 
                    ON ap.`attribute_id` = ac.`attribute_id` 
                    AND ac.`is_forsale` = 1 
                  INNER JOIN attribute attr 
                    ON ap.`value` = attr.id 
                WHERE ap.`product_id` = '.$aProductID.' AND ap.`attribute_id` = '.$attributeID.' GROUP BY ap.`value`';
                
        return XDatabase::getAll($sql);
    }
    
    function getParentID($attributeID)
    {
        $sql = 'SELECT 
                  attr.id
                FROM
                  attribute attr
                  INNER JOIN attr2prod ap
                  ON attr.id = ap.`attribute_id` AND ap.id = '.$attributeID;
                
        return XDatabase::getOne($sql);
    }
    
    public function getDeleteAllNullSkuses()
    {
        return XDatabase::query('DELETE s.* FROM sku s LEFT JOIN sku_element se ON s.id = se.`sku_id` WHERE se.id IS NULL');
    }
    
    public function getAllByProductIDForCompare($productID)
    {
        $res = XDatabase::getAll('SELECT id, CONCAT(attribute_id, "::", value) AS str FROM attr2prod WHERE product_id = '.$productID);
        
        if ($res) {
            $arrayResp = array();
            
            array_walk($res, function($a) use (&$arrayResp) {
                $arrayResp[$a['id']] = $a['str'];    
            });    
            
            return $arrayResp;
        }
        
        return false;
    }
}