<?php
    namespace app\models\admin;

use app\models\AppModel;
use shop\Db;

    class Product extends AppModel {
        public $attributes = [
            "title"=>'',
            "alias"=>'',
            "price"=>'',
            "old_price"=>0,
            "status" => '0',
            "keywords"=>'',
            "description"=> '',
            "img"=>'no_image.jpg',
            "hit"=> '0',
            "category"=>'',
            "brand"=>'',
            "content"=>'',
            "id"=>''
        ];
        public function editFilter($id, $data, $isNew = false) {
            
            if(!$isNew) Db::getPreparedQuery("DELETE FROM `attribute_product` WHERE product_id=?", [["VALUE"=>$id, "INT"=>true, "PARAMVALUE"=>100]]); 
            $preparedQueryAttr = [];
            $sqlPart = '';
            
            foreach($data as $k=>$v) {
                $sqlPart .= "(?, ?),";
                array_push($preparedQueryAttr, ["VALUE"=> $v, "INT"=>true]);
                array_push($preparedQueryAttr, ["VALUE"=> $id, "INT"=>true]);
            }
            $sqlPart = rtrim($sqlPart, ',');
            Db::getPreparedQuery("INSERT INTO `attribute_product` (attr_id, product_id) VALUES $sqlPart", $preparedQueryAttr);
        }
    }