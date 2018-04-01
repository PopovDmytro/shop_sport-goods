<?php

/**
 * Class RexSettings
 *
 * @author   MAG
 */
class RexSettings extends RexObject
{
    static protected $map;
    
    static public function init()
    {
        $default_settings_file = dirname(__FILE__).'/../settings.ini';
        $settings_file = REX_ROOT.RexConfig::get('RexPath', 'settings').'settings.ini';
        static::$map = array_merge_recursive(file_exists($default_settings_file) ? parse_ini_file($default_settings_file) : array(), 
            file_exists($settings_file) ? parse_ini_file($settings_file) : array());
    }
    
    static public function get($key)
    {
        $onmap = $key.'.value';
        if (isset(static::$map[$onmap])) {
            return static::$map[$onmap];
        } else {
            throw new Exception('Settings with key "'.$key.'" not exists');
        }
    }
}

function RexSettings($key)
{
    return RexSettings::get($key);
}

function settings($key)
{
    return RexSettings::get($key);
}