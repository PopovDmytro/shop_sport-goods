<?php

/**
 * Class RexLang
 *
 * @author   MAG
 */
class RexLang extends RexObject
{
	static protected $map;
	static protected $lang;
    static protected $init = false;
    
    static public function init()
    {
        $lang = false;
        $lang_path = RexConfig::get('RexPath', 'lang');
        
        if (RexConfig::get('Project', 'lang', 'subdomain')) {
            $lang = isset($_SERVER['HTTP_HOST']) ? preg_replace('#^([a-z]{2,3})\..+$#is', '$1', $_SERVER['HTTP_HOST']) : 'www';
            if ($lang == 'www') {
                $lang = RexConfig::get('Project', 'lang', 'default');
            }
        } else {
            $tmp_lang = XSession::get('lang');
            if (!$lang && $tmp_lang && file_exists(REX_ROOT.$lang_path.$tmp_lang.'.ini')) {
                $lang = $tmp_lang;
            }
            $tmp_lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : false;
            if (!$lang && $tmp_lang && file_exists(REX_ROOT.$lang_path.$tmp_lang.'.ini')) {
                $lang = $tmp_lang;
            }
            if (!$lang) {
                $lang = RexConfig::get('Project', 'lang', 'default');
            }
        }
        
        if (!file_exists(REX_ROOT.$lang_path.$lang.'.ini')) {
            throw new Exception('Lang file "'.$lang.'.ini" not found');
        }
        static::$lang = $lang;
        static::loadLangFile();
    }
    
    static public function setLang($lang)
    {
        if (static::$lang != $lang) {
            if (RexConfig::get('Project', 'lang', 'subdomain')) {
                throw new Exception('Set lang on subdomain lang detection');
            }
            static::$lang = $lang;
            static::$init = false;
            static::$map = array();
            
            $lang_path = RexConfig::get('RexPath', 'lang');
            if (!file_exists(REX_ROOT.$lang_path.$lang.'.ini')) {
                throw new Exception('Lang file "'.$lang.'.ini" not found');
            }
            XSession::set('lang', $lang);
            setcookie('lang', $lang, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
            static::loadLangFile();
        }
    }
    
    static public function getLang()
    {
        return static::$lang;
    }
    
    static private function loadLangFile()
    {
        $lang_file = REX_ROOT.RexConfig::get('RexPath', 'lang').static::$lang.'.ini';
        static::$map = parse_ini_file($lang_file);
        static::$init = true;
    }
    
    static public function get($key, $params = array())
    {
        /*if (!static::$init) {
            static::loadLangFile();
        }*/
        
        if (isset(static::$map[$key])) {
            $result = static::$map[$key];
            $num = 1;
            foreach ($params as $param) {
                $result = str_replace('{'.$num.'}', $param, $result);
                ++$num;
            }
            return $result;
        }
        return 'LANG "'.static::$lang.'": "'.$key.'" not found';
    }
}

function RexLang($key, $params = array())
{
    return RexLang::get($key, $params);
}

function lang($key, $params = array())
{
    $num = func_num_args();
    if ($num > 2) {
        $params = array_shift(func_get_args());
    }
    return RexLang::get($key, $params);
}