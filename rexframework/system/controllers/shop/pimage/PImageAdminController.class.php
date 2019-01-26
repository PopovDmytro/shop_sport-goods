<?php
namespace RexShop;

use \RexDisplay as RexDisplay;
use \RexFactory as RexFactory;
use \RexResponse as RexResponse;
use \RexConfig as RexConfig;
use \Request as Request;
use \XImage as XImage;
use \XFile as XFile;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;
use \RexLang as RexLang;

/**
 * Class PImageAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  PImageAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class PImageAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0'
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
            array('<b>Icon</b>', array($this, '_DGImage'), array('width' => 60)),
            'name' => 'Name',
            array('<b>Главное фото</b>', array($this, '_DGMainPhoto'), array('width' => 130)),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }

    function _DGMainPhoto($param)
    {
        if ($param['record']['main']) {
            return '<a id="'.$param['record']['id'].'" class="main_photo itemmain" href="javascript: void(0);">Главное фото</a>';
        } else {
            return '<a id="'.$param['record']['id'].'" item_id="'.$param['record'][$this->entity->__uid].'" class="set_main_photo itemsetmain" href="javascript: void(0);">Сделать главным фото</a>';
        }
    }

    function _DGUp($param)
    {
        $filters = Request::get('filters', false);

        $andWhere = '';
        $andWhereBind = array();
        $orderCol = 'sorder';
        if (isset($filters['attribute_id']) && $filters['attribute_id'] != 0) {
            $andWhere = ' AND `attribute_id` = ?';
            $andWhereBind = array($filters['attribute_id']);
            $orderCol = 'color_sorder';
        }

        $order = XDatabase::getOne('SELECT MIN(`'.$orderCol.'`) AS `sorder` FROM `pimage` WHERE `product_id` = ?'.$andWhere, array_merge(array($param['record']['product_id']), $andWhereBind));
        if ($order < $param['record'][$orderCol]) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вверх</a>';
        } 
        
        return '';
    }
    function _DGDown($param)
    {
        $filters = Request::get('filters', false);

        $andWhere = '';
        $andWhereBind = array();
        $orderCol = 'sorder';
        if (isset($filters['attribute_id']) && $filters['attribute_id'] != 0) {
            $andWhere = ' AND `attribute_id` = ?';
            $andWhereBind = array($filters['attribute_id']);
            $orderCol = 'color_sorder';
        }

        $order = XDatabase::getOne('SELECT MAX(`'.$orderCol.'`) AS `sorder` FROM `pimage` WHERE `product_id` = ?'.$andWhere, array_merge(array($param['record']['product_id']), $andWhereBind));
        if ($order > $param['record'][$orderCol]) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вниз</a>';
        }
        
        return '';
    }
    
    function _DGImage($param)
    {
        if (strlen(trim($param['record']['image'])) > 0) {
            return '<img src="'.XImage::getImg(array('type' => 'icon', 'name' => 'pImage', 'id' => $param['record']['id'], 'ext' => $param['record']['image'])).'" />';
        } 
        
        return '-';
    }

    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);
        
        $product_id = Request::get('product_id', false);
        RexDisplay::assign('product_id', $product_id);
        
        if ($product_id) {
            $arr['product_id'] = $product_id;
            $arr['inpage'] = 8;
        }

        $arr['order_by'] = 'sorder';
        $arr['order_dir'] = 'ASC';

        if (isset($arr['attribute_id']) && $arr['attribute_id'] == 0) {
            unset($arr['attribute_id']);
        }

        if(isset($arr['attribute_id']) && $arr['attribute_id'] != 0) {
            $arr['order_by'] = 'color_sorder';
            $arr['order_dir'] = 'ASC';
        }

        return $arr;
    }

    function getMainPhoto()
    {
        RexResponse::init();

        $id = Request::get('id');

        $pImageEntity = $this->entity;
        $pImageEntity->get($id);
        if (!$pImageEntity->id) {
            RexResponse::error('Фото с ID = ' . $id . ' не найдено!');
        }

        if (!$this->manager->savePImageAsMain($pImageEntity)) {
            RexResponse::error('Ошибка при обновлении фотографии с ID = ' . $pImageEntity->id);
        }       

        RexResponse::response('ok');
    }

    function getDefault()
    {
        $filters = Request::get('filters', false);
        $product_id = isset($filters['product_id']) ? $filters['product_id'] : Request::get('product_id');
        RexDisplay::assign('product_id', $product_id);
        
        $attr2prodManager = RexFactory::manager('attr2Prod');
        RexDisplay::assign('colorAttr', $attr2prodManager->getColorAttributes($product_id));
        
        $in_parent = Request::get('in_parent', false);
        if ($in_parent) {
            RexDisplay::assign('in_parent', $in_parent);
        }
        
        parent::getDefault();
    }
    
	function getOrder()
	{
        RexResponse::init();
        
		$this->entity = RexFactory::entity($this->mod);
		if (!$this->task or $this->task< 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
		}

		$order = Request::get('value', 'up');
        $attribute = Request::get('attribute_id', false);

        $orderCol = 'sorder';
        if ($attribute && $attribute != 0) {
            $orderCol = 'color_sorder';
        }

        if ($order === 'up') {
			$this->entityUP = RexFactory::entity($this->mod);
			$this->entityUP->getUP($this->entity->{$orderCol},$this->entity->product_id, $attribute);
			
			$currentSorder = $this->entity->{$orderCol};
			
			$this->entity->{$orderCol} = $this->entityUP->{$orderCol};
			$this->entity->update(true);
			
			$this->entityUP->{$orderCol} = $currentSorder;
			$this->entityUP->update(true);
		} else {
			$this->entityDown = RexFactory::entity($this->mod);
			$this->entityDown->getDown($this->entity->{$orderCol},$this->entity->product_id, $attribute);

			$currentSorder = $this->entity->{$orderCol};
			
			$this->entity->{$orderCol} = $this->entityDown->{$orderCol};
			$this->entity->update(true);
			
			$this->entityDown->{$orderCol} = $currentSorder;
			$this->entityDown->update(true);
		}

        RexResponse::response('ok');
	}
	
    function getEdit()
    {
        $this->entity->get($this->task);
        RexDisplay::assign('product_id', $this->entity->product_id);
        
        $namePi = RexFactory::entity('product');
        $namePi->getByWhere('id ='.$this->entity->product_id);
        RexDisplay::assign('product_name', $namePi->name);
        
        $list = new RexDBList('product');
        RexDisplay::assign('productList', $list);
        
        $attr2prodManager = RexFactory::manager('attr2Prod');
        RexDisplay::assign('colorAttr', $attr2prodManager->getColorAttributes($this->entity->product_id));
        if ($this->entity->attribute_id) {
            RexDisplay::assign('parent_attr', $attr2prodManager->getParentID($this->entity->attribute_id));    
        }
        
        parent::getEdit();
    }
	
    function getAdd()
    {
        $product_id = Request::get('product_id', false);
        RexDisplay::assign('product_id', $product_id);
        
        $namePi = RexFactory::entity('product');
        $namePi->getByWhere('id ='.$product_id);
        RexDisplay::assign('product_name', $namePi->name);
        
        $list = new RexDBList('product');
        RexDisplay::assign('productList', $list);

        $attr2prodManager = RexFactory::manager('attr2Prod');
        RexDisplay::assign('colorAttr', $attr2prodManager->getColorAttributes($product_id));
        
        parent::getAdd();
    }
    
    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);
        
        if ($add !== true) {
            return $add;
        }

       //echo $entity->id; print_r($arr);   exit;
        try {
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'image', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'image');
            }
            
            //echo REX_ROOT.'rexframework/files/images/pimage/'.$arr['id'].'/main.'.$arr['image']; exit;
            if ($images !== true)
                return array(
                    'mod' => $this->mod,
                    'error_no' => \ExcJs::ErrorAfterCreate,
                    'message' => $images,
                    'dialog_uin' => \RexResponse::getDialogUin(),
                    'task' => $entity->id
                );
            XImage::putWatermark(REX_ROOT.'rexframework/files/images/pimage/'.$entity->id.'/main.'.$entity->image, HTDOCS.'content/watemark.png', 355, 378, 8, 15, 4, 15);
        } catch (Exception $e) {
            return array(
                'mod' => $this->mod,
                'error_no' => \ExcJs::ErrorAfterCreate,
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
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'image', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'image');
            }

            if ($images !== true) {
                return $images;
            }

            XImage::putWatermark(REX_ROOT.'rexframework/files/images/pimage/'.$entity->id.'/main.'.$entity->image, HTDOCS.'content/watemark.png', 355, 378, 8, 15, 4, 15);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
    
    protected function _validate(&$arr, $entity = null)
    {
        if(!$arr['name'] || strlen($arr['name']) < 3)
            return 'Name must have min 3 characters';
        
        return true; 
    }
    
    protected function _deleteEntity($entity)
    {
        $name = 'image';
        
        $del_img = $this->_deleteImages($entity, $name);
        if ($del_img !== true)
            return $del_img;
        
        return parent::_deleteEntity($entity);
    }
    
    public function getTestWatermarks()
    {
        XImage::putWatermark(dirname(__FILE__).DS.'100500.png');
        //XImage::createPreview(dirname(__FILE__).DS.'100500.png', dirname(__FILE__).DS.'100500-preview.png', 100);
        exit;
    }
}