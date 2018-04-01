<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class CommentRextestController
 *
 * Comment Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  CommentRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class CommentRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexShop\CommentEntity:shop.standart:1.0',
        'RexShop\CommentManager:shop.standart:1.0'
    );
    
    function testAdd()
    {
        $entity = RexFactory::entity('product');
        $maxId = XDatabase::getOne('SELECT MAX(`'.$entity->__uid.'`) FROM `product`');
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'task[product_id]' => $maxId,
            'comment[commentsubmit]' => true,
            'comment[content]' => ''
        );
        $post = $this->generatePost('frontend', $post);
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#Комментарий не может быть пустым#', $result[0], 'Check in error where comment adding with empty text');
        
        $post['comment[content]'] = 'textest comment add';
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertRegExp('#Комментарий успешно добавлен#', $result[0], 'Check message afte add comment');
        
        $user = $this->getTestUser();
        
        $entity = RexFactory::entity('comment');
        $entity->getByFields(array('user_id' => $user->id, 'content' => 'textest comment add', 'status' => 2, 'product_id' => $maxId));
        
        $this->assertTrue($entity->id, 'Check comment in database');
        $this->assertTrue($entity->delete(), 'Check delete comment');
    }
    
    //admin
    function testDefaultAdmin()
    {
        $this->entity = RexFactory::entity($this->mod);
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#class=\"searchexec\"#', $result[0], 'Check add button in the content');
    }
    
    function testEditDeleteAdmin()
    {
        $user = $this->getTestUser();
        $entity = RexFactory::entity($this->mod);
        $entity->user_id = $user->id;
        $entity->content = 'rextest comment text';
        $entity->product_id = 9999;
        $entity->status = 1;
        
        if ($this->assertTrue($entity->create(), 'Check create order record')) {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $entity->id));
            
            if ($this->assertEquals($entity->user_id, $user->id, 'Check user id')) {
                $comment_id = $entity->id;
                
                $this->_testEditAdmin($comment_id);
                             
                $this->_testStatus($comment_id);
                                
                $this->_testDeleteAdmin($comment_id);
            }
        }
    }
    
    private function _testEditAdmin($comment_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $comment_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $comment = RexFactory::entity($this->mod);
            $comment->getByFields(array('id' => $comment_id));
            
            $this->assertRegExp('#'.$comment->content.'#', $result_decode->content->template, 'Check comment text in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $comment_id,
            'entity[submit]' => true,
            'entity[status]' => 0,
            'entity[content]' => 'rextest comment text update'
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }

        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $comment_id));
        
        $this->assertEquals($entity->status, 0, 'Verify that status');
        $this->assertEquals($entity->content, 'rextest comment text update', 'Verify that comment text');
        
        return true;
    }
    
    private function _testStatus($comment_id)
    {
        $get = $this->generateGet($this->mod, 'status', 1);
        $post = array(
            'id' => $comment_id
        );
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
        }
        
        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $comment_id));
        
        $this->assertEquals($entity->status, 1, 'Verify that status');
        
        return true;
    }
    
    private function _testDeleteAdmin($comment_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $comment_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $comment_id));
            $entity->delete();
        }
        
        return true;
    }
}