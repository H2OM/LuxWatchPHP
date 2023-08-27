<?php
    namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;
use PDO;
use shop\Db;

    class ProductController extends AppController {
        public function viewAction() {
            $alias = $this->alias;
            $product = Db::getPreparedQuery("SELECT * FROM product WHERE alias = ? AND status='1' LIMIT 1", [["VALUE"=>$alias, "PARAMVALUE"=>128]]);
            if(!$product) {
                throw new \Exception('Page not Found', 404);
            }
            $this->setMeta($product['title'], $product['description'], $product['keywords']);
            $related = Db::getQuery("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id =" . $product['id']);
            $gallery = Db::getQuery("SELECT * FROM gallery WHERE product_id =" . $product['id']);
            
            $p_model = new Product();
            $p_model->setRecentlyViewed($product['id']);
            $r_viewed = $p_model->getRecentlyViewed();
            $recentlyViewed = null;
            if($r_viewed) {
                $recentlyViewed = Db::getQuery("SELECT * FROM product WHERE id=" . implode(" OR id=", $r_viewed) . " LIMIT 3");
                
            }
            
            $breadcrumbs = Breadcrumbs::getBreadcrumbs($product['category_id'], $product['title']);
            $mods = Db::getQuery("SELECT * FROM modification WHERE product_id=" . $product['id']);



            $this->set(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs', 'mods'));
        }
    }