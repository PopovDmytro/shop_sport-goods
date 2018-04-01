<?php
/**
* HTML link tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_css($params, &$smarty) {
    if (!isset($params['rel'])) {
        $params['rel'] = 'stylesheet';
    }
    if (!isset($params['type'])) {
        $params['type'] = 'text/css';
    }
    if (isset($params['subfolder'])) {
        $subfolder = $params['subfolder'];
        unset($params['subfolder']);
    } else {
        $subfolder = 'css';
    }
    $result = '<link ';
    foreach ($params as $key => $value) {
        if ($key == 'href' || $key == 'src') {
            $key = 'href';
            $value = RexDisplay::getContentPath($value, $subfolder);
        }
        $result .= $key.'="'.$value.'" ';
    }
    $result .= '/>';
    return $result;
}