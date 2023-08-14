<?php
    namespace app\controllers\admin;

use app\models\User;

    class UserController extends AppController {
        public function loginAdminAction() {
            if(!empty($_POST)) {
                $user = new User();
                if($user->login(true)) {
                    $_SESSION['success'] = 'You are success autorizated';
                } else {
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
    }