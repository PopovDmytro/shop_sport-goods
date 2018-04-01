<?php
/**
* Start style and init namespace
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_rexstyle_start($params, &$smarty) {
    $is_uin = class_exists('RexResponse');

    if ($is_uin) {
        ob_start();
    }
}
?>