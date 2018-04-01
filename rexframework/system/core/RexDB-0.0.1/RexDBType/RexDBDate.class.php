<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBDate
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBDate extends RexDBType
{    
    public function __construct($flags = 0, $default_value = 'now')
    {
        $flags = intval($flags);
        $this->type = 'date';
        
        parent::__construct($flags, $default_value);
    }
    
    protected function validate($value)
    {
        parent::validate($value);
        return true;
    }
    
    protected function convert($value)
    {
        if ($value == 'now') {
            return strftime('%F');
        } else {
            return strftime('%F', strtotime($value));
        }
    }
}
?>