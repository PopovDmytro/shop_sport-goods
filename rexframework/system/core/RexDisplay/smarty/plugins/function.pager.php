<?php
/**
* HTML script tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_pager($params, &$smarty) {
    $result = '';
    
    if (!isset($params['delta'])) {
        $params['delta'] = 3;
    }
    
    if ($params && $params['pages'] > 1) {
        $result = '<div class="dg_pager_container">';
        
        if ($params['current'] > 1) {
            $result .= ' <a href="javascript: void(0);" class="dg_pager" page="1">First</a> ';
            $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.($params['current'] - 1).'">Prev</a> ';
        }
        
        $pager_start = $params['current'] <= $params['delta'] ? 1 : $params['current'] - $params['delta'];
        $pager_stop = $params['current'] > $params['pages'] - $params['delta'] ? $params['pages'] : $params['current'] + $params['delta'];
        
        if ($params['current'] > $params['delta'] + 1) {
            /*for ($i = 1; $i <= $params['delta'] + 1; ++$i) {
                $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.$i.'">'.$i.'</a> ';
            }*/
            $result .= ' ... ';
        }
        
        for ($i = $pager_start; $i <= $pager_stop; ++$i) {
            if ($i == $params['current']) {
                $result .= ' <span>['.$i.']</span> ';
            } else {
                $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.$i.'">'.$i.'</a> ';
            }
        }
        
        if ($params['current'] < $params['pages'] - $params['delta']) {
            $result .= ' ... ';
            /*for ($i = $params['pages'] - $params['delta']; $i <= $params['pages']; ++$i) {
                $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.$i.'">'.$i.'</a> ';
            }*/
        }

        if ($params['current'] < $params['pages']) {
            $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.($params['current'] + 1).'">Next</a> ';
            $result .= ' <a href="javascript: void(0);" class="dg_pager" page="'.$params['pages'].'">Last</a> ';
        }
        
        $result .= '</div>';
    }
    
    return $result;
}