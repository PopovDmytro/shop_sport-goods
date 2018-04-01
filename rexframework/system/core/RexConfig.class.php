<?php

/**
 * Class RexConfig
 *
 * @author   MAG
 */
class RexConfig extends RexObject
{
    static protected $config = array();
    
    static public function init()
    {
        $default_file = dirname(__FILE__).'/../config.ini';
        $file = CONFIG_DIR.'config.ini';
        
        RexConfig::set(array_merge_recursive(
            file_exists($default_file) ? RexIni::parse($default_file, true) : array(),
            file_exists($file) ? RexIni::parse($file, true) : array()
        ));
    }
    
    static public function set($config)
    {
        static::$config = $config;
    }
    
    static private function isExistByArray($keys)
    {
        $current_section = &static::$config;
        foreach ($keys as $key) {        
            if (!isset($current_section[$key])) {
                return false;
            }
            $current_section = &$current_section[$key];
        }
        return true;
    }
    
    static public function isExist($key)
    {
        $args = array();
        if (func_num_args() == 1 && is_string($key)) {
            if (strpos($key, '.') !== false) {
                $args = explode('.', $key);
            } elseif (!static::isExistByArray(array($key))) {
                $args = array('Project', $key);
            } else {
                $args = array($key);
            }
        } elseif (is_array($key)) {
            $args = $key;
        } else {
            $args = func_get_args();
        }
        return static::isExistByArray($args);
    }
    
    static public function isTrue($key) {
        $args = array();
        if (func_num_args() == 1 && is_string($key)) {
            if (strpos($key, '.') !== false) {
                $args = explode('.', $key);
            } elseif (!static::isExistByArray(array($key))) {
                $args = array('Project', $key);
            } else {
                $args = array($key);
            }
        } elseif (is_array($key)) {
            $args = $key;
        } else {
            $args = func_get_args();
        }
        try {
            $value = static::get($args);
        } catch (Exception $e) {
            return false;
        }
        return (bool)$value;
    }
    
    static private function getByArray($keys)
    {
        $current_section = &static::$config;
        foreach ($keys as $key) {
            if (!isset($current_section[$key])) {
                throw new Exception('Config key '.implode('.', $keys).' not exist');
            }
            $current_section = &$current_section[$key];
        }
        return $current_section;
    }

    static public function get($key)
    {
        $args = array();
        if (func_num_args() == 1 && is_string($key)) {
            if (strpos($key, '.') !== false) {
                $args = explode('.', $key);
            } elseif (!static::isExistByArray(array($key))) {
                $args = array('Project', $key);
            } else {
                $args = array($key);
            }
        } elseif (is_array($key)) {
            $args = $key;
        } else {
            $args = func_get_args();
        }
        return static::getByArray($args);
    }
}

function RexConfig($key)
{
    return RexConfig::get($key);
}

function config($key)
{
    return RexConfig::get($key);
}




