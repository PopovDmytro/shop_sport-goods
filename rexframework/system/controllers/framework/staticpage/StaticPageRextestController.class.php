<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexSettings as RexSettings;
use \XDatabase as XDatabase;

/**
 * Class StaticPageRextestController
 *
 * Static Page Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  StaticPageRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class StaticPageRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\StaticPageEntity:standart:1.0',
        'RexFramework\StaticPageManager:standart:1.0',
    );
    
    function testDefault()
    {
        $get = $this->generateGet($this->mod, 'default', 'contact');
        $post = array(
            'user[submitlogin]' => true,
            'user[email]' => 'user',
            'user[password]' => 'password'
        );
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity = RexFactory::entity($this->mod);
        $this->entity->getByFields(array('alias' => $get['task']));
        
        $pattern = RexSettings::get('staticpage_title');
        if (strlen(trim($this->entity->title)) > 0) {
            $this->assertRegExp('#<title>'.$this->entity->title.'</title>#', $result[0], 'Check title valid');
        } elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
            $title = str_replace('{name}', $this->entity->name, $pattern);
            $this->assertRegExp('#<title>'.$title.'</title>#', $result[0], 'Check title valid');
        } else {
            $this->assertRegExp('#<title>'.$this->entity->name.'</title>#', $result[0], 'Check title valid');
        }
    }
    
    //admin
    function testDelete()
    {
        $this->entity = RexFactory::entity($this->mod);
        $this->entity->name = 'rextest_name';
        $this->entity->alias = 'rextest_alias';
        $this->entity->content = 'rextest_content';
        $this->entity->title = 'rextest_title';
        $this->entity->description = 'rextest_description';
        $this->entity->active = 1;
        
        $this->assertTrue($this->entity->create(), 'Test to create static page');
        $staticpage_id = $this->entity->id;
        
        $get = $this->generateGet($this->mod, 'delete', $staticpage_id);
        
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        
        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $staticpage_id));
        
        $this->assertFalse($entity->id, 'Check delete static page record');
    }
    
    function testAddEdit()
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `staticpage`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[name]' => 'rextest_name',
            'entity[alias]' => 'rextest_alias',
            'entity[content]' => 'rextest_content',
            'entity[title]' => 'rextest_title',
            'entity[description]' => 'rextest_description'
        );
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `staticpage`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Static page does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to creat the static page record') && $this->assertEquals($entityNew->alias, 'rextest_alias', 'Check static page alias')) {
                $staticpage_id = $entityNew->id;
                $this->_testActive($staticpage_id, 0);
                
                $post = array(
                    'entity[exist_id]' => $staticpage_id,
                    'entity[submit]' => true,
                    'entity[name]' => 'rextest_second_name',
                    'entity[alias]' => 'rextest_alias',
                    'entity[content]' => 'rextest_content',
                    'entity[title]' => 'rextest_title',
                    'entity[description]' => 'rextest_description'
                );
                
                $result = $this->getDataAdmin($get, $post);
                $result_decode = json_decode($result[0]);
                
                if (isset($result_decode->content)) {
                    $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
                } else {
                    $this->assertEquals('error', 'ok', 'Check result by add');
                }
        
                $entity = RexFactory::entity($this->mod);
                $entity->getByFields(array('id' => $staticpage_id));
                
                $this->assertEquals($entity->name, 'rextest_second_name', 'Verify that names');
                
                $this->assertTrue($entity->delete(), 'Check to delete static page record');
            }
        }    
    }
    
    private function _testActive($staticpage_id, $value)
    {
        $get = $this->generateGet($this->mod, 'active', $staticpage_id);
        $post = array('value' => $value);
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by active');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by active');
        }
        
        $this->entity = RexFactory::entity($this->mod);
        $this->entity->getByFields(array('id' => $staticpage_id));
        
        $this->assertEquals($this->entity->active, $value, 'Check sratic page active status');
    }
    
    function testDefaultAdmin()
    {
        $get = $this->generateGet($this->mod);
        
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertNotRegExp('#user[submitlogin]#', $result[0], 'Check is not login page');
    }
}