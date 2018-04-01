<?php
namespace RexShop;

use \XImage as XImage;
use \XFile as XFile;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexLang as RexLang;
use \RexConfig as RexConfig;
use \Request as Request;
use \XDatabase as XDatabase;
use \RexDBList as RexDBList;
use \PHPExcel_IOFactory as PHPExcel_IOFactory;
use \Exception as Exception;
use \ExcJs as ExcJs;
use \RexResponse as RexResponse;

/**
 * Class AttachAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  AttachAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class AttachAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\AttachEntity:shop.standart:1.0',
        'RexShop\AttachManager:shop.standart:1.0'
    );
    
    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;
    protected $default_dialog_width = 800;
    protected $default_dialog_height = 750;
    
    protected function _getFields($fields)
    {
        return array(
            array('<b>Id</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Icon</b>', array($this, '_DGIcon'), array('width' => 60)),
            'filename' => 'Name',
            'download_count' => 'Кол-во скачиваний',
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    function _DGIcon()
    {
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
    
    /*function _DGUp($param)
    {
        $order = XDatabase::getOne('SELECT MIN(`sorder`) FROM `pimage` WHERE `product_id` = ?', array($param['record']['product_id']));

        if ($order < $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_up" href="javascript: void(0);">Вверх</a>';
        } 
        
        return '';
    }
    function _DGDown($param)
    {
        $order = XDatabase::getOne('SELECT MAX(`sorder`) FROM `pimage` WHERE `product_id` = '.$param['record']['product_id']);
        if ($order > $param['record']['sorder']) {
            return '<a id="'.$param['record']['id'].'" class="order_down" href="javascript: void(0);">Вниз</a>';
        }
        
        return '';
    }
    
    function _DGImage($param)
    {
        if (strlen(trim($param['record']['image'])) > 0) {
            return '<img src="'.RexConfig('RexPath.image.link').'pimage/'.'list_'.$param['record']['id'].'.'.$param['record']['image'].'" />';
        } 
        
        return '-';
    } */
    
    protected function _getFilters($filters)
    {
        $arr = parent::_getFilters($filters);
        
        $product_id = Request::get('product_id', false);
        RexDisplay::assign('product_id', $product_id);
        
        if ($product_id) {
            $arr['product_id'] = $product_id;
            $arr['inpage'] = 8;
        }
        
        $arr['order_by'] = 'id';
        $arr['order_dir'] = 'ASC';
        
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
    
    function getUpload()
    {
        RexResponse::init();
        
        $id = Request::get('product_id', false);
        if (!$id) {
            $id = $this->task;
        }
        
        RexDisplay::assign('product_id', $id);
        
        $entity = RexFactory::entity('product');
        if (!$id || $id < 1 || !$entity->get($id) || !$entity->id) {
            RexResponse::error('Wrong product id');
        }
        
        $name = Request::get('name', false);
        
        if ($name) {
            if (isset($_FILES[$name]['name']) && mb_strlen($_FILES[$name]['name'], 'UTF-8') >= 1) {
                $extension = XFile::getExtension($name);
                $filename = XFile::getFileName($name);
                $mimetype = XFile::getType($name);
                $size = XFile::getSize($name);
                
                if (!$extension) {
                    RexResponse::error('Файлы такого типа не могут быть загружены');    
                }
                                
                if ($size > 5242880) {
                    RexResponse::error('Файл не может быть больше 5 MB');    
                }
                
                $folder = REX_ROOT.RexConfig::get('RexPath', 'attach', 'folder').$entity->id.'/';
                
                if (!is_dir($folder)) {
                    mkdir($folder, 0777);
                    chmod($folder, 0777);
                }
                
                $folder .= $filename.'.'.$extension;
                
                if (is_file($folder)) {
                    RexResponse::error('Такое название файла уже есть');
                }
                
                if (!XFile::upload($name, $folder)) {
                    RexResponse::error(XFile::getError(), false);
                    RexResponse::error('Ошибка загрузки файла');
                }
                
                $entity = RexFactory::entity('attach');
                $entity->product_id = $id;
                $entity->filename = $filename.'.'.$extension;
                $entity->type = $mimetype;
                $entity->create();
                
                RexResponse::response('ok');
            } else {
                RexResponse::error('Ошибка загрузки файла');
            }
        }
        
        $content = RexDisplay::fetch(strtolower($this->mod).'/upload.tpl');
        RexResponse::responseDialog($content, 560, 200);
    }
    
    protected function _deleteEntity($entity)
    {
        $del_file = $this->_deleteFiles($entity);
        if ($del_file !== true)
            return $del_file;
        
        return parent::_deleteEntity($entity);
    }
    
    protected function _deleteFiles($entity)
    {
        $upload_dir = REX_ROOT.RexConfig::get('RexPath', 'attach', 'folder').$entity->product_id;
        
        $link = $upload_dir.'/'.$entity->filename;
        
        if (strlen($entity->filename) > 0) {
            XFile::delete($link);
        }
            
        if (XFile::isError()) {
            return XFile::getError();
        }
        
        return true;
    }
    
}