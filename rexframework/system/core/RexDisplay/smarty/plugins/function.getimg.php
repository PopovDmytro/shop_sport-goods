<?php
/**
* magic resize image function
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_getimg($params, &$smarty) {
    return XImage::getImg($params);    
}