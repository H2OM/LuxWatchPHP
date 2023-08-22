<?php
    namespace app\controllers\admin;

use shop\Db;
use shop\libs\Pagination;

    class OrderController extends AppController {
        public function indexAction() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perpage = 10;
            $count = Db::getQuery("SELECT COUNT(*) FROM `order`", false, true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();
            $orders = Db::getQuery("SELECT order.id, order.user_id, order.status, order.date, order.note, order.update_at, order.currency AS `curr`, user.name, user.login, SUM(order_product.qty) AS `qty`, ROUND(SUM(order_product.price), 2) AS `sum` FROM `order` JOIN `user` ON order.user_id=user.id JOIN `order_product` ON order.id=order_product.order_id GROUP BY order.id ORDER BY order.status, order.id LIMIT $start, $perpage");
            if(!empty($_GET) && isset($_GET['searchByLogin'])) {
                $orders = array_filter($orders, function($v, $k) {
                    if($v['login']==$_GET['searchByLogin']){
                        return $v;
                    }
                }, ARRAY_FILTER_USE_BOTH);
            }
            $this->set(compact('orders', 'pagination', 'count'));
            $this->setMeta('Orders');
            
        }
        public function viewAction() {
            if(empty($_GET)) throw new \Exception('Page not found', 404);
            $order = Db::getPreparedQuery("SELECT order.id, order.user_id, order.status, order.date, order.note, order.update_at, order.currency AS `curr`, user.name, user.login, SUM(order_product.qty) AS `qty`, ROUND(SUM(order_product.price), 2) AS `sum` FROM `order` JOIN `user` ON order.user_id=user.id JOIN `order_product` ON order.id=order_product.order_id WHERE order.id=? GROUP BY order.id ORDER BY order.status, order.id", [
                ["VALUE"=>$_GET['id'], "INT"=>true, "PARAMVALUE"=>125]
            ]);
            $order_products = Db::getPreparedQuery("SELECT * FROM order_product WHERE order_id=?",[["VALUE"=>$_GET['id'], "INT"=>true, "PARAMVALUE"=>100]]);
            $this->set(compact('order', 'order_products'));
            $this->setMeta("Order № " .  $_GET['id']);
        }
        public function changeAction() {
            $order_id = $_GET['id'];
            $status = $_GET['status'] ?? '0';
            if(!$order_id) throw new \Exception('Page not found', 404);
            try {
                Db::getPreparedQuery("UPDATE `order` SET status=?, update_at=? WHERE id=?", [
                    ["VALUE"=>getSafeString($status), "INT"=>true, "PARAMVALUE"=>2],
                    ["VALUE"=>date("Y-m-d H:i:s")],
                    ["VALUE"=>getSafeString($order_id), "INT"=>true, "PARAMVALUE"=>100],
                ]);
                $_SESSION['success'] = "Order was updated";
            } catch(\Exception $e) {
                $_SESSION['error'] .= "Error with updating (Order with id: " . h($_GET['id']) . " not found or error in database";
            }
            finally {
                redirect();
            }
        }
        public function deleteAction() {
            $order_id = $_GET['id'] ?? redirect();
            try {
                Db::getPreparedQuery("DELETE FROM `order` WHERE id=?", [["VALUE"=>$order_id, "INT"=>true, "PARAMVALUE"=>100]]);
                $_SESSION['success'] = "Order was deleted";
                redirect(ADMIN . "/order");
            } catch(\Exception $e) {
                $_SESSION['error'] .= "Error with deleting order";
                redirect();
            }
        }
    }
