<?php
/**
* Start javascript and init namespace
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_rexscript_start($params, &$smarty) {
    static $used_uin = array();
    $is_uin = class_exists('RexResponse');
    $uin = '';
    
    $section = $smarty->tpl_vars['workspace']->value;

    $result = '';
    if (!isset($params['ready']) || $params['ready']) {
        $result .= "jQuery(document).ready(function() {\n";
        $result .= "/* ".var_export($is_uin, true)."; ".var_export($used_uin, true)." */\n";
        if ($is_uin) {
            $uin = RexResponse::getDialogUin();
            $result .= "/* ".var_export($uin, true)." */\n";
            $section = $section == 'main' || $section == 'default' ? '' : '_'.$section;
            if ($uin && !isset($used_uin[$uin])) {
                $result .= "/* Hello1! */\n";
                $result .= "window.rex_dt_".$uin." = jQuery('#rex_dc_".$uin."');\n";
                $used_uin[$uin] = true;
            } elseif (!isset($used_uin['__global'.$section])) {
                $result .= "/* Hello2! */\n";
                $result .= "window.rex_dt___global".$section." = jQuery('#active_template_content".$section."');\n";
                $used_uin['__global'.$section] = true;
            }
        } 
    }

    $result .= "(function(){\n";

    $result .= "var mod = '".addslashes($smarty->tpl_vars['mod']->value)."'\n";
    $result .= "var act = '".addslashes($smarty->tpl_vars['act']->value)."'\n";
    $result .= "var task = '".addslashes($smarty->tpl_vars['task']->value)."'\n";
    $result .= "var workspace = '".addslashes($section)."'\n";
    $result .= "var layout = '".addslashes($smarty->tpl_vars['layout']->value)."'\n";
    $result .= "var environment = '".addslashes($smarty->tpl_vars['environment']->value)."'\n";
    $result .= "var user_environment = '".addslashes($smarty->tpl_vars['user_environment']->value)."'\n";
    $result .= "var base_path = '".addslashes($smarty->tpl_vars['base_path']->value)."'\n";

    if (!$is_uin) {
        return;
    }

    if ($uin) {
        $result .= "var uin = '".$uin."';\n";
    } else {
        $result .= "var uin = '';\n";
    }
    $result .= "var template = this;\n";
    if (!isset($params['ready']) || $params['ready'] and 1 != 1) {
        $result .= "template.find('*').die();\n";
        $result .= "template.rexClearIntervals();\n";
    }
    return $result;
}