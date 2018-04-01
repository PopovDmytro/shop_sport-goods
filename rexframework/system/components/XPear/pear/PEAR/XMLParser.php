<?php
  class PEAR_XMLParser
{
  var $_unserializedData = null;
 var $_root = null;
 var $_dataStack = array();
 var $_valStack = array();
 var $_depth = 0;
 function getData()
 {
 return $this->_unserializedData;
}
 function parse($data)
 {
 if (!extension_loaded('xml')) {
 include_once 'PEAR.php';
return PEAR::staticRaiseError("XML Extension not found", 1);
}
$this->_valStack = array();
$this->_dataStack = array();
$this->_depth = 0;
if (version_compare(phpversion(), '5.0.0', 'lt')) {
 if (strpos($data, 'encoding="UTF-8"')) {
 $data = utf8_decode($data);
}
$xp = xml_parser_create('ISO-8859-1');
} else {
 if (strpos($data, 'encoding="UTF-8"')) {
 $xp = xml_parser_create('UTF-8');
} else {
 $xp = xml_parser_create('ISO-8859-1');
}
}
xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, 0);
xml_set_object($xp, $this);
xml_set_element_handler($xp, 'startHandler', 'endHandler');
xml_set_character_data_handler($xp, 'cdataHandler');
if (!xml_parse($xp, $data)) {
 $msg = xml_error_string(xml_get_error_code($xp));
$line = xml_get_current_line_number($xp);
xml_parser_free($xp);
include_once 'PEAR.php';
return PEAR::staticRaiseError("XML Error: '$msg' on line '$line'", 2);
}
xml_parser_free($xp);
return true;
}
 function startHandler($parser, $element, $attribs)
 {
 $type = 'string';
$this->_depth++;
$this->_dataStack[$this->_depth] = null;
$val = array(
 'name' => $element,
 'value' => null,
 'type' => $type,
 'childrenKeys' => array(),
 'aggregKeys' => array()
 );
if (count($attribs) > 0) {
 $val['children'] = array();
$val['type'] = 'array';
$val['children']['attribs'] = $attribs;
}
array_push($this->_valStack, $val);
}
 function postProcess($data, $element)
 {
 return trim($data);
}
 function endHandler($parser, $element)
 {
 $value = array_pop($this->_valStack);
$data = $this->postProcess($this->_dataStack[$this->_depth], $element); 
 switch(strtolower($value['type'])) {
  case 'array':
 if ($data !== '') {
 $value['children']['_content'] = $data;
}
if (isset($value['children'])) {
 $value['value'] = $value['children'];
} else {
 $value['value'] = array();
}
break;
 case 'null':
 $data = null;
break;
 default:
 settype($data, $value['type']);
$value['value'] = $data;
break;
}
$parent = array_pop($this->_valStack);
if ($parent === null) {
 $this->_unserializedData = &$value['value'];
$this->_root = &$value['name'];
return true;
} else { 
 if (!isset($parent['children']) || !is_array($parent['children'])) {
 $parent['children'] = array();
if ($parent['type'] != 'array') {
 $parent['type'] = 'array';
}
}
if (!empty($value['name'])) { 
 if (in_array($value['name'], $parent['childrenKeys'])) { 
 if (!in_array($value['name'], $parent['aggregKeys'])) {
 if (isset($parent['children'][$value['name']])) {
 $parent['children'][$value['name']] = array($parent['children'][$value['name']]);
} else {
 $parent['children'][$value['name']] = array();
}
array_push($parent['aggregKeys'], $value['name']);
}
array_push($parent['children'][$value['name']], $value['value']);
} else {
 $parent['children'][$value['name']] = &$value['value'];
array_push($parent['childrenKeys'], $value['name']);
}
} else {
 array_push($parent['children'],$value['value']);
}
array_push($this->_valStack, $parent);
}
$this->_depth--;
}
 function cdataHandler($parser, $cdata)
 {
 $this->_dataStack[$this->_depth] .= $cdata;
}
}
?>