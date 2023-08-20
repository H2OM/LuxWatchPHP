<?php
    namespace shop\base;

    use shop\Db;


    abstract class Model {
        public $attributes = [];
        public $errors = [];
        public $rules = [];

        public function __construct() {
        }
        public function load($data) {
            foreach($this->attributes as $name=>$value) {
                if(isset($data[$name])) {
                    $this->attributes[$name] = getSafeString($data[$name]);

                }
            }
        }
        public function validate($edit = false) {
            foreach($this->attributes as $k=>$v) {
                if(($k == "password" && !$edit) && (empty($v) || strpos($v, " "))) return false;
                switch ($k) {
                    case "name":    if(strlen($v) < 2)  return false; break;
                    case "address": if(strlen($v) < 11) return false; break;
                    case "login":  
                    case "password": 
                        if($edit) break;
                    case "email":   
                        default: if(strlen($v) < 5)  return false; break;
                }
            }
            return true;
        }   
    }