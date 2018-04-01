<?php
define('DEBUG_URL', isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 10) == '/rexdebug/');
define('DEBUG_ENGINE', isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 17) == '/rexdebug/engine/');

if (DEBUG_ENGINE) {
    ini_set('display_errors', 'stdout');
    error_reporting(E_ALL);
    define('DEBUG_ECOMMAND', substr($_SERVER['REQUEST_URI'], 17, -1));
    require_once dirname(__FILE__).'/debugger.php';
    require_once dirname(__FILE__).'/engine.php';
    RexDebugEngine::process();
} elseif (DEBUG_URL) {
    setcookie('rexdebug', 'on');
    require_once dirname(__FILE__).'/layout.php';
} else {
    if (!isset($_COOKIE['rexdebug_uin']) || !isset($_COOKIE['rexdebug_session'])) {
        exit;
    }
    require_once dirname(__FILE__).'/engine.php';
    require_once dirname(__FILE__).'/debugger.php';
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    
    $uin = $_COOKIE['rexdebug_uin'];
    $session = $_COOKIE['rexdebug_session'];
    RexDebugger::run(DEBUG_FILE, $uin, $session);
}
exit;