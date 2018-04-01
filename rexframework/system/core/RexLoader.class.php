<?php

require_once dirname(__FILE__).'/RexObject.class.php';

class RexLoaderClassFilterIterator extends FilterIterator
{
    public function accept()
    {
        $file = $this->getInnerIterator()->current();
        return substr($file, -10) == '.class.php' && substr(basename($file), 0, 1) != '_';
    }
}

class RexLoader extends RexObject
{
    static protected $check_version;
    static protected $check_relation;
    
    static protected function checkLoaded($class)
    {
        //Sys::dump(debug_backtrace());
    }
    
	static public function loadClass($class)
	{
        static $environment_controller = array();
        if (!$environment_controller) {
            foreach (RexConfig::get('Environment') as $environment => $settings) {
                $environment_controller[$environment] = $settings['controller'];
            }
        }
                
        //echo '-='.$class.'=-<br />';
        /*echo '<pre>';
        debug_print_backtrace();
        echo '</pre>';
        echo 'load class "'.$class.'"<br />';*/
		if (RexConfig::isExist('Components', $class)) {
            //TODO: загрузка компонентов с инициализацией
            $path = RexConfig::get('RexPath', 'components');
            foreach ((array)RexConfig::get('Components', $class, 'include') as $include_path) {
                require_once REX_ROOT.$path.$include_path;
            }
            if (RexConfig::isTrue('Components', $class, 'init')) {
                if (RexConfig::isExist('Components', $class, 'property')) {
                    $component = new $class(RexConfig::get('Components', $class, 'property'));
                } else {
                    $component = new $class();
                }
            }
            parent::checkTrace();
            return true;
        } elseif (($type = substr($class, -6)) == 'Entity' || ($type = substr($class, -7)) == 'Manager' || ($type = substr($class, -10)) == 'Controller') {
            $slash_pos = strrpos($class, '\\');
            $namespace = '';
            if ($slash_pos !== false) {
                $namespace = substr($class, 0, $slash_pos);
                $class = substr($class, $slash_pos + 1);
            }
            
            if ($namespace && substr($namespace, 0, 3) != 'Rex') {
                return false;
            }
            $namespace = substr($namespace, 3);
            
            $folder = strtolower(substr($class, 0, -strlen($type)));
            
            if ($type == 'Controller') {
                foreach ($environment_controller as $controller_postfix) {
                    if (!strcmp(substr($folder, -strlen($controller_postfix)), $controller_postfix)) {
                        $folder = substr($folder, 0, -strlen($controller_postfix));
                        break;
                    }
                }
            }
            
            $path = $namespace ? 
                RexConfig::get('RexPath', 'controller', 'extension').strtolower($namespace).'/' : 
                RexConfig::get('RexPath', 'controller', 'project');
            $file = REX_ROOT.$path.$folder.'/'.$class.'.class.php';
            
            if (file_exists($file)) {
                require_once $file;
                //echo '-='.$file.'=-<br />';
                parent::checkTrace();
                return true;
            }
            return false;
        } else {
            foreach ((array)RexConfig::get('RexPath', 'project', 'classes') as $path) {
                $file = REX_ROOT.$path.$class.'.class.php';
                if (file_exists($file)) {
                    require_once $file;
                    //echo '-='.$file.'=-<br />';
                    parent::checkTrace();
                    return true;
                }
            }
        }
        return false;
	}
	
	static protected function loadCore()
	{
		static::loadDirClasses(CORE_DIR);
	}
    
    static protected function loadUser()
    {
        static $user_environment = false;
        
        if (!$user_environment) {
            $path = RexConfig::get('RexPath', 'project', 'environment');
            $load_user = include REX_ROOT.$path.'load_user.php';
        }
        
        return $load_user;
    }
	
	static protected function loadDirClasses($path)
	{
        $flags = FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS | RecursiveIteratorIterator::LEAVES_ONLY;
        $iterator = new RecursiveDirectoryIterator($path, $flags);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
        $iterator = new RexLoaderClassFilterIterator($iterator);
        
        foreach ($iterator as $file) {
            //echo '-='.$file.'=-<br />';
            require_once $file;
        }
	}
    
    static protected function loadEntities()
    {
        $tables = XDatabase::getAll('SHOW TABLES') ?: array();
        $init_struct = RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_startup');
        $struct_entities = array();
        foreach ($tables as $table) {
            $mod = array_shift($table);
            $entity = RexFactory::entity($mod, false);
            if ($init_struct && is_subclass_of($entity, 'RexDBEntity')) {
                $struct_entities[] = $entity;
            }
        }
        foreach ($struct_entities as $entity) {
            $entity->checkDbStruct();
        }
    }
    
	static public function initialize()
	{
		spl_autoload_register(array('RexLoader', 'loadClass'));
		
        require_once dirname(__FILE__).'/RexIni.class.php';
        require_once dirname(__FILE__).
            '/RexConfig.class.php';
        //RexConfig::set(RexIni::parse(CONFIG_DIR.'config.ini'));
        RexConfig::init();
        
        ini_set('session.cookie_domain', RexConfig::get('Project', 'cookie_domain'));
        
		static::loadCore();
        set_error_handler(array('RexRunner', 'errorHandler'));
        set_exception_handler(array('RexRunner', 'exceptionHandler'));
        
        RexDisplay::init();
        RexLang::init();        
        RexSettings::init();
        
        XDatabase::query('SET NAMES utf8');
        static::loadEntities();
        
        static::loadUser();
        //RexRoute::set(RexIni::parse(CONFIG_DIR.'routes.ini', true));
        RexRoute::init();
	}
}