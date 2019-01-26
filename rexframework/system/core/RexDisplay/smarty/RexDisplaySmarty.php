<?php
class RexDisplaySmarty implements RexDisplayTemplateEngine
{
    public $engine;
    protected $template_paths;

    function __construct($property = null)
    {                
        require_once dirname(__FILE__)."/lib/Smarty.php";
        $this->engine = new Smarty();
        
        $this->engine->addPluginsDir(dirname(__FILE__).'/plugins/');
        
        $environment = RexRunner::getEnvironment();
        $property['compile_dir'] = REX_ROOT.$property['compile_dir'].$environment.'/';
        if (!is_dir($property['compile_dir'])) {
            mkdir($property['compile_dir'], 0777);
        }
        
        foreach ($property as $key => $value) {
            $this->engine->$key = $value;
        }
        $this->engine->default_resource_type = 'rex';
        
        $this->engine->registerResource('rex', array(
            array($this, 'rexGetName'),
            array($this, 'rexGetTimestamp'),
            array($this, 'rexGetSecure'),
            array($this, 'rexGetTrusted')
        ));
    }
    
    public function assign($name, $value)
    {
        return $this->engine->assign($name, $value);
    }
    
    public function display($template)
    {
        return $this->engine->display($template);
    }
    
    public function fetch($template)
    {
        return $this->engine->fetch($template);
    }
    
    public function getVar($name) 
    {
        return $this->engine->getTemplateVars($name);
    }
    
    public function rexGetName($rsrc_name, &$source, $smarty)
    {
        if (!isset($this->template_paths[$rsrc_name])) {
            $this->template_paths[$rsrc_name] = RexDisplay::getTemplatePath($rsrc_name);
        }
        //echo 'tpl_find: "'.$this->template_paths[$rsrc_name].'"'."\n";
        $source = file_get_contents($this->template_paths[$rsrc_name]);
        return true;
    }
    
    public function rexGetTimestamp($rsrc_name, &$timestamp, $smarty)
    {
        if (!isset($this->template_paths[$rsrc_name])) {
            $this->template_paths[$rsrc_name] = RexDisplay::getTemplatePath($rsrc_name);
        }
        $path = $this->template_paths[$rsrc_name];
        
        $stat = stat($path);
        $timestamp = $stat['mtime'];
        return true;
    }
    
    public function rexGetSecure($rsrc_name, &$smarty)
    {
        return true;
    }
    
    public function rexGetTrusted($rsrc_name, &$smarty)
    {
        return true;
    }
}