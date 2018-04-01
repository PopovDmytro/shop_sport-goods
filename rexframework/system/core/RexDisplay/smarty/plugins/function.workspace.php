<?php
/**
* Display section html
*
* @param array $params
* @param Smarty $smarty
*/
function smarty_function_workspace($params, &$smarty) {
    $section = isset($params['section']) ? $params['section'] : 'default';
    $section_html = '<div class="active_template_content" section="'.$section.'" id="active_template_content'.($section == 'default' ? '' : '_'.$section).'">'.
                        RexDisplay::getWorkspaces($section).
                    '</div>';
    return $section_html;
}