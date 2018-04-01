<?php
namespace RexFramework;

use \RexRoute as RexRoute;
use \Request as Request;
use \RexSettings as RexSettings;
use \RexConfig as RexConfig;
use \XDatabase as XDatabase;
use \DomDocument as DomDocument;

/**
 * Class HomeController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SiteMapController extends ParentController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    public static $needed = array(
        'RexFramework\ParentController:standart:1.0'
        
    );
    
    /**
     * getDefault
     *
     * @author  Fatal
     * @class   HomeController
     * @access  public
     * @return  void
     */
    function getDefault()
    {   
        $dom = new DomDocument('1.0', 'UTF-8'); 
        $sitemapindex = $dom->appendChild($dom->createElement('sitemapindex'));
        $sitemapindex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        $dom = $this->domContent($dom, $sitemapindex);
        
        $dom->formatOutput = true;
                                   
        $result = $dom->saveXML();
        header ('Content-Type:text/xml');
        echo $result;
        exit();
    }
    
    protected function domContent($dom, $sitemapindex) 
    {
        $dom = $this->domContentStaticPage($dom, $sitemapindex);
        
        return $dom; 
    }
    
    protected function domContentStaticPage($dom, $sitemapindex) 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        ///get count static page
        $sql = "SELECT COUNT(*) AS 'count' FROM staticpage AS st
                WHERE st.active = 1
                LIMIT 1";
        try { 
        $countStaticPage = intval(XDatabase::getOne($sql)); 
        $countStaticPage = $this->getCountPage($countStaticPage, $countInXML);
        } catch (Exception $e) {
            throw new Exception('Site map: Static page "'.$e);    
        }
        
        for ($i = 0; $i < $countStaticPage; $i++) {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
            $loc = $sitemap->appendChild($dom->createElement('loc'));
            //$lastmod = $sitemap->appendChild($dom->createElement('lastmod'));;
            $loc->appendChild($dom->createTextNode('http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('mod' => 'siteMap', 'act' => 'siteForXML', 'task' => $i)))); 
            //$lastmod->appendChild($dom->createTextNode(date('Y-m-d H:i:s')));
        }
        return $dom; 
    }
    
    protected function getCountPage($count, $countInXML) 
    {
      if ($count % $countInXML == 0) 
        {
           return intval($count / $countInXML); 
        } else {
           return intval(($count / $countInXML) +1); 
        }  
    }
    
    function getSiteForXML () 
    {   
        $pager = Request::get('task', false);
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = "SELECT st.alias AS 'alias' FROM staticpage AS st
                WHERE st.active = 1
                LIMIT ".intval($pager*$countInXML).', '. $countInXML;
        $staticpages = XDatabase::getAll($sql);
        $dom = new DomDocument('1.0', 'UTF-8'); 

        $urlset = $dom->appendChild($dom->createElement('urlset'));
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        foreach ($staticpages as $key => $staticpage) {
            $url = $urlset->appendChild($dom->createElement('url'));
            $loc = $url->appendChild($dom->createElement('loc'));
            //$lastmod = $url->appendChild($dom->createElement('lastmod'));
            $changefreq = $url->appendChild($dom->createElement('changefreq'));
            $priority = $url->appendChild($dom->createElement('priority'));
            $loc->appendChild($dom->createTextNode('http://'.str_replace("//","/",RexConfig::get('Project', 'domain').RexRoute::getUrl(array('mod' => 'staticPage', 'act' => 'default', 'task' => $staticpage['alias']))))); 
            //$lastmod->appendChild($dom->createTextNode(date('Y-m-d H:i:s')));
            $changefreq->appendChild($dom->createTextNode('weekly'));
            $priority->appendChild($dom->createTextNode('1.0'));
        }
        
        $dom->formatOutput = true;
                                   
        $test1 = $dom->saveXML();
        header ('Content-Type:text/xml');
        echo $test1; exit();
    }
}