<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \Request as Request;
use \XSession as XSession;
use \RexLang as RexLang;

/**
 * Class CommentController
 *
 * Category Controller Class
 * 
 * 1. Подключаем вызов контроллера комментария в необходимом методе:  		
	//Comments
    $_REQUEST['task'] = array(
        'product_id' => $gameEntity->id,
        'type' => 1
    ) ;
    RexRunner::runController('comment', 'add');
 * 2. Обрабатываем тип в менеджере комментариев, методе getUserLatest
 * 3. Обрабатываем смешанный вывод в user.list.tpl
 * 4. Обрабатываем тип в админ-контроллере в методе _DGSource
 * 5. Подключаем отображение в необходимом шаблоне:
	{strip}
	<div class="post-inner">
	{if $rexPage->user}
		{latest_comments saveto=comments count=10 type=3 product_id=$garticle->id current_user=$user->id}
	{else}
		{latest_comments saveto=comments count=10 type=3 product_id=$garticle->id}
	{/if}
	<h2>Отзывы к статье "{garticle_name article=$garticle game=$game parentGame=$parentGame parentParentGame=$parentParentGame}"</h2>
	{include file="frontend/`$rexPage->skin`/comment/list.tpl"}
	{$comment_form}
	</div>
	{/strip}
 * 
 * 
 * 
 *
 * @author   Fatal
 * @access   public
 * @package  CommentController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class CommentController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\CommentEntity:shop.standart:1.0',
        'RexShop\CommentManager:shop.standart:1.0',
    );
	
	function getAdd()
	{	
		//get form data
		$arrcomment = Request::get('comment');
		RexDisplay::assign('comment', $arrcomment);
		
		//create entity
		$commentEntity = RexFactory::entity('comment');
		
		//if edit mode - get data by ID
		if (isset($arrcomment['id']) and intval($arrcomment['id']) > 0) {
			
			if (!$commentEntity->get($arrcomment['id'])) {
				RexPage::addError(RexLang::get('comment.error.edit_comment'), $this->mod);
			} else {
				$arr = $commentEntity->getArray();
				$arr = array_merge($arr, $arrcomment);
				RexDisplay::assign('commententity', $arr);				
			}
		}
		
		//if form is submitted
		if (isset($arrcomment['commentsubmit']) and !RexPage::isError($this->mod)) {

			unset($arrcomment['commentsubmit']);

			//user
			$newUser 	= RexFactory::entity('user');
			$user 		= RexFactory::entity('user');
			$user = XSession::get('user');		
			if (!$user or $user->id < 1) {
				RexPage::addError(RexLang::get('comment.error.user_error'), $this->mod);
			} elseif ($user->role != 'user' and isset($commentEntity->user_id) and $commentEntity->user_id > 0 and $newUser->get($commentEntity->user_id)) {
				$user = $newUser;
			}
			
			//content
			if (empty($arrcomment['content'])) {
				RexPage::addError(RexLang::get('comment.error.empty_comment'), $this->mod);
			}
			
			if (!RexPage::isError($this->mod)) {
				//set form fields to entity
				$commentEntity->set($arrcomment);
				$commentEntity->user_id = $user->id;
				$commentEntity->product_id = $this->task['product_id'];
				
				if (!RexPage::isError($this->mod) and !isset($arrcomment['id'])) {
					if (!$commentEntity->create()) {
						RexPage::addError(RexLang::get('comment.error.error_create'), $this->mod);
					} else {
						if ($commentEntity->status == 2) {
							RexPage::addMessage(RexLang::get('comment.message.create_successfully'), $this->mod);
						} else {
							RexPage::addMessage(RexLang::get('comment.message.add_to_moderator'), $this->mod);
						}
					}
				}

				if (!RexPage::isError($this->mod) and !$commentEntity->update()) {
					RexPage::addError(RexLang::get('comment.message.add_to_moderator'), $this->mod);
				}
			}
			
			$arr = $commentEntity->getArray();
			$arr = array_merge($arr, $arrcomment);
			RexDisplay::assign('commententity', $arr);

		}
		
		$fetched = RexDisplay::fetch($this->mod.'/'.$this->act.'.tpl');
		RexDisplay::assign('comment_form', $fetched);
	}
	
	/*function getLatest($aParams) //smarty func
	{
       
        $user = XSession::get('user');
		$commentManager = RexFactory::manager('comment');
		$commentManager->getLatest(10, $this->task, $user ? $user->id : false);
		RexDisplay::assign('comments', $commentManager->getCollection());
	}*/
    
    function getComments()
    {
        $user = XSession::get('user');
        $commentManager = RexFactory::manager('comment');
        $commentManager->getLatest(20, $this->task, $user ? $user->id : false);
        
        RexDisplay::assign('comments', $commentManager->getCollection());
    }
}