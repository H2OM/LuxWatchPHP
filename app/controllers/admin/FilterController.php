<?php 
    namespace app\controllers\admin;

    use shop\Db;
     
    class FilterController extends AppController {
        public function attributeGroupAction() {
            $attrs_group = Db::getQuery('SELECT * FROM attribute_group');
            if(!is_array($attrs_group[array_key_first($attrs_group)]))
                $attrs_group = [$attrs_group];
            $this->setMeta("Filters groups");
            $this->set(compact("attrs_group"));
        }
        public function attributeAction() {

        }
    }

