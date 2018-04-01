<?php
namespace RexFramework;

use \RexConfig as RexConfig;
use \RexFactory as RexFactory;

/**
 * Class SiteMapRextestController
 *
 * Site Map Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  SiteMapRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SiteMapRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    
    function testDefault()
    {
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions', '<?xml ', 6);
        
        $this->assertRegExp('#\<sitemapindex#', $result[0], 'Check dom element');
        
        $this->assertRegExp('#xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"#', $result[0], 'Check xmls attribute');
    }
    
    function testSiteForXML()
    {
        $get = $this->generateGet($this->mod, 'siteforxml');
        
        $result = $this->getDataFrontend($get);
        
        $manager = RexFactory::manager('staticPage');
        $manager->getByWhere('1');
        
        foreach ($manager->getCollection('object') as $key => $value) {
            $this->assertRegExp('#<loc>http://'.RexConfig::get('Project', 'cookie_domain').'/'.$value['alias'].'.html</loc>#', $result[0], 'Check xml fo url to "'.$value['alias'].'.html" page');
        }
    }
}