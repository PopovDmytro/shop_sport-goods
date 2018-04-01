<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class PImageRextestController
 *
 * PImage Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PImageRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PImageRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0'
    );
    
    //admin
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
        
        $this->assertRegExp('#name=\"entity\[name\]\"#', $result_decode->content->template, 'Check image input name in the page');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `pimage`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[name]' => 're',
            'entity[description]' => 'rextest_description',
            'entity[product_id]' => 9999
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
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `pimage`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Image does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to create the image record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check image name')) {
                $image_id = $entityNew->id;
                
                $this->_testEditAdmin($image_id);
                                
                $this->_testDeleteAdmin($image_id);
            }
        }
    }
    
    private function _testEditAdmin($image_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $image_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $brand = RexFactory::entity($this->mod);
            $brand->getByFields(array('id' => $image_id));
            
            $this->assertRegExp('#'.$brand->name.'#', $result_decode->content->template, 'Check brand name in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $image_id,
            'entity[submit]' => true,
            'entity[name]' => 'rextest_name_second',
            'entity[description]' => 'rextest_description_second'
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }

        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $image_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->description, 'rextest_description_second', 'Verify that description');
        
        return true;
    }
    
    private function _testDeleteAdmin($image_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $image_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $image_id));
            $entity->delete();
        }
        
        return true;
    }
}