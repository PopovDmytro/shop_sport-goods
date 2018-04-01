<?php

/**
 * Class TechnologyEntity
 *
 * Entity of Technology
 *
 * @author   petroved
 * @access   public
 * @created  Thu Nov 14 10:05:33 EET 2013
 */
class TechnologyEntity extends RexDBEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

    protected static $__table = "technology";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('name', new RexDBString());
        static::add('description', new RexDBText());
        static::add('icon', new RexDBString());
    }
}