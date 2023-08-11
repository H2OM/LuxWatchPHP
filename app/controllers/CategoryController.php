<?php
    namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use shop\App;
use shop\Db;
use shop\libs\Pagination;

    class CategoryController extends AppController {
        public function viewAction () {
            $alias = $this->alias;
            $category = Db::getPreparedQuery("SELECT * FROM category WHERE alias = ?", [["VALUE"=>$alias, "PARAMVALUE"=>128]]);
            if(!$category) throw new \Exception("Page not found", 404);
            
            $breadcrumbs = Breadcrumbs::getBreadcrumbs($category['id']);
            $cat_model = new Category();
            $ids = $cat_model->getIds($category['id']);
            $ids = !$ids ? $category['id'] : $ids . $category['id'];
            $page = $_GET['page'] ?? 1;
            $perpage = App::$app->getProperty('pagination');
            $total = Db::getQuery("SELECT COUNT(*) FROM product WHERE category_id IN ($ids)", false, true);
            $pagination = new Pagination($page, $perpage, $total);
            $start = $pagination->getStart();

            $products = Db::getQuery("SELECT * FROM product WHERE category_id IN ($ids) LIMIT $start, $perpage");
            $this->setMeta($category['title'], $category['description'], $category['keywords']);
            $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
        }

    }