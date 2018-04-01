<?php
namespace RexFramework;

use \RexRunner as RexRunner;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \Request as Request;
use \RexSettings as RexSettings;
use \RexConfig as RexConfig;
use \XCaptcha as XCaptcha;
use \XSession as XSession;
use \DomDocument as DomDocument;
use \RexDBList as RexDBList;
use \RexLang as RexLang;

/**
 * Class HomeController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class HomeController extends ParentController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    public static $needed = array(
        'RexFramework\ParentController:standart:1.0',
        'RexFramework\UserManager:standart:1.0'
    );
    
    function getDefault()
    {
        $_REQUEST['task'] = 'home';
        RexRunner::runController('staticPage', 'default');
        RexPage::setTitle(RexSettings::get('site_slogan'));
    }
    
    function getCaptcha()
    {
        XCaptcha::GetCaptchaFormulaImage();
        exit;
    }
    
    function getLang()
    {
        $request = Request::get('request', false);
        RexLang::setLang($this->task);

        if (RexConfig::get('Project', 'lang', 'subdomain')) {
            if (RexConfig::get('Project', 'lang', 'default') == RexLang::getLang()) {
                header('location: http://'.RexConfig::get('Project', 'domain'));
            } else {
                header('location: http://'.RexLang::getLang().'.'.RexConfig::get('Project', 'domain'));
            }
        } elseif ($request) {
            header('location: '.$request);
        } else {
            header('location: http://'.RexConfig::get('Project', 'domain'));
        }
        exit;
    }
    
    function getContact()
    {
        $_REQUEST['task'] = 'contact';
        RexRunner::runController('staticPage', 'default');
        
        $contact = Request::get('contact', false);
        if ($contact and isset($contact['submit'])) {
            $captchaCode = XSession::get('xcaptcha');

            if (trim(strlen($contact['name'])) < 3) {
                RexPage::addError(RexLang::get('contact.error.invalid_name'), $this->mod);
            }
            
            if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $contact['email'])) {
                RexPage::addError(RexLang::get('contact.error.invalid_email'), $this->mod);
            }
            
            if (trim(strlen($contact['text'])) < 5) {
                RexPage::addError(RexLang::get('contact.error.invalid_text'), $this->mod);
            }
            
            if (empty($contact['code']) || empty($captchaCode) || strtolower($contact['code']) != strtolower($captchaCode)) {
                RexPage::addError(RexLang::get('contact.error.invalid_captcha'), $this->mod);
            }
            
            if (RexPage::isError($this->mod)) {
                RexDisplay::assign('contact', $contact);
                return false;
            }
            
            $message = sprintf(RexLang::get('contact.email.message'), RexConfig::get('Project', 'sysname'), htmlspecialchars($contact['name']), htmlspecialchars($contact['email']), htmlspecialchars($contact['text']));
            $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);
            
            RexDisplay::assign('pismomes', $message);
            
            $html = RexDisplay::fetch('mail/pismo.cont.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, RexSettings::get('contact_email'), sprintf(RexLang::get('contact.email.subject'), RexConfig::get('Project', 'sysname')));
               
            RexPage::addMessage(RexLang::get('contact.congratulation'), $this->mod);
            
        }
        RexPage::setTitle('Kонтакты');
        
        RexRunner::runController('staticPage', 'list');
    }
    
    function  get404()
    {
        RexPage::setTitle(RexLang::get('404.title'));
        header('HTTP/1.0 404 Not Found');
    }
}