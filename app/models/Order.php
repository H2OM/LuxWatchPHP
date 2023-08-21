<?php
    namespace app\models;

use shop\App;
use shop\base\Model;
    use shop\Db;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

    class Order extends AppModel {
        public static function saveOrder($data) {
            try {
                Db::beginTransaction();
                Db::getPreparedQuery("INSERT INTO `order` (user_id, currency, note) VALUES((SELECT id FROM `user` WHERE login=?), ?, ?)", [
                    ["VALUE"=>$data['user_id'], "INT"=>true, "PARAMVALUE"=>256],
                    ["VALUE"=>$_SESSION['cart.currency']['code'], "PARAMVALUE"=>3],
                    ["VALUE"=>$data['note'], "PARAMVALUE"=>256],
                ]);
                $order_id = Db::getQuery("SELECT LAST_INSERT_ID()", false, true);
                self::saveOrderProduct($order_id);
                Db::commitTransaction();
                return $order_id;
            } catch (\PDOException $e) {
                Db::rollbackTransaction();
                $_SESSION['error'] .= "  Something wrong with compliting order";
                redirect();
            }
        }
        public static function saveOrderProduct($order_id) {
            $sql_part = '';
            foreach($_SESSION['cart'] as $product_id=>$product) {
                $product_id = (int)$product_id;
                //НЕОБЕЗОПАСЕННО!! ЭЛЕМЕНТЫ СТРОКИ МОГУТ СОДЕРЖАТЬ ВРЕДОНОСННЫЙ КОД
                $sql_part .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
            }
            $sql_part = rtrim($sql_part,',');
            Db::getQuery("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
        }
        public static function mailOrder($order_id, $user_email) {
            $transport = (new EsmtpTransport(
                App::$app->getProperty('smtp_host'),
                App::$app->getProperty('smtp_port'),
                App::$app->getProperty('smtp_protocol')
            ))
                ->setUsername(App::$app->getProperty('smtp_login'))
                ->setPassword(App::$app->getProperty('smtp_password'));
            $mailer = new Mailer($transport);
            ob_start();
            require APP . "/views/mail/mail_order.php";
            $body = ob_get_clean();

            $email = (new Email())
                ->from(new Address(App::$app->getProperty('smtp_login'), "LuxuryWatch"))
                ->to($user_email)
                ->priority(Email::PRIORITY_HIGHEST)
                ->subject("Order №{$order_id}")
                ->text("Your order is confirmed")
                ->html($body);
            $mailer->send($email);
        }
    }