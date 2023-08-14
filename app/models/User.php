<?php
    namespace app\models;

use shop\Db;

    class User extends AppModel {
        public $attributes = [
            "login"=>'',
            "password"=>'',
            "email"=>'',
            "name" =>'',
            "address"=>''
        ];
        public function login($isAdmin = false) {
            $login = trim($_POST['login']) ?? null;
            $password = trim($_POST['password']) ?? null;
            if($login && $password) {
                try {
                    if($isAdmin) {
                        $user = Db::getPreparedQuery("SELECT * FROM user WHERE login = ? AND role = 'admin'", [["VALUE"=>$login,"PARAMVALUE"=>22]]);
    
                    } else {
                        $user = Db::getPreparedQuery("SELECT * FROM user WHERE login = ?", [["VALUE"=>$login,"PARAMVALUE"=>22]]);
                    }
                    if($user) {
                        if(password_verify($password, $user['password'])){
                            foreach($user as $k=>$v) {
                                if($k != 'password') $_SESSION['user'][$k] = $v;
                            }
                            return true;
                        }
                    } 
                } catch (\PDOException $e) {
                    return false;
                }
            }
            return false;
        }
        public static function checAuth() {
            return isset($_SESSION['user']);
        }
        public static function isAdmin() {
            return (isset($_SESSION['user']) && $_SESSION['user']['role'] == "admin");
        }
    }
