<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class OrderRextestController
 *
 * Order Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  OrderRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class OrderRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\OrderEntity:shop.standart:1.0',
        'RexShop\OrderManager:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->_testAdd();
    }
    
    private function _testAdd()
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'user[submitlogin]' => true,
            'user[email]' => 'qwerty@qwerty.com',
            'user[password]' => 'kdghsdhgsdhjgsdg',
            'cart[submit]' => true,
            'order[comment]' => ''
        );
        
        $result = $this->getDataFrontend($get, $post);
        
        $message = 'Заказ не может быть пустым';
        $this->assertRegExp('#'.$message.'#', $result[0], 'Check for errors and exeptions');
        
        $entity = RexFactory::entity('product');
        $maxId = XDatabase::getOne('SELECT MAX(`'.$entity->__uid.'`) FROM `product`');
        
        if (!$maxId)
            $maxId = 0;
        
        $entity->get($maxId);
        
        $post = array(
            'user[submitlogin]' => true,
            'user[email]' => 'qwerty@qwerty.com',
            'user[password]' => 'kdghsdhgsdhjgsdg',
            'cart[submit]' => true,
            'cart[product_id]' => $entity->id,
            'cart[count]' => '2',
            'order[comment]' => ''
        );
        
        $result = $this->getDataFrontend($get, $post);
        $message = 'Ошибка в идентификаторе пользователя, если вы не авторизаваны - должны указать хотя бы телефон';
        $this->assertRegExp('#'.$message.'#', $result[0], 'Check for errors and exeptions');
    }
    
    //admin
    function testDefaultAdmin()
    {
        $this->entity = RexFactory::entity($this->mod);
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#class=\"searchexec\"#', $result[0], 'Check search button in the content');
    }
    
    function testEditDeleteAdmin()
    {
        $user = $this->getTestUser();
        $entity = RexFactory::entity($this->mod);
        $entity->status = 1;
        $entity->user_id = $user->id;
        $entity->comment = '';
        
        if ($this->assertTrue($entity->create(), 'Check create order record')) {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $entity->id));
            
            if ($this->assertEquals($entity->user_id, $user->id, 'Check user id')) {
                $order_id = $entity->id;
                
                $this->_testEditAdmin($order_id);
                                
                $this->_testDeleteAdmin($order_id);
            }
        }
    }
    
    private function _testEditAdmin($order_id)
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $order_id,
            'entity[submit]' => true,
            'entity[status]' => 2,
            'entity[comment]' => 'rextest_comment'
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }

        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $order_id));
        
        $this->assertEquals($entity->status, 2, 'Verify that status');
        $this->assertEquals($entity->comment, 'rextest_comment', 'Verify that comment');
        
        return true;
    }
    
    private function _testDeleteAdmin($order_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $order_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $order_id));
            $entity->delete();
        }
        
        return true;
    }
}