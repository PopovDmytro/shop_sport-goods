<?php

class Prod2TechEntity extends RexDBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

	protected static $__table = 'prod2tech';
	protected static $__uid   = 'id';

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('product_id', new RexDBInt());
        static::add('technology_id', new RexDBInt());
    }
}