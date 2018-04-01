<?php
    if (substr($_SERVER['PHP_SELF'], -1) == '/') {
        $http = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        define('CURRENT_FILENAME', '');
        define('CURRENT_PATHNAME', $http.$_SERVER['HTTP_HOST'].str_replace('\\', '/', $_SERVER['PHP_SELF']));
    } else {
        define('CURRENT_FILENAME', preg_replace('/(.*)\?.*/', '\\1', basename($_SERVER['PHP_SELF'])));
        define('CURRENT_PATHNAME', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));
    }
    define('PAGER_OK', 0);
    define('ERROR_PAGER', -1);
    define('ERROR_PAGER_INVALID', -2);
    define('ERROR_PAGER_INVALID_PLACEHOLDER', -3);
    define('ERROR_PAGER_INVALID_USAGE', -4);
    define('ERROR_PAGER_NOT_IMPLEMENTED', -5);
    class Pager_Common
    { 
        var $_totalItems;
        var $_perPage = 10;
        var $_delta = 10;
        var $_currentPage = 1;
        var $_totalPages = 1;
        var $_linkClass = '';
        var $_classString = '';
        var $_path = CURRENT_PATHNAME;
        var $_fileName = CURRENT_FILENAME;

        var $_fixFileName = true;
        var $_append = true;
        var $_httpMethod = 'GET';

        var $_formID = '';
        var $_importQuery = true;
        var $_urlVar = 'pageID';
        var $_linkData = array();
        var $_extraVars = array();

        var $_excludeVars = array();
        var $_expanded = true;

        var $_accesskey = false;
        var $_attributes = '';
        var $_altFirst = 'first page';
        var $_altPrev = 'previous page';
        var $_altNext = 'next page';
        var $_altLast = 'last page';
        var $_altPage = 'page';
        var $_prevImg = '&lt;&lt; Back';
        var $_nextImg = 'Next &gt;&gt;';
        var $_separator = '';
        var $_spacesBeforeSeparator = 0;
        var $_spacesAfterSeparator = 1;
        var $_curPageLinkClassName = '';
        var $_curPageSpanPre = '';
        var $_curPageSpanPost = '';
        var $_firstPagePre = '[';
        var $_firstPageText = '';
        var $_firstPagePost = ']';
        var $_lastPagePre = '[';
        var $_lastPageText = '';
        var $_lastPagePost = ']';
        var $_spacesBefore = '';
        var $_spacesAfter = '';
        var $_firstLinkTitle = 'first page';
        var $_nextLinkTitle = 'next page';
        var $_prevLinkTitle = 'previous page';
        var $_lastLinkTitle = 'last page';
        var $_showAllText = '';
        var $_itemData = null;
        var $_clearIfVoid = true;
        var $_useSessions = false;
        var $_closeSession = false;
        var $_sessionVar = 'setPerPage';
        var $_pearErrorMode = null;  
        var $links = '';
        var $linkTags = '';
        var $range = array();

        var $_allowed_options = array(
            'totalItems',
            'perPage',
            'delta',
            'linkClass',
            'path',
            'fileName',
            'fixFileName',
            'append',
            'httpMethod',
            'formID',
            'importQuery',
            'urlVar',
            'altFirst',
            'altPrev',
            'altNext',
            'altLast',
            'altPage',
            'prevImg',
            'nextImg',
            'expanded',
            'accesskey',
            'attributes',
            'separator',
            'spacesBeforeSeparator',
            'spacesAfterSeparator',
            'curPageLinkClassName',
            'curPageSpanPre',
            'curPageSpanPost',
            'firstPagePre',
            'firstPageText',
            'firstPagePost',
            'lastPagePre',
            'lastPageText',
            'lastPagePost',
            'firstLinkTitle',
            'nextLinkTitle',
            'prevLinkTitle',
            'lastLinkTitle',
            'showAllText',
            'itemData',
            'clearIfVoid',
            'useSessions',
            'closeSession',
            'sessionVar',
            'pearErrorMode',
            'extraVars',
            'excludeVars',
            'currentPage',
        );  

        function build()
        {
            $msg = '<b>PEAR::Pager Error:</b>'
            .' function "build()" not implemented.';
            return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
        }  
        function getPageData($pageID = null)
        {
            $pageID = empty($pageID) ? $this->_currentPage : $pageID;
            if (!isset($this->_pageData)) {
                $this->_generatePageData();
            }
            if (!empty($this->_pageData[$pageID])) {
                return $this->_pageData[$pageID];
            }
            return array();
        }  
        function getPageIdByOffset($index)
        {
            $msg = '<b>PEAR::Pager Error:</b>'
            .' function "getPageIdByOffset()" not implemented.';
            return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
        }  
        function getOffsetByPageId($pageid = null)
        {
            $pageid = isset($pageid) ? $pageid : $this->_currentPage;
            if (!isset($this->_pageData)) {
                $this->_generatePageData();
            }
            if (isset($this->_pageData[$pageid]) || is_null($this->_itemData)) {
                return array(
                    max(($this->_perPage * ($pageid - 1)) + 1, 1),
                    min($this->_totalItems, $this->_perPage * $pageid)
                );
            } else {
                return array(0, 0);
            }
        }  
        function getPageRangeByPageId($pageID)
        {
            $msg = '<b>PEAR::Pager Error:</b>'
            .' function "getPageRangeByPageId()" not implemented.';
            return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
        }  
        function getLinks($pageID=null, $next_html='')
        {
            $msg = '<b>PEAR::Pager Error:</b>'
            .' function "getLinks()" not implemented.';
            return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
        }  
        function getCurrentPageID()
        {
            return $this->_currentPage;
        }  
        function getNextPageID()
        {
            return ($this->getCurrentPageID() == $this->numPages() ? false : $this->getCurrentPageID() + 1);
        }  
        function getPreviousPageID()
        {
            return $this->isFirstPage() ? false : $this->getCurrentPageID() - 1;
        }  
        function numItems()
        {
            return $this->_totalItems;
        }  
        function numPages()
        {
            return (int)$this->_totalPages;
        }  
        function isFirstPage()
        {
            return ($this->_currentPage < 2);
        }  
        function isLastPage()
        {
            return ($this->_currentPage == $this->_totalPages);
        }  
        function isLastPageComplete()
        {
            return !($this->_totalItems % $this->_perPage);
        }  
        function _generatePageData()
        { 
            if (!is_null($this->_itemData)) {
                $this->_totalItems = count($this->_itemData);
            }
            $this->_totalPages = ceil((float)$this->_totalItems / (float)$this->_perPage);
            $i = 1;
            if (!empty($this->_itemData)) {
                foreach ($this->_itemData as $key => $value) {
                    $this->_pageData[$i][$key] = $value;
                    if (count($this->_pageData[$i]) >= $this->_perPage) {
                        $i++;
                    }
                }
            } else {
                $this->_pageData = array();
            } 
            $this->_currentPage = min($this->_currentPage, $this->_totalPages);
        }  
        function _renderLink($altText, $linkText)
        {
            if ($this->_httpMethod == 'GET') {
                if ($this->_append) {
                    $href = '?' . $this->_http_build_query_wrapper($this->_linkData);
                } else {
                    $href = str_replace('%d', $this->_linkData[$this->_urlVar], $this->_fileName);
                }
                return sprintf('<a href="%s"%s%s%s title="%s">%s</a>',
                    htmlentities($this->_url . $href),
                    empty($this->_classString) ? '' : ' '.$this->_classString,
                    empty($this->_attributes) ? '' : ' '.$this->_attributes,
                    empty($this->_accesskey) ? '' : ' accesskey="'.$this->_linkData[$this->_urlVar].'"',
                    $altText,
                    $linkText
                );
            } elseif ($this->_httpMethod == 'POST') {
                return sprintf("<a href='javascript:void(0)' onclick='%s'%s%s%s title='%s'>%s</a>",
                    $this->_generateFormOnClick($this->_url, $this->_linkData),
                    empty($this->_classString) ? '' : ' '.$this->_classString,
                    empty($this->_attributes) ? '' : ' '.$this->_attributes,
                    empty($this->_accesskey) ? '' : ' accesskey=\''.$this->_linkData[$this->_urlVar].'\'',
                    $altText,
                    $linkText
                );
            }
            return '';
        }  
        function _generateFormOnClick($formAction, $data)
        { 
            if (!is_array($data)) {
                trigger_error(
                    '_generateForm() Parameter 1 expected to be Array or Object. Incorrect value given.',
                    E_USER_WARNING
                );
                return false;
            }
            if (!empty($this->_formID)) {
                $str = 'var form = document.getElementById("'.$this->_formID.'"); var input = ""; ';
            } else {
                $str = 'var form = document.createElement("form"); var input = ""; ';
            } 
            $str .= sprintf('form.action = "%s"; ', htmlentities($formAction));
            $str .= sprintf('form.method = "%s"; ', $this->_httpMethod);
            foreach ($data as $key => $val) {
                $str .= $this->_generateFormOnClickHelper($val, $key);
            }
            if (empty($this->_formID)) {
                $str .= 'document.getElementsByTagName("body")[0].appendChild(form);';
            }

            $str .= 'form.submit(); return false;';
            return $str;
        }  
        function _generateFormOnClickHelper($data, $prev = '')
        {
            $str = '';
            if (is_array($data) || is_object($data)) { 
                foreach ((array)$data as $key => $val) { 
                    $tempKey = sprintf('%s[%s]', $prev, $key);
                    $str .= $this->_generateFormOnClickHelper($val, $tempKey);
                }
            } else {  
                $search = array("\n", "\r");
                $replace = array('\n', '\n');
                $escapedData = str_replace($search, $replace, $data);   
                if (!$this->_isEncoded($escapedData)) {
                    $escapedData = urlencode($escapedData);
                }
                $escapedData = htmlentities($escapedData, ENT_QUOTES, 'UTF-8');
                $str .= 'input = document.createElement("input"); ';
                $str .= 'input.type = "hidden"; ';
                $str .= sprintf('input.name = "%s"; ', $prev);
                $str .= sprintf('input.value = "%s"; ', $escapedData);
                $str .= 'form.appendChild(input); ';
            }
            return $str;
        }  
        function _getLinksData()
        {
            $qs = array();
            if ($this->_importQuery) {
                if ($this->_httpMethod == 'POST') {
                    $qs = $_POST;
                } elseif ($this->_httpMethod == 'GET') {
                    $qs = $_GET;
                }
            }
            foreach ($this->_excludeVars as $exclude) {
                if (array_key_exists($exclude, $qs)) {
                    unset($qs[$exclude]);
                }
            }
            if (count($this->_extraVars)){
                $this->_recursive_urldecode($this->_extraVars);
                $qs = array_merge($qs, $this->_extraVars);
            }
            if (count($qs) && get_magic_quotes_gpc()){
                $this->_recursive_stripslashes($qs);
            }
            return $qs;
        }  

        function _recursive_stripslashes(&$var)
        {
            if (is_array($var)) {
                foreach (array_keys($var) as $k) {
                    $this->_recursive_stripslashes($var[$k]);
                }
            } else {
                $var = stripslashes($var);
            }
        }  
        function _recursive_urldecode(&$var)
        {
            if (is_array($var)) {
                foreach (array_keys($var) as $k) {
                    $this->_recursive_urldecode($var[$k]);
                }
            } else {
                $trans_tbl = array_flip(get_html_translation_table(HTML_ENTITIES));
                $var = strtr($var, $trans_tbl);
            }
        }  
        function _getBackLink($url='', $link='')
        {  
            if (!empty($url)) {
                $this->_path = $url;
            }
            if (!empty($link)) {
                $this->_prevImg = $link;
            }
            $back = '';
            if ($this->_currentPage > 1) {
                $this->_linkData[$this->_urlVar] = $this->getPreviousPageID();
                $back = $this->_renderLink($this->_altPrev, $this->_prevImg)
                . $this->_spacesBefore . $this->_spacesAfter;
            }
            return $back;
        }  
        function _getPageLinks($url='')
        {
            $msg = '<b>PEAR::Pager Error:</b>'
            .' function "_getPageLinks()" not implemented.';
            return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
        }  
        function _getNextLink($url='', $link='')
        {  
            if (!empty($url)) {
                $this->_path = $url;
            }
            if (!empty($link)) {
                $this->_nextImg = $link;
            }
            $next = '';
            if ($this->_currentPage < $this->_totalPages) {
                $this->_linkData[$this->_urlVar] = $this->getNextPageID();
                $next = $this->_spacesAfter
                . $this->_renderLink($this->_altNext, $this->_nextImg)
                . $this->_spacesBefore . $this->_spacesAfter;
            }
            return $next;
        }  
        function _getFirstLinkTag()
        {
            if ($this->isFirstPage() || ($this->_httpMethod != 'GET')) {
                return '';
            }
            return sprintf('<link rel="first" href="%s" title="%s" />'."\n",
                $this->_getLinkTagUrl(1),
                $this->_firstLinkTitle
            );
        }  
        function _getPrevLinkTag()
        {
            if ($this->isFirstPage() || ($this->_httpMethod != 'GET')) {
                return '';
            }
            return sprintf('<link rel="previous" href="%s" title="%s" />'."\n",
                $this->_getLinkTagUrl($this->getPreviousPageID()),
                $this->_prevLinkTitle
            );
        }  
        function _getNextLinkTag()
        {
            if ($this->isLastPage() || ($this->_httpMethod != 'GET')) {
                return '';
            }
            return sprintf('<link rel="next" href="%s" title="%s" />'."\n",
                $this->_getLinkTagUrl($this->getNextPageID()),
                $this->_nextLinkTitle
            );
        }  
        function _getLastLinkTag()
        {
            if ($this->isLastPage() || ($this->_httpMethod != 'GET')) {
                return '';
            }
            return sprintf('<link rel="last" href="%s" title="%s" />'."\n",
                $this->_getLinkTagUrl($this->_totalPages),
                $this->_lastLinkTitle
            );
        }  
        function _getLinkTagUrl($pageID)
        {
            $this->_linkData[$this->_urlVar] = $pageID;
            if ($this->_append) {
                $href = '?' . $this->_http_build_query_wrapper($this->_linkData);
            } else {
                $href = str_replace('%d', $this->_linkData[$this->_urlVar], $this->_fileName);
            }
            return htmlentities($this->_url . $href);
        }  
        function getPerPageSelectBox($start=5, $end=30, $step=5, $showAllData=false, $extraParams=array())
        {
            require_once 'Pager/HtmlWidgets.php';
            $widget = new Pager_HtmlWidgets($this);
            return $widget->getPerPageSelectBox($start, $end, $step, $showAllData, $extraParams);
        }  
        function getPageSelectBox($params = array(), $extraAttributes = '')
        {
            require_once 'Pager/HtmlWidgets.php';
            $widget = new Pager_HtmlWidgets($this);
            return $widget->getPageSelectBox($params, $extraAttributes);
        }  
        function _printFirstPage()
        {
            if ($this->isFirstPage()) {
                return '';
            }
            $this->_linkData[$this->_urlVar] = 1;
            return $this->_renderLink(
                str_replace('%d', 1, $this->_altFirst),
                $this->_firstPagePre . $this->_firstPageText . $this->_firstPagePost
            ) . $this->_spacesBefore . $this->_spacesAfter;
        }  
        function _printLastPage()
        {
            if ($this->isLastPage()) {
                return '';
            }
            $this->_linkData[$this->_urlVar] = $this->_totalPages;
            return $this->_renderLink(
                str_replace('%d', $this->_totalPages, $this->_altLast),
                $this->_lastPagePre . $this->_lastPageText . $this->_lastPagePost
            );
        }  
        function _setFirstLastText()
        {
            if ($this->_firstPageText == '') {
                $this->_firstPageText = '1';
            }
            if ($this->_lastPageText == '') {
                $this->_lastPageText = $this->_totalPages;
            }
        }  

        function _http_build_query_wrapper($data)
        {
            $data = (array)$data;
            if (empty($data)) {
                return '';
            }
            $separator = ini_get('arg_separator.output');
            if ($separator == '&amp;') {
                $separator = '&'; 
            }
            $tmp = array ();
            foreach ($data as $key => $val) {
                if (is_scalar($val)) { 
                    $val = urlencode($val);
                    array_push($tmp, $key .'='. str_replace('%2F', '/', $val));
                    continue;
                } 
                if (is_array($val)) {
                    array_push($tmp, $this->__http_build_query($val, htmlentities($key)));
                    continue;
                }
            }
            return implode($separator, $tmp);
        }  
        function __http_build_query($array, $name)
        {
            $tmp = array ();
            $separator = ini_get('arg_separator.output');
            if ($separator == '&amp;') {
                $separator = '&'; 
            }
            foreach ($array as $key => $value) {
                if (is_array($value)) { 
                    array_push($tmp, $this->__http_build_query($value, $name.'%5B'.$key.'%5D'));
                } elseif (is_scalar($value)) { 
                    array_push($tmp, $name.'%5B'.htmlentities($key).'%5D='.htmlentities($value));
                } elseif (is_object($value)) { 
                    array_push($tmp, $this->__http_build_query(get_object_vars($value), $name.'%5B'.$key.'%5D'));
                }
            }
            return implode($separator, $tmp);
        }  
        function _isEncoded($string)
        {
            $hexchar = '&#[\dA-Fx]{2,};';
            return preg_match("/^(\s|($hexchar))*$/Uims", $string) ? true : false;
        }  
        function raiseError($msg, $code)
        {
            include_once 'PEAR.php';
            if (empty($this->_pearErrorMode)) {
                $this->_pearErrorMode = PEAR_ERROR_RETURN;
            }
            return PEAR::raiseError($msg, $code, $this->_pearErrorMode);
        }  
        function setOptions($options)
        {
            foreach ($options as $key => $value) {
                if (in_array($key, $this->_allowed_options) && (!is_null($value))) {
                    $this->{'_' . $key} = $value;
                }
            } 
            if (!isset($options['httpMethod'])
                && !isset($_GET[$this->_urlVar])
                && isset($_POST[$this->_urlVar])
            ) {
                $this->_httpMethod = 'POST';
            } else {
                $this->_httpMethod = strtoupper($this->_httpMethod);
            }
            $this->_fileName = ltrim($this->_fileName, '/'); 
            $this->_path = rtrim($this->_path, '/'); 
            if ($this->_append) {
                if ($this->_fixFileName) {
                    $this->_fileName = CURRENT_FILENAME; 
                }
                $this->_url = $this->_path.'/'.$this->_fileName;
            } else {
                $this->_url = $this->_path;
                if (strncasecmp($this->_fileName, 'javascript', 10) != 0) {
                    $this->_url .= '/';
                }
                if (strpos($this->_fileName, '%d') === false) {
                    trigger_error($this->errorMessage(ERROR_PAGER_INVALID_USAGE), E_USER_WARNING);
                }
            }
            $this->_classString = '';
            if (strlen($this->_linkClass)) {
                $this->_classString = 'class="'.$this->_linkClass.'"';
            }
            if (strlen($this->_curPageLinkClassName)) {
                $this->_curPageSpanPre = '<span class="'.$this->_curPageLinkClassName.'">';
                $this->_curPageSpanPost = '</span>';
            }
            $this->_perPage = max($this->_perPage, 1); 
            if ($this->_useSessions && !isset($_SESSION)) {
                session_start();
            }
            if (!empty($_REQUEST[$this->_sessionVar])) {
                $this->_perPage = max(1, (int)$_REQUEST[$this->_sessionVar]);
                if ($this->_useSessions) {
                    $_SESSION[$this->_sessionVar] = $this->_perPage;
                }
            }
            if (!empty($_SESSION[$this->_sessionVar])) {
                $this->_perPage = $_SESSION[$this->_sessionVar];
            }
            if ($this->_closeSession) {
                session_write_close();
            }
            $this->_spacesBefore = str_repeat('&nbsp;', $this->_spacesBeforeSeparator);
            $this->_spacesAfter = str_repeat('&nbsp;', $this->_spacesAfterSeparator);
            if (isset($_REQUEST[$this->_urlVar]) && empty($options['currentPage'])) {
                $this->_currentPage = (int)$_REQUEST[$this->_urlVar];
            }
            $this->_currentPage = max($this->_currentPage, 1);
            $this->_linkData = $this->_getLinksData();
            return PAGER_OK;
        }  

        function getOption($name)
        {
            if (!in_array($name, $this->_allowed_options)) {
                $msg = '<b>PEAR::Pager Error:</b>'
                .' invalid option: '.$name;
                return $this->raiseError($msg, ERROR_PAGER_INVALID);
            }
            return $this->{'_' . $name};
        }  
        function getOptions()
        {
            $options = array();
            foreach ($this->_allowed_options as $option) {
                $options[$option] = $this->{'_' . $option};
            }
            return $options;
        }  
        function errorMessage($code)
        {
            static $errorMessages;
            if (!isset($errorMessages)) {
                $errorMessages = array(
                    ERROR_PAGER => 'unknown error',
                    ERROR_PAGER_INVALID => 'invalid',
                    ERROR_PAGER_INVALID_PLACEHOLDER => 'invalid format - use "%d" as placeholder.',
                    ERROR_PAGER_INVALID_USAGE => 'if $options[\'append\'] is set to false, '
                    .' $options[\'fileName\'] MUST contain the "%d" placeholder.',
                    ERROR_PAGER_NOT_IMPLEMENTED => 'not implemented'
                );
            }
            return '<b>PEAR::Pager error:</b> '. (isset($errorMessages[$code]) ?
                $errorMessages[$code] : $errorMessages[ERROR_PAGER]);
        } 
    }