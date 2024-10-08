<?php
    namespace shop\base;

use shop\Db;

    abstract class Controller {
        public $route;
        public $controller;
        public $model;
        public $view;
        public $prefix;
        public $layout;
        public $alias;
        public $data = [];
        public $meta = ['title'=> '', 'description' => '', 'keywords'=> ''];
        
        public function __construct($route) {
            $this->controller = $route['controller'];
            $this->model = $route['controller'];
            $this->view= $route['action'];
            $this->prefix = $route['prefix'];
            $this->alias = $route['alias'] ?? null;
            Db::instance();
        }

        public function getView() {
            $viewObject = new View($this->controller, $this->prefix, $this->layout, $this->view, $this->meta);
            $viewObject->render($this->data);
        }

        public function set($data) {
            $this->data = $data;
        }
        public function setMeta($title = '', $desc = '', $keywords = '') {
            $this->meta['title'] = $title;
            $this->meta['description'] = $desc;
            $this->meta['keywords'] = $keywords;
        }
        public function isAjax() {
            return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') || ($_SERVER['HTTP_SEC_FETCH_DEST'] !== "document");
        }
        public function loadView($view,$vars = []) {
            extract($vars);
            require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php";
            die;
        }
    }