<?php
    namespace app\controllers;

use app\models\User;
use Exception;
use shop\Db;

    class UserController extends AppController {
        public function signupAction() {
            if(!empty($_POST)) {
                $user = new User();
                $user->load($_POST);
                if($user->validate()) {
                    $preparedQueryAttr = [];
                    foreach($user->attributes as $k=>$v) {
                        $k == "address" 
                        ? array_push($preparedQueryAttr, ["VALUE"=>getSafeString($v), "INT"=> 16, "PARAMVALUE"=>16])
                        : ($k == "password"
                        ? array_push($preparedQueryAttr, ["VALUE"=>password_hash(getSafeString($v), PASSWORD_DEFAULT), "PARAMVALUE"=>255])
                        : array_push($preparedQueryAttr, ["VALUE"=>getSafeString($v), "PARAMVALUE"=>22]));
                    }
                    try {
                        Db::getPreparedQuery("INSERT INTO user (login, password, email, name, address, role) VALUES (?, ?, ?, ?, ?, ?)", $preparedQueryAttr);
                        $_SESSION['success'] = "You have successfully registered";
                    } catch(\PDOException $e) {
                        $_SESSION['error'] = "Error with adding new user";
                    }
                } else {
                    $_SESSION['error'] = "Wrong arrguments";
                }
                redirect();
            }
            $this->setMeta('Registration');

        }
        public function signinAction() {
            if(!empty($_POST)) {
                $user = new User();
                if($user->login()){
                    $_SESSION['success'] = "You successfully login";
                    redirect(PATH);
                } else {
                    $_SESSION['error'] = "Error with autorization";
                    redirect();
                }
            }
            $this->setMeta('Login');
        }
        public function logoutAction() {
            if(isset($_SESSION['user'])) unset($_SESSION['user']);
            redirect();
        }
        public function officeAction() {
            if(!User::checAuth()) redirect();
            $this->setMeta("Personal account");
        } 
        public function editAction() {
            if(!User::checAuth()) redirect('user/login');
            if(isset($_POST['login'], $_POST['password'], $_POST['name'], $_POST['email'], $_POST['address'])) {
                $user = new User();
                $data = $_POST;
                $data['id'] = $_SESSION['user']['id'];
                $data['role'] = $_SESSION['user']['role'];
                $user->load($data);
                if(!$user->attributes['password']) {
                    unset($user->attributes['password']);
                } else {
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                }
                if(!$user->validate(true)) {
                    $_SESSION['error'] = "Something going wrong";
                    redirect();
                }
                $preparedQueryAttr = [];
                $sql_part ='';
                foreach($user->attributes as $k=>$v) {
                    if($k == "role") continue;
                    $sql_part .= $k . "=?,";
                    switch($k) {
                        case "address":
                            array_push($preparedQueryAttr, ["VALUE"=>$v,"INT"=>true]);
                            break;
                        case "password":
                            array_push($preparedQueryAttr, ["VALUE"=>$v,"PARAMVALUE"=>256]);
                            break;
                        default:
                            array_push($preparedQueryAttr, ["VALUE"=>$v,"PARAMVALUE"=>64]);
                            break;
                    }
                }
                $sql_part = rtrim($sql_part, ',');
                try {
                    Db::getPreparedQuery("UPDATE user SET $sql_part WHERE id=" . $data['id'], $preparedQueryAttr);
                    foreach($user->attributes as $k=>$v) {
                        if($k == "password") continue;
                        $_SESSION['user'][$k] = $v;
                    }
                    $_SESSION['success'] = "Changes saved";
                } catch(Exception $e) {
                    $_SESSION['error'] .= "Cant save data";
                } finally {
                    redirect();
                }
            } 
            $this->setMeta('Change personal data');
        }
        public function ordersAction() {
            if(!User::checAuth()) redirect("user/login");
            try {
                $orders = Db::getPreparedQuery("SELECT `order`.*, order_product.price AS `sum` FROM `order` 
                    JOIN `order_product` ON order.id = order_product.order_id 
                    WHERE user_id=?",[
                        ["VALUE"=>$_SESSION['user']['id'], "INT"=>true]
                    ]);
                if(!is_array($orders[array_key_first($orders)]))
                    $orders = [$orders];
            } catch (Exception $e) {
                $_SESSION['error'] .= "Error getting orders";
                redirect();
            }
            $this->setMeta("Orders history");
            $this->set(compact("orders"));
        }
    }
