<?php
  require_once '../../components/XPear/pear/PEAR.php/system/component../../components/XPear/pear/Console/Getopt.phpmponents/Getopt.phpre_once 'Console/Getopt.php';
$GLOBALS['_System_temp_files'] = array();
 class System
{
  function _parseArgs($argv, $short_options, $long_options = null)
 {
 if (!is_array($argv) && $argv !== null) {
 $argv = preg_split('/\s+/', $argv, -1, PREG_SPLIT_NO_EMPTY);
}
return Console_Getopt::getopt2($argv, $short_options);
}
 function raiseError($error)
 {
 if (PEAR::isError($error)) {
 $error = $error->getMessage();
}
trigger_error($error, E_USER_WARNING);
return false;
}
 function _dirToStruct($sPath, $maxinst, $aktinst = 0)
 {
 $struct = array('dirs' => array(), 'files' => array());
if (($dir = @opendir($sPath)) === false) {
 System::raiseError("Could not open dir $sPath");
return $struct; 
 }
$struct['dirs'][] = $sPath = realpath($sPath); 
 $list = array();
while (false !== ($file = readdir($dir))) {
 if ($file != '.' && $file != '..') {
 $list[] = $file;
}
}
closedir($dir);
sort($list);
if ($aktinst < $maxinst || $maxinst == 0) {
 foreach($list as $val) {
 $path = $sPath . DIRECTORY_SEPARATOR . $val;
if (is_dir($path) && !is_link($path)) {
 $tmp = System::_dirToStruct($path, $maxinst, $aktinst+1);
$struct = array_merge_recursive($tmp, $struct);
} else {
 $struct['files'][] = $path;
}
}
}
return $struct;
}
 function _multipleToStruct($files)
 {
 $struct = array('dirs' => array(), 'files' => array());
settype($files, 'array');
foreach ($files as $file) {
 if (is_dir($file) && !is_link($file)) {
 $tmp = System::_dirToStruct($file, 0);
$struct = array_merge_recursive($tmp, $struct);
} else {
 $struct['files'][] = $file;
}
}
return $struct;
}
 function rm($args)
 {
 $opts = System::_parseArgs($args, 'rf'); 
 if (PEAR::isError($opts)) {
 return System::raiseError($opts);
}
foreach($opts[0] as $opt) {
 if ($opt[0] == 'r') {
 $do_recursive = true;
}
}
$ret = true;
if (isset($do_recursive)) {
 $struct = System::_multipleToStruct($opts[1]);
foreach($struct['files'] as $file) {
 if (!@unlink($file)) {
 $ret = false;
}
}
foreach($struct['dirs'] as $dir) {
 if (!@rmdir($dir)) {
 $ret = false;
}
}
} else {
 foreach ($opts[1] as $file) {
 $delete = (is_dir($file)) ? 'rmdir' : 'unlink';
if (!@$delete($file)) {
 $ret = false;
}
}
}
return $ret;
}
 function mkDir($args)
 {
 $opts = System::_parseArgs($args, 'pm:');
if (PEAR::isError($opts)) {
 return System::raiseError($opts);
}
$mode = 0777; 
 foreach($opts[0] as $opt) {
 if ($opt[0] == 'p') {
 $create_parents = true;
} elseif($opt[0] == 'm') {  
 if (strlen($opt[1]) && $opt[1]{0} == '0') {
 $opt[1] = octdec($opt[1]);
} else { 
 $opt[1] += 0;
}
$mode = $opt[1];
}
}
$ret = true;
if (isset($create_parents)) {
 foreach($opts[1] as $dir) {
 $dirstack = array();
while ((!file_exists($dir) || !is_dir($dir)) &&
 $dir != DIRECTORY_SEPARATOR) {
 array_unshift($dirstack, $dir);
$dir = dirname($dir);
}
while ($newdir = array_shift($dirstack)) {
 if (!is_writeable(dirname($newdir))) {
 $ret = false;
break;
}
if (!mkdir($newdir, $mode)) {
 $ret = false;
}
}
}
} else {
 foreach($opts[1] as $dir) {
 if ((@file_exists($dir) || !is_dir($dir)) && !mkdir($dir, $mode)) {
 $ret = false;
}
}
}
return $ret;
}
 function &cat($args)
 {
 $ret = null;
$files = array();
if (!is_array($args)) {
 $args = preg_split('/\s+/', $args, -1, PREG_SPLIT_NO_EMPTY);
}
for($i=0; $i < count($args); $i++) {
 if ($args[$i] == '>') {
 $mode = 'wb';
$outputfile = $args[$i+1];
break;
} elseif ($args[$i] == '>>') {
 $mode = 'ab+';
$outputfile = $args[$i+1];
break;
} else {
 $files[] = $args[$i];
}
}
$outputfd = false;
if (isset($mode)) {
 if (!$outputfd = fopen($outputfile, $mode)) {
 $err = System::raiseError("Could not open $outputfile");
return $err;
}
$ret = true;
}
foreach ($files as $file) {
 if (!$fd = fopen($file, 'r')) {
 System::raiseError("Could not open $file");
continue;
}
while ($cont = fread($fd, 2048)) {
 if (is_resource($outputfd)) {
 fwrite($outputfd, $cont);
} else {
 $ret .= $cont;
}
}
fclose($fd);
}
if (is_resource($outputfd)) {
 fclose($outputfd);
}
return $ret;
}
 function mktemp($args = null)
 {
 static $first_time = true;
$opts = System::_parseArgs($args, 't:d');
if (PEAR::isError($opts)) {
 return System::raiseError($opts);
}
foreach($opts[0] as $opt) {
 if($opt[0] == 'd') {
 $tmp_is_dir = true;
} elseif($opt[0] == 't') {
 $tmpdir = $opt[1];
}
}
$prefix = (isset($opts[1][0])) ? $opts[1][0] : 'tmp';
if (!isset($tmpdir)) {
 $tmpdir = System::tmpdir();
}
if (!System::mkDir(array('-p', $tmpdir))) {
 return false;
}
$tmp = tempnam($tmpdir, $prefix);
if (isset($tmp_is_dir)) {
 unlink($tmp); 
 if (!mkdir($tmp, 0700)) {
 return System::raiseError("Unable to create temporary directory $tmpdir");
}
}
$GLOBALS['_System_temp_files'][] = $tmp;
if ($first_time) {
 PEAR::registerShutdownFunc(array('System', '_removeTmpFiles'));
$first_time = false;
}
return $tmp;
}
 function _removeTmpFiles()
 {
 if (count($GLOBALS['_System_temp_files'])) {
 $delete = $GLOBALS['_System_temp_files'];
array_unshift($delete, '-r');
System::rm($delete);
$GLOBALS['_System_temp_files'] = array();
}
}
 function tmpdir()
 {
 if (OS_WINDOWS) {
 if ($var = isset($_ENV['TEMP']) ? $_ENV['TEMP'] : getenv('TEMP')) {
 return $var;
}
if ($var = isset($_ENV['TMP']) ? $_ENV['TMP'] : getenv('TMP')) {
 return $var;
}
if ($var = isset($_ENV['windir']) ? $_ENV['windir'] : getenv('windir')) {
 return $var;
}
return getenv('SystemRoot') . '\temp';
}
if ($var = isset($_ENV['TMPDIR']) ? $_ENV['TMPDIR'] : getenv('TMPDIR')) {
 return $var;
}
return '/tmp';
}
 function which($program, $fallback = false)
 { 
 if (!is_string($program) || '' == $program) {
 return $fallback;
} 
 if (defined('PATH_SEPARATOR')) {
 $path_delim = PATH_SEPARATOR;
} else {
 $path_delim = OS_WINDOWS ? ';' : ':';
} 
 if (basename($program) != $program) {
 $path_elements[] = dirname($program);
$program = basename($program);
} else { 
 if (!ini_get('safe_mode') || !$path = ini_get('safe_mode_exec_dir')) {
 $path = getenv('PATH');
if (!$path) {
 $path = getenv('Path'); 
 }
}
$path_elements = explode($path_delim, $path);
}
if (OS_WINDOWS) {
 $exe_suffixes = getenv('PATHEXT')
 ? explode($path_delim, getenv('PATHEXT'))
 : array('.exe','.bat','.cmd','.com'); 
 if (strpos($program, '.') !== false) {
 array_unshift($exe_suffixes, '');
} 
 $pear_is_executable = (function_exists('is_executable')) ? 'is_executable' : 'is_file';
} else {
 $exe_suffixes = array('');
$pear_is_executable = 'is_executable';
}
foreach ($exe_suffixes as $suff) {
 foreach ($path_elements as $dir) {
 $file = $dir . DIRECTORY_SEPARATOR . $program . $suff;
if (@$pear_is_executable($file)) {
 return $file;
}
}
}
return $fallback;
}
 function find($args)
 {
 if (!is_array($args)) {
 $args = preg_split('/\s+/', $args, -1, PREG_SPLIT_NO_EMPTY);
}
$dir = array_shift($args);
$patterns = array();
$depth = 0;
$do_files = $do_dirs = true;
for ($i = 0; $i < count($args); $i++) {
 switch ($args[$i]) {
 case '-type':
 if (in_array($args[$i+1], array('d', 'f'))) {
 if ($args[$i+1] == 'd') {
 $do_files = false;
} else {
 $do_dirs = false;
}
}
$i++;
break;
case '-name':
 if (OS_WINDOWS) {
 if ($args[$i+1]{0} == '\\') { 
 $args[$i+1] = addslashes(substr(getcwd(), 0, 2) . $args[$i + 1]);
} 
 $args[$i+1] = str_replace('\\', '\\\\', $args[$i+1]);
}
$patterns[] = "(" . preg_replace(array('/\./', '/\*/'),
 array('\.', '.*', ),
 $args[$i+1])
 . ")";
$i++;
break;
case '-maxdepth':
 $depth = $args[$i+1];
break;
}
}
$path = System::_dirToStruct($dir, $depth);
if ($do_files && $do_dirs) {
 $files = array_merge($path['files'], $path['dirs']);
} elseif ($do_dirs) {
 $files = $path['dirs'];
} else {
 $files = $path['files'];
}
if (count($patterns)) {
 $patterns = implode('|', $patterns);
$ret = array();
for ($i = 0; $i < count($files); $i++) {
 if (preg_match("#^$patterns\$#", $files[$i])) {
 $ret[] = $files[$i];
}
}
return $ret;
}
return $files;
}
}
?>
