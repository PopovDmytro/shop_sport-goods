<?php
namespace RexFramework;

use \RexDisplay as RexDisplay;
use \Request as Request;
use \RexPage as RexPage;
use \RexResponse as RexResponse;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \RexFactory as RexFactory;
use \PHPSender as PHPSender;

/**
 * Class HomeAdminController
 *
 * Home Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class HomeAdminController extends ParentAdminController
{
    function getDefault()
    {
        RexDisplay::assign('configurator_id', '1.0');
        RexDisplay::assign('configurator_version', '1.0');
        RexDisplay::assign('configurator_name', 'RexFramework');
        RexDisplay::assign('configurator_description', 'Базовая конфигурация');
        
        if (isset($_COOKIE['user_login'])) {
            RexDisplay::assign('confirm_sms', true);    
        }
    }
    
    function getLang()
    {
        $request = Request::get('request', false);
        RexLang::setLang($this->task);

        if ($request) {
            header('location: '.$request);
            exit;
        }
        
        header('location: http://'.DOMAIN.'/');
        exit;
    }
    
    function getMailing() 
    {
        $mailing = Request::get('mailing', false);
        RexDisplay::assign('userphone', RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'));
        if ($mailing and isset($mailing['submit'])) {
            if (!$mailing['content'] || mb_strlen($mailing['content'], 'UTF-8') <= 5) {
                RexPage::addError('Текст сообщения должен быть не менее 5 символов');
                return false;
            }

            $urlToAdd = "http://".$_SERVER['SERVER_NAME'];
            RexDisplay::assign('urlToAdd', $urlToAdd);
            $patt = "/src=\"/i";
            if (preg_match($patt, $mailing['content']))
            {   
                echo $patt; 
                $repl = "src=\"".$urlToAdd;
                $mailing['content'] = preg_replace($patt, $repl, $mailing['content']);      
            }
            
                          
            RexDisplay::assign('mailing', $mailing);

            $userManager = RexFactory::manager('user');
            $userManager->get();
            $userList = $userManager->getCollection('object');
            if ($userList and sizeof($userList) > 0) {
                //foreach ($userList as $user) {
                    //mail($user->email, 
                    $mMail = 'free@illusix.com';
                    //mail($mMail, 
                     //   '=?UTF-8?B?'.base64_encode('Новости сайта '.RexConfig::get('Project', 'sysname')).'?=',
                     //   $mailing['content']
                    //);   exit;
                    $html = RexDisplay::fetch('mail/mailing.reg.tpl');
                    $userManager->getMail('sdfgsdfgsdfg', $mMail,"test asdfasdf");
             
                    RexPage::addMessage('Письмо пользователю "'.$mMail.'" успешно отправлено');
                    
                    if (isset($mailing['sms_send']) && $mailing['sms_send'] && strlen($mailing['sms_content']) > 0) {
                        PHPSender::sendSms($user->phone, $mailing['sms_content']);
                        RexPage::addMessage('SMS пользователю "'.$user->login.'" успешно отправлено');        
                    }
                    
                    sleep(1);
                //}
                
                RexPage::addMessage('Рассылка успешно произведена');
            }
        }
    }
    
    function getTestSms()
    {
        RexResponse::init();
        $text = Request::get('text', false);    
        $phone = Request::get('phone', false);
        PHPSender::sendSms($phone, $text);
        RexResponse::response('ok');    
    }
    
    function getPresents() 
    {
        RexDisplay::assign('is_multiselect', true);

        $presents = Request::get('presents', false);
        if ($presents and isset($presents['submit'])) {
            unset($presents['submit']);
            $sql = XDatabase::query('DELETE FROM presents');
            if ($presents){
                foreach ($presents as $present_key => $present) {
                    foreach ($present as $item){
                        $sql = XDatabase::query('INSERT INTO presents (type, present_id) VALUES ('.$present_key.','.$item.')');
                    }
                }
            }
        }
        
        $dbpresents = XDatabase::getAll('SELECT * FROM presents');
        $products = XDatabase::getAll('SELECT id, name FROM product WHERE product.active = "1"');
        
        $presentList = array();
        foreach ($dbpresents as $key => $item){
            $presentList[$item['type']][] = $item['present_id'];
        }
        unset($dbpresents);
        
        RexDisplay::assign('presentList', $presentList);
        RexDisplay::assign('products', $products);
    }
    
    function getSupport()
    {
        RexResponse::init();
         
        $contact = Request::get('contact', false);
        if ($contact and isset($contact['save'])) {
            if (trim(strlen($contact['content'])) < 3) {
                RexResponse::error('Возникла ошибка. Некорректно заполнены данные. Проверьте длину введенных полей');
                return false;
            }
            
            $sysName = RexConfig::get('Project', 'sysname');
            /*$sysEmailNoReplay = RexConfig::get('Project', 'email_noreply');
            $headers = "From: ".$sysName." <".$sysEmailNoReplay.">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".$sysName." <".$sysEmailNoReplay.">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
    
            mail('support@illusix.com',
                '=?UTF-8?B?'.base64_encode('Вопрос поддержке с сайта '.$sysName).'?=',
                htmlspecialchars($contact['content']),
                $headers
            );*/

            \Mailer::send('support@illusix.com', '=?UTF-8?B?' . base64_encode('Вопрос поддержке с сайта ' . $sysName) . '?=', htmlspecialchars($contact['content']));
                
            RexResponse::response('Спасибо, Ваше сообщение успешно отправлено');
        }
        
        RexResponse::responseDialog(RexDisplay::fetch('home/support.tpl'), 640, 320);
    }
    
}