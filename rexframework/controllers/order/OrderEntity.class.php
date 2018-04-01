<?php

class OrderEntity extends \RexShop\OrderEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

    protected static $__table = "rexorder";
    protected static $__uid   = "id";

    protected static function initTypes()
    {
        parent::initTypes();
        static::add('price_opt', new RexDBFloat());
        static::add('sale', new RexDBFloat());
        static::add('city', new RexDBString());
        static::add('fillials', new RexDBString());
        static::add('operator', new RexDBString());
        static::add('delivery', new RexDBString());
        static::add('name_single', new RexDBString());
        static::add('admin_comment', new RexDBText());
        static::add('is_readed', new RexDBInt());
    }
}