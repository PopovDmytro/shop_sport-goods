<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBString
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBString extends RexDBType
{
    /**
    * Max length of string
    * 
    * @var int
    */
    protected $max_length;
    
    public function __construct($flags = 0, $default_value = '', $length = 255)
    {
        $length = intval($length);
        $flags = intval($flags);
        
        if ($length < 1) {
            throw new Exception(__CLASS__.': length "'.$length.'" not correct');
        }
        $this->type = 'varchar('.$length.')';
        $this->max_length = $length;
        
        parent::__construct($flags, $default_value);
    }
    
    protected function validate($value)
    {
        if (mb_strlen($value, 'UTF-8') > $this->max_length) {
            throw new Exception(__CLASS__.': string "'.$value.'" longest than '.$this->max_length.' symbol');
        }
        parent::validate($value);
        return true;
    }
    
    protected function convert($value)
    {
        return rtrim($value);
    }
}
?>