<?php

$_SERVER['REQUEST_URI'] = '/?mod=order&act=checkFirstOrder';
$_SERVER['IS_CRON'] = true;
require_once dirname(__FILE__) . '/../index.php';