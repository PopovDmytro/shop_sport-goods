<?php                                             
 class MDB2_Schema_Writer
{ 
 var $valid_types = array();  
 function __construct($valid_types = array())
 {
 $this->valid_types = $valid_types;
}
function MDB2_Schema_Writer($valid_types = array())
 {
 $this->__construct($valid_types);
}  
  function &raiseError($code = null, $mode = null, $options = null, $userinfo = null)
 {
 $error =& MDB2_Schema::raiseError($code, $mode, $options, $userinfo);
return $error;
}  
  function _escapeSpecialChars($string)
 {
 if (!is_string($string)) {
 $string = strval($string);
}
$escaped = '';
for ($char = 0, $count = strlen($string); $char < $count; $char++) {
 switch ($string[$char]) {
 case '&':
 $escaped.= '&amp;';
break;
case '>':
 $escaped.= '&gt;';
break;
case '<':
 $escaped.= '&lt;';
break;
case '"':
 $escaped.= '&quot;';
break;
case '\'':
 $escaped.= '&apos;';
break;
default:
 $code = ord($string[$char]);
if ($code < 32 || $code > 127) {
 $escaped.= "&#$code;";
} else {
 $escaped.= $string[$char];
}
break;
}
}
return $escaped;
}  
  function _dumpBoolean($boolean)
 {
 if (is_string($boolean)) {
 if ($boolean !== 'true' || $boolean !== 'false'
 || preg_match('/<variable>.*</variable>/', $boolean)
 ) {
 return $boolean;
}
}
return $boolean ? 'true' : 'false';
}  
  function dumpSequence($sequence_definition, $sequence_name, $eol, $dump = MDB2_SCHEMA_DUMP_ALL)
 {
 $buffer = "$eol <sequence>$eol <name>$sequence_name</name>$eol";
if ($dump == MDB2_SCHEMA_DUMP_ALL || $dump == MDB2_SCHEMA_DUMP_CONTENT) {
 if (!empty($sequence_definition['start'])) {
 $start = $sequence_definition['start'];
$buffer.= " <start>$start</start>$eol";
}
}
if (!empty($sequence_definition['on'])) {
 $buffer.= " <on>$eol";
$buffer.= " <table>".$sequence_definition['on']['table'];
$buffer.= "</table>$eol <field>".$sequence_definition['on']['field'];
$buffer.= "</field>$eol </on>$eol";
}
$buffer.= " </sequence>$eol";
return $buffer;
}  
  function dumpDatabase($database_definition, $arguments, $dump = MDB2_SCHEMA_DUMP_ALL)
 {
 if (!empty($arguments['output'])) {
 if (!empty($arguments['output_mode']) && $arguments['output_mode'] == 'file') {
 $fp = fopen($arguments['output'], 'w');
if ($fp === false) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_WRITER, null, null,
 'it was not possible to open output file');
}
$output = false;
} elseif (is_callable($arguments['output'])) {
 $output = $arguments['output'];
} else {
 return $this->raiseError(MDB2_SCHEMA_ERROR_WRITER, null, null,
 'no valid output function specified');
}
} else {
 return $this->raiseError(MDB2_SCHEMA_ERROR_WRITER, null, null,
 'no output method specified');
}
$eol = isset($arguments['end_of_line']) ? $arguments['end_of_line'] : "\n";
$sequences = array();
if (!empty($database_definition['sequences'])
 && is_array($database_definition['sequences'])
 ) {
 foreach ($database_definition['sequences'] as $sequence_name => $sequence) {
 $table = !empty($sequence['on']) ? $sequence['on']['table'] :'';
$sequences[$table][] = $sequence_name;
}
}
$buffer = '<?xml version="1.0" encoding="ISO-8859-1" ?>'.$eol;
$buffer.= "<database>$eol$eol <name>".$database_definition['name']."</name>";
$buffer.= "$eol <create>".$this->_dumpBoolean($database_definition['create'])."</create>";
$buffer.= "$eol <overwrite>".$this->_dumpBoolean($database_definition['overwrite'])."</overwrite>$eol";
if ($output) {
 call_user_func($output, $buffer);
} else {
 fwrite($fp, $buffer);
}
if (!empty($database_definition['tables']) && is_array($database_definition['tables'])) {
 foreach ($database_definition['tables'] as $table_name => $table) {
 $buffer = "$eol <table>$eol$eol <name>$table_name</name>$eol";
if ($dump == MDB2_SCHEMA_DUMP_ALL || $dump == MDB2_SCHEMA_DUMP_STRUCTURE) {
 $buffer.= "$eol <declaration>$eol";
if (!empty($table['fields']) && is_array($table['fields'])) {
 foreach ($table['fields'] as $field_name => $field) {
 if (empty($field['type'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE, null, null,
 'it was not specified the type of the field "'.
 $field_name.'" of the table "'.$table_name);
}
if (!empty($this->valid_types) && !array_key_exists($field['type'], $this->valid_types)) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_UNSUPPORTED, null, null,
 'type "'.$field['type'].'" is not yet supported');
}
$buffer.= "$eol <field>$eol <name>$field_name</name>$eol <type>";
$buffer.= $field['type']."</type>$eol";
if (!empty($field['unsigned'])) {
 $buffer.= " <unsigned>".$this->_dumpBoolean($field['unsigned'])."</unsigned>$eol";
}
if (!empty($field['length'])) {
 $buffer.= ' <length>'.$field['length']."</length>$eol";
}
if (!empty($field['notnull'])) {
 $buffer.= " <notnull>".$this->_dumpBoolean($field['notnull'])."</notnull>$eol";
} else {
 $buffer.= " <notnull>false</notnull>$eol";
}
if (!empty($field['fixed']) && $field['type'] === 'text') {
 $buffer.= " <fixed>".$this->_dumpBoolean($field['fixed'])."</fixed>$eol";
}
if (array_key_exists('default', $field)
 && $field['type'] !== 'clob' && $field['type'] !== 'blob'
 ) {
 $buffer.= ' <default>'.$this->_escapeSpecialChars($field['default'])."</default>$eol";
}
if (!empty($field['autoincrement'])) {
 $buffer.= " <autoincrement>" . $field['autoincrement'] ."</autoincrement>$eol";
}
$buffer.= " </field>$eol";
}
}
if (!empty($table['indexes']) && is_array($table['indexes'])) {
 foreach ($table['indexes'] as $index_name => $index) {
 $buffer.= "$eol <index>$eol <name>$index_name</name>$eol";
if (!empty($index['unique'])) {
 $buffer.= " <unique>".$this->_dumpBoolean($index['unique'])."</unique>$eol";
}
if (!empty($index['primary'])) {
 $buffer.= " <primary>".$this->_dumpBoolean($index['primary'])."</primary>$eol";
}
foreach ($index['fields'] as $field_name => $field) {
 $buffer.= " <field>$eol <name>$field_name</name>$eol";
if (!empty($field) && is_array($field)) {
 $buffer.= ' <sorting>'.$field['sorting']."</sorting>$eol";
}
$buffer.= " </field>$eol";
}
$buffer.= " </index>$eol";
}
}
$buffer.= "$eol </declaration>$eol";
}
if ($output) {
 call_user_func($output, $buffer);
} else {
 fwrite($fp, $buffer);
}
$buffer = '';
if ($dump == MDB2_SCHEMA_DUMP_ALL || $dump == MDB2_SCHEMA_DUMP_CONTENT) {
 if (!empty($table['initialization']) && is_array($table['initialization'])) {
 $buffer = "$eol <initialization>$eol";
foreach ($table['initialization'] as $instruction) {
 switch ($instruction['type']) {
 case 'insert':
 $buffer.= "$eol <insert>$eol";
foreach ($instruction['data']['field'] as $field) {
 $field_name = $field['name'];
$buffer.= "$eol <field>$eol <name>$field_name</name>$eol";
$buffer.= $this->writeExpression($field['group'], 5, $arguments);
$buffer.= " </field>$eol";
}
$buffer.= "$eol </insert>$eol";
break;
case 'update':
 $buffer.= "$eol <update>$eol";
foreach ($instruction['data']['field'] as $field) {
 $field_name = $field['name'];
$buffer.= "$eol <field>$eol <name>$field_name</name>$eol";
$buffer.= $this->writeExpression($field['group'], 5, $arguments);
$buffer.= " </field>$eol";
}
if (!empty($instruction['data']['where'])
 && is_array($instruction['data']['where'])
 ) {
 $buffer.= " <where>$eol";
$buffer.= $this->writeExpression($instruction['data']['where'], 5, $arguments);
$buffer.= " </where>$eol";
}
$buffer.= "$eol </update>$eol";
break;
case 'delete':
 $buffer.= "$eol <delete>$eol$eol";
if (!empty($instruction['data']['where'])
 && is_array($instruction['data']['where'])
 ) {
 $buffer.= " <where>$eol";
$buffer.= $this->writeExpression($instruction['data']['where'], 5, $arguments);
$buffer.= " </where>$eol";
}
$buffer.= "$eol </delete>$eol";
break;
}
}
$buffer.= "$eol </initialization>$eol";
}
}
$buffer.= "$eol </table>$eol";
if ($output) {
 call_user_func($output, $buffer);
} else {
 fwrite($fp, $buffer);
}
if (isset($sequences[$table_name])) {
 foreach ($sequences[$table_name] as $sequence) {
 $result = $this->dumpSequence(
 $database_definition['sequences'][$sequence],
 $sequence, $eol, $dump
 );
if (PEAR::isError($result)) {
 return $result;
}
if ($output) {
 call_user_func($output, $result);
} else {
 fwrite($fp, $result);
}
}
}
}
}
if (isset($sequences[''])) {
 foreach ($sequences[''] as $sequence) {
 $result = $this->dumpSequence(
 $database_definition['sequences'][$sequence],
 $sequence, $eol, $dump
 );
if (PEAR::isError($result)) {
 return $result;
}
if ($output) {
 call_user_func($output, $result);
} else {
 fwrite($fp, $result);
}
}
}
$buffer = "$eol</database>$eol";
if ($output) {
 call_user_func($output, $buffer);
} else {
 fwrite($fp, $buffer);
fclose($fp);
}
return MDB2_OK;
}  
  function writeExpression($element, $offset = 0, $arguments = null)
 {
 $eol = isset($arguments['end_of_line']) ? $arguments['end_of_line'] : "\n";
$str = '';
$indent = str_repeat(' ', $offset);
$noffset = $offset + 1;
switch ($element['type']) {
 case 'value':
 $str.= "$indent<value>".$this->_escapeSpecialChars($element['data'])."</value>$eol";
break;
case 'column':
 $str.= "$indent<column>".$this->_escapeSpecialChars($element['data'])."</column>$eol";
break;
case 'function':
 $str.= "$indent<function>$eol$indent <name>".$this->_escapeSpecialChars($element['data']['name'])."</name>$eol";
if (!empty($element['data']['arguments'])
 && is_array($element['data']['arguments'])
 ) {
 foreach ($element['data']['arguments'] as $v) {
 $str.= $this->writeExpression($v, $noffset, $arguments);
}
}
$str.= "$indent</function>$eol";
break;
case 'expression':
 $str.= "$indent<expression>$eol";
$str.= $this->writeExpression($element['data']['operants'][0], $noffset, $arguments);
$str.= "$indent <operator>".$element['data']['operator']."</operator>$eol";
$str.= $this->writeExpression($element['data']['operants'][1], $noffset, $arguments);
$str.= "$indent</expression>$eol";
break;
}
return $str;
} 
}
?>