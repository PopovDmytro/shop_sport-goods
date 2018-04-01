<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBEnum
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBEnum extends RexDBType
{
    /**
    * Max length of string
    * 
    * @var int
    */
    public function __construct($flags = 0, $default_value = 'ru', $length = array('ru'))
    {
        $flags = intval($flags);
        
        if (sizeof($length) < 1) {
            throw new Exception(__CLASS__.': length "'.$length.'" not correct');
        }
        $this->type = 'enum("'.implode('", "', $length).'")';
        
        parent::__construct($flags, $default_value);
    }
    
    protected function validate($value)
    {
        parent::validate($value);
        return true;
    }
    
    protected function convert($value)
    {
        return rtrim($value);
    }
}