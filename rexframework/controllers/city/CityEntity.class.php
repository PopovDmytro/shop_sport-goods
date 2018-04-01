<?php
class CityEntity extends RexDBEntity
{
    static public $assemble = 'volley.standart';
    static public $version = '1.0';
    
    protected static $__table = "city";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString());
    }
}