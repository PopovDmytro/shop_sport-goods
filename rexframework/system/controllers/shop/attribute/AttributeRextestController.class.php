<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexConfig as RexConfig;
use \RexDBList as RexDBList;

/**
 * Class AttributeRextestController
 *
 * Attribute Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  AttributeRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class AttributeRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\AttributeEntity:shop.standart:1.0',
        'RexShop\AttributeManager:shop.standart:1.0',
    );
    
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
        
        $list = new RexDBList('attribute');
        $list->setOrderBy('`gorder` ASC');
        
        foreach ($list as $key => $value) {
            $name = str_replace('(', '\(', $value->name);
            $name = str_replace(')', '\)', $name);
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#'.$name.'#', $result_decode->content->template, 'Check parent attribute '.$value->name.' in the select');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
        }
        
        $typeList = RexConfig::get('Project', 'attributeTypeList');
        
        foreach ($typeList as $key => $value) {
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#'.$value['name'].'#', $result_decode->content->template, 'Check attribute type'.$value['name'].' in the select');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
            
            $type_id = $key;
        }
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `attribute`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[pid]' => 0,
            'entity[type_id]' => $type_id,
            'entity[name]' => 're',
            'entity[alias]' => '',
            'entity[content]' => 'rextest_content',
            'entity[filtered]' => 1,
            'entity[filtered_form]' => 1,
            'entity[active]' => 1,
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
        
        $typeId = XDatabase::getOne('SELECT '.$this->entity->__uid.' FROM `attribute` WHERE `type_id` = 1 ORDER BY '.$this->entity->__uid.' DESC LIMIT 1');
        
        if ($typeId) {
            $post['entity[pid]'] = $typeId;
            $post['entity[type_id]'] = 1;
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->error)) {
                $this->assertEquals($result_decode->error, 'Parent And Children attribute can not be "Group"', 'Check error type  id 1');
            } else {
                $this->assertEquals('error', 'ok', 'Check error type  id 1');
            }
            
            $post['entity[type_id]'] = 8;
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->error)) {
                $this->assertEquals($result_decode->error, 'Element of List will be attached to list only', 'Check error type  id 8');
            } else {
                $this->assertEquals('error', 'ok', 'Check error type  id 8');
            }
        }
        
        $typeId = XDatabase::getOne('SELECT '.$this->entity->__uid.' FROM `attribute` WHERE `type_id` IN (6, 7, 9) ORDER BY '.$this->entity->__uid.' DESC LIMIT 1');
        
        if ($typeId) {
            $post['entity[pid]'] = $typeId;
            $post['entity[type_id]'] = 1;
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->error)) {
                $this->assertEquals($result_decode->error, 'In list can be attached only list element', 'Check error type  id 6, 7, 9');
            } else {
                $this->assertEquals('error', 'ok', 'Check error type  id 6, 7, 9');
            }
        }
        
        $typeId = XDatabase::getOne('SELECT '.$this->entity->__uid.' FROM `attribute` WHERE `type_id` NOT IN (1, 6, 7, 9) ORDER BY '.$this->entity->__uid.' DESC LIMIT 1');
        
        if ($typeId) {
            $post['entity[pid]'] = $typeId;
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->error)) {
                $this->assertEquals($result_decode->error, 'Parent Element can not have childrens', 'Check error type  id not in 1, 6, 7, 9');
            } else {
                $this->assertEquals('error', 'ok', 'Check error type  id not in 1, 6, 7, 9');
            }
        }
        
        $typeId = XDatabase::getOne('SELECT '.$this->entity->__uid.' FROM `attribute` WHERE `level` > 1 ORDER BY '.$this->entity->__uid.' DESC LIMIT 1');
        
        if ($typeId) {
            $post['entity[pid]'] = $typeId;
            $post['entity[type_id]'] = 2;
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->error)) {
                $this->assertEquals($result_decode->error, 'Only 3 levels allowed', 'Check error level > 1');
            } else {
                $this->assertEquals('error', 'ok', 'Check error level > 1');
            }
        }
        
        $post['entity[pid]'] = 0;
        $post['entity[type_id]'] = $type_id;
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `attribute`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'Attribute does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to create the attribute record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check attribute name')) {
                $attribute_id = $entityNew->id;
                
                $this->_testEditAdmin($attribute_id);
                                
                $this->_testDeleteAdmin($attribute_id);
            }
        }
    }
    
    private function _testEditAdmin($attribute_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $attribute_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $attribute = RexFactory::entity($this->mod);
            $attribute->getByFields(array('id' => $attribute_id));
            
            $this->assertRegExp('#'.$attribute->name.'#', $result_decode->content->template, 'Check attribute name in the edit page');
            $this->assertRegExp('#'.$attribute->alias.'#', $result_decode->content->template, 'Check attribute alias in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $attribute_id,
            'entity[submit]' => true,
            'entity[pid]' => 0,
            'entity[name]' => 'rextest_name_second',
            'entity[alias]' => 'rextest_alias_second',
            'entity[filtered]' => 0,
            'entity[filtered_form]' => 1,
            'entity[active]' => 1,
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
        $entity->getByFields(array('id' => $attribute_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->filtered, 0, 'Verify filtered status');
        
        return true;
    }
    
    private function _testDeleteAdmin($attribute_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $attribute_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $attribute_id));
            $entity->delete();
        }
        
        return true;
    }
}