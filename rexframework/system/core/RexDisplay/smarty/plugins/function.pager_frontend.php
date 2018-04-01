<?php
/**
* HTML script tag implementation
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_pager_frontend($params, &$smarty) {
    $result = '';
    $task = $smarty->tpl_vars['task']->value;
    $lang = RexLang::getLang();
    
    if ($params && $params['pages'] > 1) {
        $result = '<div class="dg_pager_container">';
        
        if ($params['current'] > 1) {
            $result .= ' <a class="dg_pager" href="'.RexRoute(array( 'mod' => $params['mod'] , 'act' => $params['act'], 'page' => ($params['current'] - 1), 'lang' => $lang, 'task' => $task)).'">Prev</a> ';
        }
        
        $pager_start = $params['current'] <= 6 ? 1 : $params['current'] - 2;
        $pager_stop = $params['current'] > $params['pages'] - 6 ? $params['pages'] : $params['current'] + 2;
        
        if ($params['current'] > 6) {
            for ($i = 1; $i <= 3; ++$i) {
                $result .= ' <a class="dg_pager" href="'.RexRoute(array( 'mod' => $params['mod'] , 'act' => $params['act'], 'page' => $i, 'lang' => $lang, 'task' => $task)).'">'.$i.'</a> ';
            }
            $result .= ' ... ';
        }
        
        for ($i = $pager_start; $i <= $pager_stop; ++$i) {
            if ($i == $params['current']) {
                $result .= ' <span>['.$i.']</span> ';
            } else {
               $result .= ' <a class="dg_pager" href="'.RexRoute(array( 'mod' => $params['mod'] , 'act' => $params['act'], 'page' => $i, 'lang' => $lang, 'task' => $task)).'">'.$i.'</a> ';
            }
        }

        if ($params['current'] <= $params['pages'] - 6) {
            $result .= ' ... ';
            for ($i = $params['pages'] - 2; $i <= $params['pages']; ++$i) {
                $result .= ' <a class="dg_pager" href="'.RexRoute(array( 'mod' => $params['mod'] , 'act' => $params['act'], 'page' => $i, 'lang' => $lang, 'task' => $task)).'">'.$i.'</a> ';
            }
        }

        if ($params['current'] < $params['pages']) {
            $result .= ' <a class="dg_pager" href="'.RexRoute(array( 'mod' => $params['mod'] , 'act' => $params['act'], 'page' => ($params['current'] + 1), 'lang' => $lang, 'task' => $task)).'">Next</a> ';
        }
        
        $result .= '</div>';
    }
    
    return $result;
}