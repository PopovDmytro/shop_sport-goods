<?php
namespace RexShop;

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
class HomeController extends \RexFramework\HomeController
{
    public static $version = 1.0;
    public static $assemble = 'shop.standart';
    public static $needed = array(
        'RexFramework\HomeController:standart:1.0'
    );
    
    function getComplaint()
    {
        $_REQUEST['task'] = 'complaint';
        RexRunner::runController('staticPage', 'default');
        
        $complaint = Request::get('contact', false);
        if ($complaint and isset($complaint['submit'])) {
            
            $captchaCode = XSession::get('xcaptcha');
            
            if (trim(strlen($complaint['name'])) < 3) {
                RexPage::addError(RexLang::get('complaint.error.invalid_name'), $this->mod);
            }
            
            if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $complaint['email'])) {
                RexPage::addError(RexLang::get('complaint.error.invalid_email'), $this->mod);
            }
            
            if (trim(strlen($complaint['text'])) < 5) {
                RexPage::addError(RexLang::get('complaint.error.invalid_text'), $this->mod);
            }
            
            if (empty($complaint['code']) || empty($captchaCode) || strtolower($complaint['code']) != strtolower($captchaCode)) {
                RexPage::addError(RexLang::get('complaint.error.invalid_captcha'), $this->mod);
            }
            
            if (RexPage::isError($this->mod)) {
                RexDisplay::assign('contact', $complaint);
                return false;
            }         
            
            /*$headers = "From: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();*/

            $message = sprintf(RexLang::get('complaint.email.message'), RexConfig::get('Project', 'sysname'), htmlspecialchars($complaint['name']), htmlspecialchars($complaint['email']), htmlspecialchars($complaint['text']));
            $message = str_replace(array('\n', '\r'), array("\n", "\r"), $message);
            
            /*mail(RexSettings::get('contact_email'),
                '=?UTF-8?B?'.base64_encode(sprintf(RexLang::get('complaint.email.subject'), RexConfig::get('Project', 'sysname'))).'?=',
                $message,
                $headers
                );*/

            \Mailer::send(RexSettings::get('contact_email'), '=?UTF-8?B?' . base64_encode(sprintf(RexLang::get('complaint.email.subject'), RexConfig::get('Project', 'sysname'))) . '?=', $message);

            RexPage::addMessage(RexLang::get('complaint.congratulation'), $this->mod);
            
        }
        
        RexRunner::runController('staticPage', 'list');
    }
}