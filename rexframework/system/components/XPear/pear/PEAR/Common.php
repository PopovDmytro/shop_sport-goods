<?php
  require_once 'PEAR.php'; 
 define('PEAR_COMMON_ERROR_INVALIDPHP', 1);
define('_PEAR_COMMON_PACKAGE_NAME_PREG', '[A-Za-z][a-zA-Z0-9_]+');
define('PEAR_COMMON_PACKAGE_NAME_PREG', '/^' . _PEAR_COMMON_PACKAGE_NAME_PREG . '$/'); 
define('_PEAR_COMMON_PACKAGE_VERSION_PREG', '\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?');
define('PEAR_COMMON_PACKAGE_VERSION_PREG', '/^' . _PEAR_COMMON_PACKAGE_VERSION_PREG . '$/i'); 
define('_PEAR_COMMON_PACKAGE_DOWNLOAD_PREG', '(' . _PEAR_COMMON_PACKAGE_NAME_PREG .
 ')(-([.0-9a-zA-Z]+))?');
define('PEAR_COMMON_PACKAGE_DOWNLOAD_PREG', '/^' . _PEAR_COMMON_PACKAGE_DOWNLOAD_PREG .
 '$/');
define('_PEAR_CHANNELS_NAME_PREG', '[A-Za-z][a-zA-Z0-9\.]+');
define('PEAR_CHANNELS_NAME_PREG', '/^' . _PEAR_CHANNELS_NAME_PREG . '$/'); 
define('_PEAR_CHANNELS_SERVER_PREG', '[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*(\/[a-zA-Z0-9\-]+)*');
define('PEAR_CHANNELS_SERVER_PREG', '/^' . _PEAR_CHANNELS_SERVER_PREG . '$/i');
define('_PEAR_CHANNELS_PACKAGE_PREG', '(' ._PEAR_CHANNELS_SERVER_PREG . ')\/('
 . _PEAR_COMMON_PACKAGE_NAME_PREG . ')');
define('PEAR_CHANNELS_PACKAGE_PREG', '/^' . _PEAR_CHANNELS_PACKAGE_PREG . '$/i');
define('_PEAR_COMMON_CHANNEL_DOWNLOAD_PREG', '(' . _PEAR_CHANNELS_NAME_PREG . ')::('
 . _PEAR_COMMON_PACKAGE_NAME_PREG . ')(-([.0-9a-zA-Z]+))?');
define('PEAR_COMMON_CHANNEL_DOWNLOAD_PREG', '/^' . _PEAR_COMMON_CHANNEL_DOWNLOAD_PREG . '$/');
 $GLOBALS['_PEAR_Common_tempfiles'] = array();
 $GLOBALS['_PEAR_Common_maintainer_roles'] = array('lead','developer','contributor','helper');
 $GLOBALS['_PEAR_Common_release_states'] = array('alpha','beta','stable','snapshot','devel');
 $GLOBALS['_PEAR_Common_dependency_types'] = array('pkg','ext','php','prog','ldlib','rtlib','os','websrv','sapi');
 $GLOBALS['_PEAR_Common_dependency_relations'] = array('has','eq','lt','le','gt','ge','not', 'ne');
 $GLOBALS['_PEAR_Common_file_roles'] = array('php','ext','test','doc','data','src','script');
 $GLOBALS['_PEAR_Common_replacement_types'] = array('php-const', 'pear-config', 'package-info');
 $GLOBALS['_PEAR_Common_provide_types'] = array('ext', 'prog', 'class', 'function', 'feature', 'api');
 $GLOBALS['_PEAR_Common_script_phases'] = array('pre-install', 'post-install', 'pre-uninstall', 'post-uninstall', 'pre-build', 'post-build', 'pre-configure', 'post-configure', 'pre-setup', 'post-setup'); 
 class PEAR_Common extends PEAR
{ 
  var $element_stack = array();
 var $current_element;
 var $current_attributes = array();
 var $pkginfo = array();
 var $ui = null;
 var $config = null;
var $current_path = null;
 var $source_analyzer = null;
 var $_validPackageFile;  
  function PEAR_Common()
 {
 parent::PEAR();
$this->config = &PEAR_Config::singleton();
$this->debug = $this->config->get('verbose');
}  
  function _PEAR_Common()
 {  
 $tempfiles =& $GLOBALS['_PEAR_Common_tempfiles'];
while ($file = array_shift($tempfiles)) {
 if (@is_dir($file)) {
 if (!class_exists('System')) {
 require_once 'System.php';
}
System::rm(array('-rf', $file));
} elseif (file_exists($file)) {
 unlink($file);
}
}
}  
  function addTempFile($file)
 {
 if (!class_exists('PEAR_Frontend')) {
 require_once 'PEAR/Frontend.php';
}
PEAR_Frontend::addTempFile($file);
}  
  function mkDirHier($dir)
 {
 $this->log(2, "+ create dir $dir");
if (!class_exists('System')) {
 require_once 'System.php';
}
return System::mkDir(array('-p', $dir));
}  
  function log($level, $msg, $append_crlf = true)
 {
 if ($this->debug >= $level) {
 if (!class_exists('PEAR_Frontend')) {
 require_once 'PEAR/Frontend.php';
}
$ui = &PEAR_Frontend::singleton();
if (is_a($ui, 'PEAR_Frontend')) {
 $ui->log($msg, $append_crlf);
} else {
 print "$msg\n";
}
}
}  
  function mkTempDir($tmpdir = '')
 {
 if ($tmpdir) {
 $topt = array('-t', $tmpdir);
} else {
 $topt = array();
}
$topt = array_merge($topt, array('-d', 'pear'));
if (!class_exists('System')) {
 require_once 'System.php';
}
if (!$tmpdir = System::mktemp($topt)) {
 return false;
}
$this->addTempFile($tmpdir);
return $tmpdir;
}  
  function setFrontendObject(&$ui)
 {
 $this->ui = &$ui;
}  
  function infoFromTgzFile($file)
 {
 $packagefile = &new PEAR_PackageFile($this->config);
$pf = &$packagefile->fromTgzFile($file, PEAR_VALIDATE_NORMAL);
if (PEAR::isError($pf)) {
 $errs = $pf->getUserinfo();
if (is_array($errs)) {
 foreach ($errs as $error) {
 $e = $this->raiseError($error['message'], $error['code'], null, null, $error);
}
}
return $pf;
}
return $this->_postProcessValidPackagexml($pf);
}  
  function infoFromDescriptionFile($descfile)
 {
 $packagefile = &new PEAR_PackageFile($this->config);
$pf = &$packagefile->fromPackageFile($descfile, PEAR_VALIDATE_NORMAL);
if (PEAR::isError($pf)) {
 $errs = $pf->getUserinfo();
if (is_array($errs)) {
 foreach ($errs as $error) {
 $e = $this->raiseError($error['message'], $error['code'], null, null, $error);
}
}
return $pf;
}
return $this->_postProcessValidPackagexml($pf);
}  
  function infoFromString($data)
 {
 $packagefile = &new PEAR_PackageFile($this->config);
$pf = &$packagefile->fromXmlString($data, PEAR_VALIDATE_NORMAL, false);
if (PEAR::isError($pf)) {
 $errs = $pf->getUserinfo();
if (is_array($errs)) {
 foreach ($errs as $error) {
 $e = $this->raiseError($error['message'], $error['code'], null, null, $error);
}
}
return $pf;
}
return $this->_postProcessValidPackagexml($pf);
} 
  function _postProcessValidPackagexml(&$pf)
 {
 if (is_a($pf, 'PEAR_PackageFile_v2')) {  
 $arr = $pf->toArray(true);
$arr = array_merge($arr, $arr['old']);
unset($arr['old']);
unset($arr['xsdversion']);
unset($arr['contents']);
unset($arr['compatible']);
unset($arr['channel']);
unset($arr['uri']);
unset($arr['dependencies']);
unset($arr['phprelease']);
unset($arr['extsrcrelease']);
unset($arr['zendextsrcrelease']);
unset($arr['extbinrelease']);
unset($arr['zendextbinrelease']);
unset($arr['bundle']);
unset($arr['lead']);
unset($arr['developer']);
unset($arr['helper']);
unset($arr['contributor']);
$arr['filelist'] = $pf->getFilelist();
$this->pkginfo = $arr;
return $arr;
} else {
 $this->pkginfo = $pf->toArray();
return $this->pkginfo;
}
} 
  function infoFromAny($info)
 {
 if (is_string($info) && file_exists($info)) {
 $packagefile = &new PEAR_PackageFile($this->config);
$pf = &$packagefile->fromAnyFile($info, PEAR_VALIDATE_NORMAL);
if (PEAR::isError($pf)) {
 $errs = $pf->getUserinfo();
if (is_array($errs)) {
 foreach ($errs as $error) {
 $e = $this->raiseError($error['message'], $error['code'], null, null, $error);
}
}
return $pf;
}
return $this->_postProcessValidPackagexml($pf);
}
return $info;
}  
  function xmlFromInfo($pkginfo)
 {
 $config = &PEAR_Config::singleton();
$packagefile = &new PEAR_PackageFile($config);
$pf = &$packagefile->fromArray($pkginfo);
$gen = &$pf->getDefaultGenerator();
return $gen->toXml(PEAR_VALIDATE_PACKAGING);
}  
  function validatePackageInfo($info, &$errors, &$warnings, $dir_prefix = '')
 {
 $config = &PEAR_Config::singleton();
$packagefile = &new PEAR_PackageFile($config);
PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
if (strpos($info, '<?xml') !== false) {
 $pf = &$packagefile->fromXmlString($info, PEAR_VALIDATE_NORMAL, '');
} else {
 $pf = &$packagefile->fromAnyFile($info, PEAR_VALIDATE_NORMAL);
}
PEAR::staticPopErrorHandling();
if (PEAR::isError($pf)) {
 $errs = $pf->getUserinfo();
if (is_array($errs)) {
 foreach ($errs as $error) {
 if ($error['level'] == 'error') {
 $errors[] = $error['message'];
} else {
 $warnings[] = $error['message'];
}
}
}
return false;
}
return true;
}  
  function buildProvidesArray($srcinfo)
 {
 $file = basename($srcinfo['source_file']);
$pn = '';
if (isset($this->_packageName)) {
 $pn = $this->_packageName;
}
$pnl = strlen($pn);
foreach ($srcinfo['declared_classes'] as $class) {
 $key = "class;$class";
if (isset($this->pkginfo['provides'][$key])) {
 continue;
}
$this->pkginfo['provides'][$key] =
 array('file'=> $file, 'type' => 'class', 'name' => $class);
if (isset($srcinfo['inheritance'][$class])) {
 $this->pkginfo['provides'][$key]['extends'] =
 $srcinfo['inheritance'][$class];
}
}
foreach ($srcinfo['declared_methods'] as $class => $methods) {
 foreach ($methods as $method) {
 $function = "$class::$method";
$key = "function;$function";
if ($method{0} == '_' || !strcasecmp($method, $class) ||
 isset($this->pkginfo['provides'][$key])) {
 continue;
}
$this->pkginfo['provides'][$key] =
 array('file'=> $file, 'type' => 'function', 'name' => $function);
}
}
foreach ($srcinfo['declared_functions'] as $function) {
 $key = "function;$function";
if ($function{0} == '_' || isset($this->pkginfo['provides'][$key])) {
 continue;
}
if (!strstr($function, '::') && strncasecmp($function, $pn, $pnl)) {
 $warnings[] = "in1 " . $file . ": function \"$function\" not prefixed with package name \"$pn\"";
}
$this->pkginfo['provides'][$key] =
 array('file'=> $file, 'type' => 'function', 'name' => $function);
}
}  
  function analyzeSourceCode($file)
 {
 if (!function_exists("token_get_all")) {
 return false;
}
if (!defined('T_DOC_COMMENT')) {
 define('T_DOC_COMMENT', T_COMMENT);
}
if (!defined('T_INTERFACE')) {
 define('T_INTERFACE', -1);
}
if (!defined('T_IMPLEMENTS')) {
 define('T_IMPLEMENTS', -1);
}
if (!$fp = @fopen($file, "r")) {
 return false;
}
fclose($fp);
$contents = file_get_contents($file);
$tokens = token_get_all($contents);
 $look_for = 0;
$paren_level = 0;
$bracket_level = 0;
$brace_level = 0;
$lastphpdoc = '';
$current_class = '';
$current_interface = '';
$current_class_level = -1;
$current_function = '';
$current_function_level = -1;
$declared_classes = array();
$declared_interfaces = array();
$declared_functions = array();
$declared_methods = array();
$used_classes = array();
$used_functions = array();
$extends = array();
$implements = array();
$nodeps = array();
$inquote = false;
$interface = false;
for ($i = 0; $i < sizeof($tokens); $i++) {
 if (is_array($tokens[$i])) {
 list($token, $data) = $tokens[$i];
} else {
 $token = $tokens[$i];
$data = '';
}
if ($inquote) {
 if ($token != '"') {
 continue;
} else {
 $inquote = false;
continue;
}
}
switch ($token) {
 case T_WHITESPACE:
 continue;
case ';':
 if ($interface) {
 $current_function = '';
$current_function_level = -1;
}
break;
case '"':
 $inquote = true;
break;
case T_CURLY_OPEN:
 case T_DOLLAR_OPEN_CURLY_BRACES:
 case '{': $brace_level++; continue 2;
case '}':
 $brace_level--;
if ($current_class_level == $brace_level) {
 $current_class = '';
$current_class_level = -1;
}
if ($current_function_level == $brace_level) {
 $current_function = '';
$current_function_level = -1;
}
continue 2;
case '[': $bracket_level++; continue 2;
case ']': $bracket_level--; continue 2;
case '(': $paren_level++; continue 2;
case ')': $paren_level--; continue 2;
case T_INTERFACE:
 $interface = true;
case T_CLASS:
 if (($current_class_level != -1) || ($current_function_level != -1)) {
 PEAR::staticRaiseError("Parser error: invalid PHP found in file \"$file\"",
 PEAR_COMMON_ERROR_INVALIDPHP);
return false;
}
case T_FUNCTION:
 case T_NEW:
 case T_EXTENDS:
 case T_IMPLEMENTS:
 $look_for = $token;
continue 2;
case T_STRING:
 if (version_compare(zend_version(), '2.0', '<')) {
 if (in_array(strtolower($data),
 array('public', 'private', 'protected', 'abstract',
 'interface', 'implements', 'throw') 
 )) {
 PEAR::staticRaiseError('Error: PHP5 token encountered in ' . $file . 
 'packaging should be done in PHP 5');
return false;
}
}
if ($look_for == T_CLASS) {
 $current_class = $data;
$current_class_level = $brace_level;
$declared_classes[] = $current_class;
} elseif ($look_for == T_INTERFACE) {
 $current_interface = $data;
$current_class_level = $brace_level;
$declared_interfaces[] = $current_interface;
} elseif ($look_for == T_IMPLEMENTS) {
 $implements[$current_class] = $data;
} elseif ($look_for == T_EXTENDS) {
 $extends[$current_class] = $data;
} elseif ($look_for == T_FUNCTION) {
 if ($current_class) {
 $current_function = "$current_class::$data";
$declared_methods[$current_class][] = $data;
} elseif ($current_interface) {
 $current_function = "$current_interface::$data";
$declared_methods[$current_interface][] = $data;
} else {
 $current_function = $data;
$declared_functions[] = $current_function;
}
$current_function_level = $brace_level;
$m = array();
} elseif ($look_for == T_NEW) {
 $used_classes[$data] = true;
}
$look_for = 0;
continue 2;
case T_VARIABLE:
 $look_for = 0;
continue 2;
case T_DOC_COMMENT:
 case T_COMMENT:
 if (preg_match('!^/\*\*\s!', $data)) {
 $lastphpdoc = $data;
if (preg_match_all('/@nodep\s+(\S+)/', $lastphpdoc, $m)) {
 $nodeps = array_merge($nodeps, $m[1]);
}
}
continue 2;
case T_DOUBLE_COLON:
 if (!($tokens[$i - 1][0] == T_WHITESPACE || $tokens[$i - 1][0] == T_STRING)) {
 PEAR::staticRaiseError("Parser error: invalid PHP found in file \"$file\"",
 PEAR_COMMON_ERROR_INVALIDPHP);
return false;
}
$class = $tokens[$i - 1][1];
if (strtolower($class) != 'parent') {
 $used_classes[$class] = true;
}
continue 2;
}
}
return array(
 "source_file" => $file,
 "declared_classes" => $declared_classes,
 "declared_interfaces" => $declared_interfaces,
 "declared_methods" => $declared_methods,
 "declared_functions" => $declared_functions,
 "used_classes" => array_diff(array_keys($used_classes), $nodeps),
 "inheritance" => $extends,
 "implements" => $implements,
 );
}  
  function betterStates($state, $include = false)
 {
 static $states = array('snapshot', 'devel', 'alpha', 'beta', 'stable');
$i = array_search($state, $states);
if ($i === false) {
 return false;
}
if ($include) {
 $i--;
}
return array_slice($states, $i + 1);
}  
 function detectDependencies($any, $status_callback = null)
 {
 if (!function_exists("token_get_all")) {
 return false;
}
if (PEAR::isError($info = $this->infoFromAny($any))) {
 return $this->raiseError($info);
}
if (!is_array($info)) {
 return false;
}
$deps = array();
$used_c = $decl_c = $decl_f = $decl_m = array();
foreach ($info['filelist'] as $file => $fa) {
 $tmp = $this->analyzeSourceCode($file);
$used_c = @array_merge($used_c, $tmp['used_classes']);
$decl_c = @array_merge($decl_c, $tmp['declared_classes']);
$decl_f = @array_merge($decl_f, $tmp['declared_functions']);
$decl_m = @array_merge($decl_m, $tmp['declared_methods']);
$inheri = @array_merge($inheri, $tmp['inheritance']);
}
$used_c = array_unique($used_c);
$decl_c = array_unique($decl_c);
$undecl_c = array_diff($used_c, $decl_c);
return array('used_classes' => $used_c,
 'declared_classes' => $decl_c,
 'declared_methods' => $decl_m,
 'declared_functions' => $decl_f,
 'undeclared_classes' => $undecl_c,
 'inheritance' => $inheri,
 );
}  
  function getUserRoles()
 {
 return $GLOBALS['_PEAR_Common_maintainer_roles'];
}  
  function getReleaseStates()
 {
 return $GLOBALS['_PEAR_Common_release_states'];
}  
  function getDependencyTypes()
 {
 return $GLOBALS['_PEAR_Common_dependency_types'];
}  
  function getDependencyRelations()
 {
 return $GLOBALS['_PEAR_Common_dependency_relations'];
}  
  function getFileRoles()
 {
 return $GLOBALS['_PEAR_Common_file_roles'];
}  
  function getReplacementTypes()
 {
 return $GLOBALS['_PEAR_Common_replacement_types'];
}  
  function getProvideTypes()
 {
 return $GLOBALS['_PEAR_Common_provide_types'];
}  
  function getScriptPhases()
 {
 return $GLOBALS['_PEAR_Common_script_phases'];
}  
  function validPackageName($name)
 {
 return (bool)preg_match(PEAR_COMMON_PACKAGE_NAME_PREG, $name);
}  
  function validPackageVersion($ver)
 {
 return (bool)preg_match(PEAR_COMMON_PACKAGE_VERSION_PREG, $ver);
}  
  function downloadHttp($url, &$ui, $save_dir = '.', $callback = null)
 {
 if (!class_exists('PEAR_Downloader')) {
 require_once 'PEAR/Downloader.php';
}
return PEAR_Downloader::downloadHttp($url, $ui, $save_dir, $callback);
} 
  function isIncludeable($path)
 {
 if (file_exists($path) && is_readable($path)) {
 return true;
}
$ipath = explode(PATH_SEPARATOR, ini_get('include_path'));
foreach ($ipath as $include) {
 $test = realpath($include . DIRECTORY_SEPARATOR . $path);
if (file_exists($test) && is_readable($test)) {
 return true;
}
}
return false;
}
}
require_once 'PEAR/Config.php';
require_once 'PEAR/PackageFile.php';
?>