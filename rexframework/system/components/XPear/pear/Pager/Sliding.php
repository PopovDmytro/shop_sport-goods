<?php
    require_once 'Pager/Common.php';
    class Pager_Sliding extends Pager_Common
    { 
        function Pager_Sliding($options = array())
        { 
            $this->_delta = 2;
            $this->_prevImg = '&laquo;';
            $this->_nextImg = '&raquo;';
            $this->_separator = '|';
            $this->_spacesBeforeSeparator = 3;
            $this->_spacesAfterSeparator = 3;
            $this->_curPageSpanPre = '<b><u>';
            $this->_curPageSpanPost = '</u></b>'; 
            $err = $this->setOptions($options);
            if ($err !== PAGER_OK) {
                return $this->raiseError($this->errorMessage($err), $err);
            }
            $this->build();
        }  
        function build()
        { 
            $this->_pageData = array();
            $this->links = '';
            $this->_generatePageData();
            $this->_setFirstLastText();
            if ($this->_totalPages > (2 * $this->_delta + 1)) {
                $this->links .= $this->_printFirstPage();
            }
            $this->links .= $this->_getBackLink();
            $this->links .= $this->_getPageLinks();
            $this->links .= $this->_getNextLink();
            $this->linkTags .= $this->_getFirstLinkTag();
            $this->linkTags .= $this->_getPrevLinkTag();
            $this->linkTags .= $this->_getNextLinkTag();
            $this->linkTags .= $this->_getLastLinkTag();
            if ($this->_totalPages > (2 * $this->_delta + 1)) {
                $this->links .= $this->_printLastPage();
            }
        }  
        function getPageIdByOffset($index=null) { }  
        function getPageRangeByPageId($pageid = null)
        {
            $pageid = isset($pageid) ? (int)$pageid : $this->_currentPage;
            if (!isset($this->_pageData)) {
                $this->_generatePageData();
            }
            if (isset($this->_pageData[$pageid]) || is_null($this->_itemData)) {
                if ($this->_expanded) {
                    $min_surplus = ($pageid <= $this->_delta) ? ($this->_delta - $pageid + 1) : 0;
                    $max_surplus = ($pageid >= ($this->_totalPages - $this->_delta)) ?
                    ($pageid - ($this->_totalPages - $this->_delta)) : 0;
                } else {
                    $min_surplus = $max_surplus = 0;
                }
                return array(
                    max($pageid - $this->_delta - $max_surplus, 1),
                    min($pageid + $this->_delta + $min_surplus, $this->_totalPages)
                );
            }
            return array(0, 0);
        }  
        function getLinks($pageID = null, $next_html='')
        {
            if ($pageID != null) {
                $_sav = $this->_currentPage;
                $this->_currentPage = $pageID;
                $this->links = '';
                if ($this->_totalPages > (2 * $this->_delta + 1)) {
                    $this->links .= $this->_printFirstPage();
                }
                $this->links .= $this->_getBackLink();
                $this->links .= $this->_getPageLinks();
                $this->links .= $this->_getNextLink();
                if ($this->_totalPages > (2 * $this->_delta + 1)) {
                    $this->links .= $this->_printLastPage();
                }
            }
            $back = str_replace('&nbsp;', '', $this->_getBackLink());
            $next = str_replace('&nbsp;', '', $this->_getNextLink());
            $pages = $this->_getPageLinks();
            $first = $this->_printFirstPage();
            $last = $this->_printLastPage();
            $all = $this->links;
            $linkTags = $this->linkTags;
            if ($pageID != null) {
                $this->_currentPage = $_sav;
            }
            return array(
                $back,
                $pages,
                trim($next),
                $first,
                $last,
                $all,
                $linkTags,
                'back' => $back,
                'pages' => $pages,
                'next' => $next,
                'first' => $first,
                'last' => $last,
                'all' => $all,
                'linktags' => $linkTags
            );
        }  
        function _getPageLinks($url = '')
        {  
            if (!empty($url)) {
                $this->_path = $url;
            } 
            if ($this->_clearIfVoid && ($this->_totalPages < 2)) {
                return '';
            }
            $links = '';
            if ($this->_totalPages > (2 * $this->_delta + 1)) {
                if ($this->_expanded) {
                    if (($this->_totalPages - $this->_delta) <= $this->_currentPage) {
                        $expansion_before = $this->_currentPage - ($this->_totalPages - $this->_delta);
                    } else {
                        $expansion_before = 0;
                    }
                    for ($i = $this->_currentPage - $this->_delta - $expansion_before; $expansion_before; $expansion_before--, $i++) {
                        $print_separator_flag = ($i != $this->_currentPage + $this->_delta); 

                        $this->range[$i] = false;
                        $this->_linkData[$this->_urlVar] = $i;
                        $links .= $this->_renderLink($this->_altPage.' '.$i, $i)
                        . $this->_spacesBefore
                        . ($print_separator_flag ? $this->_separator.$this->_spacesAfter : '');
                    }
                }
                $expansion_after = 0;
                for ($i = $this->_currentPage - $this->_delta; ($i <= $this->_currentPage + $this->_delta) && ($i <= $this->_totalPages); $i++) {
                    if ($i < 1) {
                        ++$expansion_after;
                        continue;
                    } 
                    $print_separator_flag = (($i != $this->_currentPage + $this->_delta) && ($i != $this->_totalPages));
                    if ($i == $this->_currentPage) {
                        $this->range[$i] = true;
                        $links .= $this->_curPageSpanPre . $i . $this->_curPageSpanPost;
                    } else {
                        $this->range[$i] = false;
                        $this->_linkData[$this->_urlVar] = $i;
                        $links .= $this->_renderLink($this->_altPage.' '.$i, $i);
                    }
                    $links .= $this->_spacesBefore
                    . ($print_separator_flag ? $this->_separator.$this->_spacesAfter : '');
                }
                if ($this->_expanded && $expansion_after) {
                    $links .= $this->_separator . $this->_spacesAfter;
                    for ($i = $this->_currentPage + $this->_delta +1; $expansion_after; $expansion_after--, $i++) {
                        $print_separator_flag = ($expansion_after != 1);
                        $this->range[$i] = false;
                        $this->_linkData[$this->_urlVar] = $i;
                        $links .= $this->_renderLink($this->_altPage.' '.$i, $i)
                        . $this->_spacesBefore
                        . ($print_separator_flag ? $this->_separator.$this->_spacesAfter : '');
                    }
                }
            } else { 
                for ($i=1; $i<=$this->_totalPages; $i++) {
                    if ($i != $this->_currentPage) {
                        $this->range[$i] = false;
                        $this->_linkData[$this->_urlVar] = $i;
                        $links .= $this->_renderLink($this->_altPage.' '.$i, $i);
                    } else {
                        $this->range[$i] = true;
                        $links .= $this->_curPageSpanPre . $i . $this->_curPageSpanPost;
                    }
                    $links .= $this->_spacesBefore
                    . (($i != $this->_totalPages) ? $this->_separator.$this->_spacesAfter : '');
                }
            }
            return $links;
        } 
    }
?>