<?php
    namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use app\widgets\filter\Filter;
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
            $sql_part = '';
            if(!empty($_GET['filter'])) {
                $filter = Filter::getFilter();
                if($filter) {
                    $cnt = Filter::getCountGroups($filter);
                    $sql_part = "AND id IN (SELECT product_id FROM attribute_product WHERE attr_id IN ($filter) GROUP BY product_id HAVING COUNT(product_id) = $cnt)";
                }
                
            }

            $total = Db::getQuery("SELECT COUNT(*) FROM product WHERE category_id IN ($ids) $sql_part", false, true);
            $pagination = new Pagination($page, $perpage, $total);
            $start = $pagination->getStart();

            $products = Db::getQuery("SELECT * FROM product WHERE category_id IN ($ids) $sql_part AND status='1' LIMIT $start, $perpage");
            
            if($this->isAjax()) {
                $this->loadView('filter', compact('products', 'total', 'pagination'));
            }

            $this->setMeta($category['title'], $category['description'], $category['keywords']);
            $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
        }

    }