<?php
namespace RexFramework;

use \RexConfig as RexConfig;
use \RexDBList as RexDBList;
use \RexFactory as RexFactory;
use \Request as Request;

/**
 * Class ParentRextestController
 * 
 * @author   MAG
 */
class ParentRextestController extends ParentController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    
    protected $_result = array();
    protected $_errors = array();
    protected $_passed = array();
    protected $_time = 0;

    public function getRun()
    {
        $methods = get_class_methods($this);
        
        foreach ($methods as $method) {
            if (substr($method, 0, 4) == 'test') {
                $this->_result[$method] = array(
                    'errors' => array(),
                    'passed' => array(),
                    'time' => 0
                );
                
                $this->_errors = &$this->_result[$method]['errors'];
                $this->_passed = &$this->_result[$method]['passed'];
                $this->_generateTime();
                $this->_generateTimeForAssert();
                try {
                    $this->_clearTime();
                    //$this->_logout();
                    $this->$method();
                } catch (Exception $e) {
                    $this->_errors[] = $e->getMessage();
                }
                $end = $this->_generateTime();
                
                $this->_result[$method]['time'] = $end;
            }
        }
        
        return $this->_result;
    }
    
    private function _clearTime()
    {
        $this->_time = 0;
    }
    
    private function _generateTime()
    {
        global $GenerateTime;
        global $GenerateTimeString;
        
        $back = debug_backtrace();
        
        if (!isset($GenerateTime)) {
            list($msec, $sec) = explode(chr(32), microtime());
            $GenerateTime["start"]   = $sec + $msec;
            $GenerateTime["section"] = $sec + $msec;
            
            $GenerateTimeString = sprintf("%01.4f", 0);
            return true;
        } else {
            $start = $GenerateTime["section"];
            list($msec, $sec) = explode(chr(32), microtime());
            $GenerateTime["section"] = $sec + $msec;

            $GenerateTimeString = sprintf("%01.4f", round($GenerateTime["section"] - $start, 4).' sec');
            return $GenerateTimeString;
        }
    }
    
    private function _generateTimeForAssert()
    {
        global $GenerateTimeForAssert;
        global $GenerateTimeStringForAssert;
        
        $back = debug_backtrace();
        
        if (!isset($GenerateTimeForAssert)) {
            list($msec, $sec) = explode(chr(32), microtime());
            $GenerateTimeForAssert["start"]   = $sec + $msec;
            $GenerateTimeForAssert["section"] = $sec + $msec;
            
            $GenerateTimeStringForAssert = sprintf("%01.4f", 0);
            return true;
        } else {
            $startForAssert = $GenerateTimeForAssert["section"];
            list($msec, $sec) = explode(chr(32), microtime());
            $GenerateTimeForAssert["section"] = $sec + $msec;

            $GenerateTimeStringForAssert = sprintf("%01.4f", round($GenerateTimeForAssert["section"] - $startForAssert, 4).' sec');
            $error_line = '';
            if (isset($back['2']['file'])) {
                preg_match('#/([a-zA-Z0-9]+).class.php$#', $back['2']['file'], $controller);
                $error_line = $controller[1].':'.$back['2']['line'];
            }
            return array('time' => $GenerateTimeStringForAssert, 'error_line' => $error_line);
        }
    }
    
    protected function assertTrue($aValue, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if (!$aValue) {
            $aMessage = sprintf('%sexpected TRUE, actual FALSE', $aMessage ? $aMessage.' ' : '');
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'TRUE'));
    }
    
    protected function assertFalse($aValue, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if ($aValue) {
            $aMessage = sprintf('%sexpected FALSE, actual TRUE', $aMessage ? $aMessage.' ' : '');
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'FALSE'));
    }
    
    protected function assertMore($expected, $actual, $aMessage = '', $equally = false)
    {
        $infoMessage = $aMessage;
        
        if (is_numeric($actual) && is_numeric($expected)) {
            if ($equally)
                if ($expected < $actual) {
                    $aMessage = sprintf('%sexpected >, actual <', $aMessage ? $aMessage.' ' : '');
                    return $this->addFail($aMessage);
                }
            else
                if ($expected <= $actual) {
                    $aMessage = sprintf('%sexpected >, actual <=', $aMessage ? $aMessage.' ' : '');
                    return $this->addFail($aMessage);
                }
        } else {
            return $this->addFail('Compaire only numeric');
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'More'));
    }
    
    protected function assertLess($expected, $actual, $aMessage = '', $equally = false)
    {
        $infoMessage = $aMessage;
        
        if (is_numeric($actual) && is_numeric($expected)) {
            if ($equally)
                if ($expected > $actual) {
                    $aMessage = sprintf('%sexpected <, actual >', $aMessage ? $aMessage.' ' : '');
                    return $this->addFail($aMessage);
                }
            else
                if ($expected >= $actual) {
                    $aMessage = sprintf('%sexpected <, actual >=', $aMessage ? $aMessage.' ' : '');
                    return $this->addFail($aMessage);
                }
        } else {
            return $this->addFail('Compaire only numeric');
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'Less'));
    }
    
    protected function assertEquals($expected, $actual, $aMessage = '', $delta = 0)
    {
        $infoMessage = $aMessage;
        
        if ((is_array($actual)  && is_array($expected)) || (is_object($actual) && is_object($expected))) {
            if (is_array($actual) && is_array($expected)) {
                ksort($actual);
                ksort($expected);
            }

            $actual   = serialize($actual);
            $expected = serialize($expected);

            if ($actual !== $expected) {
                $aMessage = sprintf('%sexpected %s, actual %s', $aMessage ? $aMessage.' ' : '', $expected, $actual);
                return $this->addFail($aMessage);
            }
            
            return $this->addPassed(array('message' => $infoMessage, 'result' => $expected));
            
        } elseif (is_numeric($actual) && is_numeric($expected)) {            
            if (!($actual >= ($expected - $delta) && $actual <= ($expected + $delta))) {
                $aMessage = sprintf('%sexpected %s%s, actual %s', $aMessage ? $aMessage.' ' : '', $expected, ($delta != 0) ? ('+/- '.$delta) : '', $actual);
                return $this->addFail($aMessage);
            }
            
            return $this->addPassed(array('message' => $infoMessage, 'result' => $expected));
        } else {
            if ($actual !== $expected) {
                $aMessage = sprintf('%sexpected %s, actual %s', $aMessage ? $aMessage.' ' : '', $expected, $actual);
                return $this->addFail($aMessage);
            }
            
            return $this->addPassed(array('message' => $infoMessage, 'result' => $expected));
        }
    }
    
    protected function assertRegExp($pattern, $string, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if (!preg_match($pattern, $string)) {
            $aMessage = sprintf('%s. Result does not match pattern "%s"', $aMessage ? $aMessage.' ' : '', $pattern);
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'TRUE'));
    }
    
    protected function assertNotRegExp($pattern, $string, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if (preg_match($pattern, $string)) {
            $aMessage = sprintf('%s. Result matches pattern "%s"', $aMessage ? $aMessage.' ' : '', $pattern);
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'FALSE'));
    }
    
    protected function assertDoctype($string, $aMessage = '', $pattern = '<!DOCTYPE ', $length = 10)
    {
        $infoMessage = $aMessage;
        
        if (substr($string, 0, $length) != $pattern) {
            $aMessage = sprintf('%s. Result does not match pattern "%s"', $aMessage ? $aMessage.' ' : '', $pattern);
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'TRUE'));
    }
    
    protected function assertContains($needle, $haystack, $aMessage = '')
    {
        if (is_string($needle) && is_string($haystack)) {
            return $this->assertTrue(strpos($haystack, $needle) !== false, $aMessage);
        } elseif (is_array($haystack) && !is_object($needle)) {
            return $this->assertTrue(in_array($needle, $haystack), $aMessage);
        }
        
        return $this->addFail('Unsupported parameter passed to assertContains().');
    }
    
    protected function assertNotContains($needle, $haystack, $aMessage = '')
    {
        if (is_string($needle) && is_string($haystack)) {
            return $this->assertFalse(strpos($haystack, $needle) !== false, $aMessage);
        } elseif (is_array($haystack) && !is_object($needle)) {
            return $this->assertFalse(in_array($needle, $haystack), $aMessage);
        }

return $this->addFail('Unsupported parameter passed to assertNotContains().');
    }
    
    protected function assertSame($expected, $actual, $aMessage = '')
    {
        if (!version_compare(phpversion(), '5.0.0', '>=')) {
            return $this->addFail('assertSame() only works with PHP >= 5.0.0.');
        }

        $infoMessage = $aMessage;
        
        if ((is_object($expected) || is_null($expected)) && (is_object($actual)   || is_null($actual))) {
            if ($expected !== $actual) {
                $aMessage = sprintf('%sexpected two variables to reference the same object', $aMessage ? $aMessage.' ' : '');
                return $this->addFail($aMessage);
            }
            
            return $this->addPassed(array('message' => $infoMessage, 'result' => 'TRUE'));
        }
        
        return $this->addFail('Unsupported parameter passed to assertSame().');
    }
    
    protected function assertNotSame($expected, $actual, $aMessage = '')
    {
        if (!version_compare(phpversion(), '5.0.0', '>=')) {
            return $this->addFail('assertNotSame() only works with PHP >= 5.0.0.');
        }
        
        $infoMessage = $aMessage;

        if ((is_object($expected) || is_null($expected)) && (is_object($actual)   || is_null($actual))) {
            if ($expected === $actual) {
                $aMessage = sprintf('%sexpected two variables to reference different objects', $aMessage ? $aMessage.' ' : '');
                return $this->addFail($aMessage);
            }
            
            return $this->addPassed(array('message' => $infoMessage, 'result' => 'FALSE'));
        }
        
        return $this->addFail('Unsupported parameter passed to assertNotSame().');
    }
    
    function assertNotNull($actual, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if (is_null($actual)) {
            $aMessage = sprintf('%sexpected NOT NULL, actual NULL', $aMessage ? $aMessage.' ' : '');
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'TRUE'));
    }
    
    function assertNull($actual, $aMessage = '')
    {
        $infoMessage = $aMessage;
        
        if (!is_null($actual)) {
            $aMessage = sprintf('%sexpected NULL, actual NOT NULL', $aMessage ? $aMessage.' ' : '');
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => $infoMessage, 'result' => 'FALSE'));
    }
    
    protected function assertModules($arr)
    {
        $extensions = get_loaded_extensions();
        
        $intersect = array_intersect($extensions, $arr);
        $diff = array_diff($arr, $intersect);
        if ($diff) {
            $aMessage = 'The modules ';
            foreach ($diff as $key => $value) {
                $aMessage .= $value.', ';
            }
            
            $aMessage = trim($aMessage, ', ').' are not installed';
            return $this->addFail($aMessage);
        }
        
        return $this->addPassed(array('message' => 'All important modules are installed', 'result' => 'TRUE'));
    }
    
    protected function assertMagicQuots()
    {
        $magic = get_magic_quotes_gpc();
        
        if ($magic) {
            return $this->addFail('Magic Quotes - ON');
        }
        
        return $this->addPassed(array('message' => 'Magic Quotes - OFF', 'result' => 'TRUE'));
    }
    
    protected function assertPhpVersion()
    {
        if (!version_compare(phpversion(), '5.3.0', '>=')) {
            return $this->addFail('Php Version should be >= 5.3.0. Curent Php Version - '.phpversion());
        }
        
        return $this->addPassed(array('message' => 'Curent Php Version - '.phpversion(), 'result' => 'TRUE'));
    }
    
    protected function assertRegistrGlobals()
    {
        if (ini_get('register_globals')) {
            return $this->addFail('Register Globals - ON');
        }
        
        return $this->addPassed(array('message' => 'Register Globals - OFF', 'result' => 'TRUE'));
    }
    
    protected function assertRedirect()
    {
        $redirect = $this->_getRedirect();
        
        if ($redirect['http_code'] != 301 || !preg_match('#//www.#', $redirect['url'])) {
            return $this->addFail('301 redirect disabled');
        }
        
        return $this->addPassed(array('message' => '301 redirect enabled', 'result' => 'TRUE'));
    }
    
    protected function addFail($aMessage)
    {
        $arr = $this->_generateTimeForAssert();
        $this->_errors[] = array(
            'message' => $aMessage,
            'time' => $this->_time,
            'error_line' => $arr['error_line']
        );
        return false;
    }
    
    protected function addPassed($aParam)
    {
        $arr = $this->_generateTimeForAssert();
        $this->_passed[] = array(
            'message' => $aParam['message'],
            'result' => $aParam['result'],
            'time' => $this->_time,
            'error_line' => $arr['error_line']
        );
        return true;
    }
    
    protected function getDataFrontend($aGet, $aPost = array())
    {   
        $url = "http://".RexConfig::get('Project', 'cookie_domain').'/index.php?';
        foreach ($aGet as $key => $value) {
            $url .= $key.'='.$value.'&';    
        }
        
        $url = trim($url, '&');
        
        $query = http_build_query($aPost, '', '&');
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/cookies.txt"); 
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch,CURLOPT_USERPWD,"developer:12345");
        
        $messagedata = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        $this->_time = $info['total_time'];
        
        curl_close($ch);
        
        return array($messagedata, $info);
    }
    
    protected function getDataAdmin($aGet, $aPost = array())
    {
        $url = "http://".RexConfig::get('Project', 'cookie_domain').'/admin/index.php?';
        foreach ($aGet as $key => $value) {
            $url .= $key.'='.$value.'&';    
        }
        
        $url = trim($url, '&');
            
        $query = http_build_query($aPost, '', '&');
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/cookies.txt");
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch,CURLOPT_USERPWD,"developer:12345");
        
        $messagedata = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        $this->_time = $info['total_time'];
        
        curl_close($ch);
        
        return array($messagedata, $info);
    }
    
    protected function getDataRextest($aGet, $aPost = array())
    {
        $url = "http://".RexConfig::get('Project', 'cookie_domain').'/rextest/index.php?';
        foreach ($aGet as $key => $value) {
            $url .= $key.'='.$value.'&';    
        }
        
        $url = trim($url, '&');
            
        $query = http_build_query($aPost, '', '&');
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch,CURLOPT_USERPWD,"developer:12345");
        
        $messagedata = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        $this->_time = $info['total_time'];
        
        curl_close($ch);
        
        return $messagedata;
        //return array($messagedata, $info);
    }
    
    private function _getRedirect()
    {
        $url = "http://".RexConfig::get('Project', 'cookie_domain');
            
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch,CURLOPT_USERPWD,"developer:12345");
        
        curl_exec($ch);
        $response = curl_getinfo($ch);
        
        $this->_time = $response['total_time'];
        
        curl_close($ch);
        
        return $response;
    }
    
    /*private function _logout()
    {
        $url = "http://".RexConfig::get('Project', 'cookie_domain').'/rextest/index.php?mod=user&act=logout';
            
        ob_start();
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/cookies.txt");
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch,CURLOPT_USERPWD,"developer:12345");
        
        curl_close($ch);
        ob_end_clean();
        
        return true;
    }*/
    
    public function getReturnSession()
    {
        $session_name = Request::get('session_name');
        $session_id = Request::get('session_id');
        $params = Request::get('params', array());
        
        if (true || session_id() != $session_id || session_name() != $session_name) {
            session_destroy();
        }
        
        if (!session_id()) {
            $_COOKIE[$session_name] = $session_id;
            session_name($session_name);
            session_start();
        }
        
        if (!$params) {
            echo serialize($_SESSION);
            exit;
        }
        
        $return = array();
        foreach ($params as $k => $param) {
            if (isset($_SESSION[$param])) {
                $return[$param] = $_SESSION[$param];
            }
        }
        echo serialize($return);
        exit;
    }
    
    protected function getSession($params = array())
    {        
        $file = file(dirname(__FILE__)."/cookies.txt");
        
        foreach ($file as $key => $row) {
            if (preg_match('#'.'SESSION'.config('sysname').'\s(.+)#', $row, $data)) {
                $session_name = 'SESSION'.config('sysname');
                $session_id = $data[1];
                
                $get = $this->generateGet('home', 'returnSession');
                
                $post = array(
                    'session_name' => $session_name,
                    'session_id' => $session_id,
                    'session_name' => $session_name,
                );
                
                foreach ($params as $key => $value) {
                    $post['params['.$value.']'] = $value;
                }
                
                return unserialize($this->getDataRextest($get, $post));
            }
        }
        
        return false;
    }
    
    protected function generateGet($mod, $act = 'default', $task = false)
    {
        return array(
            'mod' => $mod,
            'act' => $act,
            'task' => $task
        );
    }
    
    protected function generatePost($type = false, $arr = array())
    {
        if ($type == 'admin')
            return array_merge(array(
                'user[submitlogin]' => true,
                'user[login]' => 'admin',
                'user[password]' => '123456'
            ), $arr);
        elseif ($type == 'frontend') {
            $user = $this->getTestUser();
            return array_merge(array(
                'user[submitlogin]' => true,
                'user[email]' => $user->email,
                'user[password]' => $user->clear_password
            ), $arr);
        }
        
        return $arr;
    }
    
    protected function getTestUser()
    {
        $users = RexFactory::entity('user'); 
        $users->getByFields(array('email' => 'rextest@phpeagles.com')); 
        
        if ($users->id) {
            return $users;
        }
        
        $entity = RexFactory::entity('user');
        $entity->login = 'rextest';
        $entity->password = md5('123456');
        $entity->email = 'rextest@phpeagles.com';
        $entity->role = 'user';
        $entity->confirm = 1;
        $entity->active = 1;
        $entity->clear_password = '123456';
        
        if (!$entity->create()) {
            return false;
        }
        
        return $entity;
    }
}