<?php

class ArticleController extends \RexFramework\ParentController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ArticleEntity:standart:1.0',
        'RexFramework\ArticleManager:standart:1.0',
        'ArticleCommEntity:volley.standart:1.0',
        'ArticleCommManager:volley.standart:1.0',
        'CommentManager:volley.standart:1.0',
        'PagerObj:standart:1.0'
    );

	function getDefault()
	{
		$this->entity = RexFactory::entity('article');
		if ($this->entity->getByFields(array('alias' => $this->task))) {
			RexDisplay::assign('article_item', $this->entity);

			/*$pattern = RexSettings::get('article_title');
			if (strlen(trim($this->entity->title)) > 0) {
                RexPage::setTitle($this->entity->title);
			} elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
                $title = str_replace('{article_name}', $this->entity->name, $pattern);
				RexPage::setTitle($title);
			} else {
                RexPage::setTitle($this->entity->name);
			}

			RexPage::setDescription($this->entity->description);*/
			RexPage::setKeywords($this->entity->keywords);

            //\sys::dump($this->entity->id);exit;
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
                $commentEntity->article_id = $this->entity->id;
                $commentEntity->type = 3;

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
        $comments = $commentsManager->getArticleComm($this->entity->id);
        $user = XSession::get('user');
        //\sys::dump($this->entity->id);exit;
        if ($user) {

            $userCom = $commentsManager->getArticleCommUser($user->id, $this->entity->id);
            RexDisplay::assign('comments', $comments);
            RexDisplay::assign('userCom', $userCom);
            //\sys::dump($userCom);exit;
        }
        RexDisplay::assign('comments', $comments);
        if (!$this->entity->title){
            RexPage::setTitle($this->entity->name.' - на сайте магазина Волеймаг');
        }
        if (!$this->entity->description) {
            $str = strip_tags($this->entity->content);
            $desc = substr($str, 0, strpos($str, ' ', 100));
            RexPage::setDescription($desc.'.. Статьи о спортивной одежде, обуви и брендах на сайте интернет-магазина Волеймаг');
        }

        //\sys::dump($comments);exit;
		}
	}

	function getArchive()
	{
		if (!$this->task or $this->task == 'default') {
			$this->task = 1;
		}

        //\sys::dump($this->task);
		$pagerObj = new PagerObj('pager_'.$this->mod, 9, $this->task);

		$this->manager = RexFactory::manager('article');
		$this->manager->getArchive($pagerObj->getFrom(), $pagerObj->getPerPage());

        RexDisplay::assign('article_archive', $this->manager->getCollection());

		$pagerObj->setCount($this->manager->getCount());
		$pagerObj->generatePages();
		RexDisplay::assign($pagerObj->name, $pagerObj);
        RexDisplay::assign('pager_count', count($pagerObj->pages));
        $task = Request::get('task', 1);
        if ($task > 1) {
            RexPage::setTitle('Статьи о спортивных товарах и брендах. Как выбрать спортивную одежду и обувь - страница '.$task);
            RexPage::setDescription('Полезная информация о спортивной одежде и обуви – как выбрать, особенности различных моделей для разных видов спорта. Статьи о волейбольных товарах, травмах и аксессуарах - страница '.$task);
        } else {
            RexPage::setTitle('Статьи о спортивных товарах и брендах. Как выбрать спортивную одежду и обувь');
            RexPage::setDescription('Полезная информация о спортивной одежде и обуви – как выбрать, особенности различных моделей для разных видов спорта. Статьи о волейбольных товарах, травмах и аксессуарах');
            RexPage::setKeywords('Статьи о спортивных товарах, как выбрать спортивную одежду, информация о спортивной одежде, статьи о волейбольных товарах');
        }

	}

	function getLatest() //smarty func
	{
        $aParams = array(
            'saveto' => 'article',
            'count' => 6
        );

		$articleManager = RexFactory::manager('article');
        $articleManager->getByWhere('1=1 ORDER BY `id` DESC LIMIT '.$aParams['count'].'');
		RexDisplay::assign($aParams['saveto'], $articleManager->getCollection());
	}


}