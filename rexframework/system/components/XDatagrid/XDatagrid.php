<?php
/**
 * XDatagrid
 * 
 * XDatagrid is a class extends from Structures_DataGrid
 * This component can be used for following template routines:
 *   - create Structures_Datagrid and work with him 
 *
 * @package XFramework
 */

/**
 * Require PEAR::Structures_Datagrid
 */
require_once 'PEAR.php';
require_once 'Structures/DataGrid.php';

define('XDATAGRID_HEADER',      	'header');
define('XDATAGRID_NUMERABLE',   	'numerable');
define('XDATAGRID_FILLLINES',   	'fill');
define('XDATAGRID_PAGED',   		'paged');
define('XDATAGRID_LINES_COLORED',	'colored');
define('XDATAGRID_SHOWNORECORDS',	'show');
define('XDATAGRID_SEARCHSHOW',		'search');
define('XDATAGRID_SEARCHFULLTEXT',	'full');

define('XDATAGRID_TEMPLATE_DEFAULT',    dirname(__FILE__) . '/XDatagrid.tpl');
define('XDATAGRID_TEMPLATE_ROW',    dirname(__FILE__) . '/XDatagridRow.tpl');
define('XDATAGRID_TEMPLATE_COLUMN',     dirname(__FILE__) . '/XDatagridColumn.tpl');
define('XDATAGRID_TEMPLATE_COLUMN_ALT', dirname(__FILE__) . '/XDatagridColumnAlt.tpl');

/**
 * Class XDatagrid
 *
 * @author   Fatal
 * @access   public
 * @name     XDadagrid
 * @package  XFramework
 * @version  0.1
 */
class XDatagrid extends Structures_DataGrid
{
   /**
    * error
    *
    * @var PEAR_Error
    */
   var $__error;
	
   /**
    * user friendly URL
    *
    * @var __userFriendlyURL
    */
   var $__userFriendlyURL;
   
   /**
    * columns
    */
   var $__columns = array();
   
   /**
    * extraparams
    */
   var $__extraParams;
   
   /**
    * XDatagrid flags
    *
    * @var bool
    */
   var $__flags;
   
   	/**
	 * Search array
	 *
	 * @var array
	 */
	var $__searchArray = array();
	
	/**
	 * Fulltext search array
	 *
	 * @var array
	 */
	var $__searchFulltextArray = array();
	
	/**
	 * Search Field
	 *
	 * @var string
	 */
	var $__searchField;
	
	/**
	 * Search Query
	 *
	 * @var string
	 */
	var $__searchQuery;
   
	/**
	 * Constructor of XDatagrid
	 *
	 * @access  public
	 */
	function XDatagrid($aProperty = null) 
	{
        $this->__flags[XDATAGRID_HEADER]        = true;
		$this->__flags[XDATAGRID_NUMERABLE]     = true;
		$this->__flags[XDATAGRID_FILLLINES]     = true;
		$this->__flags[XDATAGRID_PAGED]         = true;
		$this->__flags[XDATAGRID_SHOWNORECORDS] = true;
		$this->__flags[XDATAGRID_LINES_COLORED] = true;
		$this->__flags[XDATAGRID_SEARCHSHOW]	= false;
		$this->__flags[XDATAGRID_SEARCHFULLTEXT]= false;
	}

	/**
	 * Destructor of XDatagrid 
	 *
	 * @access  public
	 */
	 function _XDatagrid()
	 {
	 	
	 }
	 
	 /**
	  * isNumerable
	  *
	  * show datagrid with or w/out numerable
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isNumerable($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_NUMERABLE] = $bool;
	 }
	 	 
	 /**
	  * isHeader
	  *
	  * show datagrid with or w/out header
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isHeader($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_HEADER] = $bool;
	 }
	 
	 /**
	  * isFillLines
	  *
	  * show datagrid with or w/out empty lines
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isFillLines($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_FILLLINES] = $bool;
	 }
	 
	/**
	  * isPaged
	  *
	  * show datagrid with or w/out pagination string
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isPaged($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_PAGED] = $bool;
	 }
	
	 /**
	  * isShowNoRecords
	  *
	  * show datagrid with or w/out "No Records Found" message, then SQL returns 0 records
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isShowNoRecords($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_SHOWNORECORDS] = $bool;
	 }
	
	 /**
	  * isLinesColored
	  *
	  * show datagrid with or w/out zebra coloring
	  *
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function isLinesColored($bool = true) 
	 {
	 	$this->__flags[XDATAGRID_LINES_COLORED] = $bool;
	 }

	 /**
	  * isSearch
	  *
	  * allow search
	  *
	  * @author  santific
	  * @class   XDatagrid
	  * @access  public
	  * @param   bool     $bool
	  */
	 function setSearch($aSearchArray = array(), $aSearchFulltextArray = array())
	 {
	 	$this->__flags[XDATAGRID_SEARCHSHOW]		= (!empty($aSearchArray) || !empty($aSearchFulltextArray))?true:false;

	 	$this->__searchArray         = $aSearchArray;
	 	$this->__searchFulltextArray = $aSearchFulltextArray;
	 }
	 
	 /**
	 * createDatagridSQL
	 *
	 * create new Datagrid by SQL query
	 *
	 * @access  public
	 * @param   mixed   $aSource  Datagrid source
	 * @param   integer $aLimit limit records on page
	 * @param   string  $aPrefix unique Datagrid prefix
	 * @param   array   $aDefault default sortable
	 * @param   string  $aRenderer
	 * @param   array   $aBindOptions
	 * @return  bool
	 */
	function createDatagrid($aSource, $aLimit = 10, $aPrefix = "DG", $aDefault = array(), $aRenderer = DATAGRID_RENDER_SMARTY, $aBindOptions = array()) 
	{
		parent::Structures_DataGrid($aLimit);
		parent::setDefaultSort($aDefault);
		parent::setRequestPrefix($aPrefix);
		parent::setRenderer($aRenderer);
		
		// string for XCore only
		if (defined('XCORE_SEO') && XCORE_SEO) {
			$this->isUserFriendlyURL(XCORE_SEO);
		}

//		if enable mod_rewrite		
		/*if ($this->__userFriendlyURL)*/ {
			$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?"));
			$this->setRendererOption('selfPath',	$path);
			//only for XCore
			//$this->setRendererOption('excludeVars',	array('xcmod', 'xcact', 'xcseo', 'xcurl'));
			
		}
		
		if (is_array($aSource)) {
			//insert params
		} elseif (is_string($aSource) && preg_match("/select/i", $aSource)) {
		    // replace prefix of tables
			$aSource					= XDatabase::replacePrefix($aSource);
			$aBindOptions["dbc"]		= XDatabase::getObject();
//			$aBindOptions["backend"]	= "MDB2";

			// add function for integrate search 
			if ($this->__flags[XDATAGRID_SEARCHSHOW]) {
				$aSource = $this->_setSearch($aSource);
			}
		} else {
			//insert params
		}
		
		$bind = parent::bind($aSource, $aBindOptions);

		if (PEAR::isError($bind)) {
			$this->_setError($bind);
		}
	}
	/**
	 * _setSearch
	 *
	 * integrated data to source
	 *
	 * @author  dark
	 * @class   XDatagrid
	 * @access  public
	 * @param   string     $aSQL  sql query
	 * @return  rettype  return
	 */
	function _setSearch($aSQL) 
	{
	    $this->__searchField = Request::get($this->_requestPrefix . 'field');
	    $this->__searchQuery = Request::get($this->_requestPrefix . 'query');
	    
	    if (!empty($this->__searchQuery)) {
	        
	        // integrated full text search
            if (!empty($this->_searchFulltextArray) &&
                 array_key_exists($this->__searchField, $this->_searchFulltextArray)) {
                     
            	$aSQL = $this->_setSearchFulltextSQL($aSQL);
            }
            
            // integrated text search
            if (!empty($this->__searchArray) && 
                array_key_exists($this->__searchField, $this->__searchArray)) {
                    
            	$aSQL = $this->_setSearchSQL($aSQL);
            }
	    }
	   return $aSQL;
	}
	/**
	 * _setSearchSQL
	 *
	 * @author  santific
	 * @class   XDatagrid
	 * @access  public
	 * @param   string     $aSource
	 * @return  string 
	 */
	function _setSearchSQL($aSource) 
	{
		$field = $this->__searchField;
		$word  = XDatabase::quote('%' . $this->__searchQuery . '%');
		
		$sqlOptions = 'group|having|order|limit|procedure|into|for';
		
		if (stristr($aSource, ' where ')) {				
			if (preg_match('/'.$sqlOptions.'/is', $aSource)) {
				$aSource = preg_replace( '/(.*where\s+)(.*)(('.$sqlOptions.')+.*)+/si', '$1 ($2) AND '.$field.' LIKE '.$word.' $3 ',$aSource);
			} else {
				$aSource = preg_replace( '/(.*where\s+)(.*)/si', '$1 ($2) AND '.$field.' LIKE '.$word, $aSource);
			}
		} else {
			if (preg_match('/'.$sqlOptions.'/is', $aSource)) {
				$aSource = preg_replace('/(.*from\s+)(.*)((' . $sqlOptions . ')+.*)+/si', ' $1 $2 WHERE ' . $field . ' LIKE ' . $word . ' $3', $aSource);
			} else {
				$aSource .=  ' WHERE ' . $field . ' LIKE ' . $word;
			}
		}
		return $aSource;
	}
	
	/**
	 * _setSearchFulltextSQL
	 *
	 * Generate fulltext search sql
	 *
	 * @author  santific
	 * @class   XDatagrid
	 * @access  public
	 * @param   string     $aSource  param_descr
	 * @return  strin  return
	 */
	function _setSearchFulltextSQL($aSource) 
	{
		$field = $this->__searchField;
		$word  = XDatabase::quote($this->__searchQuery);

		$sqlOptions = 'group|having|order|limit|procedure|into|for';
		$condition = ' MATCH (' . $field . ') AGAINST (' . $word . ') ';
		if (stristr($aSource, ' where ')) {
			if (preg_match('/'.$sqlOptions.'/is', $aSource)) {
				$aSource = preg_replace('/^(select\s+)(.*)(from\s+)(.*)(.*where\s+)(.*)((' . $sqlOptions . ')+.*)+$/si', ' $1 '.$condition.', $2 $3 $4 $5 ($6) AND MATCH (' . $field . ') AGAINST (' . $word . ') <> 0 $7 ', $aSource);
			} else {
				$aSource = preg_replace('/^(select\s+)(.*)(from\s+)(.*)(.*where\s+)(.*)/si', ' $1 '.$condition.', $2 $3 $4 $5 ($6) AND MATCH (' . $field . ') AGAINST (' . $word . ') <> 0 ', $aSource);
			}
		} else {
			if (preg_match('/'.$sqlOptions.'/is', $aSource)) {
				$aSource = preg_replace('/^(select\s+)(.*)(from\s+)(.*)(.*(' . $sqlOptions . ')+.*)+/si', ' $1 '.$condition.', $2 $3 $4 WHERE MATCH ( ' . $field . ') AGAINST (' . $word . ') <> 0 $5', $aSource);
			} else {
				$aSource = preg_replace('/^(select\s+)(.*)(from\s+)(.*)/si', ' $1 '.$condition.', $2 $3 $4 WHERE ' . $condition . ' <> 0 ', $aSource);
			}
		}
		
		return $aSource;
	}
	
	/**
	 * addColumn
	 *
	 * add column to Datagrid
	 *
	 * @author  dark
	 * @access  public
	 * 
     * @param   string      $aColumnName     The name of the column to be printed
     * @param   string      $aFieldName      The name of the field for the column
     *                                      to be mapped to
     * @param   string      $aOrderBy        The field to order the data by
     * @param   array       $aAttribs        The HTML attributes for the TR tag
     * @param   boolean     $autoFill       Whether or not to use the autoFill
     * @param   string      $aAutoFillValue  The value to use for the autoFill
     * @param   mixed       $aFormatter      A defined function to call upon
     *                                      rendering to allow for special
     *                                      formatting.  This allows for
     *                                      call-back function to print out a 
     *                                      link or a form element, or whatever 
     *                                      you can possibly think of.
     * @param   array       $formatterArgs  Associative array of arguments 
     *                                      passed as second argument to the 
     *                                      formatter callback
     * 
     * @return Structures_DataGrid_Column $column
	 */
	function &addColumn($aColumnName, $aFieldName = null,
                        $aOrderBy = null, $aAttribs = array(),
                        $aAutoFillValue = null,
                        $aFormatter = null,
                        $aFormatterArgs = array()) 
	{
	    $column = new Structures_DataGrid_Column(
													$aColumnName,
													$aFieldName,
													$aOrderBy,
													$aAttribs,
													$aAutoFillValue,
													$aFormatter,
													$aFormatterArgs
												 );
        $this->__columns[] = $column;
		return $column;
	}
	
	/**
	 * renderer
	 *
	 * renderer Datagrid
	 *
	 * @author  dark
	 * @class   XDatagrid
	 * @access  public
	 * @param   mixed  $param  params for renderer
	 * @return  mixed  $aRenderer
	 */
	function renderer($aParams = array()) 
	{
	    for ($i = 0, $size = sizeof($this->__columns); $i < $size; $i++) {	        
	    	parent::addColumn_($this->__columns[$i]);
	    }	     
	    
		// switch statement for $this->_rendererType
		switch ($this->_rendererType) {
			case DATAGRID_RENDER_SMARTY:
				return $this->_renderDatagridSmarty($aParams);
				break;
		
			default:
				return parent::render($this->_rendererType, $aParams);
				break;
		}
	}
	
	/**
	 * renderDatagridSmarty
	 *
	 * render current Datagrid used Smarty
	 *
	 * @author  dark
	 * @class   XDatagrid
	 * @access  public
	 * @param   array  $aParams
	 * @return  mixed  $HTML
	 */
	function _renderDatagridSmarty($aParams = array()) 
	{
	    if (!empty($aParams['template'])) {
	        // switch statement for $aParams['template']
	        switch ($aParams['template']) {
	        	case XDATAGRID_TEMPLATE_DEFAULT:
	        		$aTemplate = XDATAGRID_TEMPLATE_DEFAULT;
	        		break;
                case 'dgrow':
                    $aTemplate = XDATAGRID_TEMPLATE_ROW;
                    break;
	        	case XDATAGRID_TEMPLATE_COLUMN:
	        		$aTemplate = XDATAGRID_TEMPLATE_COLUMN;
	        		break;
	        	case XDATAGRID_TEMPLATE_COLUMN_ALT:
	        		$aTemplate = XDATAGRID_TEMPLATE_COLUMN_ALT;
	        		break;	        
	        	default:
	        	    $aTemplate = $aParams['template'];
	        		break;
	        }
	        
	        if (!empty($aParams['columns'])) {
		          RexDisplay::assign("columns", $aParams['columns']);
	        }
	    } else {
	       $aTemplate = XDATAGRID_TEMPLATE_DEFAULT; 
	    }
	    
		/**
		 * fill current Smarty container
		 */
		$fill = parent::fill(RexDisplay::$engine->engine);

		if (PEAR::isError($fill)) {
			$this->_setError($fill);
			return $fill;
		}
		
		$columnPreSet = array();
		
		foreach ($this->columnSet as $fKey => $fValue) {
			$columnPreSet[$fKey] = new stdClass();
			$columnPreSet[$fKey]->columnName	= $fValue->columnName;
			$columnPreSet[$fKey]->fieldName		= $fValue->fieldName;
			$columnPreSet[$fKey]->orderBy		= $fValue->orderBy;
			$columnPreSet[$fKey]->autoFillValue	= $fValue->autoFillValue;
		}
		// if enable mod_rewrite		
		/*if ($this->__userFriendlyURL)*/ {
			
			$PROTOCOL = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';

			if (strpos($_SERVER['REQUEST_URI'], '?')) {
				$PATH = $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?"));
				
			} else {
				$PATH = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			}
			
			if (substr($PATH, -1) == "/") {
				//nothing
			} else {
				$PATH = dirname($PATH);
			}
			
			RexDisplay::assign("PATH",	$PROTOCOL . $PATH);
		}
		
		RexDisplay::assign("flagHeader",          $this->__flags[XDATAGRID_HEADER       ]);
		RexDisplay::assign("flagNumerable",       $this->__flags[XDATAGRID_NUMERABLE    ]);
		RexDisplay::assign("flagFillLines",       $this->__flags[XDATAGRID_FILLLINES    ]);
		RexDisplay::assign("flagPaged",	       $this->__flags[XDATAGRID_PAGED        ]);
		RexDisplay::assign("flagShowNoRecords",   $this->__flags[XDATAGRID_SHOWNORECORDS]);
		RexDisplay::assign("flagLinesColored",	   $this->__flags[XDATAGRID_LINES_COLORED]);
		RexDisplay::assign("flagSearchForm",	   $this->__flags[XDATAGRID_SEARCHSHOW   ]);
		
		if ($this->__flags[XDATAGRID_SEARCHSHOW   ]) {
    		RexDisplay::assign("search_select",		array_merge($this->__searchArray, $this->__searchFulltextArray));
    		RexDisplay::assign("search_selected",		$this->__searchField);
    		RexDisplay::assign("search_last_value",	$this->__searchQuery);
		}
		
		RexDisplay::assign("requestPrefix",   $this->_requestPrefix);
		RexDisplay::assign("columnPreSet",    $columnPreSet);
		
		return RexDisplay::fetch('file:/'.$aTemplate);
	}
	
	/**
	 * isUserFriendlyURL
	 *
	 * set user friendly URL
	 *
	 * @access  public
	 * @param   bool     $bool
	 */
	function isUserFriendlyURL($bool = false) 
	{
		if (is_bool($bool)) {
			$this->__userFriendlyURL = $bool;
		} elseif (is_string($bool)) {
			if ($bool == 'true') {
				$this->__userFriendlyURL = true;
			} else {
				$this->__userFriendlyURL = false;
			}
			
		}
	}
	
    /**
     * set PEAR error 
     *
     * @access public
     * 
     * @return string
     */
    function _setError($aError)
    {
    	if (PEAR::isError($aError)) {
        	$this->__error = $aError;
    	} else {
    		return null;
    	}
    }
    
    
    /**
     * check db error
     *
     * @access public
     * 
     * @return bool
     */
    function isError()
    {
        if ($this->__error) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * return PEAR error 
     *
     * @access public
     * 
     * @param bool $aIsPrint
     * 
     * @return string
     */
    function getError($aIsPrint = false)
    {
        if ($aIsPrint) {
        	if (PEAR::isError($this->__error)) {
            	return $this->__error->getMessage();
        	} else {
        		return null;
        	}
        } else {
            return $this->__error;
        }
    }
}
?>