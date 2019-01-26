<?php

use \RexFramework as RexFramework;

/**
 * Class RexRunner
 *
 * @author   MAG
 */
class RexRunner extends RexObject
{
    static public function errorHandler($errno, $errstr, $errfile, $errline) 
    {
        $trace = debug_backtrace();
        $error_in_component = $trace && is_array($trace) && isset($trace[1]['file']) && strpos($trace[1]['file'], 
            RexConfig::get('RexPath', 'components')) !== false;
        if (substr($errfile, -8) != '.tpl.php' && !$error_in_component) {
            /*$error_log = RexFactory::manager('errorLog');
            $error_log->post(1, $errstr, $trace);*/
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }
    
    /**
    * @param Exception $exception
    */
    static public function exceptionHandler($exception)
    {
        /*$error_log = RexFactory::manager('errorLog');
        $error_log->post(2, $exception);*/
        
        echo '<pre>';
        echo $exception;
        echo '</pre>';
        exit;
    }

    /**
    * @returns string
    */
    static public function getEnvironment()
    {
        static $environment = false;
        
        if (!$environment) {
            $path = RexConfig::get('RexPath', 'project', 'environment');
            $environment = include REX_ROOT.$path.'environment.php';
        }
        
        return $environment;
    }
    
    static public function getUserEnvironment()
    {
        static $user_environment = false;
        
        if (!$user_environment) {
            $path = RexConfig::get('RexPath', 'project', 'environment');
            $user_environment = include REX_ROOT.$path.'user_environment.php';
        }
        
        return $user_environment;
    }
    
    static public function getAvailableEnvironments()
    {
        static $available_environments = array();
        
        if (!$available_environments) {
            $current_environment = static::getEnvironment();
            
            $all_environments = array_keys(RexConfig::get('Environment'));
        
            $available_environments[$current_environment] = $current_environment;
            foreach ($all_environments as $environment) {
                if (!isset($available_environments[$environment])) {
                    $available_environments[$environment] = $environment;
                }
            }
        }
        
        return $available_environments;
    }
    
    static protected function allowEnvironment($environment)
    {
        $available_environments = static::getAvailableEnvironments();
        
        return isset($available_environments[$environment]);
    }
    
    /**
    * @returns array(title, description, keywords)
    */
    static protected function getSeo()
    {
        $path = RexConfig::get('RexPath', 'project', 'environment');
        return REX_ROOT.$path.$path.'seo.php';
    }
    
    static public function runController($mod, $act, $environment = false)
    {
        $method = 'get'.ucfirst($act);      
        $controller = RexFactory::controller($mod, $act, $environment = false);
        return $controller->$method();
    }
    
    static protected function runBefore($mod, $act)
    {        
        $uEnvironments = array('_all', static::getUserEnvironment());
        $environments = array('_all', static::getEnvironment());
        $mods = array('_all', $mod);
        $acts = array('_all', $act);
        foreach ($uEnvironments as $uEnvironment) {
            foreach ($environments as $environment) {
                foreach ($mods as $mod) {
                    foreach ($acts as $act) {
                        if (RexConfig::isExist('Autorun', $uEnvironment, $environment, 'before', $mod, $act)) {
                            $functions = (array)RexConfig::get('Autorun', $uEnvironment, $environment, 'before', $mod, $act); 
                            ksort($functions);
                            foreach ($functions as $function) {
                                list($run_mod, $run_act) = explode('.', $function);
                                static::runController($run_mod, $run_act);
                            }
                        }
                    }
                }
            }
        }
    }
    
    static protected function runAfter($mod, $act)
    {
        $uEnvironments = array(static::getUserEnvironment(), '_all');
        $environments = array(static::getEnvironment(), '_all');
        $mods = array($mod, '_all');
        $acts = array($act, '_all');
        
        foreach ($uEnvironments as $uEnvironment) {
            foreach ($environments as $environment) {
                foreach ($mods as $mod) {
                    foreach ($acts as $act) {
                        if (RexConfig::isExist('Autorun', $uEnvironment, $environment, 'after', $mod, $act)) {
                            $functions = (array)RexConfig::get('Autorun', $uEnvironment, $environment, 'after', $mod, $act);
                            ksort($functions);
                            
                            foreach ($functions as $function) {
                                list($run_mod, $run_act) = explode('.', $function);
                                static::runController($run_mod, $run_act);
                            }
                        }
                    }
                }
            }
        }
    }
    
    static protected function runMain($mod, $act, $section = 'default')
    {
        try {
            static::runController($mod, $act);
        } catch (ExcPermissions $e) {;
            $environment = static::getEnvironment();
            $user_environment = static::getUserEnvironment();
            if ($section == 'default' &&
                $e->getCode() == ExcPermissions::ControllerNoPermission && 
                RexConfig::isExist('EnvironmentPermissions', $user_environment, $environment, 'no_permissions')) 
            {
                $routeKey = RexConfig::get('EnvironmentPermissions', $user_environment, $environment, 'no_permissions');
                RexRoute::location(array('route' => $routeKey));
            } else {
                throw $e;
            }
        }
    }
    
    static public function run()
    {
        if (RexResponse::isLocation()) {
            RexResponse::init();
        }
        
        $environment = static::getEnvironment();
        if (!static::allowEnvironment($environment)) {
            throw new Exception('Environment "'.$environment.'" not allow');
        }
        
        $sections = RexRoute::getSections();
        
        RexDisplay::assign('environment', $environment);
        RexDisplay::assign('user_environment', static::getUserEnvironment());
        RexDisplay::assign('base_path', RexConfig::get('Environment', $environment, 'link'));
        
        list($default_mod, $default_act, $default_layout) = RexRoute::route('default');
        RexDisplay::assign('mod', $default_mod);
        RexDisplay::assign('act', $default_act);
        RexDisplay::assign('task', isset($_REQUEST['task']) ? $_REQUEST['task'] : 'default');
        RexDisplay::assign('layout', $default_layout);
        $_REQUEST['mod'] = $default_mod;
        $_REQUEST['act'] = $default_act;
        $_REQUEST['layout'] = $default_layout;
        
        $run = false;
        foreach ($sections as $section) {
            if ($section == 'default') {
                $mod = $default_mod;
                $act = $default_act;
                $run = true;
            } else {
                list($mod, $act, $layout) = RexRoute::route($section);
            }
            RexDisplay::assign('workspace', $section);
            RexDisplay::assign('workspace_mod', $mod);
            RexDisplay::assign('workspace_act', $act);
            
            if ($run) {
               static::runBefore($mod, $act);
            }
            try {               
                static::runMain($mod, $act, $section);
            } catch (ExcClass $e) {
                if ($section == 'default' || ($e->getCode() != ExcClass::ClassNotFound && $e->getCode() != ExcPermissions::ControllerNoPermission)) {
                    throw $e;
                }
            }
            if ($run) {
               static::runAfter($mod, $act);
               $run = false;
            }
            
            RexDisplay::fetchWorkspace($mod, $act, $section);
        }

        RexDisplay::assign('workspace', $section);
        RexDisplay::assign('workspace_mod', $default_mod);
        RexDisplay::assign('workspace_act', $default_act);
        
        $seo = static::getSeo();
        if (!RexPage::getTitle() && isset($seo['title'])) {
            RexPage::setTitle($seo['title']);
        }
        if (!RexPage::getKeywords() && isset($seo['keywords'])) {
            RexPage::getKeywords($seo['keywords']);
        }
        if (!RexPage::getDescription() && isset($seo['description'])) {
            RexPage::setDescription($seo['description']);
        }
        
        if (RexResponse::isLocation()) {
            $response = RexDisplay::getWorkspaces();
            $response['_seo']['title'] = RexPage::getTitle();
            $response['_seo']['keywords'] = RexPage::getKeywords();
            $response['_seo']['description'] = RexPage::getDescription();
            RexResponse::response($response);
        }
        
        RexDisplay::display($default_layout);
    }
}