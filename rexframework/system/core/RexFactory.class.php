<?php
  
/**
 * Class RexFactory
 *
 * @author   MAG
 */
class RexFactory extends RexObject
{
    static public function entity($mod, $throw_exception = true)
    {
        $entity_class = ucfirst($mod).'Entity';

        if (!class_exists($entity_class)) {
            $extensions = RexConfig::get('Configurator', 'extensions');
            foreach ((array)$extensions as $extension) {
                $extension_entity_class = '\\Rex'.ucfirst($extension).'\\'.$entity_class;

                if (class_exists($extension_entity_class)) {
                    $entity_class = $extension_entity_class;
                    break;
                }
            }
        }



        if (class_exists($entity_class)) {
            $entity = new $entity_class();
            return $entity;
        }
        
        if ($throw_exception) {
            throw new Exception('Entity with mod "'.$mod.'" not found');
        }
        return false;
    }
    
    static public function controller($mod, $act, $environment = false)
    {
        $mods = array($mod, '_all');
        $acts = array($act, '_all');
        $method = 'get'.ucfirst($act);
        
        $available_environments = RexRunner::getAvailableEnvironments();
        if ($environment) {
            if (!in_array($environment, $available_environments)) {
                throw new Exception('No rights for environment "'.$environment.'"');
            }
            $available_environments = array($environment);
        }
        
        static $class_prefix = array();
        static $extensions = array();
        if (!$extensions) {
            $extensions = RexConfig::get('Configurator', 'extensions');
        }
        
        $controller = false;
        
        $user_environment = RexRunner::getUserEnvironment();
        $system_environment = RexRunner::getEnvironment();
        
        $no_permission = true;
        foreach ($mods as $find_mod) {
            foreach ($acts as $find_act) {
                if (!RexConfig::isTrue('EnvironmentPermissions', $user_environment, $system_environment, 'controller', $find_mod, $find_act)) {
                    continue;
                }
                $no_permission = false;
                foreach ($available_environments as $environment) {
                    //echo 'EnvironmentPermissions *** ue='.$user_environment.' *** e='.$environment.' *** mod='.$find_mod.' *** act='.$find_act.'<br />';
                    if (!isset($class_prefix[$environment])) {
                        $class_prefix[$environment] = ucfirst(RexConfig::get('Environment', $environment, 'controller'));
                    }
                    $controller_class = ucfirst($mod).$class_prefix[$environment].'Controller';
                    
                    if (!class_exists($controller_class)) {
                        foreach ((array)$extensions as $extension) {
                            $extension_controller_class = '\\Rex'.ucfirst($extension).'\\'.$controller_class;
                            if (class_exists($extension_controller_class)) {
                                $controller_class = $extension_controller_class;
                                break;
                            }
                        }
                    }
                    
                    if (class_exists($controller_class) && method_exists($controller_class, $method)) {
                        
                        $controller = new $controller_class($mod, $act);
                        //echo '-=create-=-'.$controller_class.'-=<br />';
                        return $controller;
                    }
                    // var_dump(true);
                    break 3;
                }
            }
        }
        
        if ($no_permission) {
            throw new ExcPermissions('Controller with mod "'.$mod.'" and act "'.$act.'" found, but no permissions for it', ExcPermissions::ControllerNoPermission);
        }
        
        throw new ExcClass('Controller with mod "'.$mod.'" and act "'.$act.'" not found', ExcClass::ClassNotFound);
    }
    
    static public function manager($mod, $throw_exception = true)
    {
        $manager_class = ucfirst($mod).'Manager';
        
        if (!class_exists($manager_class)) {
            $extensions = RexConfig::get('Configurator', 'extensions');
            foreach ((array)$extensions as $extension) {
                $extension_manager_class = '\\Rex'.ucfirst($extension).'\\'.$manager_class;
                if (class_exists($extension_manager_class)) {
                    $manager_class = $extension_manager_class;
                    break;
                }
            }
        }
        
        if (class_exists($manager_class)) {
            $manager = new $manager_class();
            return $manager;
        }
        
        if ($throw_exception) {
            throw new Exception('Manager with mod "'.$mod.'" not found');
        }
        return false;
    }
}