<?php
namespace RexFramework;

use \RexConfig as RexConfig;
use \RexRunner as RexRunner;
use \Request as Request; 
use \RexDBList as RexDBList;
use \RexResponse as RexResponse; 
use \RexDisplay as RexDisplay; 

/**
 * Class HomeRextestController
 *
 * Home Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class TestRextestController extends ParentRextestController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    private $_modules = array(
        'curl',
        'soap',
        'SimpleXML',
        'mysqli',
        'mbstring',
        'iconv',
        'zlib',
        'magickwand',
        'json',
        'gd',
        'ereg',
        'date',
        'tokenizer',
        'SPL',
        'standard',
        'session'
    );
    
    function getDefault()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }
        
        $view = Request::get('type_view', 'all');
        $hosting = Request::get('hosting', false);
        
        $items = '';
        $sql = '';
        if (!$hosting) {
            $this->entity->getByFields(array('controller' => $this->mod)); 
            if ($this->entity->active == 1) {
                $items = $this->entity->id;
            }
            $sql = ' AND `controller` != "'.$this->mod.'"';
        } else {
            RexDisplay::assign('hosting_name', ucfirst($this->mod).'RextestController');
        }
        
        $controllers = new RexDBList('test');      
        $controllers->getByWhere('`active` = 1 AND `result` != "" AND `status` = 1'.$sql);
        $controllers->setOrderBy('`id` ASC');
        
        $result = array();
        foreach ($controllers as $controller) {
            $result = array_merge($result, unserialize($controller->result));
            $items .= ', '.$controller->id;
        }
        
        $items = trim($items, ', ');
        
        RexDisplay::assign('result', $result);
        RexDisplay::assign('view', $view);
        RexDisplay::assign('hosting', $hosting);
        RexDisplay::assign('items', $items);
        RexDisplay::assign('template_dg', strtolower($this->mod).'/dg.tpl');
        
        $compare = new RexDBList('test');      
        $compare->getByWhere('`active` = 1');
        
        if ((sizeof($controllers) == sizeof($compare) && $hosting) || ((sizeof($controllers)+1) == sizeof($compare) && !$hosting && $this->entity->active == 1) || (sizeof($controllers) == sizeof($compare) && !$hosting && $this->entity->active == 0)) {
            RexDisplay::assign('check', 'end');
        }
        
        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($this->mod).'/dg.tpl');
            RexResponse::response($content);
        }
    }
    
    function getRunConsole()
    {
        RexResponse::init();
        
        system('/usr/bin/php /home/users/developer/'.RexConfig::get('Project', 'cookie_domain').'/htdocs/cron/runtest.php >/dev/null >/dev/null &');
        
        RexResponse::response('ok');
    }
    
    function getStart()
    {   
        $controllers = new RexDBList('test');      
        $controllers->getByWhere('`active` = 1');      
        
        foreach ($controllers as $controller) {
            $this->entity->get($controller->id);
            $this->entity->status = 0;
            $this->entity->update();
        }
        
        static $extensions = array();
        if (!$extensions) {
            $extensions = RexConfig::get('Configurator', 'extensions');
        }
        
        $result = array();
        foreach ($controllers as $controller) {
            $controller_class = ucfirst($controller->controller).'RextestController';
            $result[$controller_class][$controller_class] = RexRunner::runController($controller->controller, 'run');
             
            if ($this->entity->getByFields(array('controller' => $controller->controller))) {
                $this->entity->result = serialize($result[$controller_class]);
                $this->entity->status = 1;
                $this->entity->update();
            }
        }
        exit;
    }
    
    function getCheckDB()
    {
        RexResponse::init();
        
        $sql = ' AND `status` = 1';
        $arr = Request::get('items', '');
          
        if ($arr) {
            $sql = ' AND `id` NOT IN ('.$arr.')';
        }
        
        $controllers = new RexDBList('test');      
        $controllers->getByWhere('`active` = 1'.$sql);
        
        if (sizeof($controllers) == 0) {
            RexResponse::response('end');
        }
        
        foreach ($controllers as $controller) {
            if ($controller->result) {
                RexResponse::response('yes');
            }
        }
        
        RexResponse::response('no');
    }
    
    function testHosting()
    {
        $this->assertModules($this->_modules);
        $this->assertMagicQuots();
        $this->assertPhpVersion();
        $this->assertRegistrGlobals();
        $this->assertRedirect();
    }
}