<?php                    
  require_once 'PEAR.php';
 define('XML_PARSER_ERROR_NO_RESOURCE', 200);
 define('XML_PARSER_ERROR_UNSUPPORTED_MODE', 201);
 define('XML_PARSER_ERROR_INVALID_ENCODING', 202);
 define('XML_PARSER_ERROR_FILE_NOT_READABLE', 203);
 define('XML_PARSER_ERROR_INVALID_INPUT', 204);
 define('XML_PARSER_ERROR_REMOTE', 205);
 class XML_Parser extends PEAR
{ 
  var $parser;
 var $fp;
 var $folding = true;
 var $mode;
 var $handler = array(
 'character_data_handler' => 'cdataHandler',
 'default_handler' => 'defaultHandler',
 'processing_instruction_handler' => 'piHandler',
 'unparsed_entity_decl_handler' => 'unparsedHandler',
 'notation_decl_handler' => 'notationHandler',
 'external_entity_ref_handler' => 'entityrefHandler'
 );
 var $srcenc;
 var $tgtenc;
 var $_handlerObj;
 var $_validEncodings = array('ISO-8859-1', 'UTF-8', 'US-ASCII');  
  function XML_Parser($srcenc = null, $mode = 'event', $tgtenc = null)
 {
 XML_Parser::__construct($srcenc, $mode, $tgtenc);
} 
  function __construct($srcenc = null, $mode = 'event', $tgtenc = null)
 {
 $this->PEAR('XML_Parser_Error');
$this->mode = $mode;
$this->srcenc = $srcenc;
$this->tgtenc = $tgtenc;
} 
  function setMode($mode)
 {
 if ($mode != 'func' && $mode != 'event') {
 $this->raiseError('Unsupported mode given', XML_PARSER_ERROR_UNSUPPORTED_MODE);
}
$this->mode = $mode;
return true;
}
 function setHandlerObj(&$obj)
 {
 $this->_handlerObj = &$obj;
return true;
}
 function _initHandlers()
 {
 if (!is_resource($this->parser)) {
 return false;
}
if (!is_object($this->_handlerObj)) {
 $this->_handlerObj = &$this;
}
switch ($this->mode) {
 case 'func':
 xml_set_object($this->parser, $this->_handlerObj);
xml_set_element_handler($this->parser, array(&$this, 'funcStartHandler'), array(&$this, 'funcEndHandler'));
break;
case 'event':
 xml_set_object($this->parser, $this->_handlerObj);
xml_set_element_handler($this->parser, 'startHandler', 'endHandler');
break;
default:
 return $this->raiseError('Unsupported mode given', XML_PARSER_ERROR_UNSUPPORTED_MODE);
break;
}
 foreach ($this->handler as $xml_func => $method) {
 if (method_exists($this->_handlerObj, $method)) {
 $xml_func = 'xml_set_' . $xml_func;
$xml_func($this->parser, $method);
}
}
} 
  function _create()
 {
 if ($this->srcenc === null) {
 $xp = @xml_parser_create();
} else {
 $xp = @xml_parser_create($this->srcenc);
}
if (is_resource($xp)) {
 if ($this->tgtenc !== null) {
 if (!@xml_parser_set_option($xp, XML_OPTION_TARGET_ENCODING,
 $this->tgtenc)) {
 return $this->raiseError('invalid target encoding', XML_PARSER_ERROR_INVALID_ENCODING);
}
}
$this->parser = $xp;
$result = $this->_initHandlers($this->mode);
if ($this->isError($result)) {
 return $result;
}
xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, $this->folding);
return true;
}
if (!in_array(strtoupper($this->srcenc), $this->_validEncodings)) {
 return $this->raiseError('invalid source encoding', XML_PARSER_ERROR_INVALID_ENCODING);
}
return $this->raiseError('Unable to create XML parser resource.', XML_PARSER_ERROR_NO_RESOURCE);
}  
  function reset()
 {
 $result = $this->_create();
if ($this->isError( $result )) {
 return $result;
}
return true;
}  
  function setInputFile($file)
 {
  if (eregi('^(http|ftp)://', substr($file, 0, 10))) {
 if (!ini_get('allow_url_fopen')) {
 return $this->raiseError('Remote files cannot be parsed, as safe mode is enabled.', XML_PARSER_ERROR_REMOTE);
}
}
$fp = @fopen($file, 'rb');
if (is_resource($fp)) {
 $this->fp = $fp;
return $fp;
}
return $this->raiseError('File could not be opened.', XML_PARSER_ERROR_FILE_NOT_READABLE);
}  
  function setInputString($data)
 {
 $this->fp = $data;
return null;
}  
  function setInput($fp)
 {
 if (is_resource($fp)) {
 $this->fp = $fp;
return true;
} 
 elseif (eregi('^[a-z]+://', substr($fp, 0, 10))) {
 return $this->setInputFile($fp);
} 
 elseif (file_exists($fp)) {
 return $this->setInputFile($fp);
} 
 else {
 $this->fp = $fp;
return true;
}
return $this->raiseError('Illegal input format', XML_PARSER_ERROR_INVALID_INPUT);
}  
  function parse()
 {
  $result = $this->reset();
if ($this->isError($result)) {
 return $result;
} 
 if (is_resource($this->fp)) {
 while ($data = fread($this->fp, 4096)) {
 if (!$this->_parseString($data, feof($this->fp))) {
 $error = &$this->raiseError();
$this->free();
return $error;
}
} 
 } else {
 if (!$this->_parseString($this->fp, true)) {
 $error = &$this->raiseError();
$this->free();
return $error;
}
}
$this->free();
return true;
}
 function _parseString($data, $eof = false)
 {
 return xml_parse($this->parser, $data, $eof);
}  
  function parseString($data, $eof = false)
 {
 if (!isset($this->parser) || !is_resource($this->parser)) {
 $this->reset();
}
if (!$this->_parseString($data, $eof)) {
 $error = &$this->raiseError();
$this->free();
return $error;
}
if ($eof === true) {
 $this->free();
}
return true;
}
 function free()
 {
 if (isset($this->parser) && is_resource($this->parser)) {
 xml_parser_free($this->parser);
unset( $this->parser );
}
if (isset($this->fp) && is_resource($this->fp)) {
 fclose($this->fp);
}
unset($this->fp);
return null;
}
 function raiseError($msg = null, $ecode = 0)
 {
 $msg = !is_null($msg) ? $msg : $this->parser;
$err = &new XML_Parser_Error($msg, $ecode);
return parent::raiseError($err);
}  
 function funcStartHandler($xp, $elem, $attribs)
 {
 $func = 'xmltag_' . $elem;
$func = str_replace(array('.', '-', ':'), '_', $func);
if (method_exists($this->_handlerObj, $func)) {
 call_user_func(array(&$this->_handlerObj, $func), $xp, $elem, $attribs);
} elseif (method_exists($this->_handlerObj, 'xmltag')) {
 call_user_func(array(&$this->_handlerObj, 'xmltag'), $xp, $elem, $attribs);
}
}  
 function funcEndHandler($xp, $elem)
 {
 $func = 'xmltag_' . $elem . '_';
$func = str_replace(array('.', '-', ':'), '_', $func);
if (method_exists($this->_handlerObj, $func)) {
 call_user_func(array(&$this->_handlerObj, $func), $xp, $elem);
} elseif (method_exists($this->_handlerObj, 'xmltag_')) {
 call_user_func(array(&$this->_handlerObj, 'xmltag_'), $xp, $elem);
}
}  
  function startHandler($xp, $elem, &$attribs)
 {
 return NULL;
}  
  function endHandler($xp, $elem)
 {
 return NULL;
} 
}
 class XML_Parser_Error extends PEAR_Error
{ 
  var $error_message_prefix = 'XML_Parser: ';  
  function XML_Parser_Error($msgorparser = 'unknown error', $code = 0, $mode = PEAR_ERROR_RETURN, $level = E_USER_NOTICE)
 {
 if (is_resource($msgorparser)) {
 $code = xml_get_error_code($msgorparser);
$msgorparser = sprintf('%s at XML input line %d:%d',
 xml_error_string($code),
 xml_get_current_line_number($msgorparser),
 xml_get_current_column_number($msgorparser));
}
$this->PEAR_Error($msgorparser, $code, $mode, $level);
} 
}
?>