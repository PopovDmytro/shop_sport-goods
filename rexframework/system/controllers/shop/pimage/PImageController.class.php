<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;

/**
 * Class PImageController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PImageController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PImageController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
    );
}