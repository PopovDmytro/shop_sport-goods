<?php

define('DOCUMENT_ERROR',        1);
define('DOCUMENT_MESSAGE',      2);

/**
* Class RexPage
*
* class for work with system messages
*
* @author   Fatal
*
* @access   public
*
* @package  RexFramework
*
* @created  Mon Jul 31 12:25:12 EEST 2006
*/
class RexPage extends RexObject
{
    protected static $_container;

    protected static $title;
    protected static $description;
    protected static $keywords;

    public static function allow($controller, $action)
    {
        return true;
    }

    /**
    * setTitle
    *
    * set page title
    *
    * @access  public
    * @param   string     $aTitle
    */
    public static function setTitle($title)
    {
        static::$title = $title;
    }

    /**
    * getTitle
    *
    * get page title
    *
    * @access  public
    */
    public static function getTitle()
    {
        return static::$title;
    }

    /**
    * setDescription
    *
    * set page description
    *
    * @access  public
    * @param   string     $aDescription
    */
    public static function setDescription($description)
    {
        static::$description = $description;
    }

    /**
    * getDescription
    *
    * get page description
    *
    * @access  public
    */
    public static function getDescription()
    {
        return static::$description;
    }

    /**
    * setKeywords
    *
    * set page keywords
    *
    * @access  public
    * @param   string     $aKeywords
    */
    public static function setKeywords($keywords)
    {
        static::$keywords = $keywords;
    }

    /**
    * getKeywords
    *
    * get page keywords
    *
    * @access  public
    */
    public static function getKeywords()
    {
        return static::$keywords;
    }

    /**
    * getHead
    *
    * return HTML code for tag head
    *
    * @class   RexPage
    * @access  public
    * @return  string  $HTML
    */
    /*function getHead()
    {

    $HTML = "<title>".$this->getTitle() ."</title>\n";
    $HTML .= $this->getMeta(null, DOCUMENT_META);
    $HTML .= $this->getMeta(null, DOCUMENT_METAHTTP);
    $HTML .= $this->getLinks();
    $HTML .= $this->getJavaScripts();

    return $HTML;
    }*/

    /**
    * _addMessage
    *
    * add new message to container
    *
    * @access  public
    * @param   integer     $aType
    * @param   string     $aMessage
    * @param   string		$aSection
    * @param   string		$aSubSection
    *
    */
    public static function _addMessage($aType, $aMessage, $aSection = 'main', $aSubSection = null)
    {
        if ($aSubSection) {
            if (is_array($aMessage)) {
                if (empty(static::$_container[$aType][$aSection][$aSubSection])) static::$_container[$aType][$aSection][$aSubSection] = array();
                static::$_container[$aType][$aSection][$aSubSection] = array_merge(static::$_container[$aType][$aSection][$aSubSection], $aMessage);
            } else {
                static::$_container[$aType][$aSection][$aSubSection][] = $aMessage;
            }
        } else {

            if (is_array($aMessage)) {
                if (empty(static::$_container[$aType][$aSection])) static::$_container[$aType][$aSection] = array();
                static::$_container[$aType][$aSection] = array_merge(static::$_container[$aType][$aSection], $aMessage);
            } else {
                static::$_container[$aType][$aSection][] = $aMessage;
            }
        }
    }

    /**
    * _getMessage
    *
    * add new message to container
    *
    * @access  public
    * @param   integer     $aType
    * @param   string     $aMessage
    * @param   string		$aSection
    * @param   string		$aSubSection
    *
    */
    public static function _getMessage($aType, $aSection = 'main', $aSubSection = null)
    {
        try {
            if ($aSubSection) {
                return @static::$_container[$aType][$aSection][$aSubSection];
            } else {
                return @static::$_container[$aType][$aSection];
            }
        } catch (Exception $e) {
            return '';
        }
    }

    /**
    * addError
    *
    * add new error message
    *
    * @access  public
    * @param   string     $aMessage
    * @param   string		$aSection
    * @param   string		$aSubSection
    */
    public static function addError($aMessage, $aSection = 'main', $aSubSection = null)
    {
        static::_addMessage(DOCUMENT_ERROR, $aMessage, $aSection, $aSubSection);
    }

    /**
    * isError
    *
    * @access  public
    * @param   string		$aSection
    * @param   string		$aSubSection
    */
    public static function isError($aSection = 'main', $aSubSection = null)
    {
        if (isset(static::$_container[DOCUMENT_ERROR][$aSection]) and sizeof(static::$_container[DOCUMENT_ERROR][$aSection]) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * addMessage
    *
    * add new system message
    *
    * @access  public
    * @param   string     $aMessage
    * @param   string		$aSection
    * @param   string		$aSubSection
    */
    public static function addMessage($aMessage, $aSection = 'main', $aSubSection = null)
    {
        static::_addMessage(DOCUMENT_MESSAGE, $aMessage, $aSection, $aSubSection);
    }


    /**
    * getErrors
    *
    * return all error by section (subsection)
    *
    * @access  public
    * @param   type     $param  param_descr
    * @return  rettype  return
    */
    public static function getErrors($aSection = 'main', $aSubSection = null)
    {
        return static::_getMessage(DOCUMENT_ERROR, $aSection, $aSubSection);
    }

    /**
    * getRenderedErrors
    *
    * return all error by section (subsection)
    *
    * @access  public
    * @param   type     $param  param_descr
    * @return  rettype  return
    */
    public static function getRenderedErrors($aSection = 'main', $aSubSection = null)
    {
        $arrErrors = static::_getMessage(DOCUMENT_ERROR, $aSection, $aSubSection);

        RexDisplay::assign('arrErrors', $arrErrors);
        return RexDisplay::fetch('_block/errors.tpl');
    }

    /**
    * getMessages
    *
    * return all messages by section (subsection)
    *
    * @access  public
    * @param   type     $param  param_descr
    * @return  rettype  return
    */
    public static function getMessages($aSection = 'main', $aSubSection = null)
    {
        return static::_getMessage(DOCUMENT_MESSAGE, $aSection, $aSubSection);
    }

    /**
    * getRenderedMessages
    *
    * return all messages by section (subsection)
    *
    * @access  public
    * @param   type     $param  param_descr
    * @return  rettype  return
    */
    public static function getRenderedMessages($aSection = 'main', $aSubSection = null)
    {
        $arrMessages = static::_getMessage(DOCUMENT_MESSAGE, $aSection, $aSubSection, static::$_container);
        RexDisplay::assign('arrMessages', $arrMessages);
        return RexDisplay::fetch('_block/messages.tpl');
    }

    /**
    * callMethod
    *
    * Method for call it from smarty plugin
    *
    * @class   RexPage
    * @access  public
    * @param   string     $type
    * @return  string
    */
    public static function callMethod($aType, $aSection='main')
    {
        switch ($aType){
            case 'title':
                return static::getTitle();
            case 'description':
                return static::getDescription();
            case 'keywords':
                return static::getKeywords();
            case 'head':
                return static::getHead();
            case 'getRenderedErrors':
                return static::getRenderedErrors($aSection);
            case 'getRenderedMessages':
                return static::getRenderedMessages($aSection);
            default:
                if(method_exists('RexPage', $aType)){
                    return RexPage::$aType();
                }
                return '';
        }
    }
}