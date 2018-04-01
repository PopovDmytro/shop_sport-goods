<?php

class RexObject {
    
    protected static $_rex_class_need_versions;
    protected static $_rex_class_access;
    protected static $_rex_class_loaded;
    protected static $_for_check_access = array();
    
    public static $assemble;// = 'standart';
    public static $version;// = 1.0;
    public static $needed = array();// = array('NewsEntity:standart:1.0', 'NewsManager:firefly:3.21')
    
    public function __construct()
    {
        static::checkTrace();
    }
    
    public static function checkTrace($skip_class = false)
    {
        $classes = static::getTraceClasses($skip_class);
        
        if ($classes[1] && $classes[0]) {
            $c0_exists = class_exists($classes[0], false);
            $c1_exists = class_exists($classes[1], false);
            if (!$c0_exists || !$c1_exists) {
                if ($c0_exists && !is_subclass_of($classes[0], __CLASS__)) {
                    return;
                }
                if ($c1_exists && !is_subclass_of($classes[1], __CLASS__)) {
                    return;
                }
                static::$_for_check_access[] = $classes;
                return;
            } elseif (is_subclass_of($classes[1], __CLASS__) && is_subclass_of($classes[0], __CLASS__)) {
                static::$_for_check_access[] = $classes;
            }
        }
        
        if (static::$_for_check_access) {
            $for_check = static::$_for_check_access;
            static::$_for_check_access = array();
            foreach ($for_check as $check) {
                $c_exists = array(false, false);
                $c_sub = array(false, false);
                foreach ($check as $class_key => $class) {
                    $c_exists[$class_key] = class_exists($class, false);
                    $c_sub[$class_key] = $c_exists[$class_key] && is_subclass_of($class, __CLASS__);
                    //echo '-=='.$class.'==='.is_subclass_of($class, __CLASS__).'==-';
                    if ($c_sub[$class_key] && !isset(static::$_rex_class_loaded[$class])) {
                        //echo '<br />load class "'.$class.'" real "'.$class.'"<br/>';
                        if (!$class::$assemble) {
                            throw new Exception('Set assemble for "'.$class.'"');
                        }/* else {
                            echo 'assemble "'.$class::$assemble.'"<br/>';
                        }*/
                        if (!$class::$version) {
                            throw new Exception('Set version for "'.$class.'"');
                        }/* else {
                            echo 'version "'.$class::$version.'"<br/>';
                        }*/
                    
                        if (isset(static::$_rex_class_need_versions[$class])) {
                            if (static::$_rex_class_need_versions[$class]['assemble'] != $class::$assemble || 
                                static::$_rex_class_need_versions[$class]['version'] > $class::$version)
                            {
                                throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                    '". Needed class "'.$class.':'.static::$_rex_class_need_versions[$class]['assemble'].':'.
                                    static::$_rex_class_need_versions[$class]['version'].'"');
                            }
                        }
                        foreach ($class::$needed as $need) {
                            list($need_class, $need_assemble, $need_version) = explode(':', $need);
                            if (!$need_class || !$need_assemble || !$need_version) {
                                throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                    '". Uncorrect information for need "'.$need.'"');
                            }
                            if (isset(static::$_rex_class_loaded[$need_class])) {
                                //echo '-='.static::$_rex_class_loaded[$need_class].'=-';
                                list($exist_assemble, $exist_version) = explode(':', static::$_rex_class_loaded[$need_class]);
                                
                                /*if (!is_subclass_of($need_class, __CLASS__)) {
                                    throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                    '". Need class "'.$need.'" is not subclass of "'.__CLASS__.'"');
                                }
                                $exist_assemble = $need_class::$assemble;
                                $exist_version = $need_class::$version;*/
                                if ($exist_assemble != $need_assemble) {
                                    throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                        '". Need class "'.$need.'" check error - this class with assemble "'.$exist_assemble.'" already loaded');
                                }
                                if ($exist_version < $need_version) {
                                    throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                        '". Need class "'.$need.'" check error - this class loaded with less version: "'.$exist_version.'"');
                                }
                            }
                            if (isset(static::$_rex_class_need_versions[$need_class])) {
                                if (static::$_rex_class_need_versions[$need_class]['assemble'] != $need_assemble) {
                                    throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                                        '". Need class "'.$need.'" check error - this class need in system in assemble "'.
                                            static::$_rex_class_need_versions[$need_class]['assemble'].'"');
                                }
                                if (static::$_rex_class_need_versions[$need_class]['version'] < $need_version) {
                                    static::$_rex_class_need_versions[$need_class]['version'] = $need_version;
                                }
                            } else {
                                static::$_rex_class_need_versions[$need_class] = array('assemble' => $need_assemble, 'version' => $need_version);
                            }
                            static::$_rex_class_access[$class][$need_class] = 1;
                        }
                        static::$_rex_class_loaded[$class] = $class::$assemble.':'.$class::$version;
                        //echo '-=eq=*'.$class.':'.$class::$assemble.':'.$class::$version.'*=eq=-';
                    } elseif ($c_sub[$class_key] && strcmp(static::$_rex_class_loaded[$class], $class::$assemble.':'.$class::$version)) {
                        throw new Exception('Error for loading class "'.$class.':'.$class::$assemble.':'.$class::$version.
                            '". Class "'.$class.':'.static::$_rex_class_loaded[$class].'" already loaded');
                    }
                }
                
                if ($c_sub[0] && $c_sub[1]) {
                    $find = false;
                    $current_check = $check[1];
                    
                    do {
                        $current_need = $check[0];
                        do {
                            if (isset(static::$_rex_class_access[$current_check][$current_need])) {
                                $find = true;
                                break 2;
                            }
                        } while ($current_need = get_parent_class($current_need));
                    } while ($current_check = get_parent_class($current_check));
                    
                    if (!$find) {
                        print_r(debug_backtrace());
                        throw new Exception('Class "'.$check[0].'" not find in needed for class "'.$check[1].'"');
                    }
                    static::$_rex_class_access[$check[1]][$check[0]] = 1;
                } elseif (!(($c_exists[0] && !$c_sub[0]) || ($c_exists[1] && !$c_sub[1])) && (!$c_exists[0] || !$c_exists[1])) {
                    static::$_for_check_access[] = $check;
                }
            }
        }
    }
    
    public static function getTraceClasses($skip_class = false, $trace = false)
    {
        $source = false;
        $target = false;
        
        if (!$trace) {
            $trace = debug_backtrace();
        }
        if (!$skip_class) {
            $skip_class = array(__CLASS__);
        } else {
            $skip_class = (array)$skip_class;
        }
        array_push($skip_class, __CLASS__);
        array_push($skip_class, 'RexLoader');
        array_push($skip_class, 'RexFactory');
        array_push($skip_class, 'RexRunner');
        
        $skip_class = array_combine($skip_class, $skip_class);
        while (isset($trace[0]) && isset($trace[0]['class']) && isset($skip_class[$trace[0]['class']])) 
        {
            array_shift($trace);
        }
        
        if (isset($trace[0]) && isset($trace[0]['function']) && $trace[0]['function'] == 'spl_autoload_call' && !isset($trace[0]['class'])) {
            $target = $trace[0]['args'][0];
        }
        while (isset($trace[0])) {
            $class = false;
            if (isset($trace[0]['object'])) {
                $class = get_class($trace[0]['object']);
            } elseif (isset($trace[0]['file']) && !(isset($trace[1]) && 
                isset($trace[1]['object']) && isset($trace[1]['function']) && $trace[1]['function'] == '__construct')) 
            {
                $file = basename($trace[0]['file']);
                if (substr($file, 0, 4) == 'dbg-') {
                    $dbg_pos = strpos($file, '-dbg-');
                    $file = substr($file, 4, $dbg_pos - 4);
                }
                if (substr($file, -10) == '.class.php') {
                    $class = substr($file, 0, -10);
                }
            }
            
            if ($class && !isset($skip_class[$class])) {
                if (!$target) {
                    $target = $class;
                } elseif (!$source && $target != $class) {
                    $source = $class;
                } elseif ($target && $source) {
                    break;
                }
            }
            
            array_shift($trace);
        }
        
        return array($target, $source);
    }
}