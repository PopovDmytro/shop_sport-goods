<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexDBList as RexDBList;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

/**
 * Class SkuElementManager
 *
 * Manager of SkuElement
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class SkuElementManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\SkuElementEntity:shop.standart:1.0',
    );
    
    function __construct()
    {
        parent::__construct('sku_element', 'id');
    }
        
}