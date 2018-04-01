<?php
/**
* HTML a tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_block_a($params, $content, $template, &$repeat)
{
    if (!is_null($content)) {
        return $content.'</a>';
    }
    
    if (isset($params['href']) && substr($params['href'], 0, 11) != 'javascript:') {
        $layout = RexRoute::getLayoutByUrl($params['href']);
        $params['layout'] = $layout;
    }
    
    $result = '<a ';
    foreach ($params as $key => $value) {
        $result .= $key.'="'.$value.'" ';
    }
    $result .= '>';
    return $result;
}