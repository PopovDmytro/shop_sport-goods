<?php
class NewsController extends \RexShop\NewsController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\NewsEntity:shop.standart:1.0',
        'RexShop\NewsManager:shop.standart:1.0',
        'RexShop\NewsController:shop.standart:1.0',
        'CommentManager:volley.standart:1.0',
        'RexFramework\ParentController:standart:1.0',
        'PagerObj:standart:1.0'
    );
	
	function getDefault()
	{
		$this->entity = RexFactory::entity('news');
		if ($this->entity->getByFields(array('alias' => $this->task))) {
			RexDisplay::assign('news_item', $this->entity);

			/*$pattern = RexSettings::get('news_title');
			if (strlen(trim($this->entity->title)) > 0) {
                RexPage::setTitle($this->entity->title);
			} elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
                $title = str_replace('{news_name}', $this->entity->name, $pattern);
				RexPage::setTitle($title);
			} else {
                RexPage::setTitle($this->entity->name);
			}
			
			RexPage::setDescription($this->entity->description);*/
			RexPage::setKeywords($this->entity->keywords);
            
            $arrcomment = Request::get('addcom');
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
            $newUser     = RexFactory::entity('user');
            $user         = RexFactory::entity('user');
            $user = XSession::get('user');        
            if (!$user or $user->id < 1) {
                RexPage::addError(RexLang::get('comment.error.user_error'), $this->mod);
            } elseif ($user->role != 'user' and isset($commentEntity->user_id) and $commentEntity->user_id > 0 and $newUser->get($commentEntity->user_id)) {
                $user = $newUser;
            }
            
            //content
            if (empty($arrcomment['content'])) {
                RexPage::addError(RexLang::get('about.error.empty_comment'), $this->mod);
            }
            
            if (!RexPage::isError($this->mod)) {
                //set form fields to entity
                $commentEntity->set($arrcomment);
                $commentEntity->user_id = $user->id;
                $commentEntity->product_id = -1;  
                $commentEntity->news_id = $this->entity->id;    
                $commentEntity->type = 4;    
                
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
        
        $commentsManager = RexFactory::manager('comment');
        $comments = $commentsManager->getNewsComm($this->entity->id);
        $user = XSession::get('user');
        if ($user) {
            $userCom = $commentsManager->getNewsCommUser($user->id, $this->entity->id);
        
        RexDisplay::assign('comments', $comments);
        RexDisplay::assign('userCom', $userCom);
        }
        if (!$this->entity->title){
            RexPage::setTitle($this->entity->name.' - новости на сайте магазина Волеймаг');
        }
        if (!$this->entity->description) {
            $str = trim(str_replace('&nbsp;' , '', strip_tags($this->entity->content)));
            $desc = '';
            if ($str && mb_strlen($str, 'UTF-8') > 2) {
                $desc = substr($str, 0, strpos($str, ' ', 100)).'.. ';
            }
            RexPage::setDescription($desc.'Новости о спортивной одежде, обуви и брендах на сайте интернет-магазина Волеймаг');
        }
        //\sys::dump($userCom);exit;
		}
	}
    
    function getArchive()
    {
        parent::getArchive();
        RexPage::setTitle('Новости о марках спортивной одежды и обуви. Информация о последних коллекциях спортивных брендов');
        RexPage::setDescription('Последние новости о спортивной одежде, обуви и сопутствующих товарах на сайте интернет-магазина волейбольных товаров Волеймаг');
        //RexPage::setKeywords('');
    }

    function getLatest() //smarty func
    {
        $aParams = array(
            'saveto' => 'news',
            'count' => 6
        );

        $newsManager = RexFactory::manager('news');
        $newsManager->getByWhere('1=1  ORDER BY `id` DESC LIMIT '.$aParams['count'].'');
        RexDisplay::assign($aParams['saveto'], $newsManager->getCollection());
        //\sys::dump($newsManager->getCollection());exit;
    }
}