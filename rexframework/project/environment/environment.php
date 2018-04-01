<?php 

$environment = 'frontend';
if (isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 7) == '/admin/') {
    $environment = 'admin';
} elseif (isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 9) == '/rextest/') {
    $environment = 'rextest';
}

return $environment;