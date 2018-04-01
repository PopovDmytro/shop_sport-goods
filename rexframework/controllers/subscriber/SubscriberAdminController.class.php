<?php

namespace RexFramework;

use \XDatabase as XDatabase;
use \RexLang as RexLang;

/**
 * Class UserAdminController
 *
 * User Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  UserAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SubscriberAdminController extends ParentAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';

    function getDefault()
    {
        return parent::getDefault();
    }

    public function _getFields($fields)
    {
        return array(
            array('<b>ID</b>',          array($this, '_DGId'), array('width' => 15)),
            array('<b>E-Mail</b>',      array( $this, '_DGEmail')),
            array('<b>Действие</b>',    array($this, '_DGActions'))
        );
    }

    public function _getData($filters, $fields){
        $sql = "FROM subscribers";

        $sql_where = '';
        if ( $filters['search'] != '' ) {
            $sql_where = " WHERE id LIKE '{$filters['search']}' OR subscriber_email LIKE '%{$filters['search']}%'";
        }
        $sql_order = " ORDER BY id DESC";

        return array(
            0 => XDatabase::getAll( "SELECT id, subscriber_email as email " . $sql . $sql_where . $sql_order),
            1 => XDatabase::getOne( "SELECT count(id) " . $sql . $sql_where . $sql_order)
        );
    }

    public function _DGId($param){
        return $param['record']['id'];
    }

    public function _DGEmail($param){
        return $param['record']['email'];
    }

    protected function _getActionParams($param)
    {
        $arr = array(
            array(
                'title' => RexLang::get('default.delete'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemdelete',
                'allow' => 'delete',
                'img' => 'ui-icon-trash'
            )
        );

        return $arr;
    }

    public function _deleteEntity($entity)
    {
        $entity->delete();
        return true;
    }
}