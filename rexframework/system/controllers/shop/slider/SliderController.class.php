<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \RexRunner as RexRunner;
use \XDatabase as XDatabase;
use \PagerObj as PagerObj;

/**
 * Class SliderController
 *
 * @author   Fatal
 * @access   public
 * @package  SliderController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SliderController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SliderEntity:shop.standart:1.0',
        'RexShop\SliderManager:shop.standart:1.0',
        'PagerObj:standart:1.0'
    );

    function getHome()
    {
        $this->manager->getMainSlider();
        $sliderHome = $this->manager->getCollection();
        RexDisplay::assign('sliderHome', $sliderHome);
    }
}