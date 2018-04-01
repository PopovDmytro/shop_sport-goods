<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexResponse as RexResponse;
use \RexPage as RexPage;
use \Request as Request;
use \RexDisplay as RexDisplay;
use \XDatagrid as XDatagrid;
use \ExcFile as ExcFile;

/**
 * Class ParentAdminController
 * 
 * @author   MAG
 */
class ParentAdminController extends ParentController
{
    protected function _getDG(&$data, &$fields, &$filters)
    {
        $pagecount = sizeof($data[0]);
        
        $DG = new XDatagrid();
        
        $DG->isNumerable(false);
        $DG->isHeader(true);
        $DG->isFillLines(false);
        $DG->createDatagrid($data[0], $pagecount, 'RexDG');

        foreach ($fields as $key => $caption) {
            $dg_caption = is_array($caption) && isset($caption[0]) ? $caption[0] : $caption;
            $dg_callback = is_array($caption) && isset($caption[1]) ? $caption[1] : null;
            $dg_attribute = is_array($caption) && isset($caption[2]) ? $caption[2] : null;
            $dg_key = intval($key).'' == $key ? null : $key;
            if (!is_null($dg_key)) {
                $dg_caption = $this->_DGSort($dg_caption, $dg_key, $filters['order_by'], $filters['order_dir']);
            }

            $DG->addColumn($dg_caption, $dg_key,  null, $dg_attribute, null, $dg_callback);
        }

        RexDisplay::assign('pager', array(
            'current' => $filters['page'],
            'pages' => (int)(($data[1] + $filters['inpage'] - 1) / $filters['inpage']),
            'count' => $data[1],
            'pagecount' => $pagecount));

        $dg = $DG->renderer() . RexDisplay::fetch('_block/pager.tpl');
        return $dg;
    }
    
    protected function _getDGRow(&$data, &$fields, &$filters)
    {
        $DG = new XDatagrid();
        
        $DG->isNumerable(false);
        $DG->isHeader(true);
        $DG->isFillLines(false);
        $DG->createDatagrid($data[0], 1, 'RexDG');
        
        foreach ($fields as $key => $caption) {
            $dg_caption = is_array($caption) && isset($caption[0]) ? $caption[0] : $caption;
            $dg_callback = is_array($caption) && isset($caption[1]) ? $caption[1] : null;
            $dg_attribute = is_array($caption) && isset($caption[2]) ? $caption[2] : null;
            $dg_key = intval($key).'' == $key ? null : $key;
            if (!is_null($dg_key)) {
                $dg_caption = $this->_DGSort($dg_caption, $dg_key, $filters['order_by'], $filters['order_dir']);
            }
            $DG->addColumn($dg_caption, $dg_key,  null, $dg_attribute, null, $dg_callback);
        }

        $dg = $DG->renderer(array('template' => 'dgrow'));
        return $dg;
    }

    public function _getDatagrid($params = array())
    {
        $params = array_merge(array(
            'mod' => $this->mod,
            'width' => $this->default_dialog_width,
            'height' => $this->default_dialog_height,
            '_getFilters' => array($this, '_getFilters'),
            '_getFields' => array($this, '_getFields'),
            '_getData' => array($this, '_getData'),
            '_getDG' => array($this, '_getDG'),
            '_getDGRow' => array($this, '_getDGRow'),
            '_getFilesTpl' => array($this, '_getFilesTpl')
        ), $params);
        
        $this->datagrid_mod = $params['mod'];
        $width = $params['width'];
        $height = $params['height'];
        
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }
        
        if (!RexPage::allow($this->datagrid_mod, 'view')) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Permission error');
            } else {
                die('Permission error');
            }
        }
        
        $filters = Request::get('filters', array());
        $filters = call_user_func($params['_getFilters'], $filters);
        
        $row_only = Request::get('row_only', false);
        if ($row_only) {
            $item_id = Request::get('item_id', false);
            $filters['id'] = $item_id;
        }
        
        RexDisplay::assign('filters', $filters);
        
        $fields = Request::get('fields', array());
        $fields = call_user_func($params['_getFields'], $fields);
        RexDisplay::assign('fields', $fields);

        $data = call_user_func_array($params['_getData'], array(&$filters, &$fields));
        
        if ($row_only) {
            $dg = call_user_func_array($params['_getDGRow'], array(&$data, &$fields, &$filters));
        } else {
            $dg = call_user_func_array($params['_getDG'], array(&$data, &$fields, &$filters));
        }
        
        $dg_only = Request::get('dg_only', false);
        if ($dg_only) {
            RexResponse::response($dg);
        }
        RexDisplay::assign('dg', $dg);
        
        $tpls = call_user_func($params['_getFilesTpl']);
        
        try {
            $datagrid_template = strtolower($this->datagrid_mod).'/'.$tpls['main'].'.tpl';
            RexDisplay::getTemplatePath($datagrid_template);
            if ($this->mod != $this->datagrid_mod) {
                RexDisplay::override($this->mod, $this->act, RexDisplay::getVar('workspace'), $datagrid_template);
            }
        } catch (ExcFile $e) {
            if ($e->getCode() != ExcFile::TemplateNotFound) {
                throw $e;
            }
            RexDisplay::override($this->mod, $this->act, RexDisplay::getVar('workspace'), 'parent/default.tpl');
        }
        
        try {
            RexDisplay::getTemplatePath(strtolower($this->datagrid_mod).'/'.$tpls['filter'].'.tpl');            
            $template_filters = strtolower($this->datagrid_mod).'/'.$tpls['filter'].'.tpl';
        } catch (ExcFile $e) {
            if ($e->getCode() != ExcFile::TemplateNotFound) {
                throw $e;
            }
            $template_filters = 'parent/filters.tpl';
        }
        
        try {
            RexDisplay::getTemplatePath(strtolower($this->datagrid_mod).'/'.$tpls['button'].'.tpl');
            $template_buttons = strtolower($this->datagrid_mod).'/'.$tpls['button'].'.tpl';;
        } catch (ExcFile $e) {
            if ($e->getCode() != ExcFile::TemplateNotFound) {
                throw $e;
            }
            $template_buttons = 'parent/buttons.tpl';
        }
        
        RexDisplay::assign('template_filters', $template_filters);
        RexDisplay::assign('template_buttons', $template_buttons);
        
        if (RexResponse::getDialogUin()) {
            RexDisplay::fetchWorkspace($this->datagrid_mod, $this->act, RexDisplay::getVar('workspace'));
            $content = RexDisplay::getWorkspaces(RexDisplay::getVar('workspace'));
            RexResponse::responseDialog($content, $width, $height);
        }
    }
    
    protected function _getFilesTpl()
    {
        return array(
            'main' => $this->act,
            'filter' => 'filters',
            'button' => 'buttons'
        );
    }
    
    public function getDefault()
    {
        return $this->_getDatagrid();
    }
    
    protected function _DGSort($caption, $field, $order_by, $order_dir)
    {
        $result = '<table cellpadding="0" cellspacing="0" border="0" class="order_table">
                   <tr>
                        <td>
                            <a href="javascript: void(0);" class="sort" field="'.$field.'">'.$caption.'</a>
                        </td>
                        <td width="24">'.
                            ($order_by == $field
                                ? '<span style="float: right;" class="'.
                                    ($order_dir == 'asc' ? 'ui-icon ui-icon-arrowthick-1-n' : 'ui-icon ui-icon-arrowthick-1-s').
                            '"></span>' : '').'
                        </td>
                   </tr>
                   </table>';
        return $result;
    }

    function getFieldAutoSave()
    {
        if (!RexResponse::isRequest() || $this->act === 'add') {
            return false;
        }

        RexResponse::init();

        $field = Request::get('field', false);
        if (!$field || !isset($field['name']) || !isset($field['value'])) {
            RexResponse::error('Не указаны данные для автообновления поля!');
        }

        $entityID  = Request::get('entity_id', false);
        $relatedID = Request::get('related_id', false);
        if (!$entityID || $entityID === 'false') {
            exit;
        }

        $entityField = $field['entity'];
        $entityField = $entityField != 'false' ? $entityField : false;
        if (is_array($entityID)) {
            foreach ($entityID as $id) {
                $entity = RexFactory::entity($entityField);
                $entity = $entity->get($id);
                $entity->{$field['name']} = $field['value'];
                if (!$entity->update()) {
                    return RexResponse::error('Ошибка при обновлении записи в БД!');
                }
            }

            return RexResponse::response('success');
        }

        if ($relatedID || isset($field['related_name'])) {
            $action = Request::get('action', 'add');
            $entity = RexFactory::entity($entityField);
            $relatedFieldsData = array(
                $field['name']          => $entityID,
                $field['related_name']  => $relatedID ? $relatedID : $field['value']
            );

            if (isset($field['changed_name']) && $field['changed_name']) {
                $relatedFieldsData[$field['changed_name']] = $field['value'];
            }

            $entity->getByFields($relatedFieldsData);
            if ($action === 'remove') {
                if (!$entity->delete()) {
                    return RexResponse::error('Ошибка при удалении записи в БД!');
                }

                return RexResponse::response('success');
            }

            $entity->{$field['name']}           = intval($entityID);
            $entity->{$field['related_name']}   = $relatedID ? intval($relatedID) : intval($field['value']);
            if (isset($field['changed_name']) && $field['changed_name']) {
                $entity->{$field['changed_name']}   = $field['value'];
            }

            if ($entity->{$entity::getUid()}) {
                $entity->update();
            } else {
                $entity->create();
            }

            return RexResponse::response('success');
        }

        $entity = false;

        if ($entityField) {
            $entity = RexFactory::entity($entityField);
            $entity = $entity->get($entityID);
        } else {
            $entity = $this->entity->get($entityID);
        }

        if (!$entity || !$entity->id) {
            RexResponse::error('Сущность с ID ' . $entityID . ' не найдена!');
            return false;
        }

        $field['name'] = preg_replace('#entity\[([^\]]+)\]#i', '$1', $field['name']);
        $entity->{$field['name']} = $field['value'];
        try {
            if (!$entity->update()) {
                RexResponse::error('Ошибка при обновлении записи в БД!' . "\n" . \XDatabase::getError(true));
            }
        } catch (\Exception $e) {
            RexResponse::error('Ошибка при обновлении записи в БД!' . "\n");
        }

        RexResponse::response('success');
    }
}