<?php
    class Pager
    { 
        function Pager($options = array())
        {   
            if (get_class($this) == 'pager') {  
                eval('$this = Pager::factory($options);');
            } else { 
                $msg = 'Pager constructor is deprecated.'
                .' You must use the "Pager::factory($params)" method'
                .' instead of "new Pager($params)"';
                trigger_error($msg, E_USER_ERROR);
            }
        }  
        static function &factory($options = array())
        {
            $mode = (isset($options['mode']) ? ucfirst($options['mode']) : 'Jumping');
            $classname = 'Pager_' . $mode;
            $classfile = 'Pager' . DIRECTORY_SEPARATOR . $mode . '.php';   
            if (!class_exists($classname)) {
                include_once $classfile;
            } 
            if (class_exists($classname)) {
                $pager = new $classname($options);
                return $pager;
            }
            $null = null;
            return $null;
        } 
    }
?>