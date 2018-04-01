<?php
class CommentEntity extends RexDBEntity
{
    public static $assemble = 'vooley.standart';
    public static $version = '1.0';
    
    protected static $__table = "comment";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('user_id', new RexDBInt());
        static::add('content', new RexDBText());
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('product_id', new RexDBInt());
        static::add('status', new RexDBInt());
        static::add('type', new RexDBInt());
        static::add('article_id', new RexDBInt());
        static::add('news_id', new RexDBInt());
    }
}