<?php

class RexResponse extends RexObject {
    protected static $errors = array();
    protected static $content = '';

    public static function init() {
        static $init = false;
        if (!$init) {
            ob_start();
            RexDisplay::assign('uin', RexResponse::getDialogUin());
            $init = true;
        }
    }

    public static function isRequest() {
        return isset($_REQUEST['rex_request']) && $_REQUEST['rex_request'];
    }
    
    public static function isLocation() {
        return isset($_REQUEST['rex_location']) && $_REQUEST['rex_location'];
    }
    
    public static function isSubmit() {
        return isset($_REQUEST['rex_request_form']) && $_REQUEST['rex_request_form'];
    }

    public static function getDialogUin() {
        return isset($_REQUEST['rex_dialog_uin']) ? $_REQUEST['rex_dialog_uin'] : '';
    }

    public static function error($message, $send = true) {
        static::$errors[] = $message;
        if ($send) {
            static::send();
        }
    }

    public static function response($content, $send = true) {
        static::$content = $content;
        if ($send) {
            static::send();
        }
    }

    public static function isError() {
        return (bool)static::$errors;
    }

    public static function responseDialog($template, $width, $height, $send = true) {
        static::$content = array(
            'template' => $template,
            'width' => $width,
            'height' => $height);
        if ($send) {
            static::send();
        }
    }

    public static function send() {
//        $output = ob_get_clean();
        $output = '';
        @ob_end_clean();
        if ($output) {
            echo $output;
            exit;
        }
        
        if (static::$errors) {
            echo @json_encode(array('error' => static::$errors));
            exit;
        }
        echo @json_encode(array('content' => static::$content));
        exit;
    }
}