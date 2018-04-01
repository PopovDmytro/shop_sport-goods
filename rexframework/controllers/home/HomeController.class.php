<?php
class HomeController extends \RexShop\HomeController
{
    public static $version = 1.0;
    public static $assemble = 'volley.standart';
    public static $needed = array(
        'RexFramework\HomeController:standart:1.0',
        'RexFramework\StaticPageEntity:standart:1.0',
        'RexShop\HomeController:shop.standart:1.0',
        'RexShop\CommentEntity:shop.standart:1.0',
        'RexShop\CommentManager:shop.standart:1.0',
        'CommentManager:volley.standart:1.0',
        'RexShop\CommentController:shop.standart:1.0',
        'CommentController:volley.standart:1.0',
    );

    function getDefault()
    {
        parent::getDefault();
            RexDisplay::assign('content', XDatabase::getOne('SELECT `content` FROM staticpage WHERE `alias` = "home"'));
            RexPage::setTitle('VolleyMAG – интернет-магазин профессиональной спортивной экипировки. Спортивная одежда и обувь в Украине');
            RexPage::setDescription('Мужская и женская спортивная одежда в Харькове - спортивные товары для занятий футболом, волейболом и другими видами спорта. Купить кроссовки в интернет-магазине Волеймаг с доставкой по Украине');
            RexPage::setKeywords('Интернет-магазин спортивной обуви, женская спортивная одежда Харьков, спортивные товары, купить кроссовки в интернет-магазине');
    }

    function getBels()
    {
        RexResponse::init();
        $bels = Request::get('bels', false);
        if ($bels and isset($bels['submit'])) {
            RexDisplay::assign('bels', $bels);
            RexDisplay::assign('sysname', RexSettings::get('site_name'));
            
            $html = RexDisplay::fetch('mail/pismo.bels.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, RexSettings::get('contact_email'), 'Обратный звонок на сайте '.RexSettings::get('site_name'));
            PHPSender::sendSms(RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'), 'Пользователь '.$bels['name'].' запросил обратный звонок на номер '.$bels['phone']);
            RexResponse::response(RexLang::get('complaint.congratulation'));
        }

        RexResponse::response('false');
    }

    function getContact()
    {   
        RexResponse::init();
        $_REQUEST['task'] = 'contact';
        RexRunner::runController('staticPage', 'default');

        $contact = Request::get('contact', false);
        if ($contact and isset($contact['submit'])) {
            
            $captchaCode = XSession::get('xcaptcha');
            
            if (trim(strlen($contact['name'])) < 3) {
                RexResponse::response(RexLang::get('contact.error.invalid_name'));
            }

            if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $contact['email'])) {
                RexResponse::response(RexLang::get('contact.error.invalid_email'));
            }

            if (trim(strlen($contact['text'])) < 5) {
                RexResponse::response(RexLang::get('contact.error.invalid_text'));
            }

            if (empty($contact['code']) || empty($captchaCode) || strtolower($contact['code']) != strtolower($captchaCode)) {
                RexResponse::response(RexLang::get('contact.error.invalid_captcha'));
            }
            
            if (RexPage::isError($this->mod)) {
                RexDisplay::assign('contact', $contact);
            } else {
                $message = sprintf(RexLang::get('contact.email.message'), RexConfig::get('Project', 'sysname'), htmlspecialchars($contact['name']), htmlspecialchars($contact['email']), htmlspecialchars($contact['text']));
                $message = str_replace(array('\n', '\r'), array("<br />", "<br />"), $message);

                RexDisplay::assign('pismomes', $message);

                $html = RexDisplay::fetch('mail/pismo.cont.tpl');
                $userManager = RexFactory::manager('user');
                //RexSettings::get('contact_only_email');
                $userManager->getMail($html, RexSettings::get('contact_only_email'), sprintf(RexLang::get('contact.email.subject'), RexConfig::get('Project', 'sysname')));

                RexPage::addMessage(RexLang::get('contact.congratulation'), $this->mod);
                RexResponse::response('ok');
            }
        }
        
        RexPage::setTitle('Контакты и график работы – интернет-магазин спортивных товаров Волеймаг');

        RexRunner::runController('staticPage', 'list');
    }

    function getAbout()
    {

        $staticEntity = RexFactory::entity('staticPage');
        $staticEntity->getByWhere('id = 2');
        $res = $staticEntity->content;
        //\sys::dump($res);exit;
        RexDisplay::assign('staticpage', $res);
        //\sys::dump($res);exit;

        $arrcomment = Request::get('about');
        RexDisplay::assign('comment', $arrcomment);

        //create entity
        $commentEntity = RexFactory::entity('comment');

        //if edit mode - get data by ID
        if (isset($arrcomment['id']) and intval($arrcomment['id']) > 0) {

            if (!$commentEntity->get($arrcomment['id'])) {
                RexPage::addError(RexLang::get('comment.error.edit_comment'), $this->mod);
            } else {
                $arr = $commentEntity->getArray();
                $arr = array_merge($arr, $arrcomment);
                RexDisplay::assign('commententity', $arr);
            }
        }

        //if form is submitted
        if (isset($arrcomment['commentsubmit']) and !RexPage::isError($this->mod)) {

            unset($arrcomment['commentsubmit']);

            //user
            $newUser     = RexFactory::entity('user');
            $user         = RexFactory::entity('user');
            $user = XSession::get('user');
            if (!$user or $user->id < 1) {
                RexPage::addError(RexLang::get('comment.error.user_error'), $this->mod);
            } elseif ($user->role != 'user' and isset($commentEntity->user_id) and $commentEntity->user_id > 0 and $newUser->get($commentEntity->user_id)) {
                $user = $newUser;
            }

            //content
            if (empty($arrcomment['content'])) {
                RexPage::addError(RexLang::get('about.error.empty_comment'), $this->mod);
            }

            if (!RexPage::isError($this->mod)) {
                //set form fields to entity
                $commentEntity->set($arrcomment);
                $commentEntity->user_id = $user->id;
                $commentEntity->product_id = 0;
                $commentEntity->type = 2;


                if (!RexPage::isError($this->mod) and !isset($arrcomment['id'])) {
                    if (!$commentEntity->create()) {
                        RexPage::addError(RexLang::get('comment.error.error_create'), $this->mod);
                    } else {
                        if ($commentEntity->status == 2) {
                            RexPage::addMessage(RexLang::get('comment.message.create_successfully'), $this->mod);
                        } else {
                            RexPage::addMessage(RexLang::get('comment.message.add_to_moderator'), $this->mod);
                        }
                    }
                }

                if (!RexPage::isError($this->mod) and !$commentEntity->update()) {
                    RexPage::addError(RexLang::get('comment.message.add_to_moderator'), $this->mod);
                }
            }

            $arr = $commentEntity->getArray();
            $arr = array_merge($arr, $arrcomment);
            RexDisplay::assign('commententity', $arr);

            $user->getByWhere('id ='.$commentEntity->user_id);
            RexDisplay::assign('comment_user', $user->login);

            //$to = "info@volleymag.com.ua";
            $to = RexSettings::get('contact_email');
            $subject = "Новый отзыв о сайте www.volleymag.com.ua";
            $html = RexDisplay::fetch('mail/pismo.about.tpl');

            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, $to, $subject);

        }

        $commentsManager = RexFactory::manager('comment');
        $comments = $commentsManager->getAbout();

        RexDisplay::assign('comments', $commentsManager->getAbout());
        //\sys::dump($comments);exit;
        RexPage::setTitle('Информация об интернет-магазине спортивных товаров Волеймаг');
    }
}