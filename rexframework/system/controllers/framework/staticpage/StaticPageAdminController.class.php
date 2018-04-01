<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \Request as Request;
use \RexLang as RexLang;
use \RexResponse as RexResponse;


/**
 * Class StaticPageAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  StaticPageAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class StaticPageAdminController extends ParentAdminController
{    
    public static $version = 1.0;
    public static $assemble = 'standart';
    public static $needed = array(
        'RexFramework\StaticPageEntity:standart:1.0',
        'RexFramework\StaticPageManager:standart:1.0'
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
            'alias' => 'Алиас',
            array('<b>Действие</b>', array($this, '_DGActions'), array('width' => 90))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    protected function _getActionParams($param)
    {
        $arr = parent::_getActionParams($param);
        
        $class = 'itemeactive';
        $title = 'default.active';
        $img = 'ui-icon-circle-check';
        if ($param['record']->active) {
            $class = 'itemdeactive';
            $title = 'default.deactive';
            $img = 'ui-icon-cancel';
        }
        $arr[] = array(
            'title' => RexLang::get($title),
            'item_id' => $param['record'][$this->entity->__uid],
            'class' => $class,
            'allow' => 'edit',
            'img' => $img
        );
        
        return $arr;
    }
    
    protected function _validate(&$arr, $entity = null)
    {
        $this->entity = RexFactory::entity($this->mod);
        
        if(!$arr['alias']) {
            return 'Please enter alias';
        }
        
        if (is_null($entity)) {
            $this->entity->getByFields(array('alias' => $arr['alias']));
            if ($this->entity->id && $this->entity->id > 0) {
                return 'This alias already exists in our system';
            }
        } else {
            $this->entity->getByFields(array('alias' => $arr['alias']));
            if ($this->entity->id && $this->entity->id > 0 && $this->entity->id != $entity->id) {
                return 'This alias already exists in our system';
            }
        }
        
        if(!$arr['name']) {
            return 'Please enter page name';
        }
        
        return true;
    }
    
    function getActive()
    {
        RexResponse::init();
        
        $this->entity = RexFactory::entity($this->mod);
        if (!$this->task or $this->task< 1 or !$this->entity->get($this->task)) {
            RexResponse::error('Wrong id');
        }
        
        $value = Request::get('value', 0);
        
        $this->entity->active = $value;
        
        if (!$this->entity->update()) {
            RexResponse::error('Unknown error');
        }
        
        RexResponse::response('ok');
    }
}