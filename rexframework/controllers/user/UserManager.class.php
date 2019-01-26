<?php

class UserManager extends \RexFramework\UserManager
{
    static public $assemble = 'volley.standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'UserEntity:volley.standart:1.0',
        'RexShop\UserManager:shop.standart:1.0',
    );

    function __construct()
    {
        parent::__construct('user', 'id');
    }

    function _processFilter($key, $value)
    {
        if ($key === 'orders_sum') {

        }

        return false;
    }

    function getList($filters, $fields, $mod = false)
    {
        $order_dir = 'DESC';
        $page = 1;
        $inpage = 50;
        $sql = '1 ';
        $sql_join = ' LEFT JOIN rexorder o ON t.id = o.user_id AND o.status = 3 LEFT JOIN prod2order p2o 
    ON o.id = p2o.`order_id` ';

        $mod_arr = explode('\\', get_class($this));
        if (!$mod) {
            $mod = array_pop($mod_arr);
            if (substr($mod, -7) == 'Manager') {
                $mod = substr($mod, 0, -7);
            }
            $mod = lcfirst($mod);
        }

        $entity = RexFactory::entity($mod, false);
        $manager = RexFactory::manager($mod);
        $order_by = $manager->_uid;
        foreach ($filters as $key => $value) {
            $result = $manager->_processFilter($key, $value);

            if ($result !== false) {
                if (!is_array($result))
                    $sql .= $result;
                else {
                    $sql .= $result[0];
                    $sql_join .= $result[1];
                }
            } else {
                switch ($key) {
                    case 'page':
                        $page = $value;
                        break;
                    case 'inpage':
                        $inpage = $value;
                        break;
                    case 'order_by':
                        if ($value != '_orders_sum') {
                            $order_by = 't.' . $value;
                        } else {
                            $order_by = $value;
                        }
                        break;
                    case '_orders_sum':
                        break;
                    case 'order_dir':
                        $order_dir = $value;
                        break;
                    case 'search':
                        $value = trim($value);
                        if ($value) {
                            $ors = array();
                            if ($fields && sizeof($fields)) {
                                foreach ($fields as $field => $spec) {
                                    if ($field{0} != '_' && $field && intval($field) . '' != $field) {
                                        $ors[] = '`t`.`' . $field . '` LIKE "%' . addslashes($value) . '%"';
                                    }
                                }
                            } elseif ($entity) {
                                foreach ($entity as $field => $field_value) {
                                    if ($field{0} != '_') {
                                        $ors[] = '`t`.`' . $field . '` LIKE "%' . addslashes($value) . '%"';
                                    }
                                }
                            }
                            if ($ors) {
                                $sql .= ' AND (' . implode(' OR ', $ors) . ')';
                            }
                        }
                        break;
                    default:
                        if ($value || $value === 0 || $value === '0') {
                            $sql .= ' AND `t`.`' . $key . '` = "' . addslashes($value) . '"';
                        }
                }
            }
        }
        $sql_limit = ' ORDER BY ' . $order_by . ' ' . $order_dir . '
        LIMIT ' . ($page * $inpage - $inpage) . ', ' . $inpage . '  ;';

        if ($entity && is_subclass_of($entity, 'RexDBEntity') && !$sql_join) {
            $list = new RexDBList($mod);

            $list->getByWhere(str_replace('`t`.', '', $sql));
            $count = sizeof($list);
            $list->setOrderBy($order_by . ' ' . $order_dir);
            $list->setLimit(($page * $inpage - $inpage) . ', ' . $inpage);

            return array(
                0 => $list,
                1 => $count
            );
        }

        $sql = 'FROM ' . $manager->_table . ' AS `t` ' . $sql_join . ' WHERE ' . $sql;
        return array(
            0 => XDatabase::getAll('SELECT t.*, SUM(ROUND(FLOOR(p2o.price - p2o.price * p2o.discount / 100) * p2o.`count`- FLOOR((p2o.price - p2o.price * p2o.discount / 100)) * p2o.`count` * (o.sale/100))) AS _orders_sum ' . $sql . ' GROUP BY t.id' . $sql_limit),
            1 => XDatabase::getOne('SELECT COUNT(*) ' . $sql)
        );
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
        return Mailer::send($mail, $subject, $html);

        /*$boundary = md5(uniqid(mt_rand(), 1));

        $headers = "From: ".RexSettings::get('site_name')."\r";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: multipart/related; boundary=\"$boundary\"\r\n";
        $headers .= "Reply-To: ".RexConfig::get('Project', 'sysname')." <".RexConfig::get('Project', 'email_noreply').">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();


        $body = $this->_getCidPictures($boundary, $html);

        mail($mail,
            '=?UTF-8?B?'.base64_encode($subject).'?=',
            $body,
            $headers
        );*/
    }


    function _getCidPictures($boundary, $html)
    {
        $body = "--$boundary\r\n";
        $body .= "Content-type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\n";
        $body_picture = array();

        if (preg_match_all('#(https?\:\/\/www\.volleymag\.com\.ua){2}#', $html, $res)) {
            $html = preg_replace('#(https?\:\/\/www\.volleymag\.com\.ua){2}#', '${1}', $html);
        }

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
                  c.`name` AS city_name,
                  f.`name` AS fill_name
                FROM
                  city c
                  LEFT JOIN `user` u
                    ON u.`city` = c.`id`
                  LEFT JOIN fillials f
                    ON f.`id` = u.`fillials`
                WHERE u.`id` ='.$param;
        return XDatabase::getRow($sql);
    }
    
    function getBySearchAdmin($s)
    {
        $sql = 'SELECT 
              u.`id` as user_id,
              u.`name` as user_name,
              u.`lastname` as user_lastname,
              u.`email` as user_email,
              u.`phone` as user_phone
            FROM `user` u 
            WHERE u.`name` LIKE "%'.$s.'%" 
              OR u.`lastname` LIKE "%'.$s.'%" 
              OR u.`email` LIKE "%'.$s.'%"
              OR u.`phone` LIKE "%'.$s.'%" 
              OR CONCAT(u.`name`, " ", u.`lastname`) LIKE "%ban%" 
            ORDER BY u.`name`,
              u.`lastname` LIMIT 20';
        
        return XDatabase::getAll($sql);
    }
    function getByNameAndPhone($name,$phone) 
    {
        $sql= 'SELECT `id` FROM `user` WHERE `name` LIKE "%'.$name.'%" and `phone` = "'.$phone.'"';

        return XDatabase::getOne($sql);
    }
    
    function getNameByEmail($email) 
    {
        return XDatabase::getOne('SELECT `name` FROM `user` WHERE `email` LIKE "%'.$email.'%"');
    }

    function getUsersByOrderStatus($status)
    {
        $sql = 'SELECT
              DISTINCT u.`id`,
              u.`name`,
              u.`lastname`,
              u.`email`,
              u.`phone`
              FROM `user` u
              JOIN rexorder r
                  ON r.`user_id` = u.`id`
                  WHERE u.`role` = \'user\' AND r.`status` = ' . $status;

        $res = XDatabase::getAll($sql);

        if (PEAR::isError($res)) {
            $this->_error = $res;
            $this->_collection = array();
        } else {
            $this->_collection = $res;
        }
    }
}   
