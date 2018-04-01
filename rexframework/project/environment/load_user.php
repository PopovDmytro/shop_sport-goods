<?php
function generetedCookieData($passUser) {
    return md5($passUser);
}

function setCookieData($key, $data) {
    setcookie($key, $data, time() + (60*60*24*30), '/', RexConfig::get('Project', 'cookie_domain'));
}

function clearCookieData($key) {
    setcookie($key, '', time() - (60*60*24*30), '/', RexConfig::get('Project', 'cookie_domain'));
}

function checkUserByCookie($cookieValue)
{
    if (!strlen($cookieValue)) {
        return false;
    }

    $cookieUser = explode(';', $cookieValue);

    $user = RexFactory::entity('user');
    if (isset($cookieUser[0])) {
        $user->get($cookieUser[0]);
    }

    if (count($cookieUser) == 2 and isset($cookieUser[1]) and $user->id and $user->password) {
         $genCookieData = generetedCookieData($user->password);
         if ($cookieUser[1] === $genCookieData) {
             $genCookieData = $user->id.';'.$genCookieData;
             setCookieData('rf_user', $genCookieData);
             
             return $user;
         }
    }
  
    return false;
}

$arrUser = Request::get('user');
$tmpUser = XSession::get('user');

// press login form
if (isset($arrUser['submitlogin']) && !isset($arrUser['code'])) {
    $user = RexFactory::entity('user');
    
    // admin
    if (RexRunner::getEnvironment() == 'admin') {
        //\sys::dump($arrUser['login'], md5($arrUser['password'])); exit;
        
        if ($user->getByFields(array('login' => $arrUser['login'], 'password' => md5($arrUser['password']), 'active'=>1, 'role'=>'admin'))) {
            if ($user->phone && RexSettings::get('phpsender_adminlogin') == 'true') {
                $result = PHPSender::sendValidationCode($user->phone, 6);
                if($result->status === false){
                    RexPage::addError(RexLang::get('user.error.try_again'), Request::get('mod'));
                }
                $user->phone_validation = $result->code;
                $user->update();
                setcookie('user_login', $user->id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
                RexDisplay::assign('confirm_sms', true);
            } else {
                XSession::set('user', $user);
            }
        } elseif ($user->getByFields(array('login' => $arrUser['login'], 'password' => md5($arrUser['password']), 'active'=>1, 'role'=>'operator'))) {
            if ($user->phone && RexSettings::get('phpsender_adminlogin') == 'true') {
                $result = PHPSender::sendValidationCode($user->phone, 6);
                if($result->status === false){
                    RexPage::addError(RexLang::get('user.error.try_again'), Request::get('mod'));
                }
                $user->phone_validation = $result->code;
                $user->update();
                setcookie('user_login', $user->id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
                RexDisplay::assign('confirm_sms', true);
                return true;
            } else {
                XSession::set('user', $user);
            }
        } else {
            XSession::remove('user');
            XSession::destroy();
        }
        // rextest
    } elseif (RexRunner::getEnvironment() == 'rextest') {
        if ($user->getByFields(array('login' => $arrUser['login'], 'password' => md5($arrUser['password']), 'active'=>1, 'role'=>'admin'))) {
            XSession::set('user', $user);
        } else {
            XSession::remove('user');
            XSession::destroy();
        }
        // user
    } else {
        if ($user->getByFields(array('email' => $arrUser['email'], 'password' => md5($arrUser['password']), 'active'=>1, 'role' => 'user'))) {
            if ($user->phone && RexSettings::get('phpsender_userlogin') == 'true') {
                $result = PHPSender::sendValidationCode($user->phone, 6);
                if($result->status === false){
                    RexPage::addError('Ошибка авторизации', 'user');
                    //RexPage::addError(RexLang::get('user.error.try_again'), Request::get('mod'));
                }
                $user->phone_validation = $result->code;
                $user->update();
                setcookie('user_login', $user->id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
                RexDisplay::assign('confirm_sms', true);
            } else {
                XSession::set('user', $user);
                
                //SET COOKIE
                setcookie('rf_user', $user->id.';'.$user->password, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));

                if (XSession::get('user_exist')) {
                    $arrUser['request'] = RexRoute::getUrl(array('mod' => 'cart', 'act' => 'default'));
                }
                if (!empty($arrUser['request'])) {
                    header('Location: '.$arrUser['request']);
                    exit;
                } else {
                    header('Location: /');
                    exit;
                }
            }

        } else {
            RexPage::addError('Ошибка авторизации', 'user');
            XSession::remove('user');
            XSession::destroy();
        }
    }
    // from session
} elseif ($tmpUser and !empty($tmpUser)) {
    
    $user = XSession::get('user');

    if (RexRunner::getEnvironment() == 'admin') {
        if ($user->role == 'user') {
            RexPage::addError('Ошибка авторизации', 'user');
            XSession::remove('user');
            XSession::destroy();
            exit;
        }
    }

    XSession::set('user', $user);
    
    //SET COOKIE
    //setcookie('rf_user', $user->id.';'.$user->password, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
    $genCookieData = generetedCookieData($user->password);
    setCookieData('rf_user', $user->id.';'.$genCookieData);
    
} elseif (isset($_COOKIE) and isset($_COOKIE['rf_user'])) {
    /*$cookieUser = explode(';', $_COOKIE['rf_user']);

    $user = RexFactory::entity('user');
    if (RexRunner::getEnvironment() == 'admin') {
        //
    } else {
        if ($user->getByFields(array('id' => intval($cookieUser[0]), 'password' => trim($cookieUser[1]), 'active'=>1, 'role' => 'user'))) {
            XSession::set('user', $user);
            //SET COOKIE
            setcookie('rf_user', $user->id.';'.$user->password, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        } else {
            RexPage::addError('Ошибка авторизации', 'user');
            XSession::remove('user');
        }
    }  */
    $checkCookie = checkUserByCookie($_COOKIE['rf_user']);

    if ($checkCookie !== false) {
        XSession::set('user', $checkCookie);
    } else {
        RexDisplay::assign('error', 1);
        RexPage::addError('Ошибка авторизации', 'user');
        XSession::destroy();
    }

    if ($checkCookie && !$checkCookie->name) {
        RexDisplay::assign('not_name', true);
    }
} elseif (isset($arrUser['submit']) && isset($_COOKIE['user_login'])) {
    $user = RexFactory::entity('user');
    $user->get($_COOKIE['user_login']);
    
    if ($arrUser['code'] == $user->phone_validation) {
        XSession::set('user', $user);
        if (RexRunner::getEnvironment() != 'admin') {
            //SET COOKIE
            setcookie('rf_user', $user->id.';'.$user->password, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain')); 
            $genCookieData = generetedCookieData($user->password);
            setCookieData('rf_user', $user->id.';'.$genCookieData); 
        }
        setcookie('user_login', '', 0, '/', RexConfig::get('Project', 'cookie_domain'));
        header('Location: /');
        exit;    
    }   
} elseif (isset($arrUser['cancel']) && isset($_COOKIE['user_login'])) {
    setcookie('user_login', '', 0, '/', RexConfig::get('Project', 'cookie_domain'));
    header('Location: /');
    exit;
}
RexDisplay::assign('user', XSession::get('user'));