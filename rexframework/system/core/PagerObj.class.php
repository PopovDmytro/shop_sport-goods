<?php

/**
 * Class PagerObj
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class PagerObj extends RexObject
{
    public static $assemble = 'standart';
    public static $version = '1.0';
    
	var $name;
	var $perPage;
	var $currentPage;
	var $from;	
	var $count;
	var $pages;
	
	/**
	 * Constructor of PagerObj
	 *
	 * @access  public
	 */
	public function __construct($aName, $aPerPage, $aPage)
	{
		$this->name 		= $aName;
		$this->perPage 		= $aPerPage;
		$this->currentPage 	= $aPage;
		$this->from 		= ($this->currentPage-1)*$this->perPage;
		$this->setCount(0);
		$this->pages		= array();
        
        parent::__construct();
	}
	
	/**
	 * getPerPage
	 *
	 * @author  Fatal
	 * @class   PagerObj
	 * @access  public
	 * @return  void
	 */
	function getPerPage()
	{
		return $this->perPage;
	}
	
	/**
	 * getFrom
	 *
	 * @author  Fatal
	 * @class   PagerObj
	 * @access  public
	 * @return  void
	 */
	function getFrom()
	{
		return $this->from;
	}
	
	/**
	 * setCount
	 *
	 * @author  Fatal
	 * @class   PagerObj
	 * @access  public
	 * @return  void
	 */
	function setCount($aCount)
	{
		$this->count = $aCount;
	}
	
	/**
	 * generatePages
	 *
	 * @author  Fatal
	 * @class   PagerObj
	 * @access  public
	 * @return  void
	 */
	function generatePages()
	{
		for ($i=0; $i<$this->count; $i+=$this->perPage) {
			$this->pages[] = intval($i/$this->perPage) + 1;
		}
		
		//hack
//		Sys::dump($this->pages);
//		$tmp = array();
//		if (isset($this->pages[0])) {
//			$tmp[] = $this->pages[0];
//		}
//		if (isset($this->pages[sizeof($this->pages)-1]) and $this->pages[sizeof($this->pages)-1] > 1) {
//			$tmp[] = $this->pages[sizeof($this->pages)-1];
//		}
//		$this->pages = $tmp;
	}
}

?>