<?php 
    namespace shop\base;
    class View {
        public $controller;
        public $model;
        public $view;
        public $prefix;
        public $layout;
        public $data = [];
        public $meta = ['title'=> '', 'description' => '', 'keywords'=> ''];
        
        public function __construct($controller, $prefix, $layout = '', $view = '', $meta ) {
            $this->controller = $controller;
            $this->model = $controller;
            $this->view= $view;
            $this->prefix = $prefix;
            $this->meta = $meta;
            if($layout === false) {
                $this->layout = false;
            } else {
                $this->layout = $layout ?: LAYOUT;
            }
        }

        public function render($data) {
           $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
           if(is_file($viewFile)){
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
            }else{
                throw new \Exception("На найден вид {$viewFile}", 500);
            }
            if(false !== $this->layout){
                $layoutFile = APP . "/views/layouts/{$this->layout}.php";
                if(is_file($layoutFile)){
                    require_once $layoutFile;
                }else{
                    throw new \Exception("На найден шаблон {$this->layout}", 500);
                }
            }
            $this->getMeta();
        }
        public function getMeta () {
            return "<title>" . $this->meta['title'] ."</title>" . PHP_EOL .
                    "<meta name='description' content ='" . $this->meta['description'] . "'/>" . PHP_EOL .
                    "<meta name='keywords' content ='" . $this->meta['keywords'] . "'/>";
            
        }
    }