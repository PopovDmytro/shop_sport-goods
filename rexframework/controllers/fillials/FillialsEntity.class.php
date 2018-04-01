<?php
class FillialsEntity extends RexDBEntity
{
    static public $assemble = 'volley.standart';
    static public $version = '1.0';
    
    protected static $__table = "fillials";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString());
        static::add('city_id', new RexDBInt());
        static::add('phone', new RexDBString());
    }  
}