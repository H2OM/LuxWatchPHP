<?php
    namespace app\controllers;

use PDO;
use shop\Db;

    class ProductController extends AppController {
        public function viewAction() {
            $alias = $this->alias;
            $product = Db::getPreparedQuery("SELECT * FROM product WHERE alias = ? AND status='1' LIMIT 1", [["VALUE"=>$alias, "PARAMVALUE"=>128]]);
            if(!$product) {
                throw new \Exception('Page not Found', 404);
            }
            $related = Db::getQuery("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id =" . $product['id']);
            $this->setMeta($product['title'], $product['description'], $product['keywords']);
            $this->set(compact('product', 'related'));

            
            
        
        }
    }