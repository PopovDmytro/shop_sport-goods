<?php

class LoginerEntity extends RexDBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    
    protected static $__table = "loginer";
    protected static $__uid = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('user_id', new RexDBInt());
        static::add('identity', new RexDBString());
        static::add('provider', new RexDBString());
        static::add('uid', new RexDBString());
    }
 
}