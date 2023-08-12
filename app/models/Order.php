<?php
    namespace app\models;

    use shop\base\Model;
    use shop\Db;

    class Order extends AppModel {
        public static function saveOrder($data) {
            try {
                Db::beginTransaction();
                Db::getPreparedQuery("INSERT INTO `order` (user_id, currency, note) VALUES((SELECT id FROM `user` WHERE login=?), ?, ?)", [
                    ["VALUE"=>$data['user_id'], "INT"=>true, "PARAMVALUE"=>256],
                    ["VALUE"=>$_SESSION['cart.currency']['code'], "PARAMVALUE"=>3],
                    ["VALUE"=>$data['note'], "PARAMVALUE"=>256],
                ]);
                $order_id = Db::getQuery("SELECT LAST_INSERT_ID()");
                Db::commitTransaction();
                self::saveOrderProduct($order_id);
                return $order_id;
            } catch (\PDOException $e) {
                Db::rollbackTransaction();
                $_SESSION['error'] .= "  Something wrong with compliting order";
                redirect();
            }
        }
        public static function saveOrderProduct($order_id) {

        }
        public static function mailOrder($order_id, $user_email) {

        }
    }