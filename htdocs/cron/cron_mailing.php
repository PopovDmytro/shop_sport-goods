<?php
/**
 * Created by PhpStorm.
 * User: Volkov
 * Date: 19.10.2017
 * Time: 10:04
 */

$_SERVER['REQUEST_URI'] = '/admin/?mod=home&act=mailingHandler';
$_SERVER['IS_CRON'] = true;
require_once dirname(__FILE__) . '/../index.php';