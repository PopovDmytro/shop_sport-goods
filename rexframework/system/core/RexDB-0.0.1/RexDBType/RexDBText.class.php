<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBText
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBText extends RexDBType
{
    /**
    * Max length of string
    * 
    * @var int
    */
    protected $max_length;
    
    public function __construct($flags = 0, $default_value = null, $length = 65535)
    {
        $length = intval($length);
        $flags = intval($flags);
        
        if ($length < 1) {
            throw new Exception(__CLASS__.': length "'.$length.'" not correct');
        } elseif ($length < 256) {
            $this->type = 'tinytext';
        } elseif ($length < 65536) {
            $this->type = 'text';
        } elseif ($length < 16777216) {
            $this->type = 'mediumtext';
        } else {
            $this->type = 'longtext';
        }
        $this->max_length = $length;
        
        parent::__construct($flags, $default_value);
    }
    
    protected function validate($value)
    {
        if (mb_strlen($value, 'UTF-8') > $this->max_length) {
            throw new Exception(__CLASS__.': text "'.$value.'" longest than '.$this->max_length.' symbol');
        }
        parent::validate($value);
        return true;
    }
    
    protected function convert($value)
    {
        return (string)$value;
    }
}
?>