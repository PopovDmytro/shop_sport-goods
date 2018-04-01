<?php
class UserEntity extends \RexFramework\UserEntity
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\UserEntity:standart:1.0',
    );

    protected static $__table = "user";
    protected static $__uid = "id";

    protected static function initTypes()
    {
        parent::initTypes();
        static::add('lastname', new RexDBString());
        static::add('middlename', new RexDBString());
        static::add('city', new RexDBString());
        static::add('fillials', new RexDBString());
        static::add('address', new RexDBString());
        static::add('zip', new RexDBString());
        static::add('receiver', new RexDBString());
        static::add('delivery', new RexDBInt());
        static::add('notice', new RexDBText());
        static::add('confirm', new RexDBInt(0, 0, 1));
        static::add('avatar', new RexDBString(0, ''));
        static::add('is_registered', new RexDBInt());
        static::add('has_first_order', new RexDBInt(0, 0, 1));
    }

    // public function create()
    // {
    //     //echo 12397; exit;
    //     if (!$this->validate()) {
    //         return false;
    //     }

    //     $res = XDatabase::queryInsert($this->__table, $this);

    //     if (PEAR::isError($res)) {
    //         $this->_error = $res;
    //         return false;
    //     } else {
    //         $this->{$this->__uid} = XDatabase::getLastInsertID($this->__table);
    //         return true;
    //     }
    // }

}