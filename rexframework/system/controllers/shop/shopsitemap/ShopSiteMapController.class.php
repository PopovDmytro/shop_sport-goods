<?php
namespace RexShop;

use \RexRoute as RexRoute;
use \Request as Request;
use \RexSettings as RexSettings;
use \RexConfig as RexConfig;
use \XDatabase as XDatabase;
use \DomDocument as DomDocument;
use \RexRunner as RexRunner;


class ShopSiteMapController extends \RexFramework\SiteMapController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
     
    public static $needed = array(
        'RexFramework\ParentController:standart:1.0'
        
    );
    
    protected function domContent($dom, $sitemapindex) 
    {
        $dom = $this->domContentStaticPage($dom, $sitemapindex);
        $dom = $this->domContentNews($dom, $sitemapindex);
        $dom = $this->domContentCatalog($dom, $sitemapindex);
        $dom = $this->domContentProduct($dom, $sitemapindex);
        
        return $dom; 
    }
    
    protected function domContentNews($dom, $sitemapindex) 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        
        $sql = 'SELECT COUNT(*) FROM news';
        try { 
            $countStaticPage = intval(XDatabase::getOne($sql)); 
            $countStaticPage = parent::getCountPage($countStaticPage, $countInXML);
        } catch (Exception $e) {
            throw new Exception('Site map: Static page "'.$e);    
        }
        
        for ($i = 0; $i < $countStaticPage; $i++) {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
            $loc = $sitemap->appendChild($dom->createElement('loc'));
            
            $loc->appendChild($dom->createTextNode('http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('mod' => 'shopSiteMap', 'act' => 'newsForXML', 'task' => $i)))); 
        }
        return $dom; 
    }
    
    protected function domContentStaticPage($dom, $sitemapindex) 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        
        $sql = 'SELECT COUNT(*) FROM staticpage WHERE active = 1';
        try { 
            $countStaticPage = intval(XDatabase::getOne($sql)); 
            $countStaticPage = parent::getCountPage($countStaticPage, $countInXML);
        } catch (Exception $e) {
            throw new Exception('Site map: Static page "'.$e);    
        }
        
        for ($i = 0; $i < $countStaticPage; $i++) {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
            $loc = $sitemap->appendChild($dom->createElement('loc'));
            
            $loc->appendChild($dom->createTextNode('http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl(array('mod' => 'shopSiteMap', 'act' => 'siteForXML', 'task' => $i)))); 
        }
        return $dom; 
    }
    
    protected function domContentCatalog($dom, $sitemapindex) 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = 'SELECT COUNT(*) FROM pcatalog WHERE active = 1';
        try { 
            $countCatalog = intval(XDatabase::getOne($sql));
            $countCatalog = parent::getCountPage($countCatalog, $countInXML);
        } catch (Exception $e) {
            throw new Exception('Site map: Catalog "'.$e);    
        }
        
        for ($i = 0; $i < $countCatalog; $i++) {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
            $loc = $sitemap->appendChild($dom->createElement('loc'));
            $lastmod = $sitemap->appendChild($dom->createElement('lastmod'));;
            $loc->appendChild($dom->createTextNode('http://'.str_replace("//","/",RexConfig::get('Project', 'domain').RexRoute::getUrl(array('mod' => 'shopSiteMap', 'act' => 'catalogForXML', 'task' => $i))))); 
            $sql = "SELECT MAX(`date_create`) AS 'update' FROM 
                    (SELECT *
                    FROM `pcatalog` AS pc
                    WHERE pc.`active` = 1
                    LIMIT ".$i*$countInXML.','.$countInXML.') AS t1';
            $updateCatalog = XDatabase::getOne($sql);
            $lastmod->appendChild($dom->createTextNode($updateCatalog));
        }
        return $dom; 
    }
    
    protected function domContentProduct($dom, $sitemapindex) 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = 'SELECT COUNT(*) FROM product WHERE active = 1';
        try { 
            $countProduct = intval(XDatabase::getOne($sql));
            $countProduct = parent::getCountPage($countProduct, $countInXML);
        } catch (Exception $e) {
            throw new Exception('Site map: Product "'.$e);    
        }
        
        for ($i = 0; $i < $countProduct; $i++) {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
            $loc = $sitemap->appendChild($dom->createElement('loc'));
            $lastmod = $sitemap->appendChild($dom->createElement('lastmod'));;
            $loc->appendChild($dom->createTextNode('http://'.str_replace("//","/",RexConfig::get('Project', 'domain').RexRoute::getUrl(array('mod' => 'shopSiteMap', 'act' => 'productForXML', 'task' => $i))))); 
            $sql_date = "SELECT MAX(`date_update`) AS 'update' FROM 
                        (SELECT *
                        FROM `product` AS pr
                        WHERE pr.`active` = 1
                        LIMIT ".$i*$countInXML.','.$countInXML.') AS t2';
            $updateProduct = XDatabase::getOne($sql_date);
            $lastmod->appendChild($dom->createTextNode($updateProduct));
        }
        return $dom; 
    }
    
    public function getNewsForXML () 
    {   
        $pager = Request::get('task', false);
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = 'SELECT alias FROM news LIMIT '.intval($pager*$countInXML).', '. $countInXML;
        $this->writeXMLDocument($sql, array('mod' => 'news', 'act' => 'default'), false, 'task', 'alias');
    }
    
    public function getSiteForXML () 
    {   
        $pager = Request::get('task', false);
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = 'SELECT alias FROM staticpage WHERE active = 1 LIMIT '.intval($pager*$countInXML).', '. $countInXML;
        $this->writeXMLDocument($sql, array('mod' => 'staticPage', 'act' => 'default'), false, 'task', 'alias');
    }
    
    
    public function getCatalogForXML () 
    {   
        $pager = Request::get('task', false);
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $sql = 'SELECT alias, date_create FROM pcatalog WHERE active = 1 LIMIT '.intval($pager*$countInXML).', '. $countInXML;
        $this->writeXMLDocument($sql, array('mod' => 'pCatalog', 'act' => 'default'), 'date_create', 'task', 'alias');
    }
    
    public function getProductForXML () 
    {
        $countInXML = RexSettings::get('count_data_in_xml_file');
        $pager = Request::get('task', false);
        $sql = 'SELECT p.`id`, p.`name`, p.`date_update`, pc.`alias` AS category_alias FROM `product` AS p
                LEFT JOIN `pcatalog` AS pc ON pc.`id` = p.`category_id`
                WHERE  p.`active` = 1
                ORDER BY p.id
                LIMIT '.intval($pager*$countInXML).', '. $countInXML;
        $this->writeXMLDocument($sql, array('mod' => 'product', 'act' => 'default'), 'date_update', 'task', 'id', 'cat_alias', 'category_alias');
    }
    
    public function getSiteMap()
    {
        RexRunner::runController('staticPage', 'list');
        RexRunner::runController('pCatalog', 'list');
    }
    
    private function writeXMLDocument ($sql, $urlArray, $name_date, $name_request, $name_task, $name_request2 = false, $name_task2 = false)
    {
        $dataArray = XDatabase::getAll($sql);
        $dom = new DomDocument('1.0', 'UTF-8'); 

        $urlset = $dom->appendChild($dom->createElement('urlset'));
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9'); 
        foreach ($dataArray as $key => $data) {
            $tmparr = $urlArray;
            $tmp = array($name_request => $data[$name_task]);
            $tmpUrl = array_merge ($tmparr, $tmp);
            
            if ($name_request2 and $name_task2) {
               $tmp2 = array($name_request2 => $data[$name_task2]);
               $tmpUrl = array_merge ($tmpUrl, $tmp2); 
            }
            
            $url = $urlset->appendChild($dom->createElement('url'));
            $loc = $url->appendChild($dom->createElement('loc'));
            
            if($name_date !== false) {
                $lastmod = $url->appendChild($dom->createElement('lastmod'));
            }
            
            $changefreq = $url->appendChild($dom->createElement('changefreq'));
            $priority = $url->appendChild($dom->createElement('priority'));
            $loc->appendChild($dom->createTextNode('http://'.RexConfig::get('Project', 'clear_domain').RexRoute::getUrl($tmpUrl))); 
            
            if($name_date !== false) {
                $lastmod->appendChild($dom->createTextNode($data[$name_date]));
            }
            
            $changefreq->appendChild($dom->createTextNode('hourly'));
            $priority->appendChild($dom->createTextNode('1.0'));
        }
        
        $dom->formatOutput = true;
            
        header ('Content-Type:text/xml');
        echo $dom->saveXML(); exit();    
    }
}