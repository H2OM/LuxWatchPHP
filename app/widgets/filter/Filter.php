<?php
    namespace app\widgets\filter;

use shop\Cache;
use shop\Db;

    class Filter {

        public $groups;
        public $attrs;
        public $tpl;
        public function __construct() {
            $this->tpl = __DIR__ . '/filter_tpl.php';
            $this->run();
        }
        protected function run () {
            $cache = Cache::instance();
            $this->groups = $cache->get('filter_group');
            if(!$this->groups) {
                $this->groups = $this->getGroups();
                $cache->set('filter_group', $this->groups);
            }
            $this->attrs = $cache->get('filter_attrs');
            if(!$this->attrs) {
                $this->attrs = self::getAttrs();
                $cache->set('filter_group', $this->attrs, 1);
            }
            echo $this->getHtml();
        }
        protected function getHtml() {
            ob_start();
            $filter = self::getFilter();
            if(!empty($filter)) {
                $filter = explode('', $filter);
            }
            require $this->tpl;
            return ob_get_clean();
        }
        protected function getGroups() {
            $result = Db::getQuery("SELECT id, title FROM attribute_group", true);
            foreach($result as $k=>$v) {
                $result[$k] = $v['title'];
            }
            return $result;
        }
        protected static function getAttrs() {
            $data = Db::getQuery("SELECT * FROM attribute_value", true);
            $attrs = [];
            foreach($data as $k=>$v) {
                $attrs[$v['attr_group_id']][$k] = $v['value'];
            }
            return $attrs;
        }
        public static function getFilter() {
            $filter = null;
            if(!empty($_GET['filter'])) {
                $filter = trim(preg_replace("#[^\d,]+#", '', $_GET['filter']), ',');
            }
            return $filter;
        }   
        public static function getCountGroups($filter) {
            $filters = explode(',', $filter);
            $cache = Cache::instance();
            $attrs = $cache->get('filter_attrs');
            if(!$attrs) {
                $attrs = self::getAttrs();
            }
            $data = [];
            foreach($attrs as $key=>$item) {
                foreach($item as $k=>$v) {
                    if(in_array($k, $filters)) {
                        $data[] = $key;
                        break;
                    }
                }
            }
            return count($data);
        }
    }