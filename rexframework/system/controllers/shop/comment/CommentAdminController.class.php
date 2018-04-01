<?php
namespace RexShop;

use \Exception as Exception;
use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \Request as Request;
use \RexResponse as RexResponse;
use \RexLang as RexLang;

/**
 * Class CommentAdminController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  HomeAdminController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class CommentAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\CommentEntity:shop.standart:1.0',
        'RexShop\CommentManager:shop.standart:1.0',
    );
	
    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Комментарий</b>', array($this, '_DGText')),
            array('<b>Автор</b>', array($this, '_DGAuthor'), array('width' => 100)),
            array('<b>Статус</b>', array($this, '_DGStatus'), array('width' => 90)),
            array('<b>Дата</b>', array($this, '_DGDate'), array('width' => 70)),
            array('<b>Действие</b>', array($this, '_DGActions'))
        );
    }
    
    function _DGId($param)
    {
        return $param['record']['id'];
    }
    
    function _DGText($param)
    {
        return $this->manager->truncate($param['record']['content'], 170);
    }
    
	function _DGAuthor($param)
	{
		$userEntity = RexFactory::entity('user');
        try{
            $userEntity->get($param['record']['user_id']);
            return $userEntity->login;       
        } catch (Exception $e) {
            return ' - (пользователь удален) ';
        }
	}
	
	function _DGStatus($param)
	{
		if ($param['record']['status'] == 2) {
			return 'OK';
		}
        
        return 'Не заапрувлен';
	}
	
	function _DGDate($param)
	{
		return date('Y-m-d', strtotime($param['record']['date_create'])).'<br/>'.date('Y-m-d', strtotime($param['record']['date_update']));
	}
	
    protected function _getActionParams($param)
    {
        $arr = parent::_getActionParams($param);
        
        $class = 'itemeactive';
        $title = 'default.approved';
        $img = 'ui-icon-circle-check';
        if ($param['record']->status == 2) {
            $class = 'itemdeactive';
            $title = 'default.banned';
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
    
	function getStatus() 
	{
        RexResponse::init();
        
		$id = Request::get('id');

		$commentEntity = RexFactory::entity('comment');
		$commentEntity->get($id);
		$commentEntity->status = $this->task;
		$commentEntity->update();
		
        RexResponse::response('ok');
	}
	
	function getCount($aParams) //smarty func
	{
		$commentManager = RexFactory::manager('comment');
		$count = $commentManager->getCount($aParams['status']);
		
		if ($aParams['saveto'] == 'display') {
			echo $count;
		} else {
			RexDisplay::assign($aParams['saveto'], $count);
		}
	}
}