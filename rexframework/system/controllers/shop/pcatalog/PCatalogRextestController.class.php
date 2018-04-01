<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexConfig as RexConfig;
use \RexDBList as RexDBList;

/**
 * Class PCatalogRextestController
 *
 * PCatalog Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PCatalogRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PCatalogRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\AttributeEntity:shop.standart:1.0',
        'RexShop\BrandEntity:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `pcatalog` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'default', $last_alias);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#href=\"/catalog/'.$last_alias.'/\"#', $result[0], 'Check url in the content');
    }
    
    function testByPrice()
    {
        $get = $this->generateGet($this->mod, 'byprice');
        
        $result = $this->getDataFrontend($get);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#<title>Фильтр по цене</title>#', $result[0], 'Check title valid');
    }
    
    function testByBrand()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias_catalog = XDatabase::getOne('SELECT `alias` FROM `pcatalog` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'bybrand', $last_alias_catalog);
        
        $entity = RexFactory::entity('brand');
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `brand` ORDER BY `'.$entity->__uid.'` DESC LIMIT 1');
        
        $post = array('brand' => $last_alias);
        $post = $this->generatePost('frontend', $post);
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity->getByFields(array('alias' => $last_alias_catalog));
        $entity->getByFields(array('alias' => $last_alias));
        
        $this->assertRegExp('#<title>'.$this->entity->name.' '.$entity->name.'</title>#', $result[0], 'Check title valid');
    }
    
    function testByAttribute()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias_catalog = XDatabase::getOne('SELECT `alias` FROM `attribute` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        if (!$last_alias_catalog)
            return false;
        
        $get = $this->generateGet($this->mod, 'byattribute', $last_alias_catalog);
        
        $entity = RexFactory::entity('attribute');
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `attribute` WHERE `type_id` < 8 ORDER BY `'.$entity->__uid.'` DESC LIMIT 1');
        
        $entity->getByFields(array('alias' => $last_alias));
        
        $post = array(
            'attribute' => $last_alias,
            'value' => $entity->id
        );
        $post = $this->generatePost('frontend', $post);
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity->getByFields(array('alias' => $last_alias_catalog));
                
        $this->assertRegExp('#<title>'.$this->entity->name.' '.$entity->name.'</title>#', $result[0], 'Check title valid');
    }
    
    function testByAttributeList()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias_catalog = XDatabase::getOne('SELECT `alias` FROM `pcatalog` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'byattributelist', $last_alias_catalog);
        $post = $this->generatePost('frontend');                
                
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $urlRoute = "http://".RexConfig::get('Project', 'cookie_domain').'//index.php?mod=pcatalog&task='.$last_alias_catalog;
        $this->assertEquals($urlRoute, $result[1]['url'], 'Check redirect url');
        
        $entity = RexFactory::entity('brand');
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `brand` ORDER BY `'.$entity->__uid.'` DESC LIMIT 1');
        
        $post = array('brand_alias' => $last_alias);
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $urlRoute = "http://".RexConfig::get('Project', 'cookie_domain').'//index.php?mod=pcatalog&act=bybrand&task='.$last_alias_catalog.'&brand='.$last_alias;
        $this->assertEquals($urlRoute, $result[1]['url'], 'Check redirect url');
        
        $this->entity->getByFields(array('alias' => $last_alias_catalog));
        $entity->getByFields(array('alias' => $last_alias));
        
        $this->assertRegExp('#<title>'.$this->entity->name.' '.$entity->name.'</title>#', $result[0], 'Check title valid');
    }
    
    function testSearch()
    {
        $get = $this->generateGet($this->mod, 'search');
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $post['q'] = 'qwerty';        
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#<title>Поиск по сайту</title>#', $result[0], 'Check title valid');
    }
    
    function testAutocomplete()
    {
        $get = $this->generateGet($this->mod, 'autocomplete');
        
        $post = array('q' => 'a'); 
        $post = $this->generatePost('frontend', $post);       
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertFalse($result[0], 'Check empty result');
    }
    
    function testYML()
    {
        $get = $this->generateGet($this->mod, 'yml');
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->manager = RexFactory::manager($this->mod);
        $this->manager->getByWhere('yml > 0');
        if ($this->manager->_collection && sizeof($this->manager->_collection) >= 1) {
            $this->assertDoctype($result[0], 'Check for errors and exeptions', '<?xml ', 6);
        }
    }
    
    //admin
    function testDefaultAdmin()
    {
        $this->entity = RexFactory::entity($this->mod);
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#name=\"pid\"#', $result[0], 'Check filter in the content');
    }
    
    function testAddEditDeleteAdmin()
    {
        $get = $this->generateGet($this->mod, 'add');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $list = new RexDBList('pCatalog');
        $list->setOrderBy('`gorder` ASC');
        
        foreach ($list as $key => $value) {
            $name = str_replace('(', '\(', $value->name);
            $name = str_replace(')', '\)', $name);
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#'.$name.'#', $result_decode->content->template, 'Check parent categories '.$value->name.' in the select');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
        }
        
        $brandList = new RexDBList('brand');        
        
        foreach ($brandList as $key => $value) {
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#'.$value->name.'#', $result_decode->content->template, 'Check brand '.$value->name.' in the myultiselect');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
        }
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `pcatalog`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[pid]' => 0,
            'entity[name]' => 're',
            'entity[alias]' => '',
            'entity[name_single]' => 'rextest_single_name',
            'entity[content]' => 'rextest_content',
            'entity[active]' => 1,
            'entity[yml]' => 0,
            'entity[title]' => 'rextest_title',
            'entity[description]' => 'rextest_description',
            'entity[keywords]' => 'rextest_keywords'
        );
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Name must have min 3 characters', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check error name');
        }
        
        $post['entity[name]'] = 'rextest_name';
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Please enter alias', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check error name');
        }
        
        $post['entity[alias]'] = 'rextest_alias';
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `pcatalog`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Product does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to creat the pcatalog record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check pcatalog name')) {
                $pcatalog_id = $entityNew->id;
                
                $this->_testEditAdmin($pcatalog_id);
                                
                $this->_testDeleteAdmin($pcatalog_id);
            }
        }
    }
    
    private function _testEditAdmin($pcatalog_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $pcatalog_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $pcatalog = RexFactory::entity($this->mod);
            $pcatalog->getByFields(array('id' => $pcatalog_id));
            
            $this->assertRegExp('#'.$pcatalog->name.'#', $result_decode->content->template, 'Check pcatalog name in the edit page');
            $this->assertRegExp('#'.$pcatalog->alias.'#', $result_decode->content->template, 'Check pcatalog alias in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $pcatalog_id,
            'entity[submit]' => true,
            'entity[pid]' => 0,
            'entity[name]' => 'rextest_name_second',
            'entity[alias]' => 'rextest_alias_second',
            'entity[name_single]' => 'rextest_single_name',
            'entity[content]' => 'rextest_content',
            'entity[active]' => 0,
            'entity[yml]' => 0,
            'entity[title]' => 'rextest_title',
            'entity[description]' => 'rextest_description',
            'entity[keywords]' => 'rextest_keywords'
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }

        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $pcatalog_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->active, 0, 'Verify active status');
    }
    
    private function _testDeleteAdmin($pcatalog_id)
    {
        $entity = RexFactory::entity('product');
        $entity->name = 'rextest_name';
        $entity->category_id = $pcatalog_id;
        $entity->price = 1000;
        
        $this->assertTrue($entity->create(), 'Check create product record');
        
        $get = $this->generateGet($this->mod, 'delete', $pcatalog_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Unable to delete PCatalog. Please, uncheck products from this categories tree', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
         
        $this->assertTrue($entity->delete(), 'Check delete product record');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $pcatalog_id));
            $entity->delete();
        }
    }
}