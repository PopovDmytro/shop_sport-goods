<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexRunner as RexRunner;
use \RexConfig as RexConfig;
use \XSession as XSession;
use \Request as Request;
use \RexLang as RexLang;
use \RexResponse as RexResponse;
use \RexDisplay as RexDisplay;
use \PHPSender as PHPSender;

/**
 * Class UserAdminController
 *
 * User Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  UserAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class UserAdminController extends ParentAdminController
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexFramework\UserEntity:standart:1.0',
        'RexFramework\UserManager:standart:1.0'
    );
    
    protected $add_dialog_width = 320;
    protected $add_dialog_height = 254;
    protected $edit_dialog_width = 320;
    protected $edit_dialog_height = 254;

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'login' => 'Login',
            'email' => 'E-mail',
            'name' => 'Имя',
            array('<div class="ui-state-default"><a href="javascript: void(0);">Статус</a></div>', array($this, '_DGStatus'), array('width' => 90)),
            array('Отправить SMS', array($this, '_DGSmsSend'), array('width' => 50)),
            array('<b>Действие</b>', array($this, '_DGActions'), array('width' => 90))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    function _DGStatus($param)
    {
        if ($param['record']->active == 1) {
            $field = 'active';
        } else {
            $field = 'not active';
        }
        
        return $field;
    }
    
    function _DGSmsSend($param)
    {
        return '<a href="javascript:void(0);" class="send-sms-link" user_id="'.$param['record']['id'].'">написать</a>';
    }
    
    protected function _getActionParams($param)
    {
        $arr = parent::_getActionParams($param);
        
        $class = 'itemeactive';
        $title = 'default.active';
        $img = 'ui-icon-circle-check';
        if ($param['record']->active) {
            $class = 'itemdeactive';
            $title = 'default.deactive';
            $img = 'ui-icon-cancel';
        }
        $arr[] = array(
            'title' => RexLang::get($title),
            'item_id' => $param['record'][$this->entity->__uid],
            'class' => $class,
            'allow' => 'edit',
            'img' => $img
        );
        
        return $arr;
    }
    
    function _validate(&$arr, $entity = null)
    {
        $this->entity = RexFactory::entity($this->mod);
       
        if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\.A-Za-z0-9]{2,}$/isu', $arr['email'])) {
            return 'Invalid Email format';
        }
        
        if(!$arr['login']) {
            return 'Please enter login';
        }

        if (is_null($entity)) {
            $this->entity->getByFields(array('email' => $arr['email']));
            if ($this->entity->id && $this->entity->id > 0) {
                return 'This email already exists in other user';
            }
            
            if (!$arr['clear_password']) {
                return 'Please enter password';
            }
            
            if (strlen($arr['clear_password']) < 5) {
                return 'Password must contain min 5 characters';
            }
            
            $arr['password'] = md5($arr['clear_password']);
        } else {
            if ($arr['clear_password'] && strlen($arr['clear_password']) < 5) {
                return 'Password must contain min 5 characters';
            }
            
            if ($arr['clear_password']) {
                $arr['password'] = md5($arr['clear_password']);
            } else {
                unset($arr['clear_password']);
            }
        }
        
        return true;
    }
         
    function getActive() 
    {
        RexResponse::init();
        
        $this->entity = RexFactory::entity($this->mod);
        if (!$this->task or $this->task< 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }
        
        $value = Request::get('value', 0);
        
        $this->entity->active = $value;
        
        if (!$this->entity->update()) {
            RexResponse::error('Unknown error');
        }
        
        RexResponse::response('ok');
    }
    
    protected function _deleteEntity($entity)
    {
        if ($entity->role == 'admin') {
            return 'Impossible to remove admin';
        }
        
        return parent::_deleteEntity($entity);
    }
    
    function getLogout()
    {
        XSession::destroy();
        setcookie('rf_user', '', time() - 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        
        header('location: '.RexConfig::get('Environment', RexRunner::getEnvironment(), 'link'));
        exit;
    }
    
    function getSendSms()
    {
        RexResponse::init();
        
        $text = Request::get('text', false);
        $phone = Request::get('phone', false);
        
        if (!$phone && $this->task) {
            $this->entity->get($this->task);
            $phone = $this->entity->phone;     
        }
        
        if ($text) {
            PHPSender::sendSms($phone, $text);
            RexResponse::response(true);    
        }
        
        RexDisplay::assign('phone', $phone);
        RexDisplay::assign('entity', $this->entity);
        $content = RexDisplay::fetch('user/sendSms.tpl');
        RexResponse::responseDialog($content, 393, 400);
    }
}