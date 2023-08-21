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
        public function changeEmailAction() {
            if(!empty($_POST)) {
                try {
                    $newEmail = getSafeString($_POST['email']);
                    Db::getPreparedQuery("UPDATE user SET email=? WHERE email='{$_SESSION['user']['email']}'",[["VALUE"=>$newEmail, "PARAMVALUE"=>22]]);
                    $_SESSION['user']['email'] = $newEmail;
                    $_SESSION['success'] = "Your email successfully update";
                } catch (\PDOException $e) {
                    $_SESSION['error'] .= "Some error with change email";
                }
                redirect();
            }
        }
        
    }
