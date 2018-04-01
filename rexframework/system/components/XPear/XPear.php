<?php
    class XPear {
        function XPear($property = null) {
            global $XPear;
            if (!$XPear) $XPear = $this;
            $XPear->_init($property);
        }

        function _XPear()
        {

        }
        function create() {
            return new XPear();
        }

        function _init($property = null) { 
            $inidir = dirname(__FILE__) . "/pear";
            if (!defined("PATH_SEPARATOR")) {
                define("PATH_SEPARATOR", substr(php_uname(), 0, 3) == 'Win' ? ';' : ':');
            }
            ini_set("include_path", dirname(__FILE__) . "/pear". PATH_SEPARATOR . $inidir);

            if($property && is_array($property)){
                foreach($property as $type => $items){
                    if(isset($items['item'])){
                        if(!is_array($items['item']) || !array_key_exists(0,$items['item'])){
                            echo "1";
                            $items['item'] = array($items['item']);
                        }

                        foreach($items['item'] as $require)
                        {
                            switch ($type){
                                case "include": 
                                    include "$require";
                                    break;
                                case "include_once": 
                                    include_once "$require";
                                    break; 
                                case "require":
                                    require "$require";
                                    break;
                                case "require_once":
                                    require_once "$require";
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
?>
