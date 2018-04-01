<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexRunner as RexRunner;
use \RexConfig as RexConfig;
use \XSession as XSession;
use \Request as Request;
use \RexLang as RexLang;
use \RexResponse as RexResponse;
use \RexDisplay as RexDisplay;
use \PHPSender as PHPSender;
use \RexPage as RexPage;
use \XDatabase as XDatabase;

/**
 * Class UserAdminController
 *
 * User Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  UserAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class UserAdminController extends ParentAdminController
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexFramework\UserEntity:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
        'OrderManager:volley.standart:1.0'
    );
    
    protected $add_dialog_width = 620;
    protected $add_dialog_height = 254;
    protected $edit_dialog_width = 700;
    protected $edit_dialog_height = 254;

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            array('Email', array($this, '_DGEmail'), array('width' => 100)),
            'name' => 'Имя',
            'lastname' => 'Фамилия',
            array('Адрес', array($this, '_DGAdress'),  array('width' => 150)),
            'role' => 'Роль',
            array('Регистрация', array($this, '_DGRegistered')),
            array('Участие в e-mail рассылке', array($this, '_DGDelivery'), array('align' => 'center')),
            array('Скидка', array($this, '_DGSale'), array('align' => 'center')),
            //array('<div class="user-orders-sum">Сумма заказов (грн)</div>', array($this, '_DGOrdersSum'), array('align' => 'center')),
            '_orders_sum' => array('Сумма заказов(по завершенным)', null, array('align' => 'center')),
            array('<div class="ui-state-default"><a href="javascript: void(0);">Статус</a></div>', array($this, '_DGStatus'), array('width' => 90)),
            'phone' => 'Телефон',
            array('Отправить SMS', array($this, '_DGSmsSend'), array('width' => 50)),
            array('<b>Действие</b>', array($this, '_DGActions'), array('width' => 90))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    function _DGEmail($param)
    {
        $field = '';
        if ($param['record']['is_registered'] != 0) {
            $field .= $param['record']['email'];
        }
        return '<div style ="word-wrap: break-word; width: 140px;">'.$field.'</div>';
    }
    function _DGAdress($param)
    {
        $city = $param['record']['city'];
        $fillials = $param['record']['fillials'];
        $field = '';
        if ($city) {
            $field .= 'Город: '.$city;
        }
        if ($fillials) {
            $field .= '<br/>Филлиал: '.$fillials;
        }
        
        return $field;
    }
    
    function _DGRegistered($param)
    {
        if ($param['record']['is_registered'] == 1) {
            $field = 'Зарегистрированный';
        } elseif($param['record']['is_registered'] == 2) {
            $field = 'С помощью Facebook'; 
        } elseif($param['record']['is_registered'] == 3) {
            $field = 'C помощью Вконтакте'; 
        } else {
            $field = 'Незарегистрированный'; 
        }
        
        return $field;
    }

    function _DGDelivery($param)
    {
        if ($param['record']['delivery'] == 1) {
            $field = 'Да';
        } else {
            $field = 'Нет'; 
        }
        
        return $field;
    }
    
    function _DGSale($param)
    {
        $orderManager = RexFactory::manager('order');
        $sale = $orderManager->getUserSale($param['record']['id']);
        $field = $sale.'%';        
        
        return $field;
    }
    
    function _DGStatus($param)
    {
        if ($param['record']['active'] == 1) {
            $field = 'active';
        } else {
            $field = 'not active';
        }
        
        return $field;
    }
    
    function _DGSmsSend($param)
    {
        return '<a href="javascript:void(0);" class="send-sms-link" user_id="'.$param['record']['id'].'">написать</a>';
    }

    public function _DGOrdersSum($param)
    {
        $sum = XDatabase::getOne('SELECT SUM(o.price_opt) FROM rexorder o INNER JOIN `user` u ON o.`user_id` = u.`id` WHERE u.id = ? AND o.status = 3', $param['record']['id']);

        return $sum ? $sum : 0;
    }

    protected function _getActionParams($param)
    {
        $arr = parent::_getActionParams($param);
        
        $class = 'itemeactive';
        $title = 'default.active';
        $img = 'ui-icon-circle-check';
        if ($param['record']['active']) {
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
    
    function _validate(&$arr, $entity = null)
    {
        $this->entity = RexFactory::entity($this->mod);
       
        if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\.A-Za-z0-9]{2,}$/isu', $arr['email'])) {
            return 'Invalid Email format';
        }
        
       /* if(!$arr['login']) {
            return 'Please enter login';
        }   */
        
        if (is_null($entity)) {
            $this->entity->getByFields(array('email' => $arr['email']));
            if ($this->entity->id && $this->entity->id > 0) {
                return 'This email already exists in other user';
            }
            
            if (!$arr['clear_password']) {
                return 'Please enter password';
            }
            
            if (strlen($arr['clear_password']) < 5) {
                return 'Password must contain min 5 characters';
            }
            
            $arr['password'] = md5($arr['clear_password']);
        } else {
            if ($arr['clear_password'] && strlen($arr['clear_password']) < 5) {
                return 'Password must contain min 5 characters';
            }
            
            if ($arr['clear_password']) {
                $arr['password'] = md5($arr['clear_password']);
            } else {
                unset($arr['clear_password']);
            }
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
    
    protected function _deleteEntity($entity)
    {
        if ($entity->role == 'admin') {
            return 'Impossible to remove admin';
        }
        
        if ($entity->is_registered == '2' || $entity->is_registered == '3') {
            XDatabase::query('DELETE FROM `loginer` WHERE `user_id` ='.$entity->id); 
        }
        
        return parent::_deleteEntity($entity);
    }
    
    function getLogout()
    {
        XSession::destroy();
        setcookie('rf_user', '', time() - 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        
        header('location: '.RexConfig::get('Environment', RexRunner::getEnvironment(), 'link'));
        exit;
    }
    
    function getSendSms()
    {
        RexResponse::init();
        
        $text = Request::get('text', false);
        $phone = Request::get('phone', false);
        
        if (!$phone && $this->task) {
            $this->entity->get($this->task);
            $phone = $this->entity->phone;     
        }
        
        if ($text) {
            PHPSender::sendSms($phone, $text);
            RexResponse::response(true);    
        }
        
        RexDisplay::assign('phone', $phone);
        RexDisplay::assign('entity', $this->entity);
        $content = RexDisplay::fetch('user/sendSms.tpl');
        RexResponse::responseDialog($content, 393, 400);
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
            $entity->is_registered = 1;
            if ($arr['exist_id']) {
                /*if (!RexPage::allow($this->datagrid_mod, 'edit')) {
                    RexResponse::error('Permission error');
                }*/
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

                $arr['login'] = $arr['email']; 
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

                $arr['login'] = $arr['email']; 
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
                RexResponse::response($entity->id);
            } else {
                RexRoute::location(array('mod' => $mod));
            }
        }
        $userFillials = RexFactory::manager('fillials');
        $userFillials->getByWhere('1 = 1');
        RexDisplay::assign('fillials', $userFillials->getCollection());
        //var_dump($userFillials->getCollection());exit;
        if (RexResponse::isRequest()) {
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->add_dialog_width, $this->add_dialog_height);
        }
    }
    public function getEdit()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();
        }

        $mod = $this->_getDatagridMod();

        /*if (!RexPage::allow($this->datagrid_mod, 'edit')) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Permission error');
            } else {
                RexPage::addError('Permission error', $mod);
            }
        }*/

        $entity = $this->entity;
        if (!$this->task or $this->task < 1 or !$entity->get($this->task) || !$entity->{$entity->__uid}) {
            if (RexResponse::isRequest()) {
                RexResponse::error('Wrong id');
            } else {
                RexPage::addError('Wrong id', $mod);
            }
        }
        
        $userCity = RexFactory::entity('user');
        $userCity->getByWhere('id ='.$this->task);

        if ($userCity->city) {
            $entityCity = RexFactory::entity('city');
            $entityCity->getByWhere('name = "'.$userCity->city.'"');
            RexDisplay::assign('city_id', $entityCity->id);
        }
        
        $userFillials = RexFactory::manager('fillials');
        $userFillials->getByWhere('1 = 1');
        RexDisplay::assign('fillials', $userFillials->getCollection());

        RexDisplay::assign('entity', $entity);

        if (RexResponse::isRequest()) {
            $isOnlyView = Request::get('only_view', false);
            if ($isOnlyView) {
                $orderManager = RexFactory::manager('order');
                $orderManager->getByWhere('user_id = ' . $this->entity->id);
                $userOrders   = $orderManager->getCollection();

                foreach ($userOrders as &$order) {
                    $order['items']  = $orderManager->getDetails($order['id']);
                    $order['prices'] = $orderManager->getOrderValues($order['id']);
                    $order['status_text'] = $orderManager->getTextStatus($order['status']);
                }
                unset($order);

                RexDisplay::assign('userOrders', $userOrders);
                RexDisplay::assign('userID', $this->entity->id);
            }

            RexDisplay::assign('isOnlyView', $isOnlyView);
            $content = RexDisplay::fetch(strtolower($mod).'/add.tpl');
            RexResponse::responseDialog($content, $this->edit_dialog_width, $this->edit_dialog_height);
        }
    }
    
    function getAutocomplete()
    {   
        $query = Request::get('q', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s]#isu', '', $query);

        $manager = RexFactory::manager('user');
        $res = $manager->getBySearchAdmin(addslashes($query));
        
        if ($res and sizeof($res) > 0) {
            foreach ($res as $key=>$value) {
                echo $value['user_id'].'='.$value['user_name'].'='.$value['user_lastname'].'='.$value['user_phone']."\n";
            }
        }
        exit;
    }

    public function getUserBlock()
    {
        if (!RexResponse::isRequest()) {
            return false;
        }

        RexResponse::init();
        $userID = Request::get('user_id', false);
        if (!$userID) {
            RexResponse::error('Не указан ID пользователя!');
        }

        $this->entity->get($userID);
        if (!$this->entity->id) {
            RexResponse::error('Не удалось загрузить данные пользователя с ID: ' . $userID);
        }

        $orderManager = RexFactory::manager('order');
        $orderManager->getByWhere('user_id = ' . $this->entity->id);
        $userOrders   = $orderManager->getCollection();

        if (!$userOrders) {
            RexResponse::response('У пользователя нет заказов!');
        }

        foreach ($userOrders as &$order) {
            $order['items']  = $orderManager->getDetails($order['id']);
            $order['prices'] = $orderManager->getOrderValues($order['id']);
            $order['status_text'] = $orderManager->getTextStatus($order['status']);
        }
        unset($order);

        RexDisplay::assign('userOrders', $userOrders);
        $content = RexDisplay::fetch('order/user_order_list.inc.tpl');

        RexResponse::response($content);
    }

    public function getExportExcel()
    {
        $titles = array();
        $export_data = array();

        $filters = Request::get('filters', false);
        $filters = $this->_getFilters($filters);
        $filters['inpage'] = 10000;

        $fields = Request::get('fields', array());
        $fields = $this->_getFields($fields);
        $data = $this->_getData($filters, $fields);

        foreach ($fields as $field => $spec) {
            $spec     = is_array($spec) ? strip_tags($spec[0]) : $spec;
            if (in_array($spec, array('Отправить SMS', 'Действие'))) {
                unset($fields[$field]);
                continue;
            }

            $titles[] = $spec;
        }

        foreach ($data[0] as $row) {
            $new_row = array();
            foreach ($fields as $field => $spec) {
                if (is_array($spec) && isset($spec[1][1])) {
                    $methodName = (string) $spec[1][1];
                    if (method_exists($this, $methodName)) {
                        $new_row[] = strip_tags(str_replace(array('<br>', '<br />'), '. ', $this->{$methodName}(array('record' => $row))));
                    }
                } else {
                    $new_row[] = $row[$field];
                }
            }
            $export_data[] = $new_row;
        }

        $phpExcel = new \PHPExcel();
        $phpExcel->setActiveSheetIndex(0);
        $sheet = $phpExcel->getActiveSheet();
        $sheet->setTitle('Пользователи');

        foreach ($titles as $index => $title) {
            $sheet->getCellByColumnAndRow($index, 1)->setValue($title);
            $style = $sheet->getStyleByColumnAndRow($index, 1);
            $style->getFont()->setBold(true);
            $style->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $style->getBorders()->getAllBorders()
                ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THICK);
        }

        foreach ($export_data as $row => $row_data) {
            foreach ($row_data as $key => $value) {
                $sheet->getStyleByColumnAndRow($key, $row + 2)->getAlignment()->setWrapText(true);
                $sheet->getCellByColumnAndRow($key, $row + 2)->setValue(trim($value) . "\t");
            }
        }

        foreach ($titles as $index => $title) {
            $sheet->getColumnDimensionByColumn($index)->setAutoSize(true);
        }

        $writer = new \PHPExcel_Writer_Excel5($phpExcel);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="VolleyMag_Users.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        exit;
    }
}