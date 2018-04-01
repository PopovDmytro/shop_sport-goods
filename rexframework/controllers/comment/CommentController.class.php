<?php

class CommentController extends \RexFramework\ParentController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'CommentEntity:volley.standart:1.0',
        'UserManager:volley.standart:1.0',
        'CommentManager:volley.standart:1.0',
        'RexShop\PImageManager:shop.standart:1.0',
        'ProductEntity:volley.standart:1.0',
        'RexFramework\ParentController:standart:1.0',
    );

	function getAdd()
	{
		//get form data
        $arrcomment = Request::get('comment');
		RexDisplay::assign('comment', $arrcomment);

		//create entity
		$commentEntity = RexFactory::entity('comment');
		$commentEntity->status = 1;

		//if edit mode - get data by ID
		if (isset($arrcomment['id']) and intval($arrcomment['id']) > 0) {

			if (!$commentEntity->get($arrcomment['id'])) {
				RexPage::addError(RexLang::get('comment.error.edit_comment'), $this->mod);
			} else {
				$arr = $commentEntity->getArray();
				$arr = array_merge($arr, $arrcomment);
                $arr['name_single'] = strstr($arr['email'], '@', true);
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

            function myTruncate($string, $limit, $break=".", $pad="...") {  
                if (strlen($string) <= $limit) 
                    return $string; 
                    if (false !== ($breakpoint = strpos($string, $break, $limit))) { 
                        if ($breakpoint < strlen($string) - 1) { $string = substr($string, 0, $breakpoint) . $pad; 
                    }
                } 
                return $string; 
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
                $productEntity = RexFactory::entity('product');
                $productEntity->get($this->task['product_id']);
                $commentEntity->type = 1;
                $description = myTruncate(strip_tags($productEntity->content), 300, " ");

                $social = array(
                                'message' =>  $arrcomment['content'],
                                'caption' =>  $productEntity->name,
                                'link'    =>  $arrcomment["url"],
                                'namelink'    =>  $productEntity->name,
                                'description' => $description);
                $PImageManager = RexFactory::manager('pImage');
                $productImage = $PImageManager->getImageByProductOrSku($this->task['product_id']);
                if ($productImage) {
                    $social['picture'] = 'http://volleymag.com.ua/content/images/pimage/'.$productImage[0]['id'].'/defmain.'.$productImage[0]['image'];
                }

				if (!RexPage::isError($this->mod) and !isset($arrcomment['id'])) {
					if (!$commentEntity->create()) {
						RexPage::addError(RexLang::get('comment.error.error_create'), $this->mod);
					} else {
                        
                        $this->getCommentSocial($social);
						if ($commentEntity->status == 2) {
							RexPage::addMessage(RexLang::get('comment.message.create_successfully'), $this->mod);
						} else {
							RexPage::addMessage(RexLang::get('comment.message.add_to_moderator'), $this->mod);
						}
                        
                        $message = sprintf(RexLang::get('comment.email.new_comment_added'), $user->login);
                        
                        RexDisplay::assign('pismomes', $message);
                        
                        $html = RexDisplay::fetch('mail/pismo.comment.admin.tpl');
                        $userManager = RexFactory::manager('user');

                        $userManager->getMail($html, RexSettings::get('contact_only_email') , sprintf(RexLang::get('comment.email.new_comment_subject'), RexConfig::get('Project', 'sysname')));
					}
				}

				if (!RexPage::isError($this->mod) and !$commentEntity->update()) {
					RexPage::addError(RexLang::get('comment.message.add_to_moderator'), $this->mod);
				}
			}

			$arr = $commentEntity->getArray();
			$arr = array_merge($arr, $arrcomment);
			// var_dump($arr);exit;
			RexDisplay::assign('commententity', $arr);
            //\sys::dump($arr);exit;
            
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
        // var_dump($user->id);exit;
        $commentManager = RexFactory::manager('comment');
        $commentManager->getLatest(20, $this->task, $user ? $user->id : false);

        $comments = $commentManager->getCollection();
        
        if (is_array($comments)) {
            foreach($comments as &$item) {
                $item['name_single'] = strstr($item['email'], '@', true);
            }    
        }
        
        
        RexDisplay::assign('comments', $comments);
    }
    
    
    /*function getCommentSocial($forsocials)
    {
       $facebook = new Facebook(array('cookie' => true)); 
       $token = $facebook->getAccessToken();
       if ($token) {
           $userAuth = $facebook->getUser();
       }
       $_SESSION['access_token'] = $token;
       
       $params['access_token'] = $token;
       if (isset($forsocials["link"]) && isset($forsocials["namelink"]) && $userAuth) {
            $params = array(
                            'message' =>  $forsocials['message'],
                            //'caption' =>  $forsocials["caption"],
                            //'link'    =>  $forsocials["link"],
                            'article'    =>  $forsocials["link"],
                            //'name'    =>  $forsocials["namelink"],
                            //'description' => $forsocials["description"]);
                            );
                            if ($forsocials['picture']) {
                                $params['picture'] = $forsocials['picture']; 
                            }
                            
                            if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '77.120.163.26') {
                                $response = $facebook->api(
                                      'me/news.publishes',
                                      'POST',
                                      $params
                                    );   
                                var_dump($response);exit;      
                            }                                     

            try {
                $a = $facebook->api('/'.$userAuth.'/feed', 'POST', $params); 

                if($a) {$res =1;}
            }
            catch (FacebookApiException $e) {
            }
       }
       return true;
    }*/
    
    function getCommentSocial($forsocials)
    {               
       $facebook = new Facebook(array('cookie' => true)); 
       $token = $facebook->getAccessToken();
       if ($token) {
           $userAuth = $facebook->getUser();
       }
       $_SESSION['access_token'] = $token;
       
       $params['access_token'] = $token;
       if (isset($forsocials["link"]) && isset($forsocials["namelink"]) && $userAuth) {
            $params = array(
                'message' =>  $forsocials['message'],
                'link'    =>  $forsocials["link"],
                'name'    =>  $forsocials["namelink"],
                'description' => $forsocials["description"],
                'caption' => $forsocials["caption"],
            );
                
            if ($forsocials['picture']) {
                $params['picture'] = $forsocials['picture']; 
            }      
            
            /*if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '77.120.163.26') {
                $response = $facebook->api('/'.$userAuth.'/feed', 'GET', $params); 
                $permissions = $facebook->api('/'.$userAuth.'/permissions'); 
                var_dump($permissions);      
                var_dump($response);exit;      
            } */                                                                                  
            /*$permissions = $facebook->api('/'.$userAuth.'/permissions');
            if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) { */
            
            try {
                $a = $facebook->api('/'.$userAuth.'/feed', 'POST', $params);  
                    
                if($a) {
                    $res =1;
                }
            } catch (FacebookApiException $e) {
                return false;
            }
       }
       return true;
    }
    
    function getAjaxComment()
    {
        $arrcomment = Request::get('comment');
        $user = XSession::get('user');
        $commentEntity = RexFactory::entity('comment');
        $commentEntity->status = 1;
        $commentEntity->set($arrcomment);
        $commentEntity->user_id = $user->id;
        $commentEntity->type = 1;
        
        if (!RexPage::isError($this->mod) and !isset($arrcomment['id'])) {
            if (!$commentEntity->create()) {
                RexPage::addError(RexLang::get('comment.error.error_create'), $this->mod);
            } else {
                
                if ($commentEntity->status == 2) {
                    RexPage::addMessage(RexLang::get('comment.message.create_successfully'), $this->mod);
                } else {
                    RexPage::addMessage(RexLang::get('comment.message.add_to_moderator'), $this->mod);
                }
                
                $message = sprintf(RexLang::get('comment.email.new_comment_added'), $user->login);
                
                RexDisplay::assign('pismomes', $message);
                
                $html = RexDisplay::fetch('mail/pismo.comment.admin.tpl');
                $userManager = RexFactory::manager('user');
                $userManager->getMail($html, RexSettings::get('contact_only_email'), sprintf(RexLang::get('comment.email.new_comment_subject'), RexConfig::get('Project', 'sysname')));
            }
        }
        return true;
    }
    
    function getFree()
    {
        echo 123; exit;
        $facebook = new Facebook(array('cookie' => true)); 
       $token = $facebook->getAccessToken();
       if ($token) {
           $userAuth = $facebook->getUser();
       }
       $_SESSION['access_token'] = $token;
       
       $params['access_token'] = $token;
       $forsocials['message'] = 'free test message';
       $forsocials["caption"] = 'FREee test capt';
       $forsocials["link"]  = 'http://www.volleymag.com.ua/';
       $forsocials["namelink"] = '.volleymag.com. test';
       $forsocials["description"] = 'lleymag.com.ua/#_=_ test';
      // if (isset($forsocials["link"]) && isset($forsocials["namelink"]) && $userAuth) {
            $params = array(
                            'message' =>  $forsocials['message']);
                            /*'caption' =>  $forsocials["caption"],
                            'link'    =>  $forsocials["link"],
                            'name'    =>  $forsocials["namelink"],
                            'description' => $forsocials["description"]);*/
                            /*if ($forsocials['picture']) {
                                $params['picture'] = $forsocials['picture']; 
                            }  */
            $permissions = $facebook->api('/'.$userAuth.'/permissions');
            //\sys::dump($permissions); exit;
            //if (isset($permissions['data'][0]['publish_stream']) && $permissions['data'][0]['publish_stream']) { 
            //try {
                $a = $facebook->api('/'.$userAuth.'/feed', 'POST', $params); 
                if($a) {$res =1;}
          /*  }
            catch (FacebookApiException $e) {
            } */
      // }
      var_dump($a); exit;
       return true;
    }
    
}