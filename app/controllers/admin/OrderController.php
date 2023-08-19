<?php
    namespace app\controllers\admin;

use Error;
use shop\Db;
use shop\libs\Pagination;

    class OrderController extends AppController {
        public function indexAction() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perpage = 1;
            $count = Db::getQuery("SELECT COUNT(*) FROM `order`", false, true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();

            $orders = Db::getQuery("SELECT order.id, order.user_id, order.status, order.date, order.note, order.update_at, order.currency AS `curr`, user.name, SUM(order_product.qty) AS `qty`, ROUND(SUM(order_product.price), 2) AS `sum` FROM `order` JOIN `user` ON order.user_id=user.id JOIN `order_product` ON order.id=order_product.order_id GROUP BY order.id ORDER BY order.status, order.id LIMIT $start, $perpage");
            $this->setMeta('Orders');
            $this->set(compact('orders', 'pagination', 'count'));
        }
        public function viewAction() {
            if(empty($_GET)) throw new \Exception('Page not found', 404);
            $order = $_GET;
            $order_products = Db::getPreparedQuery("SELECT * FROM order_product WHERE order_id=?",[["VALUE"=>$_GET['id'], "INT"=>true, "PARAMVALUE"=>100]]);
            $this->set(compact('order', 'order_products'));
            $this->setMeta("Order № " . $_GET['id']);

        }
    }
