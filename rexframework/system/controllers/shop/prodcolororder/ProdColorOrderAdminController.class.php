<?php
namespace RexShop;

use \RexDisplay as RexDisplay;
use \RexFactory as RexFactory;
use \RexResponse as RexResponse;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;

class ProdColorOrderAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\ProdColorOrderEntity:shop.standart:1.0',
        'RexShop\ProdColorOrderManager:shop.standart:1.0',
    );

    protected $add_dialog_width = 550;
    protected $add_dialog_height = 450;
    protected $edit_dialog_width = 550;
    protected $edit_dialog_height = 450;
    protected $default_dialog_width = 800;
    protected $default_dialog_height = 750;

    protected function _getFields($fields)
    {
        return array(
            array('<b>Id</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Цвет</b>', array($this, '_DGColor'), array('width' => 60)),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
        );
    }

    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGColor($param)
    {
        $color = RexFactory::entity('attribute');
        $color->get($param['record']['attribute_id']);

        if ($color) {
             return $color->name;
        }

        return '';
    }

    function _DGUp($param)
    {
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `prod_color_order` WHERE `product_id` = ?', array($param['record']['product_id']));

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вверх</a>';
        }

        return '';
    }
    function _DGDown($param)
    {
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `prod_color_order` WHERE `product_id` = '.$param['record']['product_id']);
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вниз</a>';
        }

        return '';
    }

    function getDefault()
    {
        RexResponse::init();

        $mod = $this->_getDatagridMod();

        $product_id = Request::get('product_id', false);

        $productEntity = RexFactory::entity('product');
        $productEntity->get($product_id);

        if (!$productEntity) {
            RexResponse::error('Продукт с ID = ' . $product_id . ' не найден!');
        }

        parent::getDefault();
    }

    protected function _getFilters($filters)
    {
        $product_id = Request::get('product_id', false);

        if (!isset($filters['order_by'])) $filters['order_by'] = 'sorder';
        if (!isset($filters['order_dir'])) $filters['order_dir'] = 'ASC';
        if (!isset($filters['page'])) $filters['page'] = 1;
        if (!isset($filters['inpage'])) $filters['inpage'] = $this->inpage;
        if (!isset($filters['product_id'])) $filters['product_id'] = $product_id;

        return $filters;
    }

    function getOrder()
    {
        RexResponse::init();

        $this->entity = RexFactory::entity($this->mod);
        if (!$this->task or $this->task< 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }

        $order = Request::get('value', 'up');

        if ($order === 'up') {
            $this->entityUP = RexFactory::entity($this->mod);
            $this->entityUP->getUP($this->entity->sorder,$this->entity->product_id);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityUP->sorder;
            $this->entity->update(true);

            $this->entityUP->sorder = $currentSorder;
            $this->entityUP->update(true);
        } else {
            $this->entityDown = RexFactory::entity($this->mod);
            $this->entityDown->getDown($this->entity->sorder,$this->entity->product_id);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityDown->sorder;
            $this->entity->update(true);

            $this->entityDown->sorder = $currentSorder;
            $this->entityDown->update(true);
        }

        RexResponse::response('ok');
    }
}