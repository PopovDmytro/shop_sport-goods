<?php
/**
* HTML script tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_js($params, &$smarty) {
    if (!isset($params['type'])) {
        $params['type'] = 'text/javascript';
    }
    if (isset($params['subfolder'])) {
        $subfolder = $params['subfolder'];
        unset($params['subfolder']);
    } else {
        $subfolder = 'js';
    }
    $result = '<script ';
    foreach ($params as $key => $value) {
        if ($key == 'src') {
            $value = RexDisplay::getContentPath($value, $subfolder);
            if (!$value) {
                return false;
            }
        }
        $result .= $key.'="'.$value.'" ';
    }
    $result .= '></script>';
    return $result;
}