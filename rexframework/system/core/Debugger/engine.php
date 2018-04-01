<?php

class RexDebugEngine 
{    
    static private function checkUinDir($uin, $create_dirs = true)
    {
        $engine_folder = dirname(__FILE__).'/engine/';
        $uin_dir = $engine_folder.$uin.'/';
        
        if (!$create_dirs) {
            if (is_dir($uin_dir)) {
                return $uin_dir;
            } else {
                return false;
            }
        }
        
        if (!is_dir($uin_dir)) {
            mkdir($uin_dir);
            chmod($uin_dir, 0777);
        }
        return $uin_dir;
    }
    
    static private function clearDir($uin, $session)
    {
        $uin_dir = static::checkUinDir($uin, false);
        $session_dir = $uin_dir.$session.'/';
        if (is_dir($session_dir)) {
            @system('rm -r '.$session_dir);
        }
    }
    
    static private function checkDirs($uin, $session, $create_dirs = true)
    {
        $uin_dir = static::checkUinDir($uin, $create_dirs);
        $session_dir = $uin_dir.$session.'/';
        
        if (!$create_dirs) {
            if ($uin_dir !== false && is_dir($session_dir)) {
                return $session_dir;
            } else {
                return false;
            }
        }
        
        if (!is_dir($session_dir)) {
            mkdir($session_dir);
            chmod($session_dir, 0777);
        }
        return $session_dir;
    }
    
    static private function findNextSession($uin, $session)
    {
        static::log('find next session', $uin, $session);
        $session = intval($session);
        $uin_dir = static::checkUinDir($uin, false);
        
        if ($uin_dir) {
            $files = array();
            $od = opendir($uin_dir);
            while (($file = readdir($od)) !== false) {
                if ($file[0] != '.' && is_dir($uin_dir.$file)) {
                    if (file_exists($uin_dir.$file.'/state.txt')) {
                        $files[intval($file)] = $file;
                    } else {
                        static::clearDir($uin, $file);
                    }
                }
            }
            closedir($od);
            
            ksort($files);
            foreach ($files as $ifile => $file) {
                static::log('val', $file, $ifile, $session);
                if ($ifile > $session) {
                    static::log('return', $file);
                    return $file;
                } else {
                    static::clearDir($uin, $file);
                }
            }
        }
        return false;
    }
    
    static private function writeDebugFile($filename, $data)
    {
        return file_put_contents($filename, serialize($data)) !== false && chmod($filename, 0777);
    }
    
    static private function readDebugFile($filename)
    {
        if (!file_exists($filename)) {
            return 0;
        }
        return unserialize(file_get_contents($filename));
    }
    
    static public function putState($uin, $session, $state)
    {
        $session_dir = static::checkDirs($uin, $session);
        static::writeDebugFile($session_dir.'state.txt', $state);
    }
    
    static public function getState($uin, $session, $last_file)
    {
        $session_dir = static::checkDirs($uin, $session, false);
        if (!$session_dir || !is_file($session_dir.'state.txt')) {
            $session = static::findNextSession($uin, $session);
            if ($session) {
                $session_dir = static::checkDirs($uin, $session, false);
            }
        }
        
        $answer = array(
            'error' => 0,
            'content' => 0,
            'status' => 'off',
            'uin' => $uin
        );
        if ($session_dir) {
            $answer['content'] = static::readDebugFile($session_dir.'state.txt');
            if (is_array($answer['content']) && isset($answer['content']['file'])) {
                if ($last_file != $answer['content']['file']) {
                    $answer['file'] = RexDebugger::getDebugInfo($answer['content']['file']);
                }
                $answer['status'] = 'on';
            }
        }
        return $answer;
    }
    
    static public function putCommand($uin, $session, $command)
    {
        $session_dir = static::checkDirs($uin, $session);
        $result = static::writeDebugFile($session_dir.'command.txt', $command);
        if ($command == 'exit') {
            @unlink($session_dir.'state.txt');
        }
        return $result;
    }
    
    static public function getCommand($uin, $session)
    {
        $session_dir = static::checkDirs($uin, $session, false);
        if (!$session_dir) {
            return 'exit';
        }
        return static::readDebugFile($session_dir.'command.txt');
    }
    
    static public function getTree($dir) {
        exec('cd "'.$dir.'"; tree --dirsfirst -f -P *.php | grep .php', $list);
        $result = array();
        foreach ($list as $file) {
            if (substr($file, -4) == '.php' && substr($file, -8) != '.tpl.php' && substr($file, 4) != 'dbg-') {
                $dirpos = strpos($file, './');
                if ($dirpos !== false) {
                    $file = substr($file, $dirpos + 2);
                    $struct = explode('/', $file);
                    $curr_result = &$result;
                    foreach ($struct as $key) {
                        $curr_result = &$curr_result[$key];
                    }
                    $curr_result = $file;
                }
            }
        }
        return $result;
    }
    
    static public function process()
    {
        switch (DEBUG_ECOMMAND) {
            case 'putCommand':
                if (!isset($_POST['rexdebug_uin']) || !isset($_POST['rexdebug_session']) || !isset($_POST['command'])) {
                    throw new Exception('RexDebug wrong post');
                }
                $uin = $_POST['rexdebug_uin'];
                $session = $_POST['rexdebug_session'];
                $command = $_POST['command'];
                static::putCommand($uin, $session, $command);
                echo json_encode(array(
                    'error' => '',
                    'content' => 'ok'
                ));
                exit;
                break;
            case 'getState':
                if (!isset($_POST['rexdebug_uin']) || !isset($_POST['rexdebug_session']) || !isset($_POST['rexdebug_file'])) {
                    throw new Exception('RexDebug wrong post');
                }
                $uin = $_POST['rexdebug_uin'];
                $session = $_POST['rexdebug_session'];
                $last_file = $_POST['rexdebug_file'];
                $answer = static::getState($uin, $session, $last_file);
                echo json_encode($answer);
                exit;
                break;
            case 'getSrc':
                if (!isset($_POST['rexdebug_file'])) {
                    throw new Exception('RexDebug wrong post');
                }
                $file = $_POST['rexdebug_file'];
                $file = realpath(DEBUG_ROOT.$file);
                $answer = RexDebugger::getDebugInfo($file);
                echo json_encode($answer);
                exit;
                break;
            case 'setBreakpoint':
                $file = $_POST['rexdebug_file'];
                if (!file_exists($file)) {
                    $file = realpath(DEBUG_ROOT.$file);
                }
                $row = $_POST['rexdebug_bprow'];
                $bp = static::getBreakpoints($file);
                $bp[$row] = true;
                static::setBreakpoints($file, $bp);
                echo json_encode(array(
                    'content' => 'ok'
                ));
                exit;
                break;
            case 'removeBreakpoint':
                $file = $_POST['rexdebug_file'];
                if (!file_exists($file)) {
                    $file = realpath(DEBUG_ROOT.$file);
                }
                $row = $_POST['rexdebug_bprow'];
                $bp = static::getBreakpoints($file);
                unset($bp[$row]);
                static::setBreakpoints($file, $bp);
                echo json_encode(array(
                    'content' => 'ok'
                ));
                exit;
                break;
            default: 
                //print_r(static::getFileStruct(realpath(DEBUG_ROOT).'/'));
                $tree = static::getTree(realpath(DEBUG_ROOT).'/');
                require_once dirname(__FILE__).'/engine_interface.php';
        }
    }
    
    static public function log($text)
    {
        if (func_num_args() > 1) {
            $arr = func_get_args();
            foreach ($arr as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $arr[$key] = json_encode($value);
                }
            }
            $text = implode('; ', $arr);
        }
        file_put_contents(dirname(__FILE__).'/log.txt', $text."\n", FILE_APPEND);
    }
    
    static public function shutdown($uin, $session) 
    {
        static::log('exit '.json_encode(get_defined_vars()));
        static::clearDir($uin, $session);
    } 
    
    static function getBreakpoints($filename = false)
    {
         $result = array();
        $uin = $_COOKIE['rexdebug_uin'];
        $uin_dir = static::checkUinDir($uin, false);
        $bp_file = $uin_dir.'breakpoints';
        if (file_exists($bp_file)) {
            $breakpoints = file_get_contents($bp_file);
            if ($filename !== false && sizeof($breakpoints)) {
                $breakpoints = unserialize($breakpoints);
                if (is_array($breakpoints) && isset($breakpoints[$filename])) {
                    $result = $breakpoints[$filename];
                }
            } elseif (sizeof($breakpoints)) {
                $result = unserialize($breakpoints);
            }
        }
        return $result;
    }
    
    static function setBreakpoints($filename, $breakpoints = false)
    {
        $result = array();
        $uin = $_COOKIE['rexdebug_uin'];
        $uin_dir = static::checkUinDir($uin);
        $bp_file = $uin_dir.'breakpoints';
        if (!file_exists($bp_file)) {
            touch($bp_file);
            chmod($bp_file, 0777);
        }
        $content = static::getBreakpoints();
        if (!$breakpoints) {
            if (isset($content[$filename])) {
                unset($content[$filename]);
            }
        } else {
            $content[$filename] = $breakpoints;
        }
        file_put_contents($bp_file, serialize($content));
    }
    
    static function clearBreakpoints($filename = false)
    {
        if ($filename) {
            static::setBreakpoints($filename, false);
        } else {
            $uin = $_COOKIE['rexdebug_uin'];
            $uin_dir = static::checkUinDir($uin, false);
            $bp_file = $uin_dir.'breakpoints';
            if (file_exists($bp_file)) {
                @unlink($bp_file);
            }
        }
    }
    
    static function isBreakpoint($filename, $row)
    {
        $breakpoints = static::getBreakpoints($filename);
        return isset($breakpoints[$row]) && $breakpoints[$row];
    }
}