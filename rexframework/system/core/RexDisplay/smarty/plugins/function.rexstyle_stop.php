<?php
/**
* Stop javascript
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_rexstyle_stop($params, &$smarty) {
    $is_uin = class_exists('RexResponse');

    if ($is_uin) {
        $uin = RexResponse::getDialogUin();
        $style = ob_get_clean();
        
        $style = preg_replace('#\/\*[^/]*\*\/#', '', $style);
        if ($uin) {
            $style = substr(preg_replace('/(}|,)([\s]+)([^\s])/', "$1$2#rex_dc_".$uin." $3", '} '.$style), 2);
        } else {
            $section = $smarty->tpl_vars['workspace']->value;
            $style = substr(preg_replace('/(}|,)([\s]+)([^\s])/', "$1$2#active_template_content".
                ($section != 'default' ? "_".$section : '')." $3", '} '.$style), 2);
        }
        return $style;
    }
}
?>