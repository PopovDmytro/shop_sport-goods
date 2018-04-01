<?php

class HomeAdminController extends \RexFramework\HomeAdminController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexFramework\HomeAdminController:standart:1.0',
        'RexFramework\UserManager:standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0',
        'RexShop\ProdColorOrderEntity:shop.standart:1.0',
        'RexShop\Attr2ProdEntity:shop.standart:1.0',
    );

    function getMailing() 
    {
        RexDisplay::assign('userphone', RexSettings::get('contact_phone_code').RexSettings::get('contact_phone'));
    }

    public function getSendMail()
    {
        RexResponse::init();

        $mailing = Request::get('mailing', false);
        if ($mailing) {
            if (!$mailing['content'] || mb_strlen($mailing['content'], 'UTF-8') <= 5) {
                RexResponse::error('Текст сообщения должен быть не менее 5 символов');
            }

            $urlToAdd = "http://" . $_SERVER['SERVER_NAME'];
            $mailing['content'] = str_replace('src="/', 'src="' . $urlToAdd . '/', $mailing['content']);

            XDatabase::query('INSERT INTO sender (sms_send, sms_content, content, role, order_status) VALUES (' . (isset($mailing['sms_send']) ? $mailing['sms_send'] : 0) . ', "' . (strlen($mailing['sms_content']) > 0 ? addslashes($mailing['sms_content']) : '') . '", "' . addslashes($mailing['content']) . '", "' . $mailing['role'] . '", ' . ($mailing['status'] ? $mailing['status'] : 0) . ')');

            $senderId = XDatabase::getOne('SELECT id FROM sender WHERE content = "' . addslashes($mailing['content']) . '" AND role = "' . $mailing['role'] . '" AND status = 0 LIMIT 1');
            if (!$senderId) {
                RexResponse::error('Произошла ошибка! Попробуйте ещё раз.');
            }

            RexResponse::response(['status' => 'success', 'progress' => 'Рассылка создана']);
        }

        RexResponse::error('Заполните все поля!');
    }

    public function getMailingHandler()
    {
        $senders = XDatabase::getAll('SELECT * FROM sender WHERE status = 0 AND content IS NOT NULL');
        if (empty($senders)) {
            exit;
        }

        $senderIds = [];
        foreach ($senders as $sender) {
            $senderIds[] = $sender['id'];
        }
        XDatabase::query('UPDATE sender SET status = 2 WHERE id IN (' . implode(',', $senderIds) . ')');

        foreach ($senders as $sender) {
            $userManager = RexFactory::manager('user');
            if ($sender['role'] && $sender['role'] != '') {
                if ($sender['order_status'] && $sender['order_status'] != '') {
                    $userManager->getUsersByOrderStatus($sender['order_status']);
                } else {
                    $userManager->getByWhere('role = "' . $sender['role'] . '"');
                }
            } else {
                $userManager->get();
            }

            $userList = $userManager->getCollection('object');
            if ($sender['sms_send'] && strlen($sender['sms_content']) > 0) {
                foreach ($userList as $user) {
                    PHPSender::sendSms($user->phone, $sender['sms_content']);
                }

                XDatabase::query('UPDATE sender SET status = 1 WHERE id = ' . $sender['id']);
                continue;
            }

            $recipients = [];
            foreach ($userList as $user) {
                if (!$user->delivery) {
                    continue;
                }

                $recipients[] = [
                    'email' => $user->email,
                ];
            }

            if ($sender['role'] and $sender['role'] == 'subscriber') {
                $subscriberList = XDatabase::getCol( "SELECT subscriber_email FROM subscribers" );
                if (!empty($subscriberList)) {
                    foreach ($subscriberList as $subscriberEmail) {
                        $recipients[] = [
                            'email' => $subscriberEmail,
                        ];
                    }
                }
            }

            $sentMails = 0;
            if (!empty($recipients)) {
                XDatabase::query('UPDATE sender SET all_count = ' . count($recipients) . ' WHERE id = ' . $sender['id']);

                RexDisplay::assign('content', $sender['content']);
                foreach ($recipients as $recipient) {
                    if ($sentMails % 10 == 0) {
                        XDatabase::query('UPDATE sender SET current_count = ' . $sentMails . ' WHERE id = ' . $sender['id']);
                    }

                    $html = RexDisplay::fetch('home/mailing_letter.tpl');
                    $userManager->getMail($html, $recipient['email'], "Новости сайта VolleyMAG.com.ua");

                    $sentMails++;
                    sleep(1);
                }

                XDatabase::query('UPDATE sender SET current_count = ' . $sentMails . ' WHERE id = ' . $sender['id']);
            }

            XDatabase::query('UPDATE sender SET status = 1 WHERE id = ' . $sender['id']);
        }

        exit;
    }

    public function getMailProgress()
    {
        RexResponse::init();

        $senderEntity = XDatabase::getRow('SELECT * FROM sender WHERE id = ' . $this->task);
        if (!isset($senderEntity['id'])) {
            exit;
        }

        if ($senderEntity['status'] != 1) {
            RexResponse::response(['status' => 'wait', 'progress' => $senderEntity['current_count'] . '/' . $senderEntity['all_count']]);
        } else {
            RexResponse::response(['status' => 'done', 'progress' => 'Рассылка завершена.']);
        }
    }

    function getGenerateMainColorImages()
    {
        exit;
        $pimages = XDatabase::getAll('SELECT id, product_id, attribute_id FROM pimage GROUP BY attribute_id, product_id ORDER BY sorder');
        foreach ($pimages as $img) {
            $colorAttr = RexFactory::entity('attr2Prod');
            $colorAttr->get($img['attribute_id']);

            $attr = RexFactory::entity('attribute');
            $attr->get($colorAttr->value);
            if ($attr->pid != 1) {
                continue;
            }

            $productColorOrderModer = RexFactory::entity('prodColorOrder');
            $productColorOrderModer->getByFields(array(
                'product_id'    => $img['product_id'],
                'attribute_id'  => $img['attribute_id']
            ));

            $productColorOrderModer->product_id     = $img['product_id'];
            $productColorOrderModer->attribute_id   = $attr->id;
            if (!$productColorOrderModer->id) {
                $productColorOrderModer->setSorder();
                $productColorOrderModer->create();
            }
            /*
             * обновление главных изабражений цветов товара
             * $pimageModel = RexFactory::entity('pImage');
            $pimageModel->get($id);
            if ($pimageModel->id) {
                $pimageModel->main = 1;
                $pimageModel->update();
            }*/
        }
    }

    function getTestSms()
    {
        RexResponse::init();
        $text = Request::get('text', false);    
        $phone = Request::get('phone', false);
        PHPSender::sendSms($phone, $text);
        RexResponse::response('ok');    
    }

    function getProgressBar()
    {
        $resp = XDatabase::getRow('SELECT `current_count`, `all_count` FROM `sender` WHERE `id`=1');
        if (!$resp['current_count'] || !$resp['all_count'] || $resp['current_count'] > $resp['all_count']) {
            $sendProgress = 0;
        } else {
            $sendProgress = $resp['current_count'] / $resp['all_count'] * 100;
        }
        RexResponse::init();
        RexResponse::response($sendProgress);
    }
}