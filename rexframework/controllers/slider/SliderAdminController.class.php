<?php

class SliderAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SliderEntity:shop.standart:1.0',
        'RexShop\SliderManager:shop.standart:1.0',
        'RexShop\SliderAdminController:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
    );

    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => 'Название',
            array('<b>Картинка</b>', array($this, '_DGImage'), array('width' => 170)),
            array('<b>Дата</b>', array($this, '_DGDate'), array('width' => 70)),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }

    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGDate($param)
    {
        return date('Y-m-d', strtotime($param['record']['date']));
    }

    function _DGUp($param)
    {
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `slider`');

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вниз</a>';
        }

        return '';
    }

    function _DGDown($param)
    {
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `slider`');
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вверх</a>';
        }

        return '';
    }

    function _DGImage($param)
    {
        $sliderManager = RexFactory::manager('slider');
        $sliderManager->getByWhere('id = '.$param['record']['id'].' limit 1');
        $arr = $sliderManager->getCollection();
        if (isset($arr[0])) {
            $id = $arr[0]['id'];
        } else {
            return '<b>нет</b>';
        }
        $sliderEntity = RexFactory::entity('slider');
        $sliderEntity->get($id);
        $src = XImage::getImg(array('name' => 'slider', 'type' => 'mini', 'id' => $param['record']['id'], 'ext' => $param['record']['banner']));
        return '<img src="'.$src.'" />';
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);

        $arr['order_by'] = 'sorder';
        $arr['order_dir'] = 'DESC';

        return $arr;
    }

    protected function _validate(&$arr, $entity = null)
    {
        if(!$arr['name'] || strlen($arr['name']) < 3)
            return 'Name must have min 3 characters';

        if(!$arr['url'] || strlen($arr['url']) < 7)
            return 'Url must have min 7 characters';

        if (is_null($entity)) {
            if ($arr['showbanner'] == 'on' && !isset($arr['cropped']))
                return 'Please selected banner icon';
        } else {
            if ($arr['showbanner'] == 'on' && !$entity->banner && !isset($arr['cropped']))
                return 'Please selected banner icon';
        }

        return true;
    }

    protected function _createEntity($entity, $arr)
    {
        $arr['showbanner'] = 0;
        if (isset($arr['showbanner']) && $arr['showbanner'] == 'on')
            $arr['showbanner'] = 1;

        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }

        try {
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'banner', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'banner');
            }

            if ($images !== true)
                return array(
                    'mod' => $this->mod,
                    'error_no' => ExcJs::ErrorAfterCreate,
                    'message' => $images,
                    'dialog_uin' => RexResponse::getDialogUin(),
                    'task' => $entity->id
                );
        } catch (Exception $e) {
            return array(
                'mod' => $this->mod,
                'error_no' => ExcJs::ErrorAfterCreate,
                'message' => $e->getMessage(),
                'dialog_uin' => RexResponse::getDialogUin(),
                'task' => $entity->id
            );
        }

        return true;
    }

    protected function _updateEntity($entity, $arr)
    {
        if ($arr['showbanner'] == 'on')
            $arr['showbanner'] = 1;
        else
            $arr['showbanner'] = 0;

        $update = parent::_updateEntity($entity, $arr);

        if ($update !== true) {
            return $update;
        }

        try {
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'banner', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'banner');
            }

            if ($images !== true)
                return $images;
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    function _deleteEntity($entity)
    {
        $name = 'banner';

        $del_img = $this->_deleteImages($entity, $name);
        if ($del_img !== true)
            return $del_img;

        $delete = parent::_deleteEntity($entity);

        if ($delete !== true) {
            return $delete;
        }

        return true;
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
            $this->entityUP->getUP($this->entity->sorder);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityUP->sorder;
            $this->entity->update();

            $this->entityUP->sorder = $currentSorder;
            $this->entityUP->update();
        } else {

            $this->entityDown = RexFactory::entity($this->mod);
            $this->entityDown->getDown($this->entity->sorder);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityDown->sorder;
            $this->entity->update();

            $this->entityDown->sorder = $currentSorder;
            $this->entityDown->update();
        }

        RexResponse::response('ok');
    }
}