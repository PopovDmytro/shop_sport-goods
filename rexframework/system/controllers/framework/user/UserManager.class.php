<?php
namespace RexFramework;

use \XDatabase as XDatabase;
use \RexConfig as RexConfig;
use \RexSettings as RexSettings;

/**
* Class UserManager
*
* Manager of User
*
* @author   Fatal
* @access   public
* @created  Wed Jan 24 12:05:33 EET 2008
*/
class UserManager extends DBManager
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\UserEntity:standart:1.0'
    );

    function __construct()
    {
        parent::__construct('user', 'id');
    }

    function getLatest($aCount=2, $all=false)
    {
        if (!$all) {
            if (!$aCount or $aCount < 1) {
                $aCount = 2;
            }

            $sql = 'SELECT * FROM `user` WHERE `avatar` <> "default" ORDER BY UNIX_TIMESTAMP(`date_create`) DESC LIMIT ?';
            $res = XDatabase::getAll($sql, array($aCount));

            if (!$res or sizeof($res) < 1) {
                $this->_collection = false;
            } else {
                $this->_collection = $res;
            }
        } else {
            $sql = 'SELECT * FROM `user` WHERE `avatar` <> "default" ORDER BY UNIX_TIMESTAMP(`date_create`) DESC';
            $res = XDatabase::getAll($sql);

            if (!$res or sizeof($res) < 1) {
                $this->_collection = false;
            } else {
                $this->_collection = $res;
            }
        }
    }

    function getData()
    {   
        if (isset($_COOKIE['user_registration'])) {
            $userArray = base64_decode($_COOKIE['user_registration']); 
            $userArray = gzuncompress($userArray);
            $userArray = unserialize($userArray);
            return $userArray;
        }
    }

    function setData($userArray)
    {
        $userArray = serialize($userArray);
        $userArray = gzcompress($userArray);
        $userArray = base64_encode($userArray); 
        setcookie('user_registration', $userArray, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        return true;
    }

    function deleteData() 
    {   
        setcookie('user_registration', '', time() - 3600, '/',RexConfig::get('Project', 'cookie_domain'));
        return true;
    }
    
    function getMail($html, $mail, $subject)
    {
        $boundary = md5(uniqid(mt_rand(), 1));
        
        $headers = "From: ".RexSettings::get('site_slogan')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: multipart/related; boundary=\"$boundary\"\r\n";
        $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        

        $body = $this->_getCidPictures($boundary, $html);

        mail($mail, 
            '=?UTF-8?B?'.base64_encode($subject).'?=',
            $body,
            $headers
        );
    }
    
    
    function _getCidPictures($boundary, $html)
    {
        $body = "--$boundary\r\n";
        $body .= "Content-type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\n";
        $body_picture = array();
        
        preg_match_all('#src\s*=\s*"\s*\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))\s*"#', $html, $result, PREG_PATTERN_ORDER);
        
        if ($result && isset($result[0]) && $result[0]) {
            foreach ($result[0] as $value) {
                $sources[$value] = 1;        
            }        
            
            foreach ($sources as $url => &$value) {
                $replace = $this->_getCIDFromPicture($url, $boundary, $body_picture);
                $html = str_replace($url, $this->_getCIDFromPicture($url, $boundary, $body_picture), $html);
            }
        }
        
        $body .= $html;
        $body .= implode('', $body_picture);
        $body .= "--$boundary--\n\n";
        
        return $body;    
    }
    
    function _getCIDFromPicture($url, $boundary, &$body_picture)
    {
        preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $url, $result);
        
        $file_path = substr(SITE_ROOT, 0, -1).parse_url($result[0], PHP_URL_PATH);
        $iterator = count($body_picture)+1;
        
        $body = "\n\n--$boundary\n";
        $body .= "Content-Type: application/octet-stream; name=\"".basename($file_path)."\"\n";
        $body .= "Content-Transfer-Encoding: base64\n";
        $body .= "Content-Disposition: attachment\n";
        $body .= "Content-ID: <$boundary"."_$iterator>\n\n";
        
        $f = fopen($file_path, 'rb');
        $body .= chunk_split(base64_encode(fread($f, filesize($file_path))));
        fclose($f);
        $body_picture[] = $body;
        
        return 'src="cid:'.$boundary.'_'.$iterator.'"';        
    }
    function getAdress($param)
    {
        $sql = 'SELECT 
                  c.`name`,
                  f.`name` 
                FROM
                  city c 
                  LEFT JOIN `user` u 
                    ON u.`city` = c.`id` 
                  LEFT JOIN fillials f 
                    ON f.`id` = u.`fillials` 
                WHERE u.`id` ='.$param;
        $res = XDatabase::getAll($sql);
    }
}