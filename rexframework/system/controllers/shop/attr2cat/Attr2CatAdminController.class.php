<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexLang as RexLang;
use \RexResponse as RexResponse;

/**
 * Class Attr2CatAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  Attr2CatAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class Attr2CatAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\Attr2CatEntity:shop.standart:1.0',
        'RexShop\Attr2CatManager:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0'
    );
    
    protected $default_dialog_width = 800;
    protected $default_dialog_height = 750;
    protected $add_dialog_width = 300;
    protected $add_dialog_height = 300;
    
    protected function _getFields($fields)
    {
        return array(
            array('<b>Атрибут</b>', array($this, '_DGAttribute')),
            array('<b>Атрибут покупки</b>', array($this, '_DGForSale'), array('width' => 30)),
            array('', array($this, '_DGUp'), array('width' => 30)),
            array('', array($this, '_DGDown'), array('width' => 30)),
            array('', array($this, '_DGActions'))
        );
    }
    
    function _DGForSale($param)
    {
        $field = '';
        
        $attributeEntity = RexFactory::entity('attribute');
        if ($attributeEntity->get($param['record']['attribute_id']) && $attributeEntity->type_id == 7) {
            $field = '<ul id="icons" class="ui-widget ui-helper-clearfix" style="width: 30px;">';
            $field .= '<li class="ui-state-default ui-corner-all" title="'.($param['record']['is_forsale'] == 1 ? 'Убрать из атрибутов покупки' : 'Сделать атрибутом покупки').'"><a item_id="'.$param['record']['id'].'" href="javascript: void(0);" class="forsale"><span class="'.($param['record']['is_forsale'] == 1 ? 'ui-icon ui-icon-circle-close' : 'ui-icon ui-icon-circle-check').'"></span></a></li>';
            $field .= '</ul>';
        }
        
        return $field;
    }
    
    function _DGAttribute($param)
    {
        $attributeEntity = RexFactory::entity('attribute');
        if ($attributeEntity->get($param['record']['attribute_id'])) {
            return $attributeEntity->name;
        }
        
        return '-';
    }
    
    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);
        
        $category_id = Request::get('category_id', false);
        RexDisplay::assign('category_id', $category_id);
        
        if ($category_id) {
            $arr['category_id'] = $category_id;
            $arr['inpage'] = 10;
            $arr['order_by'] = 'sorder';
            $arr['order_dir'] = 'ASC';
        }
        
        return $arr;
    }
    
    function _DGUp($param)
    {
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `attr2cat` WHERE `category_id` = ?', array($param['record']['category_id']));

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="move_up" href="javascript: void(0);">Вверх</a>';
        } 
        
        return '';
    }
    
    function _DGDown($param)
    {
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `attr2cat` WHERE `category_id` = '.$param['record']['category_id']);
        
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="move_down" href="javascript: void(0);">Вниз</a>';
        }
        
        return '';
    }
    
    protected function _getActionParams($param)
    {
        $arr = array(
            array(
                'title' => RexLang::get('default.delete'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemdelete',
                'allow' => 'delete',
                'img' => 'ui-icon-trash'
            )
        );
        
        return $arr;
    }
    
    function getDefault()
    {
        $in_parent = Request::get('in_parent', false);
        if ($in_parent) {
            RexDisplay::assign('in_parent', $in_parent);
        }
        
        parent::getDefault();
    }
    
    function getAdd()
    {
        RexResponse::init();
        
        $category_id = Request::get('category_id', false);
        RexDisplay::assign('category_id', $category_id);
        
        if (!$category_id) {
            RexResponse::error('Wrong Category ID');
        }
        
        $pcatalogEntity = RexFactory::entity('pCatalog');
        if (!$pcatalogEntity->get($category_id)) {
            RexResponse::error('Unknown error');
        }
        
        $this->manager = RexFactory::manager($this->mod);
        $this->manager->getNotAssigned($pcatalogEntity->id);
        RexDisplay::assign('attributeList', $this->manager->getCollection());
        
        parent::getAdd();
    }
    
    protected function _createEntity($entity, $arr)
    {
        $attributes = Request::get('attributes', array());
        
        if (!$attributes)
            return 'Attributes not exist';
        
        $data['category_id'] = $arr['category_id'];
        
        foreach ($attributes as $value) {
            $entity = RexFactory::entity($this->mod);
            
            $data['attribute_id'] = $value;
            
            $entity->set($data);
            
            $attribute = RexFactory::entity('attribute');
            
            if (!$attribute->get($entity->attribute_id)) {
                return 'Uncorrect Attribute';
            }
            
            if ($attribute->type_id == 1) {
                $subList = XDatabase::getAll('SELECT `id` FROM `attribute` WHERE `pid` = ?', array($entity->attribute_id));
                if ($subList and sizeof($subList) > 0) {
                    $list = '';
                    foreach ($subList as $val) {
                        $list .= $val['id'].',';
                    }
                    $list = trim($list, ',');
                    XDatabase::query('DELETE FROM `attr2cat` WHERE `attribute_id` IN ('.$list.') AND `category_id` = ?', array($arr['category_id']));
                }
            }
            
            if (!$entity->create()) {
                return 'Unable to create '.ucfirst($this->mod);
            }
        }
        
        return true;
    }
    
    protected function _deleteEntity($entity)
    {
        $category_id = $entity->category_id;
        $attribute_id = $entity->attribute_id;
        
        $subList = XDatabase::getAll('SELECT `id` FROM `attribute` WHERE `pid` = ?', array($attribute_id));
        if ($subList and sizeof($subList) > 0) {
            $list = '';
            foreach ($subList as $val) {
                $list .= $val['id'].',';
            }
            $list = trim($list, ',');
            XDatabase::query('DELETE FROM `attr2cat` WHERE `attribute_id` IN ('.$list.') AND `category_id` = ?', array($category_id));
            $list .= ','.$attribute_id;
        } else {
            $list = $attribute_id;
        }
        
        $subList = XDatabase::getAll('SELECT `id` FROM `pcatalog` WHERE `pid` = ?', array($category_id));
        if ($subList and sizeof($subList) > 0) {
            $listC = '';
            foreach ($subList as $val) {
                $listC .= $val['id'].',';
            }
            $listC = trim($listC, ',').', '.$category_id;
        } else {
            $listC = $category_id;
        }
        
        $subList = XDatabase::getAll('SELECT `id` FROM `product` WHERE `category_id` IN ('.$listC.')');
        if ($subList and sizeof($subList) > 0) {
            $listP = '';
            foreach ($subList as $val) {
                $listP .= $val['id'].',';
            }
            $listP = trim($listP, ',');
            XDatabase::query('DELETE FROM `attr2prod` WHERE `attribute_id` IN ('.$list.') AND `product_id` IN ('.$listP.')');
        }
        
        return parent::_deleteEntity($entity);
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
            $this->entityUP->getUP($this->entity->category_id, $this->entity->sorder);

            $currentSorder = $this->entity->sorder;
            
            $this->entity->sorder = $this->entityUP->sorder;
            $this->entity->update();
            
            $this->entityUP->sorder = $currentSorder;
            $this->entityUP->update();
        } else {
            $this->entityDown = RexFactory::entity($this->mod);
            $this->entityDown->getDown($this->entity->category_id, $this->entity->sorder);

            $currentSorder = $this->entity->sorder;
            
            $this->entity->sorder = $this->entityDown->sorder;
            $this->entity->update();
            
            $this->entityDown->sorder = $currentSorder;
            $this->entityDown->update();
        }

        RexResponse::response('ok');
    }
    
    function getForSale()
    {
        RexResponse::init();
        
        if (!$this->task or $this->task < 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }
        
        if ($this->entity->is_forsale == 1) {
            $this->entity->is_forsale = 0;    
        } else {
            $this->entity->is_forsale = 1;
        }
        $this->entity->update();
        
        RexResponse::response('ok');
    }
}