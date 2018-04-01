<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;

/**
 * Class ProductRextestController
 *
 * Product Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  ProductRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class ProductRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_id = XDatabase::getOne('SELECT `'.$this->entity->__uid.'` FROM `product` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'default', $last_id);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->entity->get($last_id);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#'.$this->entity->name.'#', $result[0], 'Check product name in page');
        
        $this->assertRegExp('#'.$this->entity->price.'#', $result[0], 'Check product name in page');
        
        $this->assertRegExp('#'.$this->entity->content.'#', $result[0], 'Check product name in page');
        
        $entity = RexFactory::entity('pCatalog');
        $entity->get($this->entity->category_id);
        
        $this->assertRegExp('#'.$entity->name.'#', $result[0], 'Check product name in page');
    }
    
    function testArchive()
    {
        $get = $this->generateGet($this->mod, 'archive');
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
    }
    
    //admin
    function testDefaultAdmin()
    {
        $get = $this->generateGet($this->mod, 'default');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $list = new RexDBList('pCatalog');
        $list->setOrderBy('`gorder` ASC');
        
        foreach ($list as $key => $value) {
            $name = str_replace('(', '\(', $value->name);
            $name = str_replace(')', '\)', $name);
            $this->assertRegExp('#'.$name.'#', $result[0], 'Check catalog '.$value->name.' in the select');
        }
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
                $this->assertRegExp('#'.$name.'#', $result_decode->content->template, 'Check catalog '.$value->name.' in the select');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
            
            $category_id = $value->id;
        }
        
        $get = $this->generateGet('brand', 'loadbycat');
        $post = array(
            'category_id' => $category_id
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $brand = '';
        if (sizeof($result_decode->content) > 0) {
            foreach ($result_decode->content as $key => $value) {
                $brand = $key;
                break;
            }
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `product`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[name]' => 're',
            'entity[price]' => '',
            'entity[content]' => 'rextest_content',
            'entity[active]' => 1,
            'entity[bestseller]' => 0,
            'entity[new]' => 0,
            'entity[event]' => 0,
            'entity[category_id]' => $category_id,
            'entity[brand_id]' => $brand,
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
            $this->assertEquals($result_decode->error, 'Please enter price', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check error name');
        }
        
        $post['entity[price]'] = '1304';
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `product`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Product does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to creat the product record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check product name')) {
                $product_id = $entityNew->id;
                
                $this->_testEditAdmin($product_id);
                
                $this->_testDeleteAdmin($product_id);
            }
        }
    }
    
    private function _testEditAdmin($product_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $product_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $product = RexFactory::entity($this->mod);
            $product->getByFields(array('id' => $product_id));
            
            $this->assertRegExp('#'.$product->name.'#', $result_decode->content->template, 'Check product name in the edit page');
            $this->assertRegExp('#'.$product->price.'#', $result_decode->content->template, 'Check product price in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $product_id,
            'entity[submit]' => true,
            'entity[name]' => 'rextest_name_second',
            'entity[price]' => '130490',
            'entity[content]' => 'rextest_content',
            'entity[active]' => 1,
            'entity[bestseller]' => 0,
            'entity[new]' => 1,
            'entity[event]' => 0,
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
        $entity->getByFields(array('id' => $product_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->new, 1, 'Verify type - new');
    }
    
    private function _testDeleteAdmin($product_id)
    {
        $entity = RexFactory::entity('prod2Order');
        $entity->order_id = 99999;
        $entity->product_id = $product_id;
        $entity->count = 1;
        $entity->attributes = '';
        
        $this->assertTrue($entity->create(), 'Check create prod2Order record');
        
        $get = $this->generateGet($this->mod, 'delete', $product_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Unable to delete Product. Please, view order list and clear linked orders', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
         
        $this->assertTrue($entity->delete(), 'Check delete prod2Order record');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $product_id));
            $entity->delete();
        }
    }
    
    function testMassAdmin()
    {
        $get = $this->generateGet($this->mod, 'mass');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
    }
}