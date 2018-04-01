<?php
namespace RexFramework;

use \RexDisplay as RexDisplay;
use \RexFactory as RexFactory;
use \RexSettings as RexSettings;

/**
 * Class HomeRextestController
 *
 * Home Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class HomeRextestController extends ParentRextestController
{   
    public static $version = 1.0;
    public static $assemble = 'standart';
    
    function getDefault()
    {
        RexDisplay::assign('configurator_id', '1.0');
        RexDisplay::assign('configurator_version', '1.0');
        RexDisplay::assign('configurator_name', 'RexFramework');
        RexDisplay::assign('configurator_description', 'Базовая конфигурация');
    }
    
    function testContact()
    {
        $get = $this->generateGet($this->mod, 'contact');
        $post = array(
            'user[submitlogin]' => true,
            'user[email]' => 'user',
            'user[password]' => 'password'
        );
        
        $this->getDataFrontend($get, $post);
        
        $get = $this->generateGet($this->mod, 'captcha');
        
        $result = $this->getDataFrontend($get, $post);
        
        $session = $this->getSession();
        
        if ($this->assertTrue($session['xcaptcha'], 'Check isset captcha code')) {
            $get = $this->generateGet($this->mod, 'contact');
            
            $post_m = array(
                'contact[submit]' => true,
                'contact[name]' => 'test_name',
                'contact[email]' => 'test@email.com',
                'contact[text]' => 'rextest text for send to email',
                'contact[code]' => $session['xcaptcha']
            );
            
            $post = array_merge($post, $post_m);
                     
            $message = 'Спасибо, Ваше сообщение успешно отправлено';
            $this->_resultContact($get, $post, $message);                     
            
            $post['contact[name]'] = 'e';
            $post['contact[email]'] = 'testemail.com';
            
            $message = 'Возникла ошибка. Некорректно заполнены данные. Проверьте длину введенных полей';
            $this->_resultContact($get, $post, $message);
            
            $post['contact[name]'] = 'test_name';
            $post['contact[email]'] = 'testemail.com';
            
            $this->_resultContact($get, $post, $message);
            
            $post['contact[email]'] = 'test@email.com';
            $post['contact[code]'] = $session['xcaptcha']+100;
            
            $message = 'Возникла ошибка. Некорректный Код верификации';
            $this->_resultContact($get, $post, $message);
        }
        
    }
    
    private function _resultContact($get, $post, $message)
    {
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');

        $this->assertRegExp('#'.$message.'#', $result[0], 'Check for error by incorrect name');
    }
    
    function testDefault()
    {
        $get = $this->generateGet($this->mod);
        $post = array(
            'user[submitlogin]' => true,
            'user[email]' => 'user',
            'user[password]' => 'password'
        );
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity = RexFactory::entity('staticPage');
        $this->entity->getByFields(array('alias' => 'home'));
        
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
}