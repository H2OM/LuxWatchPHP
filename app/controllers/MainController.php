<?php
    namespace app\controllers;

    use shop\App;
    use shop\Cache;
    use shop\Db;

    class MainController extends AppController{
        public function indexAction() {
            $brands = Db::getQuery("SELECT * FROM brand LIMIT 3");
            $hits = Db::getQuery("SELECT * FROM product WHERE hit='1' AND status='1' LIMIT 8");
            $this->set(compact('brands', 'hits'));
            $this->setMeta(App::$app->getProperty('shop_name'),'This is desc', 'key words');
        }
    } 