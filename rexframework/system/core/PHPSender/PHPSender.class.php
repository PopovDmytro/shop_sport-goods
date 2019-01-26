<?php

class PHPSender {
    
    protected static $project = '19';
    protected static $password = 'be98b894b1b6ae3266de1e10522a94c0';
    protected static $server_url = 'http://sender.illusix.com/';
    protected static $isTranslitContent = false;
    protected static $pathToLogFile = 'htdocs/files/logs/sms';
    
    /**
     * @param bool $number
     * @param string $message
     * @return bool|mixed|object|string
     */
    public static function sendSms($number = false, $message = '')
    {
        if (!$message || !$number || strlen(trim($message)) < 1) {
            return 'One or more parametrs are missing in request';
        }
        if (is_array($number)) {
            $number = implode(';', $number);
        }
        $resault = [];
        $resault['status'] =  static::sendSmsByAtompark(array(
                'nmb' => $number,
                'msg' => $message)
        );
        if($resault['status']){
            $resault['message'] = $message;
        }
        return (object)$resault;
        // old
        /*return static::_getAnswerCurl(
            static::$server_url,
            array(
                'prj' => static::$project,
                'psw' => static::$password,
                'nmb' => $number,
                'msg' => $message)
        );*/
    }
    /**
     * @param $number
     * @param $count
     * @return bool|mixed|object
     */
    public static function sendValidationCode($number, $count)
    {
        $message = static::randomNumber($count);
        $resault = [];
        $resault['status'] =  static::sendSmsByAtompark(
            array(
                'nmb' => $number,
                'msg' => $message)
        );
        if($resault['status']){
            $resault['code'] = $message;
        }
        return (object)$resault;
        // Old
        /*return static::_getAnswerCurl(
            static::$server_url,
            array(
                'prj' => static::$project,
                'psw' => static::$password,
                'nmb' => $number, 'grc' => 1,
                'cnt' => $count)
        );*/
    }
    /**
     * @param $number
     * @return bool|mixed
     */
    public static function validateNumber($number)
    {
        if( static::_validateNumber($number) ){
            return $number;
        }
        return false;
    }
    /**
     * Old
     * @param $url
     * @param $fields_post
     * @return bool|mixed
     */
    protected static function _getAnswerCurl($url, $fields_post)
    {
        if(!isset($url) || !isset($fields_post)) {
            return false;
        }
        foreach ($fields_post as $key => $value) {
            $params[] = $key.'='.urlencode($value);
        }
        $curl_post = implode('&', $params);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $curl_post);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_REFERER, $_SERVER['SERVER_NAME']);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        //return $res;
        return json_decode($res);
    }
    /**
     * @param array $massageData
     * @return bool
     */
    protected static function sendSmsByAtompark(array $massageData){
        if (!$massageData) {
            return false;
        }
        $logMassageData = '. Massage data: ' . json_encode($massageData, JSON_UNESCAPED_UNICODE);
        $src = static::createAtomparkXML($massageData);
        $ch = curl_init();
        $chOptions = array(
            CURLOPT_URL => 'http://api.atompark.com/members/sms/xml.php',
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_POSTFIELDS => array('XML' => $src),
        );
        curl_setopt_array($ch, $chOptions);
        $result = curl_exec($ch);
        curl_close($ch);
        if (!$result) {
            static::logAtompark('Curl error: ' . curl_error($ch) . $logMassageData . PHP_EOL);
            return false;
        }
        $resultsXML = [
            '-1' => 'Неправильный логин и/или пароль',
            '-2' => 'Неправильный формат XML',
            '-3' => 'Недостаточно кредитов на аккаунте пользователя',
            '-4' => 'Нет верных номеров получателей',
            '-7' => 'Ошибка в имени отправителя'
        ];
        try{
            $response = new SimpleXMLElement($result);
            $status = (string) $response->status;
        } catch (Error $e) {
            static::logAtompark ('Atompark XML error: ' . $e->getMessage() . $logMassageData . PHP_EOL);
            return false;
        } catch (Exception $e) {
            static::logAtompark ('Atompark XML exception: ' . $e->getMessage() . $logMassageData  . PHP_EOL);
            return false;
        }
        if(isset($resultsXML[$status])){
            static::logAtompark ('Atompark error: ' . $resultsXML[$status] . $logMassageData . PHP_EOL);
            return false;
        }
        static::logAtompark('SMS send' . $logMassageData . PHP_EOL);
        return $status > 0;
    }
    /**
     * @param string $msg
     * @return void
     */
    protected static function logAtompark($msg)
    {
        if(!is_dir(REX_ROOT . static::$pathToLogFile)){
            mkdir(REX_ROOT . static::$pathToLogFile, 0755, true);
        }
        file_put_contents(REX_ROOT . static::$pathToLogFile . '/log.txt', '['.date('Y-m-d H:i:s') .'] '.$msg, FILE_APPEND);
    }
    /**
     * @param array $massageData
     * @return string
     */
    protected static function createAtomparkXML(array $massageData)
    {
        $accesses = self::getLoginPasswordAtompark();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <SMS>
                        <operations>
                            <operation>SEND</operation>
                        </operations>
                        <authentification>
                            <username>' . $accesses['login'] . '</username>
                            <password>' . $accesses['password'] . '</password>
                        </authentification>
                        <message>
                            <sender>' . $accesses['brand_name'] . '</sender>
                            <text>' . $massageData['msg'] . '</text>
                        </message>';
        if (strpos($massageData['nmb'], ';')) {
            $numbers = explode(';', $massageData['nmb']);
            $xml .= '<numbers>';
            foreach ($numbers as $number) {
                $xml .= '<number>' . $number . '</number>';
            }
            $xml .= '</numbers>';
        } else {
            $xml .= '<numbers>
                        <number>' . $massageData['nmb'] . '</number>
                    </numbers>';
        }
        $xml .= '</SMS>';
        return $xml;
    }
    /**
     * @param int $length
     * @return string
     */
    protected static function randomNumber($length = 6)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }
    /**
     * @return array
     */
    protected static function getLoginPasswordAtompark(){
        $data = [];
        $fail = false;
        try{
            $data['login'] = RexConfig::get('Atompark', 'login');
            $data['password'] = RexConfig::get('Atompark', 'password');
            $data['brand_name'] = RexConfig::get('Atompark', 'brand_name');
        } catch (Exception $e){
            static::logAtompark('Config exception' . $e->getMessage() . PHP_EOL);
            $fail = true;
        } catch (Error $e){
            static::logAtompark('Config error' . $e->getMessage() . PHP_EOL);
            $fail = true;
        }
        if ($fail){
            $data['login'] = 'null';
            $data['password'] = 'null';
            $data['brand_name'] = 'null';
        }
        return $data;
    }
    /**
     * @param $numberUnval
     * @return bool
     */
    private static function _validateNumber(&$numberUnval)
    {
        $numberUnval = preg_replace('/\D/', '', $numberUnval);
        if (strlen($numberUnval) < 9 || strlen($numberUnval) > 12) {
            return false;
        }
        $countryCodes = array('380\d{9}', '7\d{10}', '375\d{9}');
        foreach ($countryCodes as $regExp) {
            if (preg_match('#'.$regExp.'#is', $numberUnval)) {
                $numberUnval = '+'.$numberUnval;
                return true;
            }
        }
        $numberUnval = '+380'.substr($numberUnval, -9);
        return true;
    }
}