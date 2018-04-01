<?php
/**
 * Class TechnologyAdminController
 *
 * AdminController of Technology
 *
 * @author   petroved
 * @access   public
 * @created  Thu Nov 14 10:05:33 EET 2013
 */
class TechnologyAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'TechnologyManager:volley.standart:1.0',
        'TechnologyEntity:volley.standart:1.0',
        'Brand2TechManager:volley.standart:1.0',
        'Brand2TechEntity:volley.standart:1.0',
        'Prod2TechManager:volley.standart:1.0',
        'Prod2TechEntity:volley.standart:1.0'
    );

    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => 'Name',
            array('<b>Описание</b>', array($this, '_DGDescription')),
            array('<b>Лого</b>', array($this, '_DGPhoto'), array('width' => 70, 'align' => 'center')),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }

    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGDescription($param)
    {
        return $param['record']['description'];
    }

    function _DGPhoto($param)
    {
        if ($param['record']['icon'])
            return '<img src="'.XImage::getImg(array('name' => $this->mod, 'type' => 'icon', 'id' => $param['record']['id'], 'ext' => $param['record']['icon'])).'" />';

        return '';
    }

    protected function _validate(&$arr, $entity = null)
    {
        if(!$arr['name'] || strlen($arr['name']) < 3)
            return 'Name must have min 3 characters';

        $this->entity = RexFactory::entity($this->mod);
        $this->entity->getByFields(array('name' => $arr['name']));

        if ($this->entity->id && $this->entity->id > 0 && $this->entity->id != $arr['exist_id'])
            return 'Technology already exist';

        return true;
    }

    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }

        try {
            $name = 'icon';

            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, $name, $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, $name);
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
        $update = parent::_updateEntity($entity, $arr);

        if ($update !== true) {
            return $update;
        }

        try {
            $name = 'icon';
            
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, $name, $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, $name);
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
        $id  = $entity->id;

        $brand2TechManager = RexFactory::manager('brand2Tech');
        $brandList = $brand2TechManager->getBrandsByTechnologyId($id);
        if($brandList) {
            return 'Unable to delete Technology. Please, uncheck this technology from brand.';
        }

        $name = 'icon';

        $del_img = $this->_deleteImages($entity, $name);
        if ($del_img !== true)
            return $del_img;

        $delete = parent::_deleteEntity($entity);

        if ($delete !== true) {
            return $delete;
        }

        return true;
    }

    function getTechnologiesByBrand()
    {
        RexResponse::init();
        $brand2TechManager = RexFactory::manager('brand2Tech');
        $brand_id = Request::get('brand_id', false);
        // var_dump($this->task);
        $technologies = $brand2TechManager->getTechnologiesByBrand($brand_id);

        // var_dump($technologies);
        //  exit;
        RexDisplay::assign('technologies', $technologies);

        $prod2Tech = XDatabase::getAll('SELECT `technology_id` FROM `prod2tech` WHERE `product_id` = '.$this->task.'');
        // var_dump($prod2Tech);
        // exit;
        RexDisplay::assign('prod2Tech', $prod2Tech);

        $response = RexDisplay::fetch('technology/multiselect.list.tpl');
        if ($response) {
           RexResponse::response($response);
       } else {
           RexResponse::response('false');
       }

    }


}