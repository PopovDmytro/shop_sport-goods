<?php
if ((isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 10) == '/rexdebug/') ||
    (isset($_COOKIE['rexdebug']) && $_COOKIE['rexdebug'] == 'on'))
{
    define('DEBUG_FILE', __FILE__);
    define('DEBUG_ROOT', dirname(__FILE__).'/../');
    include dirname(__FILE__).'/../rexframework/system/core/Debugger/index.php';
}
//REXDEBUG

ini_set('display_errors', 'stdout');
error_reporting(E_ALL);

if (isset($_COOKIE['bogdan'])) {
    ini_set('display_errors', '1');
}

define('HTDOCS', dirname(__FILE__).'/');
define('REX_ROOT', realpath(dirname(__FILE__).'/../').'/');
define('CONFIG_DIR', REX_ROOT.'rexframework/project/');
define('CORE_DIR', REX_ROOT.'rexframework/system/core/');
define('SITE_ROOT', HTDOCS);
/*define('SITE_ROOT', dirname(__FILE__).'/');
define('SITE_ROOT', dirname(__FILE__).'/');*/

/*define('API_ID',     '266568290215155');
define('API_SECRET', 'f3523929e7b6eb31532deaee032c0136');
define('FB_SCOPE',   'email, user_about_me, user_birthday, publish_stream, read_stream, publish_actions'); //xsat */

define('API_ID',     '1478406899067279');
define('API_SECRET', 'd5d773b5f192d49c02a704d754c63929');
define('FB_SCOPE',   'manage_pages,offline_access,publish_stream, read_stream, publish_checkins, publish_actions, sms, email, user_about_me, user_birthday');

set_include_path(REX_ROOT.'rexframework/system/components/XPear/pear/');

require_once CORE_DIR.'RexLoader.class.php';
RexLoader::initialize();

RexRunner::run();
//RexDisplay::display();
