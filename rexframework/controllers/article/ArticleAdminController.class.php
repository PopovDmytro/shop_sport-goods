<?php

class ArticleAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ArticleEntity:standart:1.0',
        'RexFramework\ArticleManager:standart:1.0',
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
            
        if(!$arr['alias'] || strlen($arr['alias']) < 3)
            return 'Alias must have min 3 characters';
        
        return true;
    }
    
    protected function _createEntity($entity, $arr)
    {
        $add = parent::_createEntity($entity, $arr);

        if ($add !== true) {
            return $add;
        }
        
        try {
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
            if (isset($arr['cropped'])) {
                $images = $this->_createImages($entity, 'icon', $arr['cropped']);
            } else {
                $images = $this->_createImages($entity, 'icon');
            }

            if ($images !== true) {
                return $images;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
    
    function _deleteEntity($entity)
    {
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