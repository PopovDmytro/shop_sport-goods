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

/**
 * Class AttributeAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  AttributeAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class AttributeAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\AttributeEntity:shop.standart:1.0',
        'RexShop\AttributeManager:shop.standart:1.0',
    );
    
	var $attributeTypeList;
    
    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;
	
    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            'name' => 'Name',
            array('', array($this, '_DGCategory')),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
        
    function _DGCategory($param)
    {
        return '<a href="'.RexRoute(array( 'mod' => 'attribute' , 'pid' => $param['record']['id'])).'">Подкатегории</a>';
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
            RexDisplay::assign('parent_attribute', $this->entity);
        }
        
        return $arr;
    }
    
	function _DGEdit($param)
	{
		$field = '<a href="'.RexRoute::getUrl('attribute', 'edit', array('task' => $param['record']['id'])).'">edit</a>';
		return $field;
	}
	function _DGDelete($param)
	{
		$field = "<a href='#' onclick='javascript: confirmDelete(\"attribute\", ". $param['record']['id'] .", \"Attribute\")'>delete</a>";
		return $field;
	}
	
	function _DGUp($param)
	{
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `attribute` WHERE `pid` = ?', array($param['record']['pid']));

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вверх</a>';
        } 
        
        return '';
	}
	function _DGDown($param)
	{
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `attribute` WHERE `pid` = '.$param['record']['pid']);
		
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вниз</a>';
        }
        
        return '';
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
        RexDisplay::assign('attributeTypeList', RexConfig::get('Project', 'attributeTypeList'));
        
        $pid = Request::get('pid', 0);
        RexDisplay::assign('pid', $pid);
        
        $list = new RexDBList($this->mod);
        $list->setOrderBy('`gorder` ASC');
        RexDisplay::assign('attributeList', $list);
        
        parent::getEdit();
    }
    
    protected function _updateEntity($entity, $arr)
    {
        if ($arr['pid'] != $entity->pid) {
            $aSetSorder = true;
        } else {
            $aSetSorder = false;
        }
        
        $entity->set($arr);
        if (!$entity->update(false, $aSetSorder)) {
            return 'Unable to update '.ucfirst($this->mod);
        }
        
        return true;
    }
    
	function getAdd()
    {
        RexDisplay::assign('attributeTypeList', RexConfig::get('Project', 'attributeTypeList'));
        
        $pid = Request::get('pid', 0);
        RexDisplay::assign('pid', $pid);
        
        $list = new RexDBList($this->mod);
        $list->setOrderBy('`gorder` ASC');
        RexDisplay::assign('attributeList', $list);
        
        parent::getAdd();
    }
    
    protected function _validate(&$arr, $entity = null)
    {
        $this->entity = RexFactory::entity($this->mod);
        
        if ($arr['pid'] > 0) {
            $this->entity->get($arr['pid']);
            if (!$this->entity->id)
                return 'Unable to get parent Attribute';
            
            if ($this->entity->type_id != 6 && $this->entity->type_id != 7 && $arr['type_id'] == 8)
                return 'Element of List will be attached to list only';
            
            if (($this->entity->type_id == 6 || $this->entity->type_id == 7) && $arr['type_id'] != 8)
                return 'In list can be attached only list element';
            
            if ($this->entity->type_id != 6 && $this->entity->type_id != 7 && $this->entity->type_id != 9)
                return 'Parent Element can not have childrens';
            
            /*if ($this->entity->type_id == 9 && $arr['type_id'] != 3)
                return 'In range can be attached only integer value element';*/
            
            if ($this->entity->level > 1)
                return 'Only 3 levels allowed';
        }
        if(!$arr['name'] || strlen($arr['name']) < 1)
            return 'Name must have min 3 characters';
        
        if(!$arr['alias'])
            return 'Please enter alias';
        
        return true;
    }
    
    public function _deleteEntity($entity)
    {
        $id  = $entity->id;
        $pid = $entity->pid;
        
        $delete = parent::_deleteEntity($entity);
        
        if ($delete !== true) {
            return $delete;
        }
        
        $subList = XDatabase::getAll('SELECT `id` FROM `attribute` WHERE `pid` = ?', array($id));
        if ($subList and sizeof($subList) > 0) {
            $list = '';
            foreach ($subList as $val) {
                $list .= $val['id'].',';
            }
            $list = trim($list, ',');
            if (strlen(trim($list)) > 0) {
                //sub sub attributes
                $subList = XDatabase::getAll('SELECT `id` FROM `attribute` WHERE `pid` IN ('.$list.')');
                if ($subList and sizeof($subList) > 0) {
                    $listS = '';
                    foreach ($subList as $val) {
                        $listS .= $val['id'].',';
                    }
                    $listS = trim($listS, ',');
                    if (strlen(trim($list)) > 0) {
                        $list .= ', '.$listS;
                    }
                }
            }
            XDatabase::query('DELETE FROM `attribute` WHERE `id` IN ('.$list.')');
            XDatabase::query('DELETE FROM `attr2cat` WHERE `attribute_id` IN ('.$list.')');
            XDatabase::query('DELETE FROM `attr2prod` WHERE `attribute_id` IN ('.$list.')');
        }
        
        XDatabase::query('DELETE FROM `attr2cat` WHERE `attribute_id` = '.$id);
        XDatabase::query('DELETE FROM `attr2prod` WHERE `attribute_id` = '.$id);
        
        return true;
    }
    
    public function getTypesForParent()
    {
        RexResponse::init();
        
        $parentID = Request::get('parentID', false);
        $response = 'not';
        
        if ($parentID) {
            $this->entity->get($parentID);
            $response = $this->entity->type_id;
            $attributeTypeList = RexConfig::get('Project', 'attributeTypeList');
            
            switch ($this->entity->type_id) {
                
                case 9:
                    $countReal = array(2 => 0, 3 => 0);
                    $countNeeded = array(2 => 1, 3 => 2);
                    break;
                case 7:
                    $countReal = array(8 => 0);
                    $countNeeded = array(8 => -1);
                    break;
                default:
                    RexResponse::response('not');
            }
            
            $this->manager->getByWhere('pid = '.$this->entity->id);
            
            foreach ($this->manager->_collection as $childAttr) {
                        
                if (!isset($countReal[$childAttr['type_id']])) {
                    continue;
                }
                
                $countReal[$childAttr['type_id']] += 1;
                
                if ($countReal[$childAttr['type_id']] == $countNeeded[$childAttr['type_id']]) {
                    unset($countReal[$childAttr['type_id']]);
                }
                
            }
            
            if (!$countReal) {
                RexResponse::error('Для этого элемента нельзя больше создавать значения');
                return;
            }
            
            $response = $countReal;
            
        }
        
        RexResponse::response($response);
        
    }
}