<?php
    namespace app\controllers;

use shop\Db;

    class SearchController extends AppController {
        public function typeaheadAction() {
            if($this->isAjax()) {
                $query = trim($_GET['query']) ?? null;
                if($query) {
                    $products = Db::getPreparedQuery("SELECT id, title FROM product WHERE title LIKE ? LIMIT 11", [['VALUE'=>"%{$query}%", "PARAMVALUE"=>128]]);
                    echo json_encode($products);
                }
            }
            die;
        }
    }