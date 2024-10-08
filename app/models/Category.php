<?php
    namespace app\models;

use shop\App;

    class Category extends AppModel {

        public $attributes = [
            'title'=>'',
            'parent_id'=>'',
            'keywords'=>'',
            'description'=>''
        ];

        public function getIds($id) {
            $cats = App::$app->getProperty('cats');
            $ids = null;
            foreach($cats as $k=>$v) {
                if($v['parent_id'] == $id) {
                    $ids .= $k . ',';
                    $ids .= $this->getIds($k);
                }                
            }
            return $ids;
        }
    }