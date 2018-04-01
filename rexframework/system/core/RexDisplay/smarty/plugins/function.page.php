<?php
    function smarty_function_page($params, &$smarty)
    {
        if (isset($params['section'])) {
            return RexPage::callMethod($params['type'], $params['section']);	
        } else {
            return RexPage::callMethod($params['type']);
        }
    }
?>
