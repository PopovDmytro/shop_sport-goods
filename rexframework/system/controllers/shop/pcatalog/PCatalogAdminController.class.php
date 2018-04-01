<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexRoute as RexRoute;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexResponse as RexResponse;
use \RexDBList as RexDBList;
use \XFile as XFile;
use \XImage as XImage;
use \Exception as Exception;
use \ExcJs as ExcJs;

/**
 * Class PCatalogAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PCatalogAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PCatalogAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\XDatabase:standart:1.0'
    );

    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;

    protected function _getFields($fields)
    {
        $returnArray = array(
            'id' => array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => array('<b>Название</b>', array($this, '_DGName')),
            array('<b>Показывать на главной</b>', array($this, '_DGShowMain'), array('width' => 30)),
            array('<b>Атрибуты</b>', array($this, '_DGAttribute'), array('width' => 90)),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );

        $filters = Request::get('filters', false);

        if (!Request::get('pid', false) && (!isset($filters['pid']) || !$filters['pid'])) {
            $arrayPush = array_splice($returnArray, 0, 2);
            $arrayPush[] = array('<b>Меню</b>', array($this, '_DGMenu'), array('width' => 30));
            foreach ($returnArray as $value) {
                $arrayPush[] = $value;
            }
            $returnArray = $arrayPush;
        }

        return $returnArray;
    }

    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGName($param)
    {
        return '<a href="'.RexRoute(array( 'mod' => 'pCatalog' , 'pid' => $param['record']['id'])).'">'.$param['record']['name'].'</a>';
    }

    function _DGShowMain($param)
    {
        $field = '<ul id="icons" class="ui-widget ui-helper-clearfix" style="width: 30px;">';
        $field .= '<li class="ui-state-default ui-corner-all" title="'.($param['record']['is_showmain'] == 1 ? 'Не показывать на главной' : 'Показывать на главной').'"><a item_id="'.$param['record']['id'].'" href="javascript: void(0);" class="showmain"><span class="'.($param['record']['is_showmain'] == 1 ? 'ui-icon ui-icon-circle-close' : 'ui-icon ui-icon-circle-check').'"></span></a></li>';
        $field .= '</ul>';
        return $field;
    }

    function _DGMenu($param)
    {
        $field = '<ul id="icons" class="ui-widget ui-helper-clearfix" style="width: 30px;">';
        $field .= '<li class="ui-state-default ui-corner-all" title="'.($param['record']['is_menu'] == 1 ? 'Убрать из меню' : 'Показывать в меню').'"><a item_id="'.$param['record']['id'].'" href="javascript: void(0);" class="ismenu"><span class="'.($param['record']['is_menu'] == 1 ? 'ui-icon ui-icon-circle-close' : 'ui-icon ui-icon-circle-check').'"></span></a></li>';
        $field .= '</ul>';
        return $field;
    }

    function _DGUp($param)
    {
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `pcatalog` WHERE `pid` = ?', array($param['record']['pid']));

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вверх</a>';
        }

        return '';
    }

    function _DGDown($param)
    {
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `pcatalog` WHERE `pid` = '.$param['record']['pid']);
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вниз</a>';
        }

        return '';
    }

    function _DGAttribute($param)
    {
        return '<a class="attributes" category_id="'.$param['record']['id'].'" href="javascript: void(0);">атрибуты</a>';
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);

        $arr['order_by'] = 'sorder';
        $arr['order_dir'] = 'ASC';

        $pid = Request::get('pid', 0);
        RexDisplay::assign('pid', $pid);

        $arr['pid'] = $pid;

        if ($pid == 0 && isset($filters['pid'])) {
            $arr['pid'] = $filters['pid'];
        }

        if ($arr['pid'] > 0) {
            $this->entity = RexFactory::entity($this->mod);
            $this->entity->get($arr['pid']);
            RexDisplay::assign('parent_pcatalog', $this->entity);
        } else {
            RexDisplay::assign('parent_pcatalog', false);
        }

        return $arr;
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
            $this->entityUP->getUP($this->entity->pid, $this->entity->sorder);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityUP->sorder;
            $this->entity->update();

            $this->entityUP->sorder = $currentSorder;
            $this->entityUP->update();
        } else {

            $this->entityDown = RexFactory::entity($this->mod);
            $this->entityDown->getDown($this->entity->pid, $this->entity->sorder);

            $currentSorder = $this->entity->sorder;

            $this->entity->sorder = $this->entityDown->sorder;
            $this->entity->update();

            $this->entityDown->sorder = $currentSorder;
            $this->entityDown->update();
        }

        RexResponse::response('ok');
	}

    function getEdit()
    {
        RexResponse::init();

        RexDisplay::assign('is_multiselect', true);

        $pid = Request::get('pid', 0);
        RexDisplay::assign('pid', $pid);

        $list = new RexDBList($this->mod);
        $list->setOrderBy('`gorder` ASC');
        RexDisplay::assign('pcatalogList', $list);

        $brandList = new RexDBList('brand');
        RexDisplay::assign('brandList', $brandList);

        $brand2Cat = XDatabase::getAll('SELECT `brand_id` FROM `brand2cat` WHERE `category_id` = '.$this->task.'');
        RexDisplay::assign('brand2Cat', $brand2Cat);

        parent::getEdit();
    }

	function getAdd()
    {
        RexResponse::init();

        RexDisplay::assign('is_multiselect', true);

        $pid = Request::get('pid', 0);
        RexDisplay::assign('pid', $pid);

        $list = new RexDBList($this->mod);
        $list->setOrderBy('`gorder` ASC');
        RexDisplay::assign('pcatalogList', $list);

        $brandList = new RexDBList('brand');
        RexDisplay::assign('brandList', $brandList);

        parent::getAdd();
    }

    protected function _validate(&$arr, $entity = null)
    {
        if(!$arr['name'] || strlen($arr['name']) < 3) {
            return 'Name must have min 3 characters';
        }

        if(!$arr['alias']) {
            return 'Please enter alias';
        }

        if(!is_null(XDatabase::getOne('SELECT `alias` FROM `pcatalog` WHERE alias = "' . $arr['alias']. '" AND id <> "'.$entity['id'].'"'))) {
            return 'This alias used. Please rename alias';
        }

        return true;
    }

    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }

        try {
            //brands
            $brands = Request::get('brands', false);

            if ($brands and sizeof($brands) > 0) {
                $brand2CatManager = RexFactory::manager('brand2Cat');
                $brand2CatManager->deleteByFields(array('category_id'=>$entity->id));

                foreach ($brands as $brand_id) {
                    $brand2CatEntity = RexFactory::entity('brand2Cat');
                    $brand2CatEntity->set(array('category_id'=>$entity->id, 'brand_id'=>intval($brand_id)));

                    if (!$brand2CatEntity->create()) {
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
            $entity = RexFactory::entity($this->mod);
            $entity->get($arr['exist_id']);

            if ($arr['pid'] != $entity->pid) {
                $aSetSorder = true;
            } else {
                $aSetSorder = false;
            }

            //brands
            $brands = Request::get('brands', false);

            if ($brands and sizeof($brands) > 0) {
                $brand2CatManager = RexFactory::manager('brand2Cat');
                $brand2CatManager->deleteByFields(array('category_id' => $entity->id));

                foreach ($brands as $brand_id) {
                    $brand2CatEntity = RexFactory::entity('brand2Cat');
                    $brand2CatEntity->set(array('category_id' => $entity->id, 'brand_id'=>intval($brand_id)));

                    if (!$brand2CatEntity->create()) {
                        return 'Unable to update Brands';
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
        $pid = $entity->pid;

        $this->manager = RexFactory::manager($this->mod);
        $this->manager->getSubCategoriesList($entity->id, 3);
        $categoryList = $this->manager->struct;
        if ($categoryList and sizeof($categoryList) > 0) {
            $categoryList[] = $id;
            $fullList = implode(',', $categoryList);
        } else {
            $fullList = $id;
        }

        $count = XDatabase::getOne('SELECT COUNT(*) FROM `product` WHERE `category_id` IN ('.$fullList.')');

        if ($count > 0) {
            return 'Unable to delete PCatalog. Please, uncheck products from this categories tree';
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

    function getIsMenu()
    {
        RexResponse::init();

        if (!$this->task or $this->task < 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }

        if ($this->entity->is_menu == 1) {
            $this->entity->is_menu = 0;
        } else {
            $this->entity->is_menu = 1;
        }
        $this->entity->update();

        RexResponse::response('ok');
    }

    function getShowMain()
    {
        RexResponse::init();

        if (!$this->task or $this->task < 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }

        if ($this->entity->is_showmain == 1) {
            $this->entity->is_showmain = 0;
        } else {
            $this->entity->is_showmain = 1;
        }
        $this->entity->update();

        RexResponse::response('ok');
    }

    function getAllCategoriesExcept()
    {
        RexResponse::init();
        $categories = $this->manager->getAllCategoriesExcept($this->task, Request::get('product_id', false));
        RexDisplay::assign('categories', $categories);
        $response = RexDisplay::fetch('pcatalog/multiselect.list.tpl');
        RexResponse::response($response);
    }
}