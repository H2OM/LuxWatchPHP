<?php
    namespace app\controllers\admin;

use shop\Db;

    class MainController extends AppController {
        public function indexAction() {
            $countNewOrders = Db::getQuery("SELECT COUNT(*) FROM `order` WHERE status='0'", false, true);
            $countUsers = Db::getQuery("SELECT COUNT(*) FROM `user`", false, true);
            $countProduct = Db::getQuery("SELECT COUNT(*) FROM `product`", false, true);
            $countCategories = Db::getQuery("SELECT COUNT(*) FROM `category`", false, true);
            // debug($countNewOrders . "  " . $countUsers . "  " . $countProduct . "  ". $countCategories);
            $this->set(compact('countNewOrders', 'countUsers', 'countProduct', 'countCategories'));
            $this->setMeta('Admin panel', 'admin panel', 'admin, admin panel, CMS');
        }
    }