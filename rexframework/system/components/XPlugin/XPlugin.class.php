<?php

/**
 * Class XPlugin
 *
 * Plugin Parent Class
 *
 * @author   Fatal
 * @access   public
 * @package  XPlugin.class.php
 * @created  Thu Sep 06 10:09:30 EEST 2007
 */
class XPlugin 
{
	var $name;
	
	/**
	 * Constructor of XPlugin
	 *
	 * @access  public
	 */
	function XPlugin($aName) 
	{
		$this->name = $aName;
	}
	
}

?>