<?php
/**
* HTML img tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_img($params, &$smarty) {
    if (isset($params['subfolder'])) {
        $subfolder = $params['subfolder'];
        unset($params['subfolder']);
    } else {
        $subfolder = 'img';
    }
    $result = '<img ';
    foreach ($params as $key => $value) {
        if ($key == 'src') {
            $value = RexDisplay::getContentPath($value, $subfolder);
        }
        $result .= $key.'="'.$value.'" ';
    }
    $result .= '/>';
    return $result;
}