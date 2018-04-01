<?php
class CommentAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'CommentEntity:volley.standart:1.0',
        'CommentManager:volley.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
    );

    protected function _getFields($fields)
    {
        return array(
            array('<b>ID</b>', array($this, '_DGId'), array('width' => 15)),
            array('<b>Ссылка</b>', array($this, '_DGLink'), array('width' => 40)),
            array('<b>Комментарий</b>', array($this, '_DGText'), array('width' => 250)),
            array('<b>Автор</b>', array($this, '_DGAuthor'), array('width' => 80)),
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

    function _DGComment($param)
    {
        switch ($param['record']['type']) {
            case 1 :
                $field = 'Отзыв о продукте';
                break;
            case 2 :
                $field = 'Отзыв о сайте';
                break;
            case 3 :
                $field = 'Отзыв на статью';
                break;
            case 4 :
                $field = 'Отзыв к новости';
                break;
        }
        return $field;
    }

	function _DGAuthor($param)
	{
		$userEntity = RexFactory::entity('user');
        try{
            $userEntity->get($param['record']['user_id']);
            return $userEntity->name;
        } catch (Exception $e) {
            return ' - (пользователь удален) ';
        }
	}

    function _DGLink($param)
    {
        if ($param['record']['type'] == 1) {
            $categoryAlias = $this->manager->getCatalog($param['record']['product_id']);
            return '<a href="http://www.volleymag.com.ua/product/'.$categoryAlias.'/'.$param['record']['product_id'].'.html" target="_blank">Продукт</a>';
        } elseif ($param['record']['type'] == 2) {
            return '<a href="http://www.volleymag.com.ua/about/" target="_blank">Отзыв</a>';
        } elseif ($param['record']['type'] == 3) {
            $articleAlias = $this->manager->getArticle($param['record']['article_id']);
            return '<a href="http://www.volleymag.com.ua/article/'.$articleAlias.'.html" target="_blank">Статья</a>';
        } elseif ($param['record']['type'] == 4) {
            $newsAlias = $this->manager->getNews($param['record']['news_id']);
            return '<a href="http://www.volleymag.com.ua/news/'.$newsAlias.'.html" target="_blank">Новость</a>';
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