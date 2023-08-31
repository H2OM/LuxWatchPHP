<?php
    namespace app\controllers\admin;

use app\models\User;
use shop\Db;
use shop\libs\Pagination;

    class UserController extends AppController {
        public function loginAdminAction() {
            if(!empty($_POST)) {
                $user = new User();
                if(!$user->login(true)) {
                    $_SESSION['error'] = 'failure with autorization';
                }
                if(User::isAdmin()) {
                    redirect(ADMIN);
                } else {
                    redirect();
                }
            }
            $this->layout = 'login';
            $this->setMeta('login admin', 'login in admin panel', 'admin, admin panel, admin panel login, CMS');
        }
        public function indexAction() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perpage = 10;
            $count = Db::getQuery("SELECT COUNT(*) FROM `user`", false, true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();
            $allUsers = Db::getQuery("SELECT user.id, user.name, user.login, user.email, user.address, user.role,COUNT(order.id) AS `orderCount` FROM `user`
            LEFT JOIN `order` ON user.id=order.user_id GROUP BY user.id ORDER BY user.role LIMIT $start, $perpage");
            $usersAdmins = [];
            $usersUsers = [];
            foreach($allUsers as $k=>$v) {
                if($v['role'] == 'admin') array_push($usersAdmins, $allUsers[$k]);
                    else array_push($usersUsers, $allUsers[$k]);
            }
            $this->set(compact('usersAdmins', 'usersUsers', 'pagination', 'count'));
            $this->setMeta("List of users");
        }
        public function addAction() {
            if(isset($_SESSION['success'])) $_SESSION['success'] = "New user succefully added"; 
            $this->setMeta("New user");
        }
        public function editAction() {
            $user_id = $_GET['id'] ?? $_POST['id'] ?? redirect(ADMIN. "/user");
            if(!empty($_POST)) {
                $user = new User();
                $user->load($_POST);
                if($user->validate(true)) {
                    $preparedQueryAttr = [];
                    foreach($user->attributes as $k=>$v) {
                        switch($k) {
                            case "id": break;
                            case "address":
                                array_push($preparedQueryAttr, ["VALUE"=>$v, "INT"=> 16, "PARAMVALUE"=>16]);
                                break;
                            case "password":
                                if($v == '') break;
                                array_push($preparedQueryAttr, ["VALUE"=>password_hash($v, PASSWORD_DEFAULT), "PARAMVALUE"=>255]);
                                break;
                            default:
                                array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>22]);
                            break;
                        }
                    }
                    array_push($preparedQueryAttr, ["VALUE"=>$user_id, "INT"=> true, "PARAMVALUE"=>100]);
                    try {
                        Db::getPreparedQuery("UPDATE `user` SET login=?," . (!empty($_POST['password'] ? " password=?" : "")) ." email=?, name=?, address=? WHERE id=?", $preparedQueryAttr);
                        $_SESSION['success'] = "A user successfully updated";
                    } catch(\PDOException $e) {
                        $_SESSION['error'] .= "Error with updating user";
                    }
                } else {
                    $_SESSION['error'] = "Wrong arrguments";
                }
                redirect();
            }
            
            $user = Db::getPreparedQuery("SELECT * FROM user WHERE id=?", [["VALUE"=>$user_id, "INT"=>true, "PARAMVALUE"=>100]]);
            $this->set(compact('user'));
            $this->setMeta("Editing a user");
        }
        
    }