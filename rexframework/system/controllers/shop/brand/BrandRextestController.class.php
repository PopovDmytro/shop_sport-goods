<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class BrandRextestController
 *
 * Brand Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  BrandRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class BrandRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `brand` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'default', $last_alias);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity->getByFields(array('alias' => $get['task']));
        
        $pattern = false;
        if (strlen(trim($this->entity->title)) > 0) {
            $this->assertRegExp('#<title>'.$this->entity->title.'</title>#', $result[0], 'Check title valid 1');
        } elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
            $title = str_replace('{name}', $this->entity->name, $pattern);
            $this->assertRegExp('#<title>'.$title.'</title>#', $result[0], 'Check title valid 2');
        } else {
            if ($this->entity->name) {
                $reg = '#<title>'.$this->entity->name.'</title>#';
            } else {
                $reg = '#<title>\/</title>#';
            }
            $this->assertRegExp($reg, $result[0], 'Check title valid 3');
        }
    }
    
    //damin
    function testDefaultAdmin()
    {
        $this->entity = RexFactory::entity($this->mod);
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#class=\"itemadd\"#', $result[0], 'Check add button in the content');
    }
    
    function testAddEditDeleteAdmin()
    {
        $get = $this->generateGet($this->mod, 'add');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $this->assertRegExp('#name=\"entity\[name\]\"#', $result_decode->content->template, 'Check brand input name in the page');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `brand`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[name]' => 're',
            'entity[alias]' => '',
            'entity[content]' => 'rextest_content',
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
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Brand already exist', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `brand`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Brand does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to create the brand record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check brand name')) {
                $brand_id = $entityNew->id;
                
                $this->_testEditAdmin($brand_id);
                                
                $this->_testDeleteAdmin($brand_id);
            }
        }
    }
    
    private function _testEditAdmin($brand_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $brand_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $brand = RexFactory::entity($this->mod);
            $brand->getByFields(array('id' => $brand_id));
            
            $this->assertRegExp('#'.$brand->name.'#', $result_decode->content->template, 'Check brand name in the edit page');
            $this->assertRegExp('#'.$brand->alias.'#', $result_decode->content->template, 'Check brand alias in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $brand_id,
            'entity[submit]' => true,
            'entity[name]' => 'rextest_name_second',
            'entity[alias]' => 'rextest_alias_second',
            'entity[content]' => 'rextest_content',
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
        $entity->getByFields(array('id' => $brand_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->alias, 'rextest_alias_second', 'Verify that alias');
        
        return true;
    }
    
    private function _testDeleteAdmin($brand_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $brand_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $brand_id));
            $entity->delete();
        }
        
        return true;
    }
}