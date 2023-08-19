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
            $perpage = 1;
            $count = Db::getQuery("SELECT COUNT(*) FROM `user`", false, true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();
            $allUsers = Db::getQuery("SELECT `user`.name, `user`.login, `user`.email, `user`.address, COUNT(`order`.`id`) FROM `user` JOIN `order` ON `order`.`user_id`=`user`.`id` WHERE `user`.role=1 GROUP BY `user`.`id` LIMIT $start, $perpage");
            $this->set(compact('allUsers', 'pagination', 'count'));
            $this->setMeta("List of users");
        }
    }