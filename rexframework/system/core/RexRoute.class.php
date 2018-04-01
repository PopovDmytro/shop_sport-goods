<?php
  
class RexRoute extends RexObject
{
    static protected $routes = array();
    static protected $mod_reverse = array();
    static protected $key_reverse = array();
    static protected $url_layouts = array();
    
    static public function init()
    {
        $default_file = dirname(__FILE__).'/../routes.ini';
        $file = CONFIG_DIR.'routes.ini';
        
        RexRoute::set(array_merge_recursive(
            file_exists($default_file) ? RexIni::parse($default_file, true) : array(),
            file_exists($file) ? RexIni::parse($file, true) : array()
        ));
    }
    
    static protected function calcParamValue($param)
    {
        if (strlen($param) > 1 && $param{0} == '@') {
            try {
                eval ('$param = '.substr($param, 1).';');
            } catch (Exception $e) {
                $param = null;
            }
        }
        return $param;
    }
    
    static public function set($routes)
    {
        $available_environments = RexRunner::getAvailableEnvironments();
        $user_environment = RexRunner::getUserEnvironment();
        
        $current_environment = RexRunner::getEnvironment();
        $environment_routes_init = array_combine($available_environments, array_fill(0, sizeof($available_environments), array()));
        
        foreach ($routes as $section => $section_route) { 
            
            if ($section == 'default' && !isset(static::$routes[$section])) {
                static::$routes[$section] = array();
            }
            
            $environment_routes = $environment_routes_init;
            
            foreach ($section_route as $key => $route) {
                if (!isset($route['mod'])) {
                    throw new Exception('Route "'.$key.'" without mod');
                }
                if (!isset($route['act'])) {
                    throw new Exception('Route "'.$key.'" without mod');
                }
                
                $route['key'] = $key;
                
                $user_route = false;
                
                if (isset($route['route'][$user_environment])) {
                    $user_route = $route['route'][$user_environment];
                } elseif (isset($route['route']['_all'])) {
                    $user_route = $route['route']['_all'];
                } else {
                    continue;
                }
                
                $mods = array($route['mod'], '_all');
                $acts = array($route['act'], '_all');
                
                $route_environments = array_values($available_environments);
                array_shift($route_environments);
                array_unshift($route_environments, '_all');
                array_unshift($route_environments, $current_environment);
                
                foreach ($route_environments as $environment) {
                    if (!isset($user_route[$environment])) {
                        continue;
                    }
                    $environment_route = $user_route[$environment];
                    if ($environment == '_all') {
                        $environment = $current_environment;
                    }
                    foreach ($mods as $mod) {
                        foreach ($acts as $act) {
                            if (RexConfig::isTrue('EnvironmentPermissions', $user_environment, $environment, 'controller', $mod, $act)) {
                                $toadd_route = $route;
                                $toadd_route['route'] = $environment_route;
                                $environment_routes[$environment][$key] = $toadd_route;
                                break 2;
                            }
                        }
                    }
                }
            }
            foreach ($environment_routes as $environment => $inenvironment_routes) {
                foreach ($inenvironment_routes as $key => $route) {
                    $route['key'] = $key.'.'.$environment;
                    $route_match = '#^'.$route['route'].'$#';
                    if ($environment == $current_environment) {
                        static::$routes[$section][$route_match] = $route;
                    }
                    preg_match_all('/\([^\)]+\)/', $route['route'], $route_params);
                    
                    $reverse_route = $route['route'];
                    $num = 1;
                    
                    $params = array();
                    foreach ($route_params[0] as $number => $param) {
                        $params[$number + 1] = '#'.substr($param, 1, -1).'#';
                        
                        $pos = strpos($reverse_route, $param);
                        $reverse_route = substr_replace($reverse_route, '{'.$num.'}', $pos, strlen($param));
                        ++$num;
                    }
                    $reverse = array(
                        'route' => $reverse_route,
                        'environment' => $environment,
                        'layout' => isset($route['layout']) ? $route['layout'] : 'default');
                    $request_params = array();
                    if (isset($route['request'])) {
                        $request_params = array_merge($request_params, $route['request']);
                    }
                    if (isset($route['get'])) {
                        $request_params = array_merge($request_params, $route['get']);
                    }
                    if (isset($route['post'])) {
                        $request_params = array_merge($request_params, $route['post']);
                    }
                    foreach ($request_params as $param => $param_spec) {
                        $is_param_regular = $param_spec == intval($param_spec).'';
                        if ($is_param_regular) {
                            $reverse['numbers'][$param_spec] = array(
                                'match' => $params[$param_spec],
                                'param' => $param
                            );
                        }
                        if ($is_param_regular && !isset($params[$param_spec])) {
                            throw new Exception('Route "'.$key.'" has wrong param number');
                        }
                        $reverse['params'][$param] = array(
                            'match' => $is_param_regular ? $params[$param_spec] : $param_spec,
                            'is_regular' => $is_param_regular,
                            'spec' => $param_spec
                        );
                    }
                    if ($section == 'default') {
                        static::$mod_reverse[$route['mod']][$route['act']][] = $reverse;
                        if (!isset(static::$key_reverse[$key])) {
                            static::$key_reverse[$key] = $reverse;
                        }
                        static::$key_reverse[$key.'.'.$environment] = $reverse;
                    }
                }
            }
        }
    }
    
    static public function getSections()
    {
        return array_keys(static::$routes);
    }
    
    static public function route($section = 'default', $url = false) 
    {
        $environment = RexRunner::getEnvironment();
        $user_environment = RexRunner::getUserEnvironment();
        
        $parse_url = $url;
        if (!$parse_url) {
            $parse_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }

        if (explode('/', $parse_url)[1] !== 'admin') {
            $parse_url = strtok($parse_url, '?');
            if ($parse_url[strlen($parse_url) - 1] != '/' && (substr($parse_url, strlen($parse_url) - 4, 4) != 'html')) {
                $parse_url = $parse_url . '/';
            }
            if(substr($parse_url, strlen($parse_url) - 5, 5) == 'html/')
            {
                $parse_url = rtrim($parse_url, "/");
            }
        }

        $link_prefix = RexConfig::get('Environment', $environment, 'link');
        if (substr($parse_url, 0, strlen($link_prefix)) == $link_prefix) {
            $parse_url = (substr($link_prefix, -1) == '/' ? '/' : '').substr($parse_url, strlen($link_prefix));
        } else {
            throw new Exception('Real link prefix does not match environment link prefix in config');
        }
        
        $parsed_url = parse_url($parse_url);
        $parsed_params = array();
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $parsed_params);
        }
        
        $find_mod = false;
        $find_act = false;
        $find_layout = false;
        
        if ($section == 'default' && (isset($parsed_params['mod']) || (!$url && isset($_REQUEST['mod'])))) {
            if (!$url && isset($_REQUEST['mod'])) {
                $find_mod = isset($_POST['mod']) ? $_POST['mod'] : (isset($_GET['mod']) ? $_GET['mod'] : $_REQUEST['mod']);
                $find_act = isset($_POST['act']) ? $_POST['act'] : (isset($_GET['act']) ? $_GET['act'] : (isset($_REQUEST['act']) ? $_REQUEST['act'] : 'default'));
                $find_layout = isset($_REQUEST['layout']) ? $_REQUEST['layout'] : 'default';
            } else {
                $find_mod = $parsed_params['mod'];
                $find_act = (isset($parsed_params['act']) ? $parsed_params['act'] : 'default');
                $find_layout = isset($parsed_params['layout']) ? $parsed_params['layout'] : 'default';
            }
        }
        
        if (!$find_mod && isset(static::$routes[$section])) {
            foreach (static::$routes[$section] as $route => $route_params) {
                $result = preg_match($route, $parse_url, $url_params);
                if ($result) {
                    $find_mod = $route_params['mod'];
                    $find_act = isset($route_params['act']) ? $route_params['act'] : 'default';
                    $find_layout = isset($route_params['layout']) ? $route_params['layout'] : 'default';
                    
                    if (isset($route_params['request'])) { 
                        foreach ($route_params['request'] as $param => $number) {
                            if ($number == intval($number).'') {
                                $_REQUEST[$param] = $url_params[$number];
                            } else {
                                $_REQUEST[$param] = static::calcParamValue($number);
                            }
                        }
                    }
                    if (isset($route_params['post'])) { 
                        foreach ($route_params['post'] as $param => $number) {
                            if ($number == intval($number).'') {
                                $_POST[$param] = $url_params[$number];
                            } else {
                                $_POST[$param] = static::calcParamValue($number);
                            }
                        }
                    }
                    if (isset($route_params['get'])) { 
                        foreach ($route_params['get'] as $param => $number) {
                            if ($number == intval($number).'') {
                                $_GET[$param] = $url_params[$number];
                            } else {
                                $_GET[$param] = static::calcParamValue($number);
                            }
                        }
                    }
                    break;
                }
            }
        }
        
        if (!$find_mod && $section == 'default') {
            if (RexConfig::isExist('EnvironmentPermissions', $user_environment, $environment, 'no_route')) {
                $route_key = RexConfig::get('EnvironmentPermissions', $user_environment, $environment, 'no_route');
                RexRoute::location(array('route' => $route_key));
            }
            
            throw new Exception('Route not found');
        }
        
        return array($find_mod, $find_act, $find_layout);
    }
    
    static private function getUrlByReverse($reverse, $params)
    {
        $url = $reverse['route'];
        foreach ($params as $key => $value) {
            $match = '';
            $number = 0;
            $is_regular = true;
            if (is_int($key)) {
                $number = $key + 1;
                if (isset($reverse['numbers'][$number])) {
                    $match = $reverse['numbers'][$number]['match'];
                } else {
                    return false;
                }
            } else {
                if (isset($reverse['params'][$key])) {
                    $is_regular = $reverse['params'][$key]['is_regular'];
                    $number = $reverse['params'][$key]['spec'];
                    $match = $reverse['params'][$key]['match'];
                } else {
                    return false;
                }
            }
            if ($is_regular && preg_match($match, $value)) {
                $url = str_replace('{'.$number.'}', $value, $url);
            } elseif (!$is_regular) {
                $number = static::calcParamValue($number);
                if ($value != $number) {
                    return false;
                }
            }
        }
        
        static $link_prefix = array();
        if (!isset($link_prefix[$reverse['environment']])) {
            $link_prefix[$reverse['environment']] = RexConfig::get('Environment', $reverse['environment'], 'link');
            if (substr($link_prefix[$reverse['environment']], -1) == '/') {
                $link_prefix[$reverse['environment']] = substr($link_prefix[$reverse['environment']], 0, -1);
            }
        }
                
        $url = $link_prefix[$reverse['environment']].$url;
        static::$url_layouts[$url] = $reverse['layout'];
        return $url;
    }
    
    static public function getUrl($params)
    {
        $num = func_num_args();
        $args = func_get_args();
        
        $mod = false;
        $act = false;
        $route = false;
        
        $result = false;
        
        if ($num == 1 || is_array($params)) {
            $params = (array)$params;
            if (isset($params['mod'])) {
                if (isset($params['act'])) {
                    $act = $params['act'];
                    unset($params['act']);
                } else {
                    $act = 'default';
                }
                $mod = $params['mod'];
                unset($params['mod']);
            } elseif (isset($params['route'])) {
                $route = $params['route'];
                unset($params['route']);
            } elseif (sizeof($params)) {
                $mod = array_shift($params);
                $act = array_shift($params) ?: 'default';
            }
        } elseif ($num == 2 && is_array($args[1])) {
            $route = $args[0];
            $params = $args[1];
        } elseif ($num >= 2) {
            $mod = $args[0];
            $act = $args[1];
            if (isset($args[2]) && is_array($args[2])) {
                $params = $args[2];
            } else {
                $params = array_slice($args, 2);
            }
        }
        
        $environment = RexRunner::getEnvironment();
        if ($mod !== false && $act !== false) {
            if (isset(static::$mod_reverse[$mod][$act])) {
                foreach (static::$mod_reverse[$mod][$act] as $reverse) {
                    if ($reverse['environment'] != $environment) {
                        continue;
                    }
                    $result = static::getUrlByReverse($reverse, $params);
                    if ($result !== false) {
                        break;
                    }
                }
            }
        } elseif ($route !== false) {
            if (isset(static::$key_reverse[$route])) {
                $result = static::getUrlByReverse(static::$key_reverse[$route], $params);        
            }
        }
        
        if (!$result && $mod && $act) {
            static $link_prefix = false;
            if ($link_prefix === false) {
                $link_prefix = RexConfig::get('Environment', $environment, 'link');
            }
            
            $result = $link_prefix.'index.php?mod='.$mod;
            if ($act != 'default') {
                $result .= '&act='.$act;
            }
            foreach ($params as $key => $param) {
                $result .= '&'.$key.'='.urlencode($param);
            }
            
            //static::$url_layouts[$result] = isset($params['layout']) ? $params['layout'] : 'default';
            static::$url_layouts[$result] = isset($params['layout']) ? $params['layout'] : 'default';
        }
        
        return $result;
    }
    
    static public function getLayoutByUrl($url, $section = 'default') {
        if (!isset(static::$url_layouts[$url])) {
            list($mod, $act, $layout) = static::route($section, $url); 
            static::$url_layouts[$url] = $layout;
        }
        
        return static::$url_layouts[$url];
    }
    
    static public function location($params){
        $num = func_num_args();
        $args = func_get_args();
        
        $url = false;
        if ($num < 1) {
            $url = RexRoute::getUrl();
        } elseif ($num == 1 || is_array($params)) {
            $url = RexRoute::getUrl($params);
        } elseif ($num == 2 && is_string($args[0]) && is_string($args[1])) {
            $url = RexRoute::getUrl($args[0], $args[1]);
        } elseif ($num > 2 && is_string($args[0]) && is_string($args[1])) {
            if (isset($args[2]) && is_array($args[2])) {
                $params = $args[2];
            } else {
                $params = array_slice($args, 2);
            }
            $url = RexRoute::getUrl($args[0], $args[1], $params);
        } else {
            throw new Exception('Wrond params');
        }
        if (!$url) {
            throw new Exception('Wrond url');
        }
        header('Location: '.$url);
        exit;
    }
}

function RexRoute($params)
{
    $num = func_num_args();
    $args = func_get_args();
    
    if ($num < 1) {
        return RexRoute::getUrl();
    } elseif ($num == 1 || is_array($params)) {
        return RexRoute::getUrl($params);
    } elseif ($num == 2 && is_string($args[0]) && is_string($args[1])) {
        return RexRoute::getUrl($args[0], $args[1]);
    } elseif ($num > 2 && is_string($args[0]) && is_string($args[1])) {
        if (isset($args[2]) && is_array($args[2])) {
            $params = $args[2];
        } else {
            $params = array_slice($args, 2);
        }
        return RexRoute::getUrl($args[0], $args[1], $params);
    } else {
        throw new Exception('Wrond params');
    }
}

function route($params)
{
    $num = func_num_args();
    $args = func_get_args();
    
    if ($num < 1) {
        return RexRoute::getUrl();
    } elseif ($num == 1 || is_array($params)) {
        return RexRoute::getUrl($params);
    } elseif ($num == 2 && is_string($args[0]) && is_string($args[1])) {
        return RexRoute::getUrl($args[0], $args[1]);
    } elseif ($num > 2 && is_string($args[0]) && is_string($args[1])) {
        if (isset($args[2]) && is_array($args[2])) {
            $params = $args[2];
        } else {
            $params = array_slice($args, 2);
        }
        return RexRoute::getUrl($args[0], $args[1], $params);
    } else {
        throw new Exception('Wrond params');
    }
}