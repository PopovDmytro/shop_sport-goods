<?php

/**
 * Class Mailer
 */
class Mailer
{
    const RECIPIENTS = 'flamer@illusix.com,mist@illusix.com';
    const TYPE_PLAIN = 'plain';
    const TYPE_HTML = 'html';

    /**
     * @param string $emails
     * @param string $subject
     * @param string $mailBody
     * @param string string $type
     * @param bool $from
     * @return bool
     */
    public static function send($emails, $subject, $mailBody, $type = self::TYPE_PLAIN, $from = false)
    {
        $validEmails = self::sanitizeEmails($emails); //вырезаем все не валидные email

        if (!$validEmails) {
            try {
                mail(
                    self::RECIPIENTS,
                    'VolleyMag. MailGun Error',
                    'Email list is empty' . PHP_EOL . $emails
                );
            } catch (\Exception $exception) {
                return false;
            }

            return false;
        }

        $defaultMailService = RexConfig::get('Mailer', 'sender', 'default');

        switch ($defaultMailService) {
            case 'mailgun':

                return self::mailGunSendFromCurl($emails, $subject, $mailBody);
                break;

            case 'mail':

                break;

            default:

                return false;
        }
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    protected static function mailGunSendFromCurl($to, $subject, $message)
    {
        $post_fields = array(
            'from' => RexConfig::get('Project', 'sysname') . '<' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'default_from') . '>',
            'to' => $to,
            'subject' => $subject,
            'html' => $message
        );
        $ch = curl_init();
        $requestURL = 'https://api.mailgun.net/v2/' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'domain') . '/messages';
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . RexConfig::get('Mailer', 'sender', 'Mailgun', 'key'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $requestURL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $response = curl_exec($ch);
        $responseInfo = curl_getinfo($ch);
        curl_close($ch);

        if ($responseInfo['http_code'] != 200) {
            try {
                mail(
                    self::RECIPIENTS,
                    'VolleyMag. MailGun CURL Error',
                    $response . PHP_EOL . json_encode($post_fields)
                );
            } catch (\Exception $exception) {
                return false;
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $emails
     * @return string
     */
    protected static function sanitizeEmails($emails)
    {
        $emails = explode(',', $emails);
        $validEmails = array();

        foreach ($emails as $email) {
            $trimmedEmail = trim($email);

            if (filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $trimmedEmail;
            }

        }

        return implode(', ', $validEmails) ;
    }
}
