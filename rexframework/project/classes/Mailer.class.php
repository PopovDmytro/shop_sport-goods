<?php

class Mailer
{
    public static function send($emailList, $subject, $mailBody, $type = 'plain', $from = false)
    {
        $defaultMailService = RexConfig::get('Mailer', 'sender', 'default');

        switch ($defaultMailService) {
            case 'mailgun':
                return self::mailGunSendFromCurl($emailList, $subject, $mailBody);
                break;
            case 'mail':

                break;
        }
    }

    public static function mailGunSendFromCurl($to, $subject, $message)
    {
        $ch     = curl_init();
        $requestURL = 'https://api.mailgun.net/v2/' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'domain') . '/messages';
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'key'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $requestURL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'from'      => RexConfig::get('Project', 'sysname') . '<' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'default_from') . '>',
            'to'        => $to,
            'subject'   => $subject,
            'html'      => $message
        ));

        $response = json_decode(curl_exec($ch));

        $responseInfo = curl_getinfo($ch);

        if ($responseInfo['http_code'] != 200) {
            mail('volkov@illusix.com,serge@illusix.com,mist@illusix.com', 'VolleyMag. MailGun CURL Error', json_encode($response));
            return false;
        }

        curl_close($ch);

        return true;
    }
}