<?php 

function smarty_modifier_mb_strlen($string, $charset='UTF-8')
{
    return mb_strlen($string, $charset);
}