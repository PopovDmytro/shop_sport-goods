<?php
/**
* Get url to site action
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_url($params, &$smarty) {
    return RexRoute::getUrl($params);
}