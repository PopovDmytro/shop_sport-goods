<?php

define('XCAPTCHA_PRIVKEY_SESSION', 'session');
define('XCAPTCHA_PRIVKEY_SESSION_FORMULA',    'session_formula'); 
define('XCAPTCHA_PRIVKEY_DB', 'db');
define('XCAPTCHA_SESSION_KEY', 'xcaptcha');

class XCaptcha
{
    static protected $ttf_folder = "";
    static protected $chars = 5;
    static protected $lx = 200;
    static protected $ly = 50;
    static protected $minsize = 20;
    static protected $maxsize = 25;
    static protected $noise = 50;
    static protected $maxrotation    = 10;
    static protected $ttf_range = array();
    static protected $privkey;
    static protected $privkey_result;
    static protected $maxold = 1800;
    static protected $phpfile;
    static protected $privkey_method;
    
    public function __construct($aProperty)
    {
        static::init($aProperty);
    }
    
    static public function init($aProperty)
    {
        foreach ($aProperty as $fKey => $fValue) {
            if ($fKey == 'ttf_folder') {
                static::$$fKey = dirname(__FILE__).'/fonts/';
            } else {
                static::$$fKey = $fValue;
            }
        } 
        static::_load_ttf();
    }
    
    static public function MakeCaptcha($aProperty, $aPrintHTML = false)
    {
        static::init($aProperty);
        if ($aPrintHTML) {
            static::GetHTML();
        }
    }
    
    static public function GetCaptchaImage()
    {
        static::_generate_private(); 
        static::_saveCaptchaKey(); 
        static::_make_captcha();
    }
    
    static public function GetCaptchaFormulaImage()
    {
        static::$chars = 8;
        static::$lx = 85;
        static::$ly = 25;
        static::$minsize = 15;
        static::$maxsize = 15;
        static::$noise = 0;
        static::$maxrotation    = 0;
        static::$ttf_range    = array();
        static::$ttf_range[]    = 'verdanab.ttf';
        static::$privkey_method    = XCAPTCHA_PRIVKEY_SESSION_FORMULA; 
        static::_generate_formula(); 
        static::_saveCaptchaKey(); 
        static::_make_captcha();
    }
    
    static public function GetHTML()
    {
        print "<img src='" . static::$phpfile . "'>";
    }
    
    static public function Validate($aKey = null)
    {
        if (!$aKey) return false; 
        switch (static::$privkey_method) {
            case XCAPTCHA_PRIVKEY_SESSION:
            case XCAPTCHA_PRIVKEY_SESSION_FORMULA:
                return static::_validateSession($aKey);
                break;
            default:
                return static::_validateSession($aKey);
                break;
        }
        return false;
    }
    
    static protected function _validateSession($aKey)
    {
        if ($aKey == XSession::get(XCAPTCHA_SESSION_KEY)) {
            return true;
        } else {
            return false;
        }
    }
    
    static protected function _saveCaptchaKey()
    {
        switch (static::$privkey_method) {
            case XCAPTCHA_PRIVKEY_SESSION:
                static::_saveCaptchaKeySession();
                break;
            case XCAPTCHA_PRIVKEY_SESSION_FORMULA:
                static::_saveCaptchaFormulaResultSession();
                break;
            default:
                static::_saveCaptchaKeySession();
                break;
        }
    }
    
    static protected function _saveCaptchaKeySession()
    {
        XSession::remove(XCAPTCHA_SESSION_KEY); 
        XSession::set(XCAPTCHA_SESSION_KEY, static::$privkey);
    }
    
    static protected function _saveCaptchaFormulaResultSession()
    {
        XSession::remove(XCAPTCHA_SESSION_KEY); 
        XSession::set(XCAPTCHA_SESSION_KEY, static::$privkey_result); 
    }
    
    static protected function _load_ttf()
    {
        $handle = opendir(static::$ttf_folder);
        while ($file = readdir ($handle)) {
            if ($file != "." && $file != ".." && $file != "" && preg_match('#.ttf$#is', $file)) {
                static::$ttf_range[] = $file;
            }
        }
        closedir($handle);
    }
    
    static protected function _random_color($aMin, $aMax)
    {
        srand((double)microtime() * 1000000);
        $randcol['r'] = intval(rand($aMin, $aMax));
        srand((double)microtime() * 1000000);
        $randcol['g'] = intval(rand($aMin, $aMax));
        srand((double)microtime() * 1000000);
        $randcol['b'] = intval(rand($aMin, $aMax));
        return $randcol;
    }
    
    static protected function _random_char()
    {
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '2', '3', '4', '5', '6', '7', '8', '9'); 
        $randcharindex = rand(0,count($chars)-1);
        return $chars[$randcharindex];
    }
    
    static protected function _generate_private()
    {
        static::$privkey = "";
        for ($i=0; $i<static::$chars; $i++) {
            static::$privkey .= static::$_random_char();
        }
    }
    
    static protected function _generate_formula()
    {
        $x = rand(1, 5);
        $y = rand(1, 4);
        static::$privkey = $x . "+" . $y . "=";
        static::$privkey_result = $x + $y;
    }
    
    static protected function _random_ttf()
    {
        $filename = static::$ttf_folder;
        $filename .= static::$ttf_range[rand(0,count(static::$ttf_range)-1)]; 
        return $filename;
    }
    
    static protected function _make_captcha()
    {
        $image    = @imagecreatetruecolor(static::$lx,static::$ly); 
        $randcol    = static::_random_color(224, 255);
        $back = @imagecolorallocate($image, $randcol['r'], $randcol['g'], $randcol['b']);
        @ImageFilledRectangle($image, 0, 0, static::$lx, static::$ly, $back); 
        if (static::$noise > 0) { 
            for ($i=0; $i < static::$noise; $i++) {
                srand((double)microtime()*1000000);
                $size    = intval(rand((int)(static::$minsize / 2.3), (int)(static::$maxsize / 1.7)));
                srand((double)microtime()*1000000);
                $angle    = intval(rand(0, 360));
                srand((double)microtime()*1000000);
                $x = intval(rand(0, static::$lx));
                srand((double)microtime()*1000000);
                $y = intval(rand(0, (int)(static::$ly - ($size / 5))));
                $randcol=static::_random_color(160, 224);
                $color    = @imagecolorallocate($image, $randcol['r'], $randcol['g'], $randcol['b']);
                srand((double)microtime()*1000000);
                $text    = static::_random_char();
                @ImageTTFText($image, $size, $angle, $x, $y, $color, static::_random_ttf(), $text);
            }
        } else { 
            for ($i=0; $i < static::$lx; $i += (int)(static::$minsize / 1.5)) {
                $randcol    = static::_random_color(160, 224);
                $color = @imagecolorallocate($image, $randcol['r'], $randcol['g'], $randcol['b']);
                @imageline($image, $i, 0, $i, static::$ly, $color);
            }
            for ($i=0 ; $i < static::$ly; $i += (int)(static::$minsize / 1.8)) {
                $randcol=static::_random_color(160, 224);
                $color    = imagecolorallocate($image, $randcol['r'], $randcol['g'], $randcol['b']);
                @imageline($image, 0, $i, static::$lx, $i, $color);
            }
        }
        for ($i=0, $x = intval(rand(static::$minsize,static::$maxsize)); $i < static::$chars; $i++) {
            $text    = strtolower(substr(static::$privkey, $i, 1));
            srand((double)microtime()*1000000);
            $angle    = intval(rand((static::$maxrotation * -1), static::$maxrotation));
            srand((double)microtime()*1000000);
            $size    = intval(rand(static::$minsize, static::$maxsize));
            srand((double)microtime()*1000000);
            $y = intval(rand((int)($size * 1.5), (int)(static::$ly - ($size / 7))));
            $randcol=static::_random_color(0, 127);
            $color    = @imagecolorallocate($image, $randcol['r'], $randcol['g'], $randcol['b']);
            $randcol=static::_random_color(0, 127);
            $shadow = @imagecolorallocate($image, $randcol['r'] + 127, $randcol['g'] + 127, $randcol['b'] + 127);
            $TTF_file=static::_random_ttf();
            @ImageTTFText($image, $size, $angle, $x + (int)($size / 15), $y, $shadow, $TTF_file, $text);
            @ImageTTFText($image, $size, $angle, $x, $y - (int)($size / 15), $color, $TTF_file, $text);
            $x += (int)($size + (static::$minsize / 5));
        }
        header("Content-type: image/png");
        @ImagePNG($image);
        @ImageDestroy($image);
    }
}