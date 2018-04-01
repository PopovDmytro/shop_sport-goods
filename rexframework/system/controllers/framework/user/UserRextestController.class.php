<?php
namespace RexFramework;

use \RexRunner as RexRunner;
use \RexConfig as RexConfig;
use \XSession as XSession;
use \RexFactory as RexFactory;
use \XDatabase as XDatabase;

/**
 * Class UserRextestController
 *
 * User Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  UserRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class UserRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\UserEntity:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
    );
    
    function getLogout()
    {
        XSession::destroy();
        setcookie('rf_user', '', time() - 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        if (RexRunner::getEnvironment() == 'rextest') {
            header('location: /rextest/');
        } else {
            header('location: /');
        }
        exit;
    }
    
    //admin
    function testLogout()
    {
        $get = $this->generateGet($this->mod, 'logout');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
    }
    
    function testActive()
    {
        $user = $this->getTestUser();
        if ($user->active == 1) {
            $value = 0;
        } else {
            $value = 1;
        }
        
        $get = $this->generateGet($this->mod, 'active', $user->id);
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
        $this->entity->getByFields(array('id' => $user->id));
        
        $this->assertEquals($this->entity->active, $value, 'Check user active status');
    }
    
    function testAddEdit()
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `user`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[login]' => 'new_user',
            'entity[email]' => 'email',
            'entity[clear_password]' => '123456',
            'entity[name]' => 'test new user',
            'entity[role]' => 'user',
            'entity[phone]' => '0507748081'
        );
        $post = $this->generatePost('admin', $post);
        
        $message = 'Invalid Email format';
        $this->_resultAddEdit($get, $post, $message); 
        
        $post['entity[email]'] = 'email@gmail.com';
        $post['entity[login]'] = '';
        
        $message = 'Please enter login';
        $this->_resultAddEdit($get, $post, $message); 
        
        $user = $this->getTestUser();
        
        $post['entity[email]'] = $user->email;
        $post['entity[login]'] = 'new_user';
        
        $message = 'This email already exists in other user';
        $this->_resultAddEdit($get, $post, $message);
        
        $post['entity[email]'] = 'email@gmail.com';
        $post['entity[clear_password]'] = '';
        
        $message = 'Please enter password';
        $this->_resultAddEdit($get, $post, $message);
        
        $post['entity[clear_password]'] = '1234';
        
        $message = 'Password must contain min 5 characters';
        $this->_resultAddEdit($get, $post, $message);
        
        $post['entity[clear_password]'] = '123456';
        
        $this->_resultAddEdit($get, $post);
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `user`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'User does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to creat the user') && $this->assertEquals($entityNew->email, 'email@gmail.com', 'Check user email')) {
                $user_id = $entityNew->id;
                
                $post = array(
                    'entity[exist_id]' => $user_id,
                    'entity[submit]' => true,
                    'entity[login]' => 'new_user_second',
                    'entity[email]' => 'email2@gmail.com',
                    'entity[clear_password]' => '123456',
                    'entity[name]' => 'test new user second',
                    'entity[role]' => 'user',
                    'entity[phone]' => '0507748081'
                );
                
                $this->_resultAddEdit($get, $post);
                
                $entity = RexFactory::entity($this->mod);
                $entity->getByFields(array('id' => $user_id));
                
                $this->assertEquals($entity->name, 'test new user second', 'Verify that names');
                $this->assertEquals($entity->email, 'email2@gmail.com', 'Verify that names');
                
                $this->assertTrue($entity->delete(), 'Check to delete user record');
            }
        }    
    }
    
    private function _resultAddEdit($get, $post, $message = false)
    {
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, $message, 'Check error by add or edit');
        } elseif ($result_decode->content) {
            $this->assertEquals($result_decode->content, 'ok', 'Check error by add or edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
    }
    
    function testDefault()
    {
        $get = $this->generateGet($this->mod, 'default');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
    }
}