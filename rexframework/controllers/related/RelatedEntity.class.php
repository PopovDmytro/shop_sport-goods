<?php

class RelatedEntity extends RexDBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

    protected static $__table = "related";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('product_id', new RexDBInt());
        static::add('related_id', new RexDBInt());
    }
}