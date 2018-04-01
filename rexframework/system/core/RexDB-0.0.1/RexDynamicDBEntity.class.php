<?php
/**
 * Class RexDynamicDBEntity
 * For dynamic creating entity classes
 *
 * @author   MAG
 * @access   public
 * @created  Fri Mar 23 11:25:33 EET 2007
 */
class RexDynamicDBEntity
{
    public static function declaration($class_name, $table, $uid)
    {
        eval('class '.$class_name.' extends RexDBEntity
            {
                protected static $__table = \''.addslashes($table).'\';
                protected static $__uid = \''.addslashes($uid).'\';
                
                public function __construct($id = null)
                {
                    parent::__construct($id);
                }
            }');
    }
}