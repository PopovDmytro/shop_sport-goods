<?php
namespace RexFramework;

use \RexFactory as RexFactory;

/**
 * Class ErrorLogManager
 *
 * Manager of Article
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ErrorLogManager
{
    function post($type, $message, $trace = '')
    {
        return false;
        $logEntity = RexFactory::entity('errorLog');
        
        try {
            $logEntity->server = var_export($_SERVER, true);
            $logEntity->request = var_export($_REQUEST, true);
            if ($_FILES) {
                $logEntity->files = var_export($_FILES, true);
            }
            $logEntity->session = var_export($_SESSION, true);
            $logEntity->cookie = var_export($_COOKIE, true);
            $logEntity->error_message = $message;
            //$logEntity->trace = var_export($trace, true);
            $logEntity->type = $type;
            $logEntity->create();
        } catch (Exception $e) {
            
        }
        
        return $logEntity->id;
    }
}