<?php
/**
 * Class TestEntity
 *
 * Entity of test
 *
 * @author   Fatal
 * @access   public
 * @created  Fri Mar 23 11:25:33 EET 2007
 */
class TestEntity extends RexDBEntity
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    
    protected static $__table = "test";
    protected static $__uid   = "id";
     
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('controller', new RexDBString());
        static::add('result', new RexDBText());
        static::add('date_create', new RexDBDatetime());
        static::add('status', new RexDBInt(0, 0, 1));
        static::add('active', new RexDBInt(0, 1, 1));
    }
        

    public function __construct($id = null)
    {
        parent::__construct($id);
    }
}