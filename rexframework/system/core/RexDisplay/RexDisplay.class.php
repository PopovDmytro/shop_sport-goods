<?php
  
/**
 * Class RexConfig
 * class for future smarty replacement (fritz and others)
 *
 * @author   MAG
 */
 class RexDisplay extends RexObject
 {
     /**
     * @var RexDisplayTemplateEngine
     */
     static public $engine;
     static public $workspace;
     static protected $override;
     
     static public function init()
     {
         $properties = RexConfig::get('Core', 'RexDisplay');
         $engine_type = $properties['engine'];
         $engine_params = $properties[$engine_type];
         
         $engine_class = 'RexDisplay'.ucfirst($engine_type);
         include_once dirname(__FILE__).'/'.$engine_type.'/'.$engine_class.'.php';
         static::$engine = new $engine_class($engine_params);
     }
     
     static public function override($mod, $act, $section, $template)
     {
         static::$override[$mod][$act][$section] = $template;
     }
     
     static public function getTemplate($mod, $act, $section)
     {
         if (isset(static::$override[$mod]) &&
             isset(static::$override[$mod][$act]) &&
             isset(static::$override[$mod][$act][$section]))
         {
             return static::$override[$mod][$act][$section];
         }
         return strtolower($mod).'/'.$act.'.tpl';
     }
     
     static public function getContentPath($unprocessed_content_path, $subfolder)
     {
         static $environment_content_folders = array();
         if (!$environment_content_folders) {
             $environments = RexRunner::getAvailableEnvironments();
             
             $user_environment = RexRunner::getUserEnvironment();
             
             foreach ($environments as $environment) {
                 if (RexConfig::isTrue('EnvironmentPermissions', $user_environment, $environment, 'content')) {
                     $environment_content_folders[] = RexConfig::get('Environment', $environment, 'content');
                 }
             }
         }

         static $content_paths = array();         
         if (!$content_paths) {
             $current_skin = static::getSkin();
             $default_skin = RexConfig::get('Project', 'skin', 'default');
             $skins = array();
             $skins[] = $current_skin;
             if ($current_skin != $default_skin) {
                $skins[] = $default_skin;
             }
             
             $find_paths = array();
             $project_prop = array(
                'path' => REX_ROOT.RexConfig::get('RexPath', 'content', 'project', 'path'),
                'link' => RexConfig::get('RexPath', 'content', 'project', 'link'));
             $extension_prop = array(
                'path' => REX_ROOT.RexConfig::get('RexPath', 'content', 'extension', 'path'),
                'link' => RexConfig::get('RexPath', 'content', 'extension', 'link'));
                
             $extensions = RexConfig::get('Configurator', 'extensions');
             
             $find_props[] = $project_prop;
             foreach ($extensions as $extension) {
                 $find_props[] = array(
                    'path' => $extension_prop['path'].$extension.'/',
                    'link' => $extension_prop['link'].$extension.'/');
             }             
             
             foreach ($environment_content_folders as $environment_folder) {
                 foreach ($find_props as $find_prop) {
                     foreach ($skins as $skin) {
                         $content_paths[$environment_folder][] = array(
                            'path' => $find_prop['path'].$skin.'/'.$environment_folder.'/',
                            'link' => $find_prop['link'].$skin.'/'.$environment_folder.'/');
                     }
                 }
             }
         }
         
         //Sys::dump($content_paths);
         
         //print_r($environment_content_folders);
         //print_r($content_paths);
         
         foreach ($environment_content_folders as $environment_folder) {
             foreach ($content_paths[$environment_folder] as $content_path) {
                 //$content_path = $content_path['path'].$subfolder.'/'.$unprocessed_content_path;
                 //echo '**-find-**-'.$content_path['path'].$subfolder.'/'.$unprocessed_content_path.'-**<br />';
                 if (file_exists($content_path['path'].$subfolder.'/'.$unprocessed_content_path)) {
                     //echo '-='.$content_path['link'].$subfolder.'/'.$unprocessed_content_path.'=-';
                     return $content_path['link'].$subfolder.'/'.$unprocessed_content_path;
                 }
             }
         }
         return false;
         //throw new Exception('Content "'.$unprocessed_content_path.'" not found');
     }
     
     static public function getTemplatePath($unprocessed_template_path)
     {
         $mods = array('_all');
         $acts = array('_all');
         
         if ($unprocessed_template_path && $unprocessed_template_path{0} != '/') {
             $path_mod = false;
             $path_act = false;
             
             $sl_pos = strpos($unprocessed_template_path, '/');
             if ($sl_pos !== false) {
                 $path_mod = substr($unprocessed_template_path, 0, $sl_pos);
             }
             if (strpos($unprocessed_template_path, '/', $sl_pos + 1) === false) {
                 $path_act = basename($unprocessed_template_path, '.tpl');
             }
             if ($path_mod && $path_act) {
                 array_unshift($mods, $path_mod);
                 array_unshift($acts, $path_act);
             }
         }
         
         static $environment_content_folders = array();
         if (!$environment_content_folders) {
             $environments = RexRunner::getAvailableEnvironments('template');
             foreach ($environments as $available_environment) {
                 $environment_content_folders[$available_environment] = RexConfig::get('Environment', $available_environment, 'template');
             }
         }

         static $template_paths = array();
         
         if (!$template_paths) {
             $current_skin = static::getSkin();
             $default_skin = RexConfig::get('Project', 'skin', 'default');
             $skins = array();
             $skins[] = $current_skin;
             if ($current_skin != $default_skin) {
                $skins[] = $default_skin;
             }
             
             $extensions = RexConfig::get('Configurator', 'extensions');
             
             $find_paths = array();
             $project_path = RexConfig::get('RexPath', 'template', 'project');
             $extension_path = RexConfig::get('RexPath', 'template', 'extension');
             $find_paths[] = REX_ROOT.$project_path;
             foreach ($extensions as $extension) {
                 $find_paths[] = REX_ROOT.$extension_path.$extension.'/';
             }
             
             
             foreach ($environment_content_folders as $environment => $environment_folder) {                 
                 foreach ($find_paths as $find_path) {
                     foreach ($skins as $skin) {
                         $template_paths[$environment][] = $find_path.$skin.'/'.$environment_folder.'/';
                     }
                 }
             }
         }
         
         $user_environment = RexRunner::getUserEnvironment();
         
         $curr_environment = RexRunner::getEnvironment();
         foreach ($environment_content_folders as $environment => $environment_folder) {
            foreach ($mods as $mod) {
                foreach ($acts as $act) {
                    if (RexConfig::isTrue('EnvironmentPermissions', $user_environment, $environment, 'template', $mod, $act)) {
                        foreach ($template_paths[$environment] as $template_path) {
                            $template_path = $template_path.$unprocessed_template_path;
                            //echo '**-test-**-'.$template_path.'-**<br />'."\n";
                            if (file_exists($template_path) && $environment == $curr_environment) {
                                //echo '**-find-**-'.$template_path.'-**<br />'."\n";
                                //debug_print_backtrace();
                                return $template_path;
                            }
                        }
                        break 2;
                    }
                }
            }
         }
         
         throw new ExcFile('Template "'.$unprocessed_template_path.'" not found', ExcFile::TemplateNotFound);
     }
     
     static public function getSkin()
     {
         $skin = XSession::get('skin', false);
         if (!$skin && isset($_REQUEST['skin']) && $_REQUEST['skin']) {
             $skin = $_REQUEST['skin'];
         }
         if (!$skin) {
             try {
                 $skin = RexSettings::get('active_skin');
             } catch (Exception $e) {
                 $skin = RexConfig::get('Project', 'skin', 'default');
             }
         }
         return $skin;
     }
     
     static public function setSkin($skin)
     {
         XSession::set('skin', $skin);
         setcookie('skin', $skin, time() + 60 * 60 * 24 * 30);
     }

     static public function assign($name, $value)
     {
         return static::$engine->assign($name, $value);
     }
     
     static public function display($layout)
     {
         $layout_folder = RexConfig::get('Core', 'RexDisplay', 'layout_folder');
         $layout_template = $layout_folder.'/'.$layout.'.tpl';
         
         //$index_path = static::getTemplatePath('index.tpl');
         //return static::$engine->display($index_path);
         static::assign('workspace', 'main');
         return static::$engine->display($layout_template);
     }
     
     static public function getVar($name)
     {
         return static::$engine->getVar($name);
     }
     
     static public function fetchWorkspace($mod, $act, $section = 'default')
     {
         //var_dump($section);
         if (!isset(static::$workspace[$section])) {
             if ($mod) {
                 $template = static::getTemplate($mod, $act, $section);
                 static::assign('workspace', $section);
                 static::$workspace[$section] = static::$engine->fetch($template);
             } else {
                 static::$workspace[$section] = '';
             }
         }
     }
     
     static public function getWorkspaces($section = false)
     {
         if ($section) {
             if (!isset(static::$workspace[$section])) {
                 throw new Exception('Section "'.$section.'" not find in route');
             }
             return static::$workspace[$section];
         } else {
             return static::$workspace;
         }
     }
     
     static public function fetch($template)
     {
         //$template_path = static::getTemplatePath($template);
         //return static::$engine->fetch($template);
         return static::$engine->fetch($template);
     }
 }
 
 function RexDisplay($name, $value) {
     RexDisplay::assign($name, $value);
 }
 
 function assign($name, $value) {
     RexDisplay::assign($name, $value);
 }
