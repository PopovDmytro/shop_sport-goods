<?php                     
 class XML_Tree_Node {
  var $attributes;
 var $children;
 var $content;
 var $name;
 var $namespaces = array();
 var $error = null;
 var $use_cdata_section = null;
 function XML_Tree_Node($name, $content = '', $attributes = array(), $lineno = null, $use_cdata_section = null)
 {
 $check_name = XML_Tree::isValidName($name, 'element');
if (PEAR::isError($check_name)) {
 $this->error =& $check_name;
return;
}

 if (!is_array($attributes)) {
 $attributes = array();
}

 foreach ($attributes as $attribute_name => $value) {
 $error = XML_Tree::isValidName($attribute_name, 'Attribute');
if (PEAR::isError($error)) {
 $this->error =& $error;
return;
}
}
$this->name = $name;
$this->setContent($content, $use_cdata_section);
$this->attributes = $attributes;
$this->children = array();
$this->lineno = $lineno;
}
 function &addChild($child, $content = '', $attributes = array(), $lineno = null, $use_cdata_section = null)
 {
 $index = sizeof($this->children);
if (is_object($child)) {
 if (strtolower(get_class($child)) == 'xml_tree_node') {
 $this->children[$index] = $child;
}
if (strtolower(get_class($child)) == 'xml_tree' && isset($child->root)) {
 $this->children[$index] = $child->root->getElement();
}
} else {
 $node = new XML_Tree_Node($child, $content, $attributes, $lineno, $use_cdata_section);
if (PEAR::isError($node->error)) {
 return $node->error;
}
$this->children[$index] = $node;
}
return $this->children[$index];
}
 function &cloneNode()
 {
 $clone = new XML_Tree_Node($this->name,$this->content,$this->attributes);
$max_child=count($this->children);
for($i=0;$i<$max_child;$i++) {
 $clone->children[]=$this->children[$i]->cloneNode();
}
 return $clone;
}
 function &insertChild($path,$pos,&$child, $content = '', $attributes = array())
 {
 $parent =& $this->getNodeAt($path);
if (PEAR::isError($parent)) { 
 return $parent;
} elseif ($parent != $this) { 
 return $parent->insertChild(null, $pos, $child, $content, $attributes);
}
if (($pos < -count($this->children)) || ($pos > count($this->children))) {
 return new PEAR_Error("Invalid insert position.");
}
if (is_object($child)) {  
 if (strtolower(get_class($child)) == 'xml_tree_node') {
 array_splice($this->children, $pos, 0, 'dummy');
if ($pos < 0) {
 $pos = count($this->children) + $pos - 1;
}
$this->children[$pos] = &$child; 
 } elseif (strtolower(get_class($child)) == 'xml_tree' && isset($child->root)) {
 array_splice($this->children, $pos, 0, 'dummy');
if ($pos < 0) {
 $pos = count($this->children) + $pos - 1;
}
$this->children[$pos] = $child->root;
} else {
 return new PEAR_Error("Bad node (must be a XML_Tree or an XML_Tree_Node)");
}
} else { 
 array_splice($this->children, $pos, 0, 'dummy');
if ($pos < 0) {
 $pos = count($this->children) + $pos - 1;
}
$this->children[$pos] = new XML_Tree_Node($child, $content, $attributes);
}
return $this;
}
 function &removeChild($pos)
 {
 if (($pos < -count($this->children)) || ($pos >= count($this->children))) {
 return new PEAR_Error("Invalid remove position.");
} 
 return array_splice($this->children, $pos, 1);
}
 function registerName($name, $path) {
 $this->namespace[$name] = $path;
}
 function &get($use_cdata_section = false)
 {
 static $deep = -1;
static $do_ident = true;
$deep++;
$empty = false;
$ident = str_repeat(' ', $deep);
if ($this->name !== null) {
 if ($do_ident) {
 $out = $ident . '<' . $this->name;
} else {
 $out = '<' . $this->name;
}
foreach ($this->attributes as $name => $value) {
 $out .= ' ' . $name . '="' . $value . '"';
}
if (isset($this->namespace) && (is_array($this->namespace))) {
 foreach ($this->namespace as $qualifier => $uri) {
 if ($qualifier == '') {
 $out .= " xmlns='$uri'";
} else {
 $out .= " xmlns:$qualifier='$uri'";
}
}
}
if ($this->content == '' && sizeof($this->children) === 0 && $deep != 0) {
 $out .= ' />';
$empty = true;
} else {
 $out .= '>';
if ($this->use_cdata_section == true || ($use_cdata_section == true && $this->use_cdata_section !== false)) {
 if (trim($this->content) != '') {
 $out .= '<![CDATA[' .$this->content. ']]>';
}
} else {
 if (trim($this->content) != '') {
 $out .= $this->content;
}
}
}
if (sizeof($this->children) > 0) {
 $out .= "\n";
foreach ($this->children as $child) {
 $out .= $child->get($use_cdata_section);
}
} else {
 $ident = '';
}
if ($do_ident && $empty != true) {
 $out .= $ident . '</' . $this->name . ">\n";
} elseif ($empty != true) {
 $out .= '</' . $this->name . '>';
}
$do_ident = true;
} else {
 if ($this->use_cdata_section == true || ($use_cdata_section == true && $this->use_cdata_section !== false)) {
 if (trim($this->content) != '') {
 $out = $ident . '<![CDATA[' .$this->content. ']]>' . "\n";
}
} else {
 if (trim($this->content) != '') {
 $out = $ident . $this->content . "\n";
}
}
}
$deep--;
return $out;
}
 function getAttribute($name)
 {
 if (isset($this->attributes[$name])) {
 return $this->attributes[$name];
}
return null;
}
 function setAttribute($name, $value = '')
 {
 $this->attributes[$name] = $value;
}
 function unsetAttribute($name)
 {
 if (isset($this->attributes[$name])) {
 unset($this->attributes[$name]);
}
}
 function setContent($content, $use_cdata_section = null)
 {
 $this->use_cdata_section = $use_cdata_section;
if ($use_cdata_section == true) {
 $this->content = $content;
} else {
 $this->content = $this->encodeXmlEntities($content);
}
}
 function &getElement($path)
 {
 if (!is_array($path)) {
 $path = array($path);
}
if (sizeof($path) == 0) {
 return $this;
}
$path1 = $path;
$next = array_shift($path1);
if (isset($this->children[$next])) {
 $x =& $this->children[$next]->getElement($path1);
if (!PEAR::isError($x)) {
 return $x;
}
}
return new PEAR_Error("Bad path to node: [".implode('-', $path)."]");
}
 function &getNodeAt($path)
 {
 if (is_string($path))
 $path = explode("/", $path);
if (sizeof($path) == 0) {
 return $this;
}
$path1 = $path;
$next = array_shift($path1); 
 $child = null;
for ($i = 0; $i < count($this->children); $i++) {
 if ($this->children[$i]->name == $next) {
 $child =& $this->children[$i];
break;
}
}
if (!is_null($child)) {
 $x =& $child->getNodeAt($path1);
if (!PEAR::isError($x)) {
 return $x;
}
} 
 return new PEAR_Error("Bad path to node: [".implode('/', $path)."]");
}
 function encodeXmlEntities($xml)
 {
 $xml = str_replace(array('ü', 'Ü', 'ö',
 'Ö', 'ä', 'Ä',
 'ß', '<', '>',
 '"', '\''
 ),
 array('&#252;', '&#220;', '&#246;',
 '&#214;', '&#228;', '&#196;',
 '&#223;', '&lt;', '&gt;',
 '&quot;', '&apos;'
 ),
 $xml
 );
$xml = preg_replace(array("/\&([a-z\d\#]+)\;/i",
 "/\&/",
 "/\#\|\|([a-z\d\#]+)\|\|\#/i",
 "/([^a-zA-Z\d\s\<\>\&\;\.\:\=\"\-\/\%\?\!\'\(\)\[\]\{\}\$\#\+\,\@_])/e"
 ),
 array("#||\\1||#",
 "&amp;",
 "&\\1;",
 "'&#'.ord('\\1').';'"
 ),
 $xml
 );
return $xml;
}
 function decodeXmlEntities($xml)
 {
 static $trans_tbl = null;
if (!$trans_tbl) {
 $trans_tbl = get_html_translation_table(HTML_ENTITIES);
$trans_tbl = array_flip($trans_tbl);
}
for ($i = 1; $i <= 255; $i++) {
 $ent = sprintf("&#%03d;", $i);
$ch = chr($i);
$xml = str_replace($ent, $ch, $xml);
}
return strtr($xml, $trans_tbl);
}
 function dump() {
 echo $this->get();
}
}
?>
