<?php
    namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\User;
use Error;
use ErrorException;
use shop\Db;

    class CartController extends AppController {
        public function addAction() {
            $DATA = json_decode(file_get_contents("php://input"), true);
            $id = $DATA['id'] ?? null;
            $qty = $DATA['qty'] ?? null;
            $mode_id = $DATA['mod'] ?? null;
            $mod = null;
            if($id) {
                $product = Db::getPreparedQuery("SELECT * FROM product WHERE id=?",[["VALUE"=>$id, "PARAMVALUE"=>128, "INT"=>true]]);
                if(!$product) throw new Error();
                if($mode_id) {
                    $mod = Db::getPreparedQuery("SELECT * FROM modification WHERE id =? AND product_id =?", [
                        ["VALUE"=>$mode_id, "PARAMVALUE"=>128, "INT"=>true], 
                        ["VALUE"=>$id, "PARAMVALUE"=>128, "INT"=>true]
                    ]);
                }
            }
            $cart = new Cart();
            $cart->addToCart($product, $qty, $mod);   
            
            if($this->isAjax()) {
                $this->loadView('cart_modal');
            } 
            redirect();            
        }
        public function showAction() {
            $this->loadView('cart_modal');
        }
        public function deleteAction() {
            $id = $_GET['id'] ?? null;
            if(isset($_SESSION['cart'][$id])) {
                $cart = new Cart();
                $cart->deleteItem($id);
            } 
            if($this->isAjax()) {
                $this->loadView('cart_modal');
            } 
            redirect();
        }
        public function clearAction(){
            unset($_SESSION['cart']);
            unset($_SESSION['cart.qty']);
            unset($_SESSION['cart.sum']);
            unset($_SESSION['cart.currency']);
            $this->loadView('cart_modal');
        }
        public function viewAction() {
            $this->setMeta("Basket");
        }
        public function checkoutAction() {
            if(!empty($_POST)) {
                if(!User::checAuth()) {
                    $user = new User();
                    $user->load($_POST);
                    if($user->validate()) {
                        $preparedQueryAttr = [];
                        foreach($user->attributes as $k=>$v) {
                            if($k == "login") $user_id = $v;
                            $k == "address" 
                            ? array_push($preparedQueryAttr, ["VALUE"=>$v, "INT"=> 16, "PARAMVALUE"=>16])
                            : ($k == "password"
                            ? array_push($preparedQueryAttr, ["VALUE"=>password_hash($v, PASSWORD_DEFAULT), "PARAMVALUE"=>255])
                            : array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>22]));
                        }
                        try {
                            Db::getPreparedQuery("INSERT INTO user (login, password, email, name, address) VALUES (?, ?,  ?, ?, ?)", $preparedQueryAttr);
                        } catch(\PDOException $e) {
                            $_SESSION['error'] = "Error with adding new user";
                            redirect();
                        }
                    } else {
                        $_SESSION['error'] = "Wrong arrguments";
                        redirect();
                    }
                }
                $data['user_id'] = $user_id ?? $_SESSION['user']['login'];
                $data['note'] = getSafeString($_POST['note']) ?? '';
                $user_email = $_SESSION['user']['email'] ?? $_POST['email'];
                $order_id = Order::saveOrder($data);

                if(!empty($_POST['pay'])) {
                    self::setPaymentData($order_id);
                }
                try {
                    Order::mailOrder($order_id, $user_email);
                } catch(\Exception $e) { }
                if(!empty($_POST['pay'])) {
                    redirect(PATH . "/payment/form.php");   
                }
                unset($_SESSION['cart'], $_SESSION['cart.qty'], $_SESSION['cart.sum'], $_SESSION['cart.currency']);  
                $_SESSION['success'] = "Thank you for your order! The manager will contact you soon";
            }
            redirect();
        }
        protected static function setPaymentData($order_id){
            if(isset($_SESSION['payment'])) unset($_SESSION['payment']);
            $_SESSION['payment']['id'] = $order_id;
            $_SESSION['payment']['curr'] = $_SESSION['cart.currency']['code'];
            $_SESSION['payment']['sum'] = $_SESSION['cart.sum'];
        }
        public function paymentACtion() {
            if(empty($_POST)) {
                throw new ErrorException("", 404);
            }
            $dataSet = $_POST;
            $key = "КЛЮЧ КОТОРЫЙ ПРЕДЛОГАЕТ ПЛАТЕЖНАЯ СИСТЕМА";
            unset($dataSet['ik_sign']);
            ksort($dataSet, SORT_STRING);
            array_push($dataSet, $key);
            $signString =implode(':', $dataSet);
            $sign = base64_encode(md5($signString, true));
            $order = Db::getQuery("SELECT * FROM `order` WHERE id=" . (int)$dataSet['ik_pm_no']);
            if(!$order) die;
            if($dataSet['ik_co_id'] != "...Какой то индификатор кассы" ||
                $dataSet['ik_inv_st'] != "success" || $dataSet['ik_am'] != $order['sum'] 
                || $sign != $_POST['ik_sign'] ) {
                    die;
            }
            Db::getQuery("UPDATE order SET status='2' WHERE id=" . (int)$dataSet['ik_pm_no']);
            die;
        }
    }