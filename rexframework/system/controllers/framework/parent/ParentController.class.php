<?php
namespace RexFramework;

use \RexObject as RexObject;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexResponse as RexResponse;
use \RexPage as RexPage;
use \RexLang as RexLang;
use \Request as Request;
use \XFile as XFile;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;
use \XImage as XImage;
use \RexRoute as RexRoute;
use \XSession as XSession;

/**
 * Class ParentController
 *
 * Parent Controller
 *
 * @author   Fatal
 * @access   public
 * @package  ParentController.class.php
 * @created  Sun Jan 20 10:22:06 EET 2008
 */
class ParentController extends RexObject
{
    public static $version = 1.0;
    public static $assemble = 'standart';

    protected $user = null;
    protected $datagrid_mod = false;

    protected $default_dialog_width = 1000;
    protected $default_dialog_height = 800;
    protected $add_dialog_width = 520;
    protected $add_dialog_height = 337;
    protected $edit_dialog_width = 520;
    protected $edit_dialog_height = 337;

    protected $mod;
    protected $act;
    protected $task;
    protected $skin;

    protected $entity;
    protected $manager;

    protected $inpage = 20;

    protected $fields = array();

    public function __construct($mod = 'parent', $act = 'default')
    {
        $this->mod = $mod;
        $this->act = $act;
        $this->task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 'default';
        $this->skin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : RexSettings::get('active_skin');

        $this->mod = $this->_getDatagridMod();

        $this->entity = RexFactory::entity($this->mod, false);
        $this->manager = RexFactory::manager($this->mod, false);

        $this->user = XSession::get('user');
        RexDisplay::assign('user', $this->user);

        RexDisplay::assign('skin', $this->skin);
        RexDisplay::setSkin($this->skin);

        parent::__construct();
    }

    protected function _getDatagridMod()
    {
        return $this->datagrid_mod ?: $this->mod;
    }

    protected function _getData($filters, $fields)
    {
        return $this->manager->getList($filters, $fields);
    }

    protected function _getFields($fields)
    {
        $fields = array();
        foreach ($this->entity as $key => $value) {
            if ($key{0} != '_') {
                $caption = ucfirst(str_replace('_', ' ', $key));
                $fields[$key] = $caption;
            }
        }
        $fields[] = array(' ', array($this,'_DGActions'), array('width' => 1));
        return $fields;
    }

    protected function _getFilters($filters)
    {
        if (!isset($filters['search'])) $filters['search'] = '';
        if (!isset($filters['order_by'])) $filters['order_by'] = $this->entity->__uid;
        if (!isset($filters['order_dir'])) $filters['order_dir'] = 'DESC';
        if (!isset($filters['page'])) $filters['page'] = 1;
        if (!isset($filters['inpage'])) $filters['inpage'] = $this->inpage;

        return $filters;
    }

    public function _DGActions($param)
    {
        $arr = $this->_getActionParams($param);

        $field = $this->_getActionCode($arr);

        return $field;
    }

    protected function _getActionParams($param)
    {
        $arr = array(
            array(
                'title' => RexLang::get('default.edit'),
                'item_id' => $param['record'][$this->entity->__uid],
                'class' => 'itemedit',
                'allow' => 'edit',
                'img' => 'ui-icon-pencil'
            ),
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

    protected function _getActionCode($arr)
    {
        $field = '<ul id="icons" class="ui-widget ui-helper-clearfix" style="width: '.(30*sizeof($arr)+1).'px;">';

        foreach ($arr as $key => $value) {
            if (RexPage::allow($this->datagrid_mod, $value['allow'])) {
                $field .= '<li class="ui-state-default ui-corner-all" title="'.$value['title'].'"><a '.(isset($value['item_id']) ? 'item_id="'.$value['item_id'].'"' : '').' href="'.(isset($value['url']) ? $value['url'] : 'javascript: void(0);').'" '.(isset($value['class']) ? 'class="'.$value['class'].'"' : '').' '.(isset($value['target']) ? 'target="'.$value['target'].'"' : '').'><span class="ui-icon '.$value['img'].'"></span></a></li>';
            }
        }

        $field .= '</ul>';

        return $field;
    }

    public function getEdit()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();

        if (!RexPage::allow($this->datagrid_mod, 'edit')) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Permission error');
            } else {
                RexPage::addError('Permission error', $mod);
            }
        }

        $entity = $this->entity;
        if (!$this->task or $this->task < 1 or !$entity->get($this->task) || !$entity->{$entity->__uid}) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Wrong id');
            } else {
                RexPage::addError('Wrong id', $mod);
            }
        }

        RexDisplay::assign('entity', $entity);

        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->edit_dialog_width, $this->edit_dialog_height);
        }
    }

    protected function _validate(&$arr, $entity = null)
    {
        return true;
    }

    protected function _createEntity($entity, $arr)
    {
        $entity->set($arr);

        if (!$entity->create()) {
            return 'Unable to create '.ucfirst($this->datagrid_mod);
        }

        return true;
    }

    protected function _updateEntity($entity, $arr)
    {
        $entity->set($arr);
        if (!$entity->update()) {
            return 'Unable to update '.ucfirst($this->datagrid_mod);
        }

        return true;
    }

    public function getAdd()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();
        $arr = Request::get('entity', array());

        if ($arr) {
            $entity = $this->entity;
            if ($arr['exist_id']) {
                if (!RexPage::allow($this->datagrid_mod, 'edit')) {
                    RexResponse::error('Permission error');
                }
                $entity->get($arr['exist_id']);
                $validate = $this->_validate($arr, $entity);
                if ($validate !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($validate);
                    } else {
                        RexPage::addError($validate, $mod);
                    }
                }
                if (!$entity->{$entity->__uid}) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error('Wrong '.ucfirst($this->datagrid_mod).' id');
                    } else {
                        RexPage::addError('Wrong '.ucfirst($this->datagrid_mod).' id', $this->mod);
                    }
                }

                $update = $this->_updateEntity($entity, $arr);
                if ($update !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($update);
                    } else {
                        RexPage::addError($update, $mod);
                    }
                }
            } else {
                if (!RexPage::allow($this->datagrid_mod, 'add')) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error('Permission error');
                    } else {
                        RexPage::addError('Permission error', $mod);
                    }
                }
                $validate = $this->_validate($arr);
                if ($validate !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($validate);
                    } else {
                        RexPage::addError($validate, $mod);
                    }
                }

                $create = $this->_createEntity($entity, $arr);
                if ($create !== true) {
                    if (RexResponse::isRequest()) {
                        RexResponse::error($create);
                    } else {
                        RexPage::addError($create, $mod);
                    }
                }
            }

            if (RexResponse::isRequest()) {
                if (isset($arr['cropped']) && $arr['cropped'] && $this->mod == 'pimage') {
                    Request::set('id', $entity->id);
                    $this->getMainPhoto();
                }
                RexResponse::response($entity->id);
            } else {
                RexRoute::location(array('mod' => $mod));
            }
        }

        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->add_dialog_width, $this->add_dialog_height);
        }
    }

    protected function _deleteEntity($entity)
    {
        if (!$entity->delete()) {
            return 'Unable to delete '.ucfirst($this->datagrid_mod);
        }

        return true;
    }

    public function getDelete()
    {
        RexResponse::init();

        if (!RexPage::allow($this->datagrid_mod, $this->act)) {
            RexResponse::error('Permissions error');
        }

        $entity = $this->entity;
        $entity->get($this->task);

        if (!$entity->{$entity->__uid}) {
            RexResponse::error('Wrong id');
        }

        $delete = $this->_deleteEntity($entity);
        if ($delete !== true) {
            RexResponse::error($delete);
        }

        RexResponse::response('ok');
    }

    protected function _createImages($entity, $name, $is_file = false)
    {
        $file_name = false;
        $mod = $this->_getDatagridMod();

        if ($is_file) {
            $file_name = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').strtolower($mod).'/'.$is_file;
            $ptn = '/\.([^.]+)$/';
            preg_match($ptn, $is_file, $matches);
            if ($matches) {
                $extension = $matches[1];
            } else {
                $file_name = false;
            }
        } elseif (!$is_file && isset($_FILES[$name]) && strlen(trim($_FILES[$name]['tmp_name'])) > 0) {
            $file_name = $_FILES[$name]['tmp_name'];

            if (!XImage::validMimeType($_FILES[$name]['type'])) {
               return XImage::getError().' - in '.$name;
            }

            $extension = XFile::getExtension($name);
        }

        if ($file_name and strlen(trim($file_name)) > 0) {
            $from = $file_name;

            $config_mod = (array)RexConfig::get('Images', $mod);

            if (isset($config_mod['settings'])) {
                $settings_mod = (array)$config_mod['settings'];
            } else {
                $settings_mod = (array)RexConfig::get('Images', 'default', 'settings');
            }
            if (isset($config_mod['size']) && isset($config_mod['size']['main'])) {
                $params_mod = $config_mod['size']['main'];
            } else {
                $params_mod = RexConfig::get('Images', 'default', 'size', 'main');
            }

            $param_mod = explode('x', trim($params_mod)); // эксплодим полученную строку в массив $param_mod в котором нулевой элемент - ширина, а первый - высота

            if ($param_mod[0] != 'auto' && count($param_mod) != 2) { // если не удалось получить ровно два параметра - выдаём ошибку
                RexResponse::error('Error in config file: size must be a value like "{width}x{height}"');
            }

            $new_extension = isset($settings_mod['extension']) ? $settings_mod['extension'] : false;

            if (isset($settings_mod['fixed_width']) && isset($settings_mod['fixed_width'])) {
                if (!$this->_checkResolution($from, $settings_mod['fixed_width'], $settings_mod['fixed_height'])) {
                    return 'Размер загружаемой картинки должен быть '.$settings_mod['fixed_width'].'x'.$settings_mod['fixed_height'].'px.';
                }
            }

            XImage::unlinkResized($mod, $entity->{$entity->__uid});

            $this->_generateUploadDir($mod, true);
            $upload_dir = $this->_generateUploadDir($mod.'/'.$entity->{$entity->__uid}, true);

            $auto = false;

            $link = $upload_dir.'/main.'.($new_extension ? $new_extension : $extension);

            if (!isset($settings_mod['resize_method'])) {
                try {
                    $method = (string)RexConfig::get('Images', 'default', 'settings', 'resize_method');
                } catch (Exception $e) {
                    $method = false;
                }
            } else {
                $method = $settings_mod['resize_method'];
            }

            if (!isset($settings_mod['borders_color'])) {
                try {
                    $color = (string)RexConfig::get('Images', 'default', 'settings', 'borders_color');
                } catch (Exception $e) {
                    $color = false;
                }
            } else {
                $color = $settings_mod['borders_color'];
            }

            if (isset($param_mod[0]) && $param_mod[0] == 'auto') {
                  if (!XFile::upload($name, $link)) {
                      return XFile::getError();
                  }
                  $from = $link;
            } elseif (!$auto && !XImage::createPreview($from, $link, isset($param_mod[0]) ? $param_mod[0] : false,
                                                       isset($param_mod[1]) ? $param_mod[1] : false, $method, $color && $method == 'borders' ? hexdec($color) : false)) {
                return XImage::getError();
            }

            $entity->{$name} = $new_extension ? $new_extension : $extension;

            if (!$entity->update()) {
                return 'Unable to update '.$name;
            }

            if ($is_file) {
                unlink(REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').strtolower($mod).'/'.$is_file);
            }
        }

        return true;
    }

    protected function _generateUploadDir($mod, $isMain = false)
    {
        if ($isMain) {
            $upload_dir = REX_ROOT.RexConfig::get('RexPath', 'image', 'storage').strtolower($mod);
        } else {
            $upload_dir = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').strtolower($mod);
        }

        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
            chmod($upload_dir, 0777);
        }

        return $upload_dir;
    }

    protected function _deleteImages($entity, $name)
    {
        XImage::unlinkResized($this->mod, $entity->{$entity->__uid});
        return true;
    }

    protected function _checkResolution($src, $w, $h)
    {
        $size = getimagesize($src);

        if ($size[0] != $w || $size[1] != $h) {
            return false;
        }

        return true;
   }
}