<?php
                  
require_once 'PEAR.php';
 class Console_Getopt {
  function getopt2($args, $short_options, $long_options = null)
 {
 return Console_Getopt::doGetopt(2, $args, $short_options, $long_options);
}
 function getopt($args, $short_options, $long_options = null)
 {
 return Console_Getopt::doGetopt(1, $args, $short_options, $long_options);
}
 function doGetopt($version, $args, $short_options, $long_options = null)
 { 
 if (PEAR::isError($args)) {
 return $args;
}
if (empty($args)) {
 return array(array(), array());
}
$opts = array();
$non_opts = array();
settype($args, 'array');
if ($long_options) {
 sort($long_options);
}
 if ($version < 2) {
 if (isset($args[0]{0}) && $args[0]{0} != '-') {
 array_shift($args);
}
}
reset($args);
while (list($i, $arg) = each($args)) {
  if ($arg == '--') {
 $non_opts = array_merge($non_opts, array_slice($args, $i + 1));
break;
}
if ($arg{0} != '-' || (strlen($arg) > 1 && $arg{1} == '-' && !$long_options)) {
 $non_opts = array_merge($non_opts, array_slice($args, $i));
break;
} elseif (strlen($arg) > 1 && $arg{1} == '-') {
 $error = Console_Getopt::_parseLongOption(substr($arg, 2), $long_options, $opts, $args);
if (PEAR::isError($error))
 return $error;
} else {
 $error = Console_Getopt::_parseShortOption(substr($arg, 1), $short_options, $opts, $args);
if (PEAR::isError($error))
 return $error;
}
}
return array($opts, $non_opts);
}
 function _parseShortOption($arg, $short_options, &$opts, &$args)
 {
 for ($i = 0; $i < strlen($arg); $i++) {
 $opt = $arg{$i};
$opt_arg = null;
 if (($spec = strstr($short_options, $opt)) === false || $arg{$i} == ':')
 {
 return PEAR::staticRaiseError("Console_Getopt: unrecognized option -- $opt");
}
if (strlen($spec) > 1 && $spec{1} == ':') {
 if (strlen($spec) > 2 && $spec{2} == ':') {
 if ($i + 1 < strlen($arg)) {
  $opts[] = array($opt, substr($arg, $i + 1));
break;
}
} else {
  if ($i + 1 < strlen($arg)) {
 $opts[] = array($opt, substr($arg, $i + 1));
break;
} else if (list(, $opt_arg) = each($args))
  ;
else
 return PEAR::staticRaiseError("Console_Getopt: option requires an argument -- $opt");
}
}
$opts[] = array($opt, $opt_arg);
}
}
 function _parseLongOption($arg, $long_options, &$opts, &$args)
 {
 @list($opt, $opt_arg) = explode('=', $arg);
$opt_len = strlen($opt);
for ($i = 0; $i < count($long_options); $i++) {
 $long_opt = $long_options[$i];
$opt_start = substr($long_opt, 0, $opt_len);
 if ($opt_start != $opt)
 continue;
$opt_rest = substr($long_opt, $opt_len);
 if ($opt_rest != '' && $opt{0} != '=' &&
 $i + 1 < count($long_options) &&
 $opt == substr($long_options[$i+1], 0, $opt_len)) {
 return PEAR::staticRaiseError("Console_Getopt: option --$opt is ambiguous");
}
if (substr($long_opt, -1) == '=') {
 if (substr($long_opt, -2) != '==') {
  ;
if (!strlen($opt_arg) && !(list(, $opt_arg) = each($args))) {
 return PEAR::staticRaiseError("Console_Getopt: option --$opt requires an argument");
}
}
} else if ($opt_arg) {
 return PEAR::staticRaiseError("Console_Getopt: option --$opt doesn't allow an argument");
}
$opts[] = array('--' . $opt, $opt_arg);
return;
}
return PEAR::staticRaiseError("Console_Getopt: unrecognized option --$opt");
}
 function readPHPArgv()
 {
 global $argv;
if (!is_array($argv)) {
 if (!@is_array($_SERVER['argv'])) {
 if (!@is_array($GLOBALS['HTTP_SERVER_VARS']['argv'])) {
 return PEAR::staticRaiseError("Console_Getopt: Could not read cmd args (register_argc_argv=Off?)");
}
return $GLOBALS['HTTP_SERVER_VARS']['argv'];
}
return $_SERVER['argv'];
}
return $argv;
}
}
?>
