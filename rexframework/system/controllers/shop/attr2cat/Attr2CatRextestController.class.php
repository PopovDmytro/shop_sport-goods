<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class Attr2CatRextestController
 *
 * Attr2Cat Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  Attr2CatRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class Attr2CatRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\Attr2CatEntity:shop.standart:1.0',
        'RexShop\Attr2CatManager:shop.standart:1.0',
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
    
    function testAddDeleteAdmin()
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `pcatalog`');
        
        $post = array(
            'category_id' => $maxId
        );
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $this->manager = RexFactory::manager($this->mod);
        $this->manager->getNotAssigned($maxId);
        $no_assigned = $this->manager->getCollection();
        
        $attributes = array();
        if ($no_assigned) {
            foreach ($no_assigned as $key => $value) {
                if (isset($result_decode->content->template)) {
                    $this->assertRegExp('#'.$value['name'].'#', $result_decode->content->template, 'Check attribute '.$value['name'].' in the select');
                    $this->assertRegExp('#theid=\"'.$value['id'].'\"#', $result_decode->content->template, 'Check attribute id '.$value['name'].' in the select');
                } else {
                    $this->assertEquals('error', 'ok', 'Check result by display the add page');
                }
                
                $attributes['attributes['.$value['id'].']'] = $value['id'];
            }
        }
        
        $count = XDatabase::getOne('SELECT COUNT(*) FROM `attr2cat` WHERE `category_id` = ?', array($maxId));
            
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[category_id]' => $maxId,
            'category_id' => $maxId
        );
        $post = array_merge($post, $attributes);
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $countNew = XDatabase::getOne('SELECT COUNT(*) FROM `attr2cat` WHERE `category_id` = ?', array($maxId));
        
        if ($this->assertMore($countNew, $count, 'Check the count of attributes')) {
            $id = XDatabase::getOne('SELECT `id` FROM `attr2cat` WHERE `category_id` = ? ORDER BY `id` DESC LIMIT 1', array($maxId));
            
            $this->_testDeleteAdmin($id);
        }
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