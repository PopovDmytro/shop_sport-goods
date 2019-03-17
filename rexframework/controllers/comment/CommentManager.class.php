<?php
class CommentManager extends \RexShop\CommentManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\CommentEntity:shop.standart:1.0',
        'RexShop\CommentManager:shop.standart:1.0',
    );

	function __construct()
	{
		parent::__construct('comment', 'id');
	}
	function getAbout($user_id = null){

	    if($user_id) {
            $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`,
                  c.`status`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`status` = 0 
                    AND c.`user_id` ='.$user_id;
            return XDatabase::getAll($sql);
        } else {
            $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`product_id` = 0
                  AND c.`status` = 2
                ORDER BY c.`date_create` DESC';
            return XDatabase::getAll($sql);
        }
    }

	function getLatest($aCount=2, $aProductID=false, $aUser=false)
	{
		if (!$aCount or $aCount < 1) {
			$aCount = 10;
		}

		if (!$aProductID or ($aProductID < 1 and $aProductID != 'all')) {
			return false;
		}

		if (!$aUser) {

			if ($aProductID == 'all') {
				$sql = 'SELECT c.*,c.date_create as date_comment, u.* FROM `comment` AS c, `user` AS u WHERE c.`user_id` = u.`id` AND c.`status` = 2 ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aCount));
			} else {
				$sql = 'SELECT c.*,c.date_create as date_comment, u.* FROM `comment` AS c, `user` AS u WHERE c.`product_id` = ? AND c.`user_id` = u.`id` AND c.`status` = 2 ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aProductID, $aCount));
			}
		} else {

			if ($aProductID == 'all') {
				$sql = 'SELECT c.*,c.date_create as date_comment, u.* FROM `comment` AS c, `user` AS u WHERE c.`user_id` = u.`id` AND (c.`status` = 2 OR (c.`status` = 1 AND c.`user_id` = ?)) ORDER BY c.`date_create` DESC LIMIT ?';
				$res = XDatabase::getAll($sql, array($aUser, $aCount));
			} else {
				/*$sql = 'SELECT c.*, u.* FROM `comment` AS c, `user` AS u WHERE c.`product_id` = ? AND c.`user_id` = u.`id` AND (c.`status` = 2 OR (c.`status` = 1 AND c.`user_id` = ?)) ORDER BY c.`date_create` DESC LIMIT ?';*/
                $sql = 'SELECT c.*,c.date_create as date_comment, u.* FROM `comment` AS c, `user` AS u WHERE c.`product_id` = ? AND c.`user_id` = u.`id` AND (c.`status` = 2 OR (c.`status` = 1 AND c.`user_id` = ?)) ORDER BY c.`date_create` DESC LIMIT ?';
                $res = XDatabase::getAll($sql, array($aProductID, $aUser, $aCount));
			}
		}

		if (!$res or sizeof($res) < 1) {
			$this->_collection = false;
		} else {
			$this->_collection = $res;
		}
	}
    
    function getArticleComm($article_id){
        $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`,
                  c.`status`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`status` = 2 AND c.`article_id` ='.$article_id;
        return XDatabase::getAll($sql);
    }
    
    function getNewsComm($news_id){
        $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`,
                  c.`status`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`status` = 2 AND c.`news_id` ='.$news_id;
        return XDatabase::getAll($sql);
    }
    
    function getArticleCommUser($user_id, $article_id){
        $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`,
                  c.`status`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`status` = 0 
                    AND c.`user_id` ='.$user_id.'
                    AND c.`article_id` ='.$article_id;
        return XDatabase::getAll($sql);
    }
    
    function getNewsCommUser($user_id, $news_id){
        $sql = 'SELECT
                  c.`user_id`,
                  u.`login`,
                  u.`name`,
                  c.`content`,
                  c.`date_create`,
                  c.`status`
                FROM
                  `comment` c
                  LEFT JOIN `user` u
                    ON c.`user_id` = u.`id`
                WHERE c.`status` = 0 
                    AND c.`user_id` ='.$user_id.'
                    AND c.`news_id` ='.$news_id;
        return XDatabase::getAll($sql);
    }
    
    function getCatalog($product_id){
        $sql = 'SELECT 
                  p.`alias` 
                FROM
                  pcatalog p 
                  LEFT JOIN product pr 
                    ON pr.`category_id` = p.`id` 
                WHERE pr.`id` ='.$product_id;
        return XDatabase::getOne($sql);
    }
    
    function getArticle($article_id){
        $sql = 'SELECT 
                  a.`alias` 
                FROM
                  article a 
                  LEFT JOIN `comment` c 
                    ON c.`article_id` = a.`id` 
                WHERE a.`id` ='.$article_id;
        return XDatabase::getOne($sql);
    }
    
    function getNews($news_id){
        $sql = 'SELECT 
                  n.`alias` 
                FROM
                  news n 
                  LEFT JOIN `comment` c 
                    ON c.`article_id` = n.`id` 
                WHERE n.`id` = '.$news_id;
        return XDatabase::getOne($sql);
    }
}