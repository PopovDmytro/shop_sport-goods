<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexConfig as RexConfig;
use \RexIni as RexIni;
use \RexDisplay as RexDisplay;
use \XDatagrid as XDatagrid;
use \Request as Request;

/**
 * Class SettingsAdminController
 *
 * @author   MAG
 */
class SettingsAdminController extends ParentAdminController
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    
    function getDefault()
    {
        $settings_file = REX_ROOT.RexConfig::get('RexPath', 'settings').'settings.ini';
        $settings = RexIni::parse($settings_file);
        
        if (!$this->task or $this->task == 'default') {
            $this->task = 1;
        }
        unset($settings['Настройки Скина']);
        $settings_sections = array_keys($settings);
        $active_setting_section = $settings_sections[$this->task - 1];
        
        RexDisplay::assign('settings_sections', $settings_sections);
        RexDisplay::assign('task', $this->task);
        
        $setSettings = Request::get('settings', array());
        if ($setSettings) {
            foreach ($setSettings as $key => $value) {
                $type = $settings[$active_setting_section][$key]['type'];
                switch ($type) {
                    case 'integer':
                        $value = intval($value);
                        break;
                    case 'double':
                        $value = doubleval($value);
                        break;
                    case 'string':
                    case 'text':
                        break;
                    default:
                        throw new Exception('Wrong settings type "'.$type.'"');
                }
                $settings[$active_setting_section][$key]['value'] = $value;
            }
            RexIni::write($settings_file, $settings);
        }
        
        $settingsList = $settings[$active_setting_section];
        foreach ($settingsList as $key => $value) {
            $settingsList[$key]['key'] = $key;
        }
        $settingsList = array_values($settingsList);

        $DG = new XDatagrid();
        $DG->isNumerable(false);
        $DG->isHeader(true); 
        $DG->isFillLines(false);
        $DG->createDatagrid($settingsList, sizeof($settingsList), 'RexDG');

        
        $DG->addColumn('Имя', 'name', 'name');
        $DG->addColumn('Значение', 'value', 'value', null, null, array($this,'_DGValue'));
        $DG->addColumn('Описание', 'description', 'description');
        $DG->addColumn('Ключ', 'key', 'key');
        
        $dg = $DG->renderer();
        RexDisplay::assign('dg', $dg);
    }
    
    function _DGValue($param)
    {   
        if ($param['record']['key'] == 'ajax_paging'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'filter_instant'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'filter_reduce'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'catalog_block'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="block" '.($param['record']['value'] == "block" ? "checked" : "").'> block  <input type="radio" name="settings['.$param['record']['key'].']" value="list" '.($param['record']['value'] == "list" ? "checked" : "").'> list';
        }     
        if ($param['record']['key'] == 'phpsender_registraton'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'phpsender_adminlogin'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'phpsender_userlogin'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        if ($param['record']['key'] == 'phpsender_order'){
            return '<input type="radio" name="settings['.$param['record']['key'].']" value="true" '.($param['record']['value'] == "true" ? "checked" : "").'> Да  <input type="radio" name="settings['.$param['record']['key'].']" value="false" '.($param['record']['value'] == "false" ? "checked" : "").'> Нет';
        }
        return '<input type="text" name="settings['.$param['record']['key'].']" value="'.$param['record']['value'].'" style="width: 400px;">';
    }
}