<?php
namespace RexFramework;

/**
 * Class StaticPageManager
 *
 * Manager of StaticPage
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class StaticPageManager extends DBManager
{
	public function __construct()
	{
		parent::__construct('staticpage', 'id');
	}
}