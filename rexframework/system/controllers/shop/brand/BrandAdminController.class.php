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
use \Exception as Exception;
use \ExcJs as ExcJs;

/**
 * Class BrandAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  BrandAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class BrandAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
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
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    protected function _validate(&$arr, $entity = null)
    {
        if(!$arr['name'] || strlen($arr['name']) < 3)
            return 'Name must have min 3 characters';
        
        if(!$arr['alias'])
            return 'Please enter alias';
            
        $this->entity = RexFactory::entity($this->mod);
        $this->entity->getByFields(array('name' => $arr['name']));
        
        if ($this->entity->id && $this->entity->id > 0 && $this->entity->id != $arr['exist_id'])
            return 'Brand already exist';
        
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
            
            $images = $this->_createImages($entity, $name);
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
            
            $images = $this->_createImages($entity, $name);
            if ($images !== true)
                return $images;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
    
    function _deleteEntity($entity)
    {
        $count = XDatabase::getOne('SELECT COUNT(*) FROM `product` WHERE `brand_id` = ?', array($entity->id));
        if ($count > 0) {
            return 'Unable to delete Brand. Please - uncheck products for this brand';
        }
        
        $name = 'icon';
        
        $del_img = $this->_deleteImages($entity, $name);
        if ($del_img !== true)
            return $del_img;
        
        XDatabase::query('DELETE FROM `brand2cat` WHERE `brand_id` = ?', array($entity->id));
        
        $delete = parent::_deleteEntity($entity);
        
        if ($delete !== true) {
            return $delete;
        } 
        
        return true;
    }
    
    /*function getDeleteImage()
    {        
        $this->entity = RexFactory::entity('brand');
        if($this->entity->get($this->task)) {
            $name = 'icon';
             

            $delete_1 = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').$this->mod.'_'.$name.'_'.$this->entity->id.'.'.$this->entity->{$name};
            $delete_2 = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').$this->mod.'_'.$name.'_preview_'.$this->entity->id.'.'.$this->entity->{$name};
            
            XFile::delete($delete_1);
            XFile::delete($delete_2);
                                   
            if (XDatabase::query('UPDATE brand SET icon = NULL WHERE id= ?', array($this->entity->id))) {
                    RexRoute::location($this->mod);
            } else {
                    RexPage::addError('Unable to delete Brand');
            }
        }
    }*/
    
	static function getLoadByCat()
	{
        RexResponse::init();
        
        $aCategoryID = Request::get('category_id') or RexResponse::error('Wrong category ID');

		//get main categories tree
		$manager = RexFactory::manager('pCatalog');
		$manager->getUpList($aCategoryID, RexFactory::entity('pCatalog'));
		$navCategoryList = array_reverse($manager->getCollection());		
		if (sizeof($navCategoryList) < 1) {
			$navCategoryList = $aCategoryID;			
		}
		
		$brand2CatManager = RexFactory::manager('brand2Cat');
		$brand2CatManager->getByWhere('`category_id` IN ('.implode(',', $navCategoryList).')');
		if (!$brand2CatManager->_collection) {
			RexResponse::response(array());
		} else {
			$list = array();
			foreach ($brand2CatManager->getCollection() as $val) {
				$list[] = $val['brand_id'];
			}
		}
		
		$brandManager = RexFactory::manager('brand');
		$brandManager->getByWhere('`id` IN ('.implode(',', $list).')');
		
		$brand_list = array();
		if ($brandManager->_collection) {
            $brands = $brandManager->getCollection();
            foreach ($brands as $brand) {
                $brand_list[$brand['id']] = $brand['name'];
            }
		}
		
		RexResponse::response($brand_list);
	}
}