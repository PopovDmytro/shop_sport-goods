<?php
/**
* Stop javascript
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_rexscript_stop($params, &$smarty) {
    $result = '';
    $section = $smarty->tpl_vars['workspace']->value;
    if (!class_exists('RexResponse') || (isset($params['ready']) && !$params['ready'])) {
        $result .= "})();\n";
    } else {
        $uin = RexResponse::getDialogUin();
        $section = $section == 'main' || $section == 'default' ? '' : '_'.$section;
        if ($uin) {
            $result .= "}).call(window.rex_dt_".$uin.");\n";
        } else {
            $result .= "}).call(window.rex_dt___global".$section.");\n";
        }
        $result .= "});\n";
    }
    return $result;
}