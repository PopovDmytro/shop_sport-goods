<?php
namespace RexFramework;

use \RexConfig as RexConfig;
use \RexIni as RexIni;

/**
 * Class SettingsRextestController
 *
 * Settings Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  SettingsRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SettingsRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    
    //admin
    function testDefault()
    {
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertRegExp('#^\<\!DOCTYPE\s#', $result[0], 'Check for errors and exeptions');
        
        $settings_file = REX_ROOT.RexConfig::get('RexPath', 'settings').'settings.ini';
        $settings = RexIni::parse($settings_file);
        
        $settings_sections = array_keys($settings);
        
        foreach ($settings_sections as $key => $setting) {
            $this->assertRegExp('#'.$setting.'#', $result[0], 'Check settings section - "'.$setting.'" in page');
        }
    }
}