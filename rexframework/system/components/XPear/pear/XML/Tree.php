<?php                      
require_once 'XML/Parser.php';
require_once 'XML/Tree/Node.php';
 class XML_Tree extends XML_Parser
{
  var $file = NULL;
 var $filename = '';
 var $namespace = array();
 var $root = NULL;
 var $version = '1.0';
 var $use_cdata_sections = false;
 function XML_Tree($filename = '', $version = '1.0')
 {
 $this->filename = $filename;
$this->version = $version;
}
 function useCdataSections()
 {
 $this->use_cdata_sections = true;
}
 function &getRoot()
 {
 if (!is_null($this->root)) {
 return $this->root;
}
return $this->raiseError("No root");
}
 function &addRoot($name, $content = '', $attributes = array(), $lineno = null)
 {
 $this->root = new XML_Tree_Node($name, $content, $attributes, $lineno);
return $this->root;
}
 function &insertChild($path, $pos, $child, $content = '', $attributes = array())
 {
 $parent =& $this->getNodeAt($path);
if (PEAR::isError($parent)) {
 return $parent;
}
$x =& $parent->insertChild(null, $pos, $child, $content, $attributes);
if (!PEAR::isError($x)) { 
 $count = count($path);
foreach ($this->namespace as $key => $val) {
 if ((array_slice($val,0,$count)==$path) && ($val[$count]>=$pos)) {
 $this->namespace[$key][$count]++;
}
}
}
return $x;
}
 function &removeChild($path, $pos)
 {
 $parent =& $this->getNodeAt($path);
if (PEAR::isError($parent)) {
 return $parent;
}
$x =& $parent->removeChild($pos);
if (!PEAR::isError($x)) { 
 $count=count($path);
foreach($this->namespace as $key => $val) {
 if (array_slice($val,0,$count)==$path) {
 if ($val[$count]==$pos) {
 unset($this->namespace[$key]); break;
}
if ($val[$count]>$pos) {
 $this->namespace[$key][$count]--;
}
}
}
}
return $x;
}
 function &getTreeFromFile ($encoding = null)
 {
 $this->folding = false;
$this->XML_Parser($encoding, 'event');
$err = $this->setInputFile($this->filename);
if (PEAR::isError($err)) {
 return $err;
}
$this->cdata = null;
$err = $this->parse();
if (PEAR::isError($err)) {
 return $err;
}
return $this->root;
}
 function &getTreeFromString($str, $encoding = null)
 {
 $this->i = null;
$this->folding = false;
$this->XML_Parser($encoding, 'event');
$this->cdata = null;
$err = $this->parseString($str);
if (PEAR::isError($err)) {
 return $err;
}
return $this->root;
}
 function startHandler($xp, $elem, &$attribs)
 {
 $lineno = xml_get_current_line_number($xp); 
 if (!isset($this->i)) {
 $this->obj1 =& $this->addRoot($elem, null, $attribs, $lineno);
$this->i = 2;
} else { 
 if (!empty($this->cdata)) {
 $parent_id = 'obj' . ($this->i - 1);
$parent =& $this->$parent_id;
$parent->children[] = &new XML_Tree_Node(null, $this->cdata, null, $lineno);
}
$obj_id = 'obj' . $this->i++;
$this->$obj_id = &new XML_Tree_Node($elem, null, $attribs, $lineno);
}
$this->cdata = null;
return null;
}
 function endHandler($xp, $elem)
 {
 $this->i--;
if ($this->i > 1) {
 $obj_id = 'obj' . $this->i; 
 $node =& $this->$obj_id; 
 if (count($node->children) > 0) {
 if (trim($this->cdata) != '') {
 $node->children[] = &new XML_Tree_Node(null, $this->cdata);
}
} else {
 $node->setContent($this->cdata);
}
$parent_id = 'obj' . ($this->i - 1);
$parent =& $this->$parent_id; 
 $parent->children[] = $node;
} else {
 $node =& $this->obj1;
if (count($node->children) > 0) {
 if (trim($this->cdata)) {
 $node->children[] = &new XML_Tree_Node(null, $this->cdata);
}
} else {
 $node->setContent($this->cdata);
}
}
$this->cdata = null;
return null;
}
 function cdataHandler($xp, $data)
 {
 $this->cdata .= $data;
}
 function cloneTree()
 {
 $clone = new XML_Tree($this->filename, $this->version);
if (!is_null($this->root)) {
 $clone->root = $this->root->cloneTree();
} 
 $temp = get_object_vars($this);
foreach($temp as $varname => $value) {
 if (!in_array($varname,array('filename','version','root'))) {
 $clone->$varname=$value;
}
}
return $clone;
}
 function dump($xmlHeader = false)
 {
 if ($xmlHeader) {
 header('Content-type: text/xml');
}
echo $this->get($this->use_cdata_sections);
}
 function &get()
 {
 $out = '<?xml version="' . $this->version . "\"?>\n";
if (!is_null($this->root))
 {
 if(!is_object($this->root) || (strtolower(get_class($this->root)) != 'xml_tree_node'))
 return $this->raiseError("Bad XML root node");
$out .= $this->root->get($this->use_cdata_sections);
}
return $out;
}
 function &getName($name) {
 return $this->root->getElement($this->namespace[$name]);
}
 function getNodeNamespace(&$node) {
 $name_parts = explode(':',$node->name);
if (sizeof($name_parts) > 1) {
 $namespace = $name_parts[0];
} else {
 $namespace = '';
}
if (isset($node->namespace[$namespace])) {
 return $node->namespace[$namespace];
} elseif (isset($this->root->namespace[$namespace])) {
 return $this->root->namespace[$namespace];
} else {
 return '';
}
}
 function &getNodeAt($path)
 {
 if (is_null($this->root)){
 return $this->raiseError("XML_Tree hasn't a root node");
}
if (is_string($path))
 $path = explode("/", $path);
if (sizeof($path) == 0) {
 return $this->raiseError("Path to node is empty");
}
$path1 = $path;
$rootName = array_shift($path1);
if ($this->root->name != $rootName) {
 return $this->raiseError("Path does not match the document root");
}
$x =& $this->root->getNodeAt($path1);
if (!PEAR::isError($x)) {
 return $x;
} 
 return $this->raiseError("Bad path to node: [".implode('/', $path)."]");
}
 function &getElementsByTagName($tagName)
 {
 if (empty($tagName)) {
 return $this->raiseError('Empty tag name');
}
$result = array();
foreach ($this->root->children as $child) {
 if ($child->name == $tagName) {
 $result[] = $child;
}
}
return $result;
}
 function &getElementsByTagNameFromNode($tagName, &$node)
 {
 if (empty($tagName)) {
 return $this->raiseError('Empty tag name');
}
$result = array();
foreach ($node->children as $child) {
 if ($child->name == $tagName) {
 $result[] = $child;
}
}
return $result;
}
 function isValidName($name, $type) {
 if (trim($name) == '') {
 return true;
} 
 if (!preg_match("/[[:alpha:]_]/", $name{0})) {
 return new PEAR_Error( ucfirst($type) . " ('$name') has an invalid name, an XML name may only start with a letter or underscore");
}
if (!preg_match("/^([a-zA-Z_]([a-zA-Z0-9_\-\.]*)?:)?[a-zA-Z_]([a-zA-Z0-9_\-\.]+)?$/", $name)) {
 return new PEAR_Error( ucfirst($type) . " ('$name') has an invalid name, an XML name may only contain alphanumeric chars, period, hyphen, colon and underscores");
}
return true;
}
}
?>
