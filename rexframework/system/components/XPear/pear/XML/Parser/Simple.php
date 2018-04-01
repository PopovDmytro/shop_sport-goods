<?php                  
  require_once 'XML/Parser.php';
 class XML_Parser_Simple extends XML_Parser
{
  var $_elStack = array();
 var $_data = array();
 var $_depth = 0;
 var $handler = array(
 'default_handler' => 'defaultHandler',
 'processing_instruction_handler' => 'piHandler',
 'unparsed_entity_decl_handler' => 'unparsedHandler',
 'notation_decl_handler' => 'notationHandler',
 'external_entity_ref_handler' => 'entityrefHandler'
 );

  function XML_Parser_Simple($srcenc = null, $mode = 'event', $tgtenc = null)
 {
 $this->XML_Parser($srcenc, $mode, $tgtenc);
}
 function _initHandlers()
 {
 if (!is_object($this->_handlerObj)) {
 $this->_handlerObj = &$this;
}
if ($this->mode != 'func' && $this->mode != 'event') {
 return $this->raiseError('Unsupported mode given', XML_PARSER_ERROR_UNSUPPORTED_MODE);
}
xml_set_object($this->parser, $this->_handlerObj);
xml_set_element_handler($this->parser, array(&$this, 'startHandler'), array(&$this, 'endHandler'));
xml_set_character_data_handler($this->parser, array(&$this, 'cdataHandler'));

  foreach ($this->handler as $xml_func => $method) {
 if (method_exists($this->_handlerObj, $method)) {
 $xml_func = 'xml_set_' . $xml_func;
$xml_func($this->parser, $method);
}
}
}
 function reset()
 {
 $this->_elStack = array();
$this->_data = array();
$this->_depth = 0;

 $result = $this->_create();
if ($this->isError( $result )) {
 return $result;
}
return true;
}
 function startHandler($xp, $elem, &$attribs)
 {
 array_push($this->_elStack, array(
 'name' => $elem,
 'attribs' => $attribs
 )
 );
$this->_depth++;
$this->_data[$this->_depth] = '';
}
 function endHandler($xp, $elem)
 {
 $el = array_pop($this->_elStack);
$data = $this->_data[$this->_depth];
$this->_depth--;
switch ($this->mode) {
 case 'event':
 $this->_handlerObj->handleElement($el['name'], $el['attribs'], $data);
break;
case 'func':
 $func = 'handleElement_' . $elem;
if (strchr($func, '.')) {
 $func = str_replace('.', '_', $func);
}
if (method_exists($this->_handlerObj, $func)) {
 call_user_func(array(&$this->_handlerObj, $func), $el['name'], $el['attribs'], $data);
}
break;
}
}
 function cdataHandler($xp, $data)
 {
 $this->_data[$this->_depth] .= $data;
}
 function handleElement($name, $attribs, $data)
 {
 }
 function getCurrentDepth()
 {
 return $this->_depth;
}
 function addToData( $data )
 {
 $this->_data[$this->_depth] .= $data;
}
}
?>
