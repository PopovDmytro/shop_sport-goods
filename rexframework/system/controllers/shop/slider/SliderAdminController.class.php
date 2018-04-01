<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \Exception as Exception;  
use \ExcJs as ExcJs;  
use \RexResponse as RexResponse;
use \XImage as XImage;
use \XFile as XFile;

/**
 * Class SliderAdminController
 *
 * @author   Fatal
 * @access   public
 * @package  SliderAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class SliderAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\SliderEntity:shop.standart:1.0',
        'RexShop\SliderManager:shop.standart:1.0',
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
            array('<b>Дата</b>', array($this, '_DGDate'), array('width' => 70)),
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
}