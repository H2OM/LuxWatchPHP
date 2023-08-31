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
        public function editDetails($id, $data, $table, $insertingAttrs, $isNew = false) {
            if(!$isNew) Db::getPreparedQuery("DELETE FROM ". $table . " WHERE product_id=?", [["VALUE"=>$id, "INT"=>true, "PARAMVALUE"=>100]]); 
            $preparedQueryAttr = [];
            $sqlPart = '';
            foreach($data as $k=>$v) {
                $sqlPart .= "(?, ?),";
                array_push($preparedQueryAttr, ["VALUE"=> $v, "INT"=>true]);
                array_push($preparedQueryAttr, ["VALUE"=> $id, "INT"=>true]);
            }
            $sqlPart = rtrim($sqlPart, ',');
            Db::getPreparedQuery("INSERT INTO " .$table . " (" . $insertingAttrs . ", product_id) VALUES $sqlPart", $preparedQueryAttr);
        }
        public function setMods($id, $mods) {
            $preparedQueryAttr = [];
            $sql_part = "";
            foreach($mods as $k=>$v) {
                $sql_part .= "(?, ?, ?),";
                array_push($preparedQueryAttr, ["VALUE"=> $id, "INT"=>true]);
                array_push($preparedQueryAttr, ["VALUE"=> $v['mod'], "PARAMVALUE"=>60]);
                array_push($preparedQueryAttr, ["VALUE"=> $v['price'], "INT"=>true]);
            }
            $sql_part = rtrim($sql_part, ',');
            Db::getPreparedQuery("INSERT INTO `modification` (product_id, title, price) VALUES $sql_part", $preparedQueryAttr);
        }
        public function imgSingleCleaning($data) {
            if(!empty($data)) {
                // @unlink(DIR . '/images/' . $data);
                if($_SESSION['single'] == $data) unset($_SESSION['single']);
            }
        }
        public function imgMultiCleaning($data) {
            if(!empty($data)) {
                $sql_part = '';
                $preparedQueryAttr = [];
                
                foreach($data as $k=>$val) {
                    $sql_part .= " id=? OR";
                    array_push($preparedQueryAttr, ["VALUE"=> $val['id'], "INT"=>true]);
                    //@unlink(DIR . '/images/' . $val['name']);
                }
                $sql_part = preg_replace('/OR$/','',$sql_part);
                Db::getPreparedQuery("DELETE FROM gallery WHERE $sql_part", $preparedQueryAttr);
            }
        }
        public function getImg(){
            if(isset($_SESSION['single']) && !empty($_SESSION['single'])){
                $this->attributes['img'] = $_SESSION['single'];
                unset($_SESSION['single']);
            }
        }
        public function saveGallery($id){
            if(!empty($_SESSION['multi'])){
                $sql_part = '';
                foreach($_SESSION['multi'] as $v){
                    $sql_part .= "('$v', $id),";
                }
                $sql_part = rtrim($sql_part, ',');
                Db::getQuery("INSERT INTO gallery (img, product_id) VALUES $sql_part");
                unset($_SESSION['multi']);
            }
        }

        public function uploadImg($name, $wmax, $hmax){
            $uploaddir = DIR . '/images/';
            $output= [];
            if($name == "multi") $newMultiSession = [];
            foreach($_FILES[$name] as $fileKey=>$fileValue) {
                $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name][$fileKey]['name'])); // расширение картинки
                $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png"); // массив допустимых расширений
                if($_FILES[$name][$fileKey]['size'] > 2097152){
                    $res = array("error" => "Error! Max file size is - 1 Мб!");
                    exit(json_encode($res));
                }
                if($_FILES[$name][$fileKey]['error']){
                    $res = array("error" => "Error! Probably, file is too large.");
                    exit(json_encode($res));
                }
                if(!in_array($_FILES[$name][$fileKey]['type'], $types)){
                    $res = array("error" => "Error! Acceptable extensions - .gif, .jpg, .png");
                    exit(json_encode($res));
                }
                $new_name = md5(time().$_FILES[$name][$fileKey]['name']).".$ext";
                $uploadfile = $uploaddir.$new_name;
                if(move_uploaded_file($_FILES[$name][$fileKey]['tmp_name'], $uploadfile)){
                    if($name == 'single'){
                        if(isset($_SESSION['single'])) unset($_SESSION['single']);
                        $_SESSION['single'] = $new_name;
                    }else{
                        $newMultiSession['multi'][] = $new_name;
                    }
                    self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
                    $uploadName = htmlentities($_FILES[$name][$fileKey]['name']);
                    $uploadName = strlen($uploadName) < 18 ? $uploadName
                    : substr(substr($uploadName, 0, strrpos($uploadName,'.')), 0, 10) . "..." . $ext; 
                    array_push($output, ["servName"=> $new_name, "uploadName"=>$uploadName]);
                }else {
                    $res = array("error" => "Error! Saving the file is rejected");
                    exit(json_encode($res));
                }
            }
            if($name == "multi") {
                if(isset($_SESSION['multi'])) unset($_SESSION['multi']);
                $_SESSION['multi'] = $newMultiSession['multi'];
            }
            exit(json_encode($output));   
        }
    
        /**
         * @param string $target путь к оригинальному файлу
         * @param string $dest путь сохранения обработанного файла
         * @param string $wmax максимальная ширина
         * @param string $hmax максимальная высота
         * @param string $ext расширение файла
         */
        public static function resize($target, $dest, $wmax, $hmax, $ext){
            list($w_orig, $h_orig) = getimagesize($target);
            $ratio = $w_orig / $h_orig; // =1 - квадрат, <1 - альбомная, >1 - книжная
    
            if(($wmax / $hmax) > $ratio){
                $wmax = $hmax * $ratio;
            }else{
                $hmax = $wmax / $ratio;
            }
    
            $img = "";
            // imagecreatefromjpeg | imagecreatefromgif | imagecreatefrompng
            switch($ext){
                case("gif"):
                    $img = imagecreatefromgif($target);
                    break;
                case("png"):
                    $img = imagecreatefrompng($target);
                    break;
                default:
                    $img = imagecreatefromjpeg($target);
            }
            $newImg = imagecreatetruecolor($wmax, $hmax); // создаем оболочку для новой картинки
    
            if($ext == "png"){
                imagesavealpha($newImg, true); // сохранение альфа канала
                $transPng = imagecolorallocatealpha($newImg,0,0,0,127); // добавляем прозрачность
                imagefill($newImg, 0, 0, $transPng); // заливка
            }
    
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
            switch($ext){
                case("gif"):
                    imagegif($newImg, $dest);
                    break;
                case("png"):
                    imagepng($newImg, $dest);
                    break;
                default:
                    imagejpeg($newImg, $dest);
            }
            imagedestroy($newImg);
        }
    }