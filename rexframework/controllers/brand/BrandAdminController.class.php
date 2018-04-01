<?php

class BrandAdminController extends \RexShop\BrandAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\ProductEntity:shop.standart:1.0',
        'RexShop\QRCodeEntity:shop.standart:1.0',
        'RexShop\ProductAdminController:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'Brand2TechManager:volley.standart:1.0',
        'Brand2TechEntity:volley.standart:1.0',
        'Prod2TechManager:volley.standart:1.0',
        'Prod2TechEntity:volley.standart:1.0'
    );

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => 'Name',
            array('<b>Лого</b>', array($this, '_DGPhoto'), array('width' => 70, 'align' => 'center')),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }

    function _DGPhoto($param)
    {
        if ($param['record']['icon'])
            return '<img src="'.XImage::getImg(array('name' => $this->mod, 'type' => 'icon', 'id' => $param['record']['id'], 'ext' => $param['record']['icon'])).'" />'; 

        return '';
    }

    function getEdit()
    {
        RexResponse::init();

        RexDisplay::assign('is_multiselect', true);

        // $pid = Request::get('pid', 0);
        // RexDisplay::assign('pid', $pid);

        // $list = new RexDBList($this->mod);
        // $list->setOrderBy('`gorder` ASC');
        // RexDisplay::assign('pcatalogList', $list);

        $technologyList = new RexDBList('technology');
        RexDisplay::assign('technologyList', $technologyList);

        $brand2Tech = XDatabase::getAll('SELECT `technology_id` FROM `brand2tech` WHERE `brand_id` = '.$this->task.'');
        RexDisplay::assign('brand2Tech', $brand2Tech);

        parent::getEdit();
    }

    function getAdd()
    {
        RexResponse::init();

        RexDisplay::assign('is_multiselect', true);

        // $pid = Request::get('pid', 0);
        // RexDisplay::assign('pid', $pid);

        // $list = new RexDBList($this->mod);
        // $list->setOrderBy('`gorder` ASC');
        // RexDisplay::assign('brandList', $list);

        $technologyList = new RexDBList('technology');
        RexDisplay::assign('technologyList', $technologyList);

        parent::getAdd();
    }

    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }

        try {
            //technologies
            $technologies = Request::get('technologies', false);

            if ($technologies and sizeof($technologies) > 0) {
                $brand2TechManager = RexFactory::manager('brand2Tech');
                $brand2TechManager->deleteByFields(array('brand_id'=>$entity->id));

                foreach ($technologies as $technology_id) {
                    $brand2TechEntity = RexFactory::entity('brand2Tech');
                    $brand2TechEntity->brand_id = $entity->id;
                    $brand2TechEntity->technology_id = intval($technology_id);

                    if (!$brand2TechEntity->create()) {
                        return array(
                            'mod' => $this->mod,
                            'error_no' => ExcJs::ErrorAfterCreate,
                            'message' => 'Unable to create Brands',
                            'dialog_uin' => RexResponse::getDialogUin(),
                            'task' => $entity->id
                        );
                    }
                }
            }

            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'icon', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'icon');
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
            //technologies
            $technologies = Request::get('technologies', false);
            if ($technologies and sizeof($technologies) > 0) {
                $brand2TechManager = RexFactory::manager('brand2Tech');
                $brand2TechManager->deleteByFields(array('brand_id'=>$entity->id));

                foreach ($technologies as $technology_id) {
                    $brand2TechEntity = RexFactory::entity('brand2Tech');
                    $brand2TechEntity->brand_id = $entity->id;
                    $brand2TechEntity->technology_id = intval($technology_id);

                    if (!$brand2TechEntity->create()) {
                        return 'Unable to update Brand';
                    }
                }
            }

            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'icon', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'icon');
            }

            if ($images !== true)
                return $images;
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function _deleteEntity($entity)
    {
        $id  = $entity->id;

        $prod2TechManager = RexFactory::manager('prod2Tech');
        $prodList = $prod2TechManager->getProdTechnologiesByBrandId($id);
        if($prodList) {
            return 'Unable to delete Technology. Please, uncheck technologies from product.';
        }

        $brand2TechManager = RexFactory::manager('brand2Tech');
        $brand2TechManager->deleteByFields(array('brand_id'=>$entity->id));

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
}