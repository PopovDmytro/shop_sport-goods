<?php
namespace RexFramework;

use \XDatabase as XDatabase;
use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBFloat as RexDBFloat;
use \RexDBString as RexDBString;
use \RexDBText as RexDBText;
use \RexDBDate as RexDBDate;
use \RexDBDatetime as RexDBDatetime;

/**
 * Class UserEntity
 *
 * Entity of users
 *
 * @author   Fatal
 * @access   public
 * @created  Fri Mar 23 11:25:33 EET 2007
 */
class UserEntity extends RexDBEntity
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    
    protected static $__table = "user";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('login', new RexDBString(REXDB_FIELD_NOTNULL, '', 64));
        static::add('password', new RexDBString(REXDB_FIELD_NOTNULL, '', 32));
        static::add('email', new RexDBString(REXDB_FIELD_NOTNULL, '', 128));
        static::add('role', new RexDBString(REXDB_FIELD_NOTNULL, 'user', 64));
        static::add('date_create', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('date_update', new RexDBDatetime(REXDB_FIELD_NOTNULL, 'now'));
        static::add('name', new RexDBString());
        static::add('active', new RexDBInt(REXDB_FIELD_NOTNULL & REXDB_FIELD_UNSIGNED, 1, 1));
        static::add('clear_password', new RexDBString(REXDB_FIELD_NOTNULL, '', 32));
        static::add('phone', new RexDBString());
        static::add('phone_validation', new RexDBString());
    }

    
    function deactivateUser($id){
        $sql = 'UPDATE `user` SET `active` = 0 WHERE `id` = ' . $id;
        $res = XDatabase::query($sql);
        if($res) {
            return true;
        }
        return false;
    }
    
     function getUserByLoginOrEmail($login = false, $email = false){
         if(!$login or !$email){ 
             return false; 
         }
         
         $sql = "SELECT * FROM `user` WHERE `login` = ? OR `email` = ?";
         $result = XDatabase::getRow($sql, array($login, $email));
         
         if(is_array($result)){
             $this->set($result);
             return true;
         }
         
         return false;
     }
     
    function confirmation($aHash){
        $sql = 'SELECT COUNT(*) FROM `user` WHERE md5(CONCAT(`id`,`date_create`)) = ? AND `confirm` = 0';
        $res = XDatabase::getOne($sql, array($aHash));
        if($res and $res > 0) {
            XDatabase::query('UPDATE `user` SET `active` = 1, `confirm` = 1 WHERE md5(CONCAT(`id`,`date_create`)) = ?', array($aHash));
            return true;
        }
        return false;
    }
}