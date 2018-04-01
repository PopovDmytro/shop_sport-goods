<?php

$_SERVER['REQUEST_URI'] = '/rextest/?mod=test&act=start';
$_SERVER['IS_CRON'] = true;
require_once dirname(__FILE__).'/../index.php';