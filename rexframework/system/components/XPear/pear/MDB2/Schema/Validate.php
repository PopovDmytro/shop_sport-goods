<?php                                              
 class MDB2_Schema_Validate
{ 
 var $fail_on_invalid_names = true;
var $valid_types = array();
var $force_defaults = true;  
 function __construct($fail_on_invalid_names = true, $valid_types = array(), $force_defaults = true)
 {
 if (is_array($fail_on_invalid_names)) {
 $this->fail_on_invalid_names
 = array_intersect($fail_on_invalid_names, array_keys($GLOBALS['_MDB2_Schema_Reserved']));
} elseif ($this->fail_on_invalid_names === true) {
 $this->fail_on_invalid_names = array_keys($GLOBALS['_MDB2_Schema_Reserved']);
} else {
 $this->fail_on_invalid_names = array();
}
$this->valid_types = $valid_types;
$this->force_defaults = $force_defaults;
}
function MDB2_Schema_Validate($fail_on_invalid_names = true, $valid_types = array(), $force_defaults = true)
 {
 $this->__construct($fail_on_invalid_names, $valid_types, $force_defaults);
}  
 function &raiseError($ecode, $msg = null)
 {
 $error =& MDB2_Schema::raiseError($ecode, null, null, $msg);
return $error;
}  
  function isBoolean(&$value)
 {
 if (is_bool($value)) {
 return true;
}
if ($value === 0 || $value === 1 || $value === '') {
 $value = (bool)$value;
return true;
}
if (!is_string($value)) {
 return false;
}
switch ($value) {
 case '0':
 case 'N':
 case 'n':
 case 'no':
 case 'false':
 $value = false;
break;
case '1':
 case 'Y':
 case 'y':
 case 'yes':
 case 'true':
 $value = true;
break;
default:
 return false;
}
return true;
}  
   function validateTable($tables, &$table, $table_name)
 {
  if (!$table_name) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'a table has to have a name');
}
 if (is_array($tables) && isset($tables[$table_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'table "'.$table_name.'" already exists');
}
 if (is_array($this->fail_on_invalid_names)) {
 $name = strtoupper($table_name);
foreach ($this->fail_on_invalid_names as $rdbms) {
 if (in_array($name, $GLOBALS['_MDB2_Schema_Reserved'][$rdbms])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'table name "'.$table_name.'" is a reserved word in: '.$rdbms);
}
}
}
 if (empty($table['was'])) {
 $table['was'] = $table_name;
}
 if (empty($table['fields']) || !is_array($table['fields'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'tables need one or more fields');
}
 $autoinc = $primary = false;
foreach ($table['fields'] as $field_name => $field) {
 if (!empty($field['autoincrement'])) {
 if ($primary) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'there was already an autoincrement field in "'.$table_name.'" before "'.$field_name.'"');
}
$autoinc = $primary = true;
}
}
 if (!empty($table['indexes']) && is_array($table['indexes'])) {
 foreach ($table['indexes'] as $name => $index) {
 $skip_index = false;
if (!empty($index['primary'])) {
  if ($autoinc && count($index['fields']) == '1') {
 $skip_index = true;
} elseif ($primary) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'there was already an primary index or autoincrement field in "'.$table_name.'" before "'.$name.'"');
} else {
 $primary = true;
}
}
if (!$skip_index && is_array($index['fields'])) {
 foreach ($index['fields'] as $field_name => $field) {
 if (!isset($table['fields'][$field_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'index field "'.$field_name.'" does not exist');
}
if (!empty($index['primary'])
 && !$table['fields'][$field_name]['notnull']
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'all primary key fields must be defined notnull in "'.$table_name.'"');
}
}
} else {
 unset($table['indexes'][$name]);
}
}
}
return true;
}  
  function validateField($fields, &$field, $field_name)
 {
  if (!$field_name) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field name missing');
}
 if (is_array($fields) && isset($fields[$field_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "'.$field_name.'" already exists');
}
 if (is_array($this->fail_on_invalid_names)) {
 $name = strtoupper($field_name);
foreach ($this->fail_on_invalid_names as $rdbms) {
 if (in_array($name, $GLOBALS['_MDB2_Schema_Reserved'][$rdbms])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field name "'.$field_name.'" is a reserved word in: '.$rdbms);
}
}
}
 if (empty($field['type'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'no field type specified');
}
if (!empty($this->valid_types) && !array_key_exists($field['type'], $this->valid_types)) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'no valid field type ("'.$field['type'].'") specified');
}
 if (array_key_exists('unsigned', $field) && !$this->isBoolean($field['unsigned'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'unsigned has to be a boolean value');
}
 if (array_key_exists('fixed', $field) && !$this->isBoolean($field['fixed'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'fixed has to be a boolean value');
}
 if (array_key_exists('length', $field) && $field['length'] <= 0) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'length has to be an integer greater 0');
}
 if (empty($field['was'])) {
 $field['was'] = $field_name;
}
 if (empty($field['notnull'])) {
 $field['notnull'] = false;
}
if (!$this->isBoolean($field['notnull'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "notnull" has to be a boolean value');
}
 if ($this->force_defaults
 && !array_key_exists('default', $field)
 && $field['type'] != 'clob' && $field['type'] != 'blob'
 ) {
 $field['default'] = $this->valid_types[$field['type']];
}
if (array_key_exists('default', $field)) {
 if ($field['type'] == 'clob' || $field['type'] == 'blob') {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field['type'].'"-fields are not allowed to have a default value');
}
if ($field['default'] === '' && !$field['notnull']) {
 $field['default'] = null;
}
}
if (isset($field['default'])
 && PEAR::isError($result = $this->validateDataFieldValue($field, $field['default'], $field_name))
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'default value of "'.$field_name.'" is incorrect: '.$result->getUserinfo());
}
 if (!empty($field['autoincrement'])) {
 if (!$field['notnull']) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'all autoincrement fields must be defined notnull');
}
if (empty($field['default'])) {
 $field['default'] = '0';
} elseif ($field['default'] !== '0' && $field['default'] !== 0) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'all autoincrement fields must be defined default "0"');
}
}
return true;
}  
  function validateIndex($table_indexes, &$index, $index_name)
 {
 if (!$index_name) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'an index has to have a name');
}
if (is_array($table_indexes) && isset($table_indexes[$index_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'index "'.$index_name.'" already exists');
}
if (array_key_exists('unique', $index) && !$this->isBoolean($index['unique'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "unique" has to be a boolean value');
}
if (array_key_exists('primary', $index) && !$this->isBoolean($index['primary'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "primary" has to be a boolean value');
}
if (empty($index['was'])) {
 $index['was'] = $index_name;
}
return true;
}  
  function validateIndexField($index_fields, &$field, $field_name)
 {
 if (is_array($index_fields) && isset($index_fields[$field_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'index field "'.$field_name.'" already exists');
}
if (!$field_name) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'the index-field-name is required');
}
if (empty($field['sorting'])) {
 $field['sorting'] = 'ascending';
} elseif($field['sorting'] !== 'ascending' && $field['sorting'] !== 'descending') {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'sorting type unknown');
}
return true;
}  
  function validateSequence($sequences, &$sequence, $sequence_name)
 {
 if (!$sequence_name) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'a sequence has to have a name');
}
if (is_array($sequences) && isset($sequences[$sequence_name])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'sequence "'.$sequence_name.'" already exists');
}
if (is_array($this->fail_on_invalid_names)) {
 $name = strtoupper($sequence_name);
foreach ($this->fail_on_invalid_names as $rdbms) {
 if (in_array($name, $GLOBALS['_MDB2_Schema_Reserved'][$rdbms])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'sequence name "'.$sequence_name.'" is a reserved word in: '.$rdbms);
}
}
}
if (empty($sequence['was'])) {
 $sequence['was'] = $sequence_name;
}
if (!empty($sequence['on'])
 && (empty($sequence['on']['table']) || empty($sequence['on']['field']))
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'sequence "'.$sequence_name.'" on a table was not properly defined');
}
return true;
}  
  function validateDatabase(&$database)
 {
  if (!is_array($database) || !isset($database['name']) || !$database['name']) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'a database has to have a name');
}
 if (is_array($this->fail_on_invalid_names)) {
 $name = strtoupper($database['name']);
foreach ($this->fail_on_invalid_names as $rdbms) {
 if (in_array($name, $GLOBALS['_MDB2_Schema_Reserved'][$rdbms])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'database name "'.$database['name'].'" is a reserved word in: '.$rdbms);
}
}
}
 if (isset($database['create'])
 && !$this->isBoolean($database['create'])
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "create" has to be a boolean value');
}
 if (isset($database['overwrite'])
 && $database['overwrite'] !== ''
 && !$this->isBoolean($database['overwrite'])
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "overwrite" has to be a boolean value');
}
 if (isset($database['sequences'])) {
 foreach ($database['sequences'] as $seq_name => $seq) {
 if (!empty($seq['on'])
 && empty($database['tables'][$seq['on']['table']]['fields'][$seq['on']['field']])
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'sequence "'.$seq_name.'" was assigned on unexisting field/table');
}
}
}
return true;
}  
   function validateDataField($table_fields, $instruction_fields, &$field)
 {
 if (!$field['name']) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field-name has to be specified');
}
if (is_array($instruction_fields) && isset($instruction_fields[$field['name']])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'field "'.$field['name'].'" already initialized');
}
if (is_array($table_fields) && !isset($table_fields[$field['name']])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field['name'].'" is not defined');
}
if (!isset($field['group']['type'])) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field['name'].'" has no initial value');
}
if (isset($field['group']['data'])
 && $field['group']['type'] == 'value'
 && $field['group']['data'] !== ''
 && PEAR::isError($result = $this->validateDataFieldValue($table_fields[$field['name']], $field['group']['data'], $field['name']))
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 'value of "'.$field['name'].'" is incorrect: '.$result->getUserinfo());
}
return true;
}  
  function validateDataFieldValue($field_def, &$field_value, $field_name)
 {
 switch ($field_def['type']) {
 case 'text':
 case 'clob':
 if (!empty($field_def['length']) && strlen($field_value) > $field_def['length']) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is larger than "'.$field_def['length'].'"');
}
break;
case 'blob':
 $field_value = pack('H*', $field_value);
if (!empty($field_def['length']) && strlen($field_value) > $field_def['length']) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is larger than "'.$field_def['type'].'"');
}
break;
case 'integer':
 if ($field_value != ((int)$field_value)) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
} 
 if (!empty($field_def['unsigned']) && $field_def['unsigned'] && $field_value < 0) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" signed instead of unsigned');
}
break;
case 'boolean':
 if (!$this->isBoolean($field_value)) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
}
break;
case 'date':
 if (!preg_match('/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/', $field_value)
 && $field_value !== 'CURRENT_DATE'
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
}
break;
case 'timestamp':
 if (!preg_match('/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/', $field_value)
 && $field_value !== 'CURRENT_TIMESTAMP'
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
}
break;
case 'time':
 if (!preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $field_value)
 && $field_value !== 'CURRENT_TIME'
 ) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
}
break;
case 'float':
 case 'double':
 if ($field_value != (double)$field_value) {
 return $this->raiseError(MDB2_SCHEMA_ERROR_VALIDATE,
 '"'.$field_value.'" is not of type "'.$field_def['type'].'"');
} 
 break;
}
return true;
}
}
?>