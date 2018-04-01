<?php

class RexDebugger 
{
    static $test = false;
    
    static function run($file, $uin, $session) 
    {
        register_shutdown_function(array('RexDebugEngine', 'shutdown'), $uin, $session);
        $index = static::compile($file, true);
        include $index;
    }
    
    static private function getCompileName($file)
    {
        $finfo = pathinfo($file);
        $name = 'dbg-'.$finfo['basename'].'-dbg-'.md5($file).'.'.$finfo['extension'];
        return $name;
    }
    
    static private function needUpdate($source_file, $compile_file)
    {
        return !file_exists($compile_file) || filemtime($source_file) > filemtime($compile_file);
    }
    
    static private function compile($file, $index = false)
    {
        if (!file_exists($file)) {
            $file = get_include_path().$file;
            //echo '-i='.$file.'=i-';
        }
        if (!file_exists($file)) {
            print_r(debug_backtrace());
            exit;
        }
        $file = realpath($file);
        
        $compile_dir = dirname(__FILE__).'/compile/';
        $compile_name = static::getCompileName($file);
        $compile_filename = $compile_dir.$compile_name;
        if (static::needUpdate($file, $compile_filename)) {
            
            $content = file_get_contents($file);
            
            if ($index) {
                $rexdebug_pos = strpos($content, '//REXDEBUG');
                if ($rexdebug_pos === false) {
                    throw new Exception('Index file uncorrect. Add debugger logic');
                }
                $content = '<?php'.substr($content, $rexdebug_pos + 10);
            }
            
            //echo $content; exit;
            
            $tokens = token_get_all($content);
            
            /*foreach ($tokens as $key => $token) {
                if (is_array($token)) {
                    $tokens[$key][3] = token_name($token[0]);
                }
            }
            
            print_r($tokens);
            exit;*/
            
            $start = 0;
            $breakPointRows = array();
            $includeRows = array();
            $includeOnceRows = array();
            $requireRows = array();
            $requireOnceRows = array();
            
            $inclass = array();
            $infunction = array();
            $near_class = false;
            $near_function = false;
            $near_object = false;
            
            $incase = false;
            $infollelse = false;
            
            $scop_cnt = 0;
            $wait_forend = false;
            $wait_forendround = false;
            $wait_forendround_cnt = 0;
            
            foreach ($tokens as $token) {
                $tag = is_array($token) ? $token[0] : $token.'';
                if ($tag === '{') {
                    $scop_cnt++;
                    if ($near_class) {
                        array_unshift($inclass, $scop_cnt);
                        $near_class = false;
                    }
                    if ($near_function) {
                        array_unshift($infunction, $scop_cnt);
                        $near_function = false;
                    }
                    $infollelse = false;
                } elseif ($tag === '}') {
                    $scop_cnt--;
                    if ($inclass && $inclass[0] > $scop_cnt) {
                        array_shift($inclass);
                    }
                    if ($infunction && $infunction[0] > $scop_cnt) {
                        array_shift($infunction);
                    }
                } elseif ($tag === T_CLASS || $tag === T_INTERFACE) {
                    $near_class = true;
                } elseif ($tag === T_FUNCTION) {
                    $near_function = true;
                } elseif ($tag === ';') {
                    $near_class = false;
                    $near_function = false;
                } elseif ($tag === T_ELSE || $tag === T_ELSEIF) {
                    $infollelse = true;
                } elseif ($tag === T_INCLUDE) {
                    $includeRows[] = $token[2];
                } elseif ($tag === T_INCLUDE_ONCE) {
                    $includeOnceRows[] = $token[2];
                } elseif ($tag === T_REQUIRE) {
                    $requireRows[] = $token[2];
                } elseif ($tag === T_REQUIRE_ONCE) {
                    $requireOnceRows[] = $token[2];
                }
                
                if ($tag === T_CASE) {
                    $incase = true;
                } elseif ($incase && $tag === ':') {
                    $incase = false;
                } elseif ($tag === T_OBJECT_OPERATOR) {
                    $near_object = true;
                } elseif ($tag === T_NAMESPACE) {
                    $wait_forend = true;
                } elseif ($wait_forend && $tag === ';') {
                    $wait_forend = false;
                    $start = 0;
                } elseif ($tag === T_FOR) {
                    $wait_forendround = true;
                    $wait_forendround_cnt = 0;
                } elseif ($wait_forendround && $tag === '(') {
                    $wait_forendround_cnt++;
                } elseif ($wait_forendround && $tag === ')') {
                    $wait_forendround_cnt--;
                    if (!$wait_forendround_cnt) {
                        $wait_forendround = false;
                    }
                } elseif (!$incase && !$infollelse && !$wait_forend && !$wait_forendround) {
                    if ($start) {
                        if ($tag === ';') {
                            if (!$inclass || ($inclass && $infunction)) {
                                $breakPointRows[] = $start;
                            }
                            $near_object = false;
                            $start = 0;
                            //echo '-start=02'."\n";
                        } elseif ($tag === T_OPEN_TAG || $tag === T_CLOSE_TAG || 
                            (!$near_object && ($tag === '}' || $tag === '{'))) 
                        {
                            //echo '-start=01'." tag $tag ".token_name($tag).print_r($token, true)."\n";
                            $start = 0;
                        }
                    } elseif (is_array($token) && $tag !== T_WHITESPACE && $tag !== T_COMMENT && $tag !== T_OPEN_TAG && $tag !== T_CLOSE_TAG)
                    {
                        //echo '-start='.$token[2]." tag $tag ".token_name($tag)."\n";
                        $start = $token[2];
                    }
                }
            }            
            //exit;
            
            //var_dump($includeRows, $includeOnceRows, $requireRows, $requireOnceRows); exit;
            
            $content = preg_split('/\r?\n/s', $content);
            if (substr($file, -6) != '.tpl.php') {
                foreach ($breakPointRows as $row) {
                    $content[$row - 1] = '\RexDebugger::breakPoint(__FILE__, __LINE__, get_defined_vars()); '.$content[$row - 1];
                }
            }
            $row_positions = array(1 => 0);
            foreach ($content as $row => $str) {
                //echo '-='.$str.'=-';
                $row_positions[$row + 2] = $row_positions[$row + 1] + strlen($str) + 1;
            }
            $content = implode("\n", $content);
            
            //var_dump($content); exit;
            //var_dump($row_positions); exit;
            
            $replace = array();
            $filedir = dirname($file).'/';
            if (basename($file) != 'smarty_internal_templatebase.php') {
                foreach ($includeRows as $row) {
                    /*$content = preg_replace(array(
                        '/\n{'.($row-1).'}[^\n]*\s+include\s*\(([^;]+)\)\s*;/is',
                        '/\n{'.($row-1).'}[^\n]*\s+include\s+([^;]+)\s*;/is'
                        ), array(
                        ' RexDebugger::debugInclude($1);',
                        ' RexDebugger::debugInclude($1);'
                        ), $content, 1);*/
                    $math = preg_match('/include\s*\(([^;]+)\)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    if (!$math) {
                        $math = preg_match('/include\s+([^;]+)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    }
                    if (($matches[1][0] == '\'' || $matches[1][0] == '"') && $matches[1][1] != '/' && file_exists($filedir.substr($matches[1], 1, -1))) {
                        $matches[1] = 'dirname(__FILE__).\'/\'.'.$matches[1];
                    }
                    $replace[$matches[0]] = '\RexDebugger::debugInclude('.$matches[1].');';
                }
                foreach ($includeOnceRows as $row) {
                    /*$content = preg_replace(array(
                        '/\n{'.($row-1).'}[^\n]*\s+include_once\s*\(([^;]+)\)\s*;/is',
                        '/\n{'.($row-1).'}[^\n]*\s+include_once\s+([^;]+)\s*;/is'
                        ), array(
                        ' RexDebugger::debugIncludeOnce($1);',
                        ' RexDebugger::debugIncludeOnce($1);'
                        ), $content, 1);*/
                    $math = preg_match('/include_once\s*\(([^;]+)\)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    if (!$math) {
                        $math = preg_match('/include_once\s+([^;]+)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    }
                    if (($matches[1][0] == '\'' || $matches[1][0] == '"') && $matches[1][1] != '/' && file_exists($filedir.substr($matches[1], 1, -1))) {
                        $matches[1] = 'dirname(__FILE__).\'/\'.'.$matches[1];
                    }
                    $replace[$matches[0]] = '\RexDebugger::debugIncludeOnce('.$matches[1].');';
                }
                foreach ($requireRows as $row) {
                    $math = preg_match('/require\s*\(([^;]+)\)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    if (!$math) {
                        $math = preg_match('/require\s+([^;]+)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    }
                    if (($matches[1][0] == '\'' || $matches[1][0] == '"') && $matches[1][1] != '/' && file_exists($filedir.substr($matches[1], 1, -1))) {
                        $matches[1] = 'dirname(__FILE__).\'/\'.'.$matches[1];
                    }
                    $replace[$matches[0]] = '\RexDebugger::debugRequire('.$matches[1].');';
                    /*$content = preg_replace(array(
                        '/\n{'.($row-1).'}[^\n]*\s+require\s*\(([^;]+)\)\s*;/is',
                        '/\n{'.($row-1).'}[^\n]*\s+require\s+([^;]+)\s*;/is'
                        ), array(
                        ' RexDebugger::debugRequire($1);',
                        ' RexDebugger::debugRequire($1);'
                        ), $content, 1);*/
                }
                foreach ($requireOnceRows as $row) {
                    $math = preg_match('/require_once\s*\(([^;]+)\)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    if (!$math) {
                        $math = preg_match('/require_once\s+([^;]+)\s*;/is', $content, $matches, null, $row_positions[$row]);
                    }
                    if (($matches[1][0] == '\'' || $matches[1][0] == '"') && $matches[1][1] != '/' && file_exists($filedir.substr($matches[1], 1, -1))) {
                        $matches[1] = 'dirname(__FILE__).\'/\'.'.$matches[1];
                    }
                    $replace[$matches[0]] = '\RexDebugger::debugRequireOnce('.$matches[1].');';
                    /*$content = preg_replace(array(
                        '/(\n{'.($row-1).'}[^\n]*\s+)require_once\s*\(([^;]+)\)\s*;/is',
                        '/(\n{'.($row-1).'}[^\n]*\s+)require_once\s+([^;]+)\s*;/is'
                        ), array(
                        '$1RexDebugger::debugRequireOnce($2);',
                        '$1RexDebugger::debugRequireOnce($2);'
                        ), $content, 1);*/
                }
            }
            
            //exit;
            //echo $content; exit;
            $replace['__FILE__'] = '\''.$file.'\'';
            $content = str_replace(array_keys($replace), array_values($replace), $content);
            /*$content = preg_replace(array(
                '/\srequire_once\s*\(([^;\/]+)\)\s*;/is',
                '/\srequire_once\s+([^;\/]+)\s*;/is',
                '/\srequire\s*\(([^;\/]+)\)\s*;/is',
                '/\srequire\s+([^;\/]+)\s*;/is',
                '/\sinclude_once\s*\(([^;\/]+)\)\s*;/is',
                '/\sinclude_once\s+([^;\/]+)\s*;/is',
                '/\sinclude\s*\(([^;\/]+)\)\s*;/is',
                '/\sinclude\s+([^;\/]+)\s*;/is'
                ), array(
                ' RexDebugger::debugRequireOnce($1);',
                ' RexDebugger::debugRequireOnce($1);',
                ' RexDebugger::debugRequire($1);',
                ' RexDebugger::debugRequire($1);',
                ' RexDebugger::debugIncludeOnce($1);',
                ' RexDebugger::debugIncludeOnce($1);',
                ' RexDebugger::debugInclude($1);',
                ' RexDebugger::debugInclude($1);'
                ), $content);*/
                
            //echo $content; exit;
                
            file_put_contents($compile_filename, $content);
            chmod($compile_filename, 0777);
        }
        return $compile_filename;
    }
    
    static function getDebugInfo($file)
    {
        if (!file_exists($file)) {
            throw new Exception('Unknown file');
        }
        $compile_dir = dirname(__FILE__).'/compile/';
        $compile_name = static::getCompileName($file);
        $compile_filename = $compile_dir.$compile_name;
        
        
        if (!file_exists($compile_filename)) {
            static::compile($file);
        } 
        
        $source_file = file($file);
        $compile_file = file($compile_filename);
        
        if (sizeof($source_file) != sizeof($compile_file)) {
            while ($source_file && trim($source_file[0]) !== '//REXDEBUG') {
                array_shift($source_file);
            }
            if (!$source_file) {
                throw new Exception('Wrong compile');
            }
            $source_file[0] = "<?php\n";
        }
        
        $debug_info = array();
        $content = '';
        $allow_break = array();
        foreach ($source_file as $row_number => $source_row) {
            if (substr($compile_file[$row_number], 0, 25) == '\RexDebugger::breakPoint(') {
                $allow_break[] = $row_number + 1;
            }
            $content .= $source_row;
        }
        return array(
            'content' => $content,
            'allow_break' => $allow_break,
            'breakpoints' => RexDebugEngine::getBreakpoints(realpath($file))
        );
    }
    
    static function breakPoint($file, $line, $defined_vars) 
    {
        static $last_command = 'wait';
        static $last_file = '';
        static $last_line = '';
        static $last_backtrace = array();
        
        $backtrace = debug_backtrace();
        
        if (!RexDebugEngine::isBreakpoint($file, $line)) {
            switch ($last_command) {
                case 'run':
                    return;
                case 'stepover':
                    if (sizeof($last_backtrace) < sizeof($backtrace) || 
                        !isset($backtrace[sizeof($backtrace) - 1]['file']) ||
                        $last_backtrace[sizeof($last_backtrace) - 1]['file'] != $backtrace[sizeof($backtrace) - 1]['file']) 
                    {
                        return;
                    }
                    RexDebugEngine::log($backtrace, $last_backtrace);
                    break;
                case 'stepout':
                    if (sizeof($last_backtrace) <= sizeof($backtrace)) {
                        return;
                    }
                    break;
            }
        }
        
        if (!isset($_COOKIE['rexdebug_uin']) || !isset($_COOKIE['rexdebug_session'])) {
            return;
        }
        
        $uin = $_COOKIE['rexdebug_uin'];
        $session = $_COOKIE['rexdebug_session'];
        
        $state = array(
            'file' => $file,
            'line' => $line,
            'session' => $_COOKIE['rexdebug_session'],
            'vars' => $defined_vars
        );
        RexDebugEngine::putState($uin, $session, $state);
        RexDebugEngine::putCommand($uin, $session, 'wait');
        $command = 'wait';
        do {
            time_nanosleep(0, 500);
            $command = RexDebugEngine::getCommand($uin, $session);
        } while ($command == 'wait');
        
        switch ($command) {
            case 'exit':
                exit;
        }
        
        $last_command = $command;
        $last_file = $file;
        $last_line = $line;
        $last_backtrace = $backtrace;
        /*static $cnt = 0;
        $cnt++;
        if ($cnt == 200) {
            file_put_contents(dirname(__FILE__).'/log.txt', var_export($defined_vars, true));
        }*/
    }
    
    static function debugRequireOnce($file)
    {
        /*if (strstr($file, 'Database') !== false || static::$test) {
            static::$test = true;
            echo "-=$file=-\n";
        }*/
        $compile_file = static::compile($file);
        return require_once $compile_file;
    }
    
    static function debugRequire($file)
    {
        /*if (strstr($file, 'Database') !== false || static::$test) {
            static::$test = true;
            echo "-=$file=-\n";
        }*/
        $compile_file = static::compile($file);
        return require $compile_file;
    }
    
    static function debugIncludeOnce($file)
    {
        /*if (strstr($file, 'Database') !== false || static::$test) {
            static::$test = true;
            echo "-=$file=-\n";
        }*/
        $compile_file = static::compile($file);
        return include_once $compile_file;
    }
    
    static function debugInclude($file)
    {
        /*if (strstr($file, 'Database') !== false || static::$test) {
            static::$test = true;
            echo "-=$file=-\n";
        }*/
        $compile_file = static::compile($file);
        return include $compile_file;
    }
}