<?php
namespace RexFramework;

use \RexConfig as RexConfig;
use \RexFactory as RexFactory;

/**
 * Class ShopSiteMapRextestController
 *
 * Shop Site Map Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  ShopSiteMapRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class ShopSiteMapRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexFramework\StaticPageManager:standart:1.0',
        'RexFramework\PCatalogManager:standart:1.0'
    );
    
    function testSiteForXML()
    {
        $get = $this->generateGet($this->mod, 'siteforxml');
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $manager = RexFactory::manager('staticPage');
        $manager->getByWhere('1');
        
        foreach ($manager->getCollection('object') as $key => $value) {
            $this->assertRegExp('#<loc>http://'.RexConfig::get('Project', 'cookie_domain').'/'.$value['alias'].'.html</loc>#', $result[0], 'Check xml fo url to "'.$value['alias'].'.html" page');
        }
    }
    
    function testCatalogForXML()
    {
        $get = $this->generateGet($this->mod, 'catalogforxml');
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $manager = RexFactory::manager('pCatalog');
        $manager->getByWhere('1');
        
        foreach ($manager->getCollection('object') as $key => $value) {
            $this->assertRegExp('#<loc>http://'.RexConfig::get('Project', 'cookie_domain').'/catalog/'.$value['alias'].'/</loc>#', $result[0], 'Check xml fo url to "'.$value['alias'].'.html" page');
        }
    }    
}