<?php
    namespace app\controllers\admin;

use shop\Db;
use shop\libs\Pagination;

    class ProductController extends AppController {
        public function indexAction() {
            $page = $_GET['page'] ?? 1;
            $perpage = 10;
            $count = Db::getQuery("SELECT COUNT(*) FROM `product`", false , true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();
            $products = Db::getQuery("SELECT product.*, category.title AS `cat` FROM `product` JOIN `category` ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
            $this->set(compact('products', 'pagination', 'count'));
            $this->setMeta('Goods list');
        }
    }