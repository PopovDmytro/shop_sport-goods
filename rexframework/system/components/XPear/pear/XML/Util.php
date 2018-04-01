<?PHP
                  
 define("XML_UTIL_ERROR_INVALID_CHARS", 51);
 define("XML_UTIL_ERROR_INVALID_START", 52);
 define("XML_UTIL_ERROR_NON_SCALAR_CONTENT", 60);
 define("XML_UTIL_ERROR_NO_TAG_NAME", 61);
 define("XML_UTIL_REPLACE_ENTITIES", 1);
 define("XML_UTIL_CDATA_SECTION", 5);
 define("XML_UTIL_ENTITIES_NONE", 0);
 define("XML_UTIL_ENTITIES_XML", 1);
 define("XML_UTIL_ENTITIES_XML_REQUIRED", 2);
 define("XML_UTIL_ENTITIES_HTML", 3);
 define("XML_UTIL_COLLAPSE_ALL", 1);
 define("XML_UTIL_COLLAPSE_XHTML_ONLY", 2);
 class XML_Util {
  function apiVersion()
 {
 return '1.1';
}
 function replaceEntities($string, $replaceEntities = XML_UTIL_ENTITIES_XML)
 {
 switch ($replaceEntities) {
 case XML_UTIL_ENTITIES_XML:
 return strtr($string,array(
 '&' => '&amp;',
 '>' => '&gt;',
 '<' => '&lt;',
 '"' => '&quot;',
 '\'' => '&apos;' ));
break;
case XML_UTIL_ENTITIES_XML_REQUIRED:
 return strtr($string,array(
 '&' => '&amp;',
 '<' => '&lt;',
 '"' => '&quot;' ));
break;
case XML_UTIL_ENTITIES_HTML:
 return htmlentities($string);
break;
}
return $string;
}
 function reverseEntities($string, $replaceEntities = XML_UTIL_ENTITIES_XML)
 {
 switch ($replaceEntities) {
 case XML_UTIL_ENTITIES_XML:
 return strtr($string,array(
 '&amp;' => '&',
 '&gt;' => '>',
 '&lt;' => '<',
 '&quot;' => '"',
 '&apos;' => '\'' ));
break;
case XML_UTIL_ENTITIES_XML_REQUIRED:
 return strtr($string,array(
 '&amp;' => '&',
 '&lt;' => '<',
 '&quot;' => '"' ));
break;
case XML_UTIL_ENTITIES_HTML:
 $arr = array_flip(get_html_translation_table(HTML_ENTITIES));
return strtr($string, $arr);
break;
}
return $string;
}
 function getXMLDeclaration($version = "1.0", $encoding = null, $standalone = null)
 {
 $attributes = array(
 "version" => $version,
 ); 
 if ($encoding !== null) {
 $attributes["encoding"] = $encoding;
} 
 if ($standalone !== null) {
 $attributes["standalone"] = $standalone ? "yes" : "no";
}
return sprintf("<?xml%s?>", XML_Util::attributesToString($attributes, false));
}
 function getDocTypeDeclaration($root, $uri = null, $internalDtd = null)
 {
 if (is_array($uri)) {
 $ref = sprintf( ' PUBLIC "%s" "%s"', $uri["id"], $uri["uri"] );
} elseif (!empty($uri)) {
 $ref = sprintf( ' SYSTEM "%s"', $uri );
} else {
 $ref = "";
}
if (empty($internalDtd)) {
 return sprintf("<!DOCTYPE %s%s>", $root, $ref);
} else {
 return sprintf("<!DOCTYPE %s%s [\n%s\n]>", $root, $ref, $internalDtd);
}
}
 function attributesToString($attributes, $sort = true, $multiline = false, $indent = ' ', $linebreak = "\n", $entities = XML_UTIL_ENTITIES_XML)
 {
  if (is_array($sort)) {
 if (isset($sort['multiline'])) {
 $multiline = $sort['multiline'];
}
if (isset($sort['indent'])) {
 $indent = $sort['indent'];
}
if (isset($sort['linebreak'])) {
 $multiline = $sort['linebreak'];
}
if (isset($sort['entities'])) {
 $entities = $sort['entities'];
}
if (isset($sort['sort'])) {
 $sort = $sort['sort'];
} else {
 $sort = true;
}
}
$string = '';
if (is_array($attributes) && !empty($attributes)) {
 if ($sort) {
 ksort($attributes);
}
if( !$multiline || count($attributes) == 1) {
 foreach ($attributes as $key => $value) {
 if ($entities != XML_UTIL_ENTITIES_NONE) {
 if ($entities === XML_UTIL_CDATA_SECTION) {
 $entities = XML_UTIL_ENTITIES_XML;
}
$value = XML_Util::replaceEntities($value, $entities);
}
$string .= ' '.$key.'="'.$value.'"';
}
} else {
 $first = true;
foreach ($attributes as $key => $value) {
 if ($entities != XML_UTIL_ENTITIES_NONE) {
 $value = XML_Util::replaceEntities($value, $entities);
}
if ($first) {
 $string .= " ".$key.'="'.$value.'"';
$first = false;
} else {
 $string .= $linebreak.$indent.$key.'="'.$value.'"';
}
}
}
}
return $string;
}
 function collapseEmptyTags($xml, $mode = XML_UTIL_COLLAPSE_ALL) {
 if ($mode == XML_UTIL_COLLAPSE_XHTML_ONLY) {
 return preg_replace(
 '/<(area|base|br|col|hr|img|input|link|meta|param)([^>]*)><\/\\1>/s',
 '<\\1\\2 />',
 $xml
 );
} else {
 return preg_replace(
 '/<(\w+)([^>]*)><\/\\1>/s',
 '<\\1\\2 />',
 $xml
 );
}
}
 function createTag($qname, $attributes = array(), $content = null, $namespaceUri = null, $replaceEntities = XML_UTIL_REPLACE_ENTITIES, $multiline = false, $indent = "_auto", $linebreak = "\n", $sortAttributes = true)
 {
 $tag = array(
 "qname" => $qname,
 "attributes" => $attributes
 ); 
 if ($content !== null) {
 $tag["content"] = $content;
} 
 if ($namespaceUri !== null) {
 $tag["namespaceUri"] = $namespaceUri;
}
return XML_Util::createTagFromArray($tag, $replaceEntities, $multiline, $indent, $linebreak, $sortAttributes);
}
 function createTagFromArray($tag, $replaceEntities = XML_UTIL_REPLACE_ENTITIES, $multiline = false, $indent = "_auto", $linebreak = "\n", $sortAttributes = true)
 {
 if (isset($tag['content']) && !is_scalar($tag['content'])) {
 return XML_Util::raiseError( 'Supplied non-scalar value as tag content', XML_UTIL_ERROR_NON_SCALAR_CONTENT );
}
if (!isset($tag['qname']) && !isset($tag['localPart'])) {
 return XML_Util::raiseError( 'You must either supply a qualified name (qname) or local tag name (localPart).', XML_UTIL_ERROR_NO_TAG_NAME );
} 
 if (!isset($tag["attributes"]) || !is_array($tag["attributes"])) {
 $tag["attributes"] = array();
}
if (isset($tag['namespaces'])) {
 foreach ($tag['namespaces'] as $ns => $uri) {
 $tag['attributes']['xmlns:'.$ns] = $uri;
}
} 
 if (!isset($tag["qname"])) { 
 if (isset($tag["namespace"]) && !empty($tag["namespace"])) {
 $tag["qname"] = $tag["namespace"].":".$tag["localPart"];
} else {
 $tag["qname"] = $tag["localPart"];
} 
 } elseif (isset($tag["namespaceUri"]) && !isset($tag["namespace"])) {
 $parts = XML_Util::splitQualifiedName($tag["qname"]);
$tag["localPart"] = $parts["localPart"];
if (isset($parts["namespace"])) {
 $tag["namespace"] = $parts["namespace"];
}
}
if (isset($tag["namespaceUri"]) && !empty($tag["namespaceUri"])) { 
 if (isset($tag["namespace"]) && !empty($tag["namespace"])) {
 $tag["attributes"]["xmlns:".$tag["namespace"]] = $tag["namespaceUri"];
} else { 
 $tag["attributes"]["xmlns"] = $tag["namespaceUri"];
}
} 
 if ($multiline === true) {
 if ($indent === "_auto") {
 $indent = str_repeat(" ", (strlen($tag["qname"])+2));
}
} 
 $attList = XML_Util::attributesToString($tag['attributes'], $sortAttributes, $multiline, $indent, $linebreak, $replaceEntities );
if (!isset($tag['content']) || (string)$tag['content'] == '') {
 $tag = sprintf('<%s%s />', $tag['qname'], $attList);
} else {
 switch ($replaceEntities) {
 case XML_UTIL_ENTITIES_NONE:
 break;
case XML_UTIL_CDATA_SECTION:
 $tag['content'] = XML_Util::createCDataSection($tag['content']);
break;
default:
 $tag['content'] = XML_Util::replaceEntities($tag['content'], $replaceEntities);
break;
}
$tag = sprintf('<%s%s>%s</%s>', $tag['qname'], $attList, $tag['content'], $tag['qname'] );
}
return $tag;
}
 function createStartElement($qname, $attributes = array(), $namespaceUri = null, $multiline = false, $indent = '_auto', $linebreak = "\n", $sortAttributes = true)
 { 
 if (!isset($attributes) || !is_array($attributes)) {
 $attributes = array();
}
if ($namespaceUri != null) {
 $parts = XML_Util::splitQualifiedName($qname);
} 
 if ($multiline === true) {
 if ($indent === "_auto") {
 $indent = str_repeat(" ", (strlen($qname)+2));
}
}
if ($namespaceUri != null) { 
 if (isset($parts["namespace"]) && !empty($parts["namespace"])) {
 $attributes["xmlns:".$parts["namespace"]] = $namespaceUri;
} else { 
 $attributes["xmlns"] = $namespaceUri;
}
} 
 $attList = XML_Util::attributesToString($attributes, $sortAttributes, $multiline, $indent, $linebreak);
$element = sprintf("<%s%s>", $qname, $attList);
return $element;
}
 function createEndElement($qname)
 {
 $element = sprintf("</%s>", $qname);
return $element;
}
 function createComment($content)
 {
 $comment = sprintf("<!-- %s -->", $content);
return $comment;
}
 function createCDataSection($data)
 {
 return sprintf("<![CDATA[%s]]>", $data);
}
 function splitQualifiedName($qname, $defaultNs = null)
 {
 if (strstr($qname, ':')) {
 $tmp = explode(":", $qname);
return array(
 "namespace" => $tmp[0],
 "localPart" => $tmp[1]
 );
}
return array(
 "namespace" => $defaultNs,
 "localPart" => $qname
 );
}
 function isValidName($string)
 { 
 if (!preg_match('/^[[:alpha:]_]$/', $string{0})) {
 return XML_Util::raiseError('XML names may only start with letter or underscore', XML_UTIL_ERROR_INVALID_START);
} 
 if (!preg_match('/^([[:alpha:]_]([[:alnum:]\-\.]*)?:)?[[:alpha:]_]([[:alnum:]\_\-\.]+)?$/', $string)) {
 return XML_Util::raiseError('XML names may only contain alphanumeric chars, period, hyphen, colon and underscores', XML_UTIL_ERROR_INVALID_CHARS);
} 
 return true;
}
 function raiseError($msg, $code)
 {
 require_once 'PEAR.php';
return PEAR::staticRaiseError($msg, $code);
}
}
?>