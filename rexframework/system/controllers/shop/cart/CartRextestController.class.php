<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexConfig as RexConfig;

/**
 * Class CartRextestController
 *
 * Cart Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  CartRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class CartRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexShop\CartEntity:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->_testClear();
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#Ваша корзина пуста!#', $result[0], 'Check for empty cart');
        
        if ($this->_testAdd()) {
            $this->_testClear();
        }
    }
    
    private function _testAdd()
    {
        $get = $this->generateGet($this->mod, 'add');
        
        $entity = RexFactory::entity('product');
        $maxId = XDatabase::getOne('SELECT MAX(`'.$entity->__uid.'`) FROM `product`');
        
        if (!$maxId)
            $maxId = 0;
        
        $entity->get($maxId);
        
        $post = array(
            'cart[submit]' => true,
            'cart[product_id]' => $entity->id,
            'cart[count]' => '2'
        );
        
        $result = $this->getDataFrontend($get, $post);
        
        if (!$this->assertEquals($result[1]['url'], 'http://'.RexConfig::get('Project', 'cookie_domain').'/cart/', 'Check redirect url') || !$this->assertRegExp('#name=\"cart\['.$entity->id.'\]\[count\]\"#', $result[0], 'Check for display data')) {
            return false;
        }
        
        return true;
    }
    
    private function _testClear()
    {
        $get = $this->generateGet($this->mod, 'clear');
        
        $result = $this->getDataFrontend($get);
        
        if (!$this->assertEquals($result[1]['url'], 'http://'.RexConfig::get('Project', 'cookie_domain').'/cart/', 'Check redirect url') || $this->assertRegExp('#Ваша корзина пуста!#', $result[0], 'Check for empty cart')) {
            return false;
        }
        
        return true;
    }
}