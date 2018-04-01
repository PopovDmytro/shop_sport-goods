<?php
namespace RexShop;

/**
 * Class AttributeController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  AttributeController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class AttributeController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\AttributeEntity:shop.standart:1.0',
        'RexShop\AttributeManager:shop.standart:1.0',
    );
    
	/**
	 * getDefault
	 *
	 * @author  Fatal
	 * @class   AttributeController
	 * @access  public
	 * @return  void
	 */
	function getDefault()
	{
		//
	}
}