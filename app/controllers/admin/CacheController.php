<?php
    namespace app\controllers\admin;

use shop\Cache;

    class CacheController extends AppController {
        public function indexAction() {
            $this->setMeta("Cache cleaning");
        }
        public function deleteAction() {
            $key = $_GET['key'] ?? null;
            $cache = Cache::instance();
            switch($key) {
                case 'category':
                    $cache->delete('cats');
                    $cache->delete('shop_menu');
                    break;
                case 'filter':
                    $cache->delete('filter_group');
                    $cache->delete('filter_attrs');
                    break;
                default:
                    $_SESSION['error'] = "Cache with this name was not found";
                    redirect();
            }
            $_SESSION['success'] = "Cache was successfully cleared";
            redirect();
        }
    }