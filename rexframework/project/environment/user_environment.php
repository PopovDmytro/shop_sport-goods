<?php

$user = XSession::get('user', false);
$user_environment = $user ? $user->role : (isset($_SERVER['IS_CRON']) && $_SERVER['IS_CRON'] ? 'admin' : 'nologin');

return $user_environment;