<?php
    namespace app\controllers;

use app\models\User;
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
                        Db::getPreparedQuery("INSERT INTO user (login, password, email, name, address) VALUES (?, ?,  ?, ?, ?)", $preparedQueryAttr);
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
                } else {
                    $_SESSION['error'] = "Error with autorization";
                    
                }
                redirect();
            }
            $this->setMeta('Login');
        }
        public function logoutAction() {
            if(isset($_SESSION['user'])) unset($_SESSION['user']);
            redirect();
        }
        
    }
