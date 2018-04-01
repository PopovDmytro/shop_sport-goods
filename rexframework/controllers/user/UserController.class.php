<?php
class UserController extends \RexFramework\UserController
{
    static public $assemble = 'volley.standart';
    static public $version = 1.0;
    static public $needed = array(
        // 'RexFramework\UserEntity:standart:1.0',
        // 'RexFramework\UserManager:standart:1.0',
        'UserEntity:volley.standart:1.0',
        'UserManager:volley.standart:1.0',
        'RexFramework\ParentController:standart:1.0',
        'RexFramework\UserController:standart:1.0',
        'CityManager:volley.standart:1.0',
    );

    private $_fb_scope = FB_SCOPE;
    private $_photo_size = 450;

    public function getForgot()
    {
        $arr = Request::get('forgot', false);

        RexPage::setTitle(RexLang::get('user.forgot'));

        RexDisplay::assign('forgot', $arr);

        if ($arr && isset($arr['submit'])) {
            unset($arr['submit']);

            if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $arr['email'])) {
                RexPage::addError(RexLang::get('user.error.invalid_email'), $this->mod);
            }

            $captchaCode = false;

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
            RexDisplay::assign('entity', $this->entity);

            /*$headers = "From: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion()."\r\n"; */

            /*$message = sprintf(RexLang::get('user.email.forgot_message'), $this->entity->email, $this->entity->clear_password);
            $message = str_replace(array('\n', '\r'), array("\n", "\r"), $message);  */

            /*mail($this->entity->email,
                '=?UTF-8?B?'.base64_encode(sprintf(RexLang::get('user.email.forgot_subject'), addslashes($this->entity->login), RexConfig::get('Project', 'sysname'))).'?=',
                $message,
                $headers
                ); */

            $html = RexDisplay::fetch('mail/pismo.forgot.tpl');
            $userManager = RexFactory::manager('user');
            $userManager->getMail($html, $this->entity->email, sprintf(RexLang::get('user.email.forgot_subject'), RexSettings::get('site_name')));
            RexRoute::location(array('mod' => 'user', 'act' => 'login'));
        }
    }

    public function getAutocomplete()
    {
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $res = XDatabase::getAll('
                    SELECT
                      c.`id` AS city_id,
                      c.`name` AS city_name
                    FROM
                      city c
                    WHERE c.`name` LIKE "%'.addslashes($query).'%"
                    GROUP BY c.`id`
                    LIMIT 30');
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['city_name'].'='.$value['city_id']."\n";
            }
        }
        exit;
    }

    public function getMain()
    {
        $userEntity = RexFactory::entity('user');
        $user = XSession::get('user');
        RexDisplay::assign('who_i', $user);
        if (!$user or intval($user->id) < 1) {
            exit;
        }
        $urlProfile = $_SERVER['SERVER_NAME'].RexRoute::getUrl(array('mod' => 'user', 'act' => 'default', 'id' => $user->id));
        $urlUserMain = $_SERVER['SERVER_NAME'].RexRoute::getUrl(array('mod' => 'user', 'act' => 'main'));
        $arruser = Request::get('profile', array());
        RexDisplay::assign('user', $arruser);

        if (isset($arruser['submit'])) {
            foreach($arruser as $key => $value) {
                $arruser[$key] = trim(strip_tags($value));
            }

            $userEntity->get($user->id);

            if (preg_match('#^\+38\([0-9]+\)[0-9]{3}-[0-9]{2}-[0-9]{2}$#', $arruser['phone'])) {
                $userEntity->set($arruser);

                if ($userEntity->update()) {
                    XSession::set('user', $userEntity);
                    header('location: http://'.$urlUserMain);
                    exit;
                }
            } else {
                RexPage::addError(RexLang::get('user.error.incorrect_phone'));
            }

            RexPage::addError(RexLang::get('user.error.update'));
        }
        if (!$userEntity->get(intval($user->id))) {
            exit;
        }


        // echo "<pre>";var_dump($userEntity->getUid());exit;
        // $userEntity->get(intval($user->id));
        // $userEntity->get(27);
        RexDisplay::assign('userentity', $userEntity);

        $entityCity = RexFactory::entity('city');
        $entityCity->getByWhere("name = '".$user->city."'");

        RexDisplay::assign('usercity_id', $entityCity->id);
        RexPage::setTitle('Редактирование профиля пользователя');
    }

    function getLogin()
    {
        RexPage::setTitle('Авторизация пользователя');
        if (XSession::get('user_exist')) {
            $oemail = explode(';', XSession::get('user_exist'));
            RexDisplay::assign('oemail', $oemail[0]);
        }
        if (isset($_COOKIE['user_login'])) {
            RexDisplay::assign('confirm_sms', true);
        }
    }

    private function _reg_validate($arr)
    {
        $captchaCode = XSession::get('xcaptcha');

        if (!$arr['code'] || !$captchaCode || strtolower($arr['code']) != strtolower($captchaCode)) {
            return RexLang::get('user.error.invalid_captcha');
        }

        if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $arr['email'])) {
            return RexLang::get('user.error.invalid_email');
        }

        preg_match_all('#\d+#', $arr['phone'], $matches);

        $arr['phone'] = implode('',$matches[0]);

        if(!$arr['phone'] || mb_strlen($arr['phone'], 'UTF-8') > 17 ) {
            return RexLang::get('user.error.incorrect_phone');
        }

        /*if(!$arr['login'])
            return RexLang::get('user.error.enter_login');

        $this->entity->getByWhere('login LIKE "'.$arr['login'].'"');
        if ($this->entity->id && $this->entity->id > 0) {
            return RexLang::get('user.error.login_already_exist');
        }*/

        $this->entity->getByWhere('email LIKE "'.$arr['email'].'"');
        if ($this->entity->id) {
            return RexLang::get('user.error.email_already_exist');
        }

        if (!$arr['clear_password'])
            return RexLang::get('user.error.enter_password');


        if (strlen($arr['clear_password']) < 5)
            return RexLang::get('user.error.incorrect_password');


        if ($arr['clear_password'] != $arr['passconfirm'])
            return RexLang::get('user.error.passwords_do_not_match');

        return true;
    }

    public function getRegistration()
    {
        $arr = Request::get('registration', false);
        $phone = Request::get('phone', false);
        $allDone = false;

        $manager = RexFactory::manager($this->mod);
        $entity = RexFactory::entity('user');
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
            $arr['login'] = $arr['email'];
            $arr['is_registered'] = 1;
            $arr['delivery'] = 1;

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
                if($result->status === false){
                    RexPage::addError(RexLang::get('user.error.incorrect_phone'), $this->mod);
                    return false;
                }
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
            RexDisplay::assign('site_name', RexSettings::get('site_name'));
            $html = RexDisplay::fetch('mail/pismo.reg.tpl');
            $userManager = RexFactory::manager('user');

            $userManager->getMail($html, $entity->email,sprintf(RexLang::get('user.email.registration_subject'), RexSettings::get('site_name')));


            /*$headers = "From: ".RexSettings::get('site_name')."\r";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            $subject = sprintf(RexLang::get('user.email.registration_subject'), RexSettings::get('site_name'));

            mail($entity->email,
                '=?UTF-8?B?'.base64_encode($subject).'?=',
                $html,
                $headers
            );*/

            RexDisplay::assign('okprocess', true);
        }
    }

    public function getDefault()
    {
        $id = Request::get('id', false);
        if (!$id or $id < 1) {
            exit;
        }

        $userEntity = RexFactory::entity('user');
        if (!$userEntity->get(intval($id))) {
            exit;
        }

        RexDisplay::assign('user', $userEntity);
        RexDisplay::assign('userEntity', $userEntity);
    }

    public function getAvatar()
    {
        $entity = RexFactory::entity('user');
        $user = XSession::get('user');
        if (!$user or intval($user->id) < 1) {
            exit;
        }

        $profile = Request::get('profile', false);
        $cropped = Request::get('cropped', false);

        if ($profile and isset($profile['submit'])) {
            unset($profile['submit']);
            $entity->get($user->id);

            $urlProfile = $_SERVER['SERVER_NAME'].RexRoute::getUrl(array('mod' => 'user', 'act' => 'avatar'));

            if ($cropped) {
                $images = $this->_createImages($entity, 'avatar', $cropped);
            } else {
                $images = $this->_createImages($entity, 'avatar');
            }

            if ($images !== true)
                RexPage::addError($images);


            XSession::set('user', $entity);
            RexRoute::location(array('mod' => 'user', 'act' => 'default', 'id' => $entity->id));
        }
    }

    function getFillialsByCityId()
    {
        RexResponse::init();
        if(!isset($this->task) || $this->task == 0 || $this->task == ''){
            RexResponse::response('false');
            exit;
        }
        $entity = RexFactory::entity('user');
        $user = XSession::get('user');
        if($user) {
            $entity->get($user->id);
            // var_dump($entity); exit;
            RexDisplay::assign('user', $entity);
        }

        $template = Request::get('template', 'cart');


        $fillials = XDatabase::getAll('SELECT * FROM `fillials` WHERE `city_id` = '.intval($this->task).'');
        // var_dump($prod2Tech);
        // exit;
        RexDisplay::assign('templ', $template);
        RexDisplay::assign('fillials', $fillials);

        $response = RexDisplay::fetch('_block/fillials.inc.tpl');
        if ($response) {
            RexResponse::response($response);
        } else {
            RexResponse::response('false');
        }

    }

    public function getSocialLogin()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $provider = Request::get('provider', false);

        if ($provider) {
            switch ($provider) {
                case 'facebook':
                    $data = $this->_facebookRequest();
                    break;

                case 'vk':
                    $data = $this->_vkRequest();
                    break;
            }

            //\sys::dump($data); exit;
            if (!$data['identity'] || !$data['uid']) {
                RexRoute::location(array('mod' => 'home', 'act' => 'default'));
            }

            $data['country'] =  isset($_SERVER['GEOIP_COUNTRY_NAME']) ? $_SERVER['GEOIP_COUNTRY_NAME'] : 'N/A';
            $data['country_code'] =  isset($_SERVER['GEOIP_COUNTRY_CODE']) ? $_SERVER['GEOIP_COUNTRY_CODE'] : 'N/A';

            $loginer = RexFactory::entity('Loginer');
            $loginer->getByWhere('identity = "'.$data['identity'].'" AND provider = "'.$provider.'"');

            if ($loginer->id) {
                $user = RexFactory::entity('user');
                $user->get($loginer->user_id);

                if (!$user->id || $user->id <= 0) {
                    RexRoute::location(array('mod' => 'home', 'act' => 'default'));
                }

                XSession::set('user', $user);
                //FUserManager::setLoginer('fuser', $fuser);

                if (!$user->avatar) {
                    if (isset($data['photo']) && $data['photo']) {
                        $this->_saveAvatar($user->id, $data['photo']);
                    } elseif ($provider == 'facebook') {
                        $token = XSession::get('token', false);
                        if ($token) {
                            $link = 'https://graph.facebook.com/me/picture?type=large&access_token='.$token;
                            $this->_saveAvatar($user->id, $link, 'jpg');
                        }
                    }
                }
            } else {
                $get_photo = false;
                $user = RexFactory::entity('user');
                $user->getByWhere('email = "'.$data['email'].'"');
                $password = uniqid();
                $data['clear_password'] = $password;
                $data['password'] = md5($password);
                if ($provider == 'facebook') {
                    $data['is_registered'] = 2;
                    $data['delivery'] = 1;
                } else {
                    $data['is_registered'] = 3;
                    $data['delivery'] = 0;
                }
                $data['login'] = strstr($data['email'], '@', true);
                if (!$user->id || $user->id <= 0) {
                    $get_photo = true;

                    $user->set($data);
                    if (!$user->create()) {
                        die('Error create user');
                    }
                }

                if (!$user->avatar) {
                    $get_photo = true;
                }

                $loginer->user_id = $user->id;
                $loginer->identity = $data['identity'];
                $loginer->provider = $provider;
                $loginer->uid = $data['uid'];
                if (!$loginer->create()) {
                    die('Error create loginer');
                }

                XSession::set('user', $user);
                //FUserManager::setLoginer('fuser', $fuser);

                if ($get_photo) {
                    if (isset($data['photo']) && $data['photo']) {
                        $this->_saveAvatar($user->id, $data['photo']);
                    } elseif ($provider == 'facebook') {
                        $token = XSession::get('token', false);
                        if ($token) {
                            $link = 'https://graph.facebook.com/me/picture?type=large&access_token='.$token;
                            $this->_saveAvatar($user->id, $link, 'jpg');
                        }
                    }
                }
                RexRoute::location(array('mod' => 'home', 'act' => 'default'));
            }

            RexRoute::location(array('mod' => 'home', 'act' => 'default'));
        }

        $facebook = new Facebook(array('cookie' => true));
        //echo $this->_fb_scope; exit;
        $login_url = $facebook->getLoginUrl(array('redirect_uri' => 'http://'.RexConfig::get('Project', 'clear_domain').'/sociallogin/?provider=facebook',
            'scope' => $this->_fb_scope));
        //&scope=publish_stream

        //var_dump($login_url);exit;
        RexDisplay::assign('login_url', $login_url);

        $paramsVk = array(
            'client_id'     => RexConfig::get('Components', 'Vkontakte', 'property', 'client_id'),
            'redirect_uri'  => RexConfig::get('Components', 'Vkontakte', 'property', 'redirect_uri'),
            'response_type' => RexConfig::get('Components', 'Vkontakte', 'property', 'response_type')
        );

        $authUrlVk  = 'http://oauth.vk.com/authorize?';
        $authUrlVk .= urldecode(http_build_query($paramsVk));

        RexDisplay::assign('authUrlVk', $authUrlVk);

        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch('user/sociallogin.tpl');
            RexResponse::response($content, true);
        }
    }

    private function _getFacebookUserInfo($param)
    {
        $data = array();
        $data['name'] = isset($param['first_name']) ? $param['first_name'] : '';
        $data['lastname'] = isset($param['last_name']) ? $param['last_name'] : '';
        $data['uid'] = $param['id'];
        $data['identity'] = $param['link'];
        $data['email'] = '';

        if (isset($param['email']) && $param['email']) {
            $data['email'] = $param['email'];
        }

        $gender = 0;
        if (isset($param['gender'])) {
            if ($param['gender'] == 'male') {$gender = 1;}
            if ($param['gender'] == 'female') {$gender = 2;}
        }

        $data['gender_id'] = $gender;

        if (isset($param['aboutMe']) &&  $param['aboutMe']) {
            $data['description'] = strip_tags(trim($param['aboutMe']));
        }

        return $data;
    }

    private function _vkRequest()
    {
        $code = Request::get('code', false);

        $result = false;
        $params = array(
            'client_id' => RexConfig::get('Components', 'Vkontakte', 'property', 'client_id'),
            'client_secret' => RexConfig::get('Components', 'Vkontakte', 'property', 'client_secret'),
            'code' => $code,
            'redirect_uri' => RexConfig::get('Components', 'Vkontakte', 'property', 'redirect_uri')
        );

        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        if (isset($token['access_token'])) {
            $params = array(
                'uids'         => $token['user_id'],
                'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $token['access_token']
            );

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        }

        return  $this->_getVKUserInfo($userInfo['response'][0]);
    }

    private function _getVKUserInfo($param)
    {
        $data              = array();
        $data['name']     = $param['first_name'];
        $data['lastname']     = $param['last_name'];
        $data['uid']       = $param['uid'];
        $data['identity']  = 'http://vk.com/'.$param['screen_name'];
        $data['email']     = $param['uid'].'@vk.com';
        $data['provider']  = 'vk';
        $data['gender_id'] = $param['sex'];
        $data['photo']     = $param['photo_big'];

        return $data;
    }

    private function _facebookRequest()
    {
        $facebook = new Facebook(array(
            /*'appId'  => FB_ID,
            'secret' => FB_SECRET, */
            'cookie' => true
        ));

        $code = Request::get('code', false);

        //echo $code; exit;
        if ($code) {
            $token = $facebook->getAccessToken();
            XSession::set('token', $token);
            $userAuth = $facebook->getUser();
        }

        $token = XSession::get('token', false);

        if ($token) {
            $userAuth = $facebook->getUser();
        } else {
            die("Error token");
        }

        if ($userAuth) {
            $userProfile = $facebook->api('/me');
            $userProfile['provider'] = 'facebook';
        } else {
            die('Error');
        }

        return $this->_getFacebookUserInfo($userProfile);
    }

    private function _saveAvatar($user_id, $link, $ext = false)
    {
        $date = date_create();
        $d = date_timestamp_get($date);
        $r = md5($d);
        $this->_generateUploadDir('user', true);
        $dirname = $this->_generateUploadDir('user/'.$user_id, true);

        if (!is_dir($dirname)) {
            mkdir($dirname);
            chmod($dirname, 0777);
        }

        if (!$ext) {
            $ext = $this->_getExtension($link);
        }
        $file_name = $dirname.'/'.$r.'.'.$ext;
        $fh = fopen($file_name, 'w');

        $ch = curl_init($link);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 3);

        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_exec($ch);
        curl_close($ch);

        fclose($fh);

        $this->_doUserAvatar($file_name, $user_id, $ext);
        unlink($file_name);

        $user = new UserEntity();
        $user->get($user_id);
        XSession::set('user', $user);
        //FUserManager::setLoginer('fuser', $fuser);
    }

    private function _doUserAvatar($file_name, $user_id, $ext = 'jpg')
    {
        $imgInfo = getimagesize($file_name);
        $width = $imgInfo[0];
        $height = $imgInfo[1];

        $this->_generateUploadDir('user', true);
        $upload_dir = $this->_generateUploadDir('user/'.$user_id, true);

        $destPrev = $upload_dir.'/preview.'.$ext;
        $destMain = $upload_dir.'/main.'.$ext;

        $params_mod = RexConfig::get('Images', 'user', 'size', 'main');

        $param_mod = explode('x', trim($params_mod));

        if ($width > 100 || $height > 100) {
            if ($width >= $height) {
                if (!XImage::createPreview($file_name, $destPrev, 32, false, 'resize') || !XImage::createPreview($file_name, $destMain, $param_mod[0], $param_mod[1], 'borders', 0xFFFFFF)) {
                    return false;
                }
            } else {
                if (!XImage::createPreview($file_name, $destPrev, false, 32, 'resize') || !XImage::createPreview($file_name, $destMain, $param_mod[0], $param_mod[1], 'borders', 0xFFFFFF)) {
                    return false;
                }
            }

        } elseif ($width > 32 || $height > 32) {
            if ($width > $height) {
                if (!XImage::createPreview($file_name, $destPrev, 32, false, 'borders', 0xFFFFFF) || XImage::createPreview($file_name, $destMain, $width, $height, 'borders', 0xFFFFFF)) {
                    return false;
                }
            } else {
                if (!XImage::createPreview($file_name, $destPrev, false, 32, 'borders', 0xFFFFFF) || XImage::createPreview($file_name, $destMain, $width, $height, 'borders', 0xFFFFFF)) {
                    return false;
                }
            }
        } else {
            @copy($file_name, $destPrev);
            @copy($file_name, $destMain);
        }

        $user = new UserEntity();
        $user->get($user_id);
        $user->avatar = $ext;
        if (!$user->update()) {
            return false;
        }

        return true;
    }

    private function _getExtension($aField)
    {
        if (!preg_match('#^.+\.([a-z0-9]{2,4})$#is', $aField, $ext)) {
            return false;
        }

        return $ext[1];
    }

    public function getAddSubscriber() {
        RexResponse::init();

        $subscriber_email = isset( $_POST['subscribe_email'] ) ? $_POST['subscribe_email'] : '';
        if ( $subscriber_email != '' and preg_match( '/[^@]+@[^\.@]+\..*/', $subscriber_email ) ) {
            $subscriber_email = htmlspecialchars( addslashes( trim( $subscriber_email ) ) );
            $subscr_exist   = XDatabase::getOne( "SELECT count(*) FROM subscribers WHERE subscriber_email = '{$subscriber_email}'" );
            $user_exist     = XDatabase::getOne( "SELECT count(*) FROM user WHERE email = '{$subscriber_email}'" );

            if ( !$subscr_exist and !$user_exist ) {
                XDatabase::query( "INSERT INTO subscribers ( subscriber_email ) VALUE ( '{$subscriber_email}' )" );
            }
        }

        RexResponse::response('ok');
    }
}