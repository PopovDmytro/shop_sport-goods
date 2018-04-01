<?php
namespace RexFramework;

use \RexRunner as RexRunner;
use \RexFactory as RexFactory;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \XDatabase as XDatabase;
use \XSession as XSession;
use \Request as Request;
use \RexRoute as RexRoute;
use \XImage as XImage;
use \XFile as XFile;
use \XEmail as XEmail;
use \RexLang as RexLang;
use \PHPSender as PHPSender;

/**
 * Class UserController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  UserController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class UserController extends ParentController
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\UserEntity:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
    );
    
    var $fetched;
    
    function getDefault()
    {
        $id = Request::get('id', false);
        if (!$id or $id < 1) {
            //404
            exit;
        }
        
        $userEntity = RexFactory::entity('user');
        if (!$userEntity->get(intval($id))) {
            //404
            exit;
        }
        RexDisplay::assign('user', $userEntity);
    }
    
    function getLogin()
    {
        if (isset($_COOKIE['user_login'])) {
            RexDisplay::assign('confirm_sms', true);    
        }
        RexPage::setTitle('Авторизация пользователя');
    }
    
    function getLogout()
    {
        XSession::destroy();
        setcookie('rf_user', '', time() - 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        
        header('location: '.RexConfig::get('Environment', RexRunner::getEnvironment(), 'link'));
        exit;
    }
    
    private function _reg_validate($arr)
    {
        $captchaCode = XSession::get('xcaptcha');

        if (!$arr['code'] || !$captchaCode || strtolower($arr['code']) != strtolower($captchaCode)) {
            return RexLang::get('user.error.invalid_captcha');          
        }
       
        if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $arr['email']))
            return RexLang::get('user.error.invalid_email'); 
            
        $arr['phone'] = (int)($arr['phone']);    
        if(!$arr['phone'] || mb_strlen($arr['phone'], 'UTF-8') != 12 ) {
            return RexLang::get('user.error.incorrect_phone'); 
        }
        
        
        if(!$arr['login'])
            return RexLang::get('user.error.enter_login');
        
        
        $this->entity->getByWhere('email LIKE "'.$arr['email'].'"');
        if ($this->entity->id && $this->entity->id > 0) {
            return RexLang::get('user.error.email_already_exist');
        }
        
        $this->entity->getByWhere('login LIKE "'.$arr['login'].'"');
        if ($this->entity->id && $this->entity->id > 0) {
            return RexLang::get('user.error.login_already_exist');
        }
        
        if (!$arr['clear_password'])
            return RexLang::get('user.error.enter_password');
        
        
        if (strlen($arr['clear_password']) < 5)
            return RexLang::get('user.error.incorrect_password');
        
        
        if ($arr['clear_password'] != $arr['passconfirm'])
            return RexLang::get('user.error.passwords_do_not_match');
            
        return true;
    }
    
    function getRegistration()
    {
        $arr = Request::get('registration', false);
        $phone = Request::get('phone', false);
        $allDone = false;
        
        $manager = RexFactory::manager($this->mod);
        $entity = RexFactory::entity($this->mod);
        $regArray = $manager->getData();
        
        if ($phone && isset($phone['cancel'])) {
            $entity->get($regArray['user_id']);
            $entity->delete();
            $manager->deleteData();
            unset($regArray);    
        }
        
        RexPage::setTitle(RexLang::get('user.registration'));
        
        RexDisplay::assign('registration', $arr);
        
        if ($arr && isset($arr['submit']) && !isset($regArray) && !isset($regArray['step_registration'])) {
            unset($arr['submit']);
            
            $validate = $this->_reg_validate($arr);
            if ($validate !== true)
                RexPage::addError($validate, $this->mod);
            
            if (RexPage::isError($this->mod)) {
                return false;
            }
            
            unset($arr['passconfirm']);
            
            $arr['password'] = md5($arr['clear_password']);
            
            if (isset($arr['phone']) && RexSettings::get('phpsender_registraton') == 'true') {
                $arr['phone'] = PHPSender::validateNumber($arr['phone']);
                
                if (!$arr['phone']) {
                    RexPage::addError(RexLang::get('user.error.incorrect_phone'), $this->mod);
                    return false;    
                }
            }
            
            $entity->set($arr);
            if (!$entity->create()) {
                RexPage::addError(RexLang::get('user.error.create'), $this->mod);
            }

            if (isset($arr['phone']) && RexSettings::get('phpsender_registraton') == 'true') {
                $result = PHPSender::sendValidationCode($arr['phone'], 6);
                $entity->phone_validation = $result->code;
                $entity->active = 0;
                $entity->update();
                
                $regArray['user_id'] = $entity->id;
                $regArray['step_registration'] = 1;
                $manager->setData($regArray);
                
                RexDisplay::assign('confirm_sms', true);
                return true;
            }
                
            if (RexPage::isError($this->mod)) {
                return false;
            }
            
            $allDone = true;
        }
        
        if (isset($regArray['step_registration']) && $regArray['step_registration'] == 1) {
            $entity->get($regArray['user_id']);
            
            if ($phone && $phone['code'] == $entity->phone_validation) {
                $entity->phone_validation = 1;
                $entity->active = 1;
                $entity->update();
                
                $manager->deleteData();
                
                $allDone = true;    
            } else {
                RexDisplay::assign('confirm_sms', true);
                return true;        
            }
        }
        
        if ($allDone) {                        
            RexPage::addMessage(RexLang::get('user.registration_congratulation'), $this->mod);
              
            RexDisplay::assign('sysname', RexConfig::get('Project', 'sysname'));
            $entity->password = $entity->clear_password;
            RexDisplay::assign('pismomes', $entity);
            
            $html = RexDisplay::fetch('mail/pismo.reg.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, $entity->email,sprintf(RexLang::get('user.email.registration_subject'), addslashes($entity->login), RexConfig::get('Project', 'sysname')));
                            
            //RexDisplay::assign('location_url', RexRoute::getUrl(array('mod' => 'home')));
            RexDisplay::assign('okprocess', true);
        }
    }
    
    /**
     * getConfirmation
     *
     * @author  Fatal
     * @class   UserController
     * @access  public
     */
    function getConfirmation()
    {
        if ($this->entity->confirmation($this->task)) {
            RexPage::addMessage('Ваш акаунт активирован! Добро пожаловать в '.RexConfig::get('Project', 'name').'! <a href="/login/">Войти в систему</a>', $this->mod);
        } else {
            RexPage::addError('Ошибка активации пользовательского акаунта', $this->mod);
        }
    }
    
    /**
     * getForgot
     *
     * @author  Fatal
     * @class   UserController
     * @access  public
     */
    function getForgot()
    {
        $arr = Request::get('forgot', false);
        
        RexPage::setTitle(RexLang::get('user.forgot'));
        
        RexDisplay::assign('forgot', $arr);
        
        if ($arr && isset($arr['submit'])) {
            unset($arr['submit']);
            
            if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $arr['email'])) {
                RexPage::addError(RexLang::get('user.error.invalid_email'), $this->mod);
            }

            if (XSession::get('xcaptcha')) {                
                $captchaCode = XSession::get('xcaptcha');
            }
            
            if (!$arr['code'] || !$captchaCode || strtolower($arr['code']) != strtolower($captchaCode)) {
                RexPage::addError(RexLang::get('user.error.invalid_captcha'), $this->mod);
            }   
            
            if (RexPage::isError($this->mod)) {
                return false;
            }
            
            $this->entity = RexFactory::entity($this->mod);
            
            if (!$this->entity->getByFields(array('email'=>$arr['email']))) {
                RexPage::addError(Rexlang::get('user.error.try_again'), $this->mod);
            }
            
            RexPage::addMessage(RexLang::get('user.forgot_congratulation'), $this->mod);
            RexDisplay::assign('entity', false);

            /*$headers = "From: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion()."\r\n";*/
            
            $message = sprintf(RexLang::get('user.email.forgot_message'), $this->entity->email, $this->entity->clear_password);
            $message = str_replace(array('\n', '\r'), array("\n", "\r"), $message);
            
            /*mail($this->entity->email,
                '=?UTF-8?B?'.base64_encode(sprintf(RexLang::get('user.email.forgot_subject'), addslashes($this->entity->login), RexConfig::get('Project', 'sysname'))).'?=',
                $message,
                $headers
                );*/

            \Mailer::send($this->entity->email, '=?UTF-8?B?' . base64_encode(sprintf(RexLang::get('user.email.forgot_subject'), addslashes($this->entity->login), RexConfig::get('Project', 'sysname'))) . '?=', $message);

            RexRoute::location(array('mod' => 'user', 'act' => 'login'));
        }
    }
    
    /**
     * getStatusWindow
     *
     * @author  Fatal
     * @class   UserController
     * @access  public
     */
    function getForumStatus()
    {
        $this->afterBuild();
        $this->fetched = RexDisplay::fetch($this->mod.'/forum_status.tpl');    
    }
    
    /**
     * getAvatar
     *
     * @author  Fatal
     * @class   UserController
     * @access  public
     */
    function getAvatar() 
    {
        $entity = RexFactory::entity('user');
        $user = XSession::get('user');
        if (!$user or intval($user->id) < 1) {
            exit;
        }

        $profile = Request::get('profile', false);
        
        if ($profile and isset($profile['submit'])) {
            unset($profile['submit']);
            $entity->get($user->id);
            
            $urlProfile = $_SERVER['SERVER_NAME'].RexRoute::getUrl(array('mod' => 'user', 'act' => 'avatar'));
            $name = 'avatar';
            
            $images = $this->_createImages($entity, $name);
            if ($images !== true)
                RexPage::addError($images);
            
            
            XSession::set('user', $entity);
            header('location: http://'.$urlProfile);
            exit;
        }
    }
    
    function getMain() 
    {
        $userEntity = RexFactory::entity('user');
        $user = XSession::get('user');
        RexDisplay::assign('who_i', $user);
        $urlProfile = $_SERVER['SERVER_NAME'].RexRoute::getUrl(array('mod' => 'user', 'act' => 'default', 'id' => $user->id));
        
        if (!$user or intval($user->id) < 1) {
            exit;
        }
        
        $arruser = Request::get('profile', array());
        RexDisplay::assign('user', $arruser);

        if (isset($arruser['submit'])) {
            foreach($arruser as $key => $value) {
                $arruser[$key] = trim(strip_tags($value));
            }
            
            $userEntity->get($user->id);
            $arruser['phone'] = (integer)$arruser['phone'];
            $userEntity->set($arruser);
            
            if ($userEntity->update()) {
                header('location: http://'.$urlProfile);
                exit;
            }
            
            RexPage::addError(RexLang::get('user.error.update'));
        }

        $userEntity->get($user->id);
        RexDisplay::assign('userentity', $userEntity);
        //\sys::dump($user->id);exit;
    }
    
    function getPassword() 
    {
        $entity = RexFactory::entity('user');
        $user = XSession::get('user');
        
        if (!$user or intval($user->id) < 1) {
            exit;
        }
        
        $arr = Request::get('profile', array());
        RexDisplay::assign('user', $arr);

        if (isset($arr['submit'])) {
            $entity->get($user->id);
            
            if ($entity->password != md5($arr['curr_password'])) {
                RexPage::addError(RexLang::get('user.error.incorrect_current_password'), $this->mod);
            }

            if (strlen(trim($arr['password'])) < 5) {
                RexPage::addError(RexLang::get('user.error.incorrect_password'), $this->mod);
            }
            
            if ($arr['password'] !== $arr['passconfirm']) {
                RexPage::addError(RexLang::get('user.error.passwords_do_not_match'), $this->mod);
            }
            
            if (!RexPage::isError($this->mod)) {
                $entity->clear_password = $arr['password'];
                $entity->password = md5($arr['password']);
                $entity->update();
                $this->getLogout();
            }
        }

        $arrUser = $user->getArray();
        $arr = array_merge($arrUser, $arr);
        RexDisplay::assign('userentity', $arr);
    }
    
    function _avatar($aParams)
    {        
        if ($aParams['avatar'] == 'default') {
            $url = 'http://'.MEDIA_DOMAIN.'/avatar/'.$aParams['size'].'_x_default.jpg';
        } else {
            if (file_exists(XIMAGE_AVATAR_ROOT.$aParams['size'].'_x_'.$aParams['user'].'.'.$aParams['avatar'])) {
                $url = 'http://'.MEDIA_DOMAIN.'/avatar/'.$aParams['size'].'_x_'.$aParams['user'].'.'.$aParams['avatar'];
            } else {
                //пережим!!!
                $url = 'http://'.MEDIA_DOMAIN.'/content/images/user-icon-'.$aParams['size'].'.jpg';
            }
        }

        if ($aParams['saveto'] == 'display') {
            echo $url;
        } else {
            RexDisplay::assign($aParams['saveto'], $url);
        }
    }
    
    function _latest($aParams)
    {        
        $userManager = RexFactory::manager('user');
        $userManager->getLatest($aParams['count']);
        RexDisplay::assign($aParams['saveto'], $userManager->getCollection());
    }
}