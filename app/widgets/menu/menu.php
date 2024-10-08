<?php
    namespace app\widgets\menu;

    use shop\App;
    use shop\Cache;
    use shop\Db;

    class Menu {

        protected $data;
        protected $tree;
        public $menuHtml;
        protected $tpl;
        protected $container = 'ul';
        protected $class = 'menu';
        protected $table = 'category';
        protected $cache = '3600';
        protected $cacheKey = 'cats';
        protected $elementCond = '';
        protected $elementCondId = '';
        protected $attrs = [];
        protected $prepend = '';

        public function __construct($options = [], $auto = true) {
            $this->tpl = __DIR__ . '\menu_tpl\menu.php';
            $this->getOptions($options);
            if($auto) $this->run();
        }
        protected function getOptions($options) {
            
            foreach($options as $k =>$v) {
                if(property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        }
        protected function run() {
            $cache = Cache::instance();
            $this->data = $cache->get($this->cacheKey);
            if(!$this->menuHtml) {
                $this->data = App::$app->getProperty('cats');
                if(!$this->data) {
                    $this->data = $cats = Db::getQuery("SELECT * FROM {$this->table}", true);
                }
                $cache->set($this->cacheKey, $this->data, $this->cache);
            }   
            $this->tree = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);
            $this->output();
        }
        public function output() {
            $attrs = '';
            if(!empty($this->attrs)){
                foreach($this->attrs as $k => $v){
                    $attrs .= " $k='$v' ";
                }
            }
            echo "<{$this->container} class='{$this->class}' $attrs>";
            echo $this->prepend;
            echo $this->menuHtml;
            echo "</{$this->container}>";
        }
        public function getTree($data = false){
            $tree = [];
            if(!$data) $data = $this->data;
            foreach ($data as $id=>&$node) {
                if (!$node['parent_id']){
                    $tree[$id] = &$node;
                }else{
                    $data[$node['parent_id']]['childs'][$id] = &$node;
                }
            }
            return $tree;
        }
        public function getMenuHtml ($tree, $tab = '') {
            $str = '';
            $cond = $this->elementCond;
            foreach($tree as $id=>$category) {
                if(!is_array($category)) $category = ["title"=>$category];
                $str .= $this->catToTemplate($category, $tab, $id);
            }
            return $str;
        }
        protected function catToTemplate($category, $tab, $id) {
            $cond = '';
            if($this->elementCond == '') {
                $cond = ((isset($_GET['id']) && $id==$_GET['id']) ? 'disabled style="background-color: #c9c9c9; color:#9b9999;"' : "");
            
            } else if($id == $this->elementCondId) {
                $cond = $this->elementCond;
            } 
            ob_start();
            require $this->tpl;
            return ob_get_clean();
        }
    }