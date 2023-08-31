<?php
    namespace app\controllers\admin;

use app\models\admin\Product;
use Error;
use ErrorException;
use Exception;
use shop\App;
use shop\Db;
use shop\libs\Pagination;

    class ProductController extends AppController {
        public function indexAction() {
            
            $page = $_GET['page'] ?? 1;
            $perpage = 10;
            $count = Db::getQuery("SELECT COUNT(*) FROM `product`", false , true);
            $pagination = new Pagination($page, $perpage, $count);
            $start = $pagination->getStart();
            $products = Db::getQuery("SELECT product.*, category.title AS `cat`, brand.title as 'brand' FROM `product` JOIN `category` ON category.id = product.category_id JOIN `brand` ON brand.id=product.brand_id ORDER BY product.title LIMIT $start, $perpage");
            $this->set(compact('products', 'pagination', 'count'));
            $this->setMeta('Goods list');
        }
        public function deleteAction() {
            $product_id = $_GET['id'] ?? redirect();
            try {
                Db::getPreparedQuery("DELETE FROM `product` WHERE id=?", [['VALUE'=>$product_id, "INT"=>true, "PARAMVALUE"=>128]]);
                $_SESSION['success'] = "Product succefully deleted";
            } catch(\Exception $e){
                $_SESSION['error'] .= "Error deleting product";
            } finally {
                redirect();
            }
        }
        public function modDeleteAction() {
            $mod_id = $_GET['modId'] ?? die;
            try {
                Db::getPreparedQuery("DELETE FROM `modification` WHERE id=?", [["VALUE"=>$mod_id, "INT"=>true]]);
                exit;
            } catch(\Exception $e) {
            }
        }
        public function deleteImgsAction() {
            if(!empty($_GET) && isset($_GET['path']) && isset($_GET['name'])) {
                if($_GET['path']=="single") {
                    unset($_SESSION['single']);
                } else if($_GET['path']=="multi") {
                    unset($_SESSION['multi'][array_search($_GET['name'], $_SESSION['multi'])]);
                } else {
                    throw new ErrorException("Args not supported", 400);
                }
            } else {
                throw new ErrorException("Args not supported", 400);
            }
            die;
        }
        public function editAction() {
            $product_id = $_GET['id'] ?? $_POST['id'] ?? redirect(ADMIN. "/product");
            if(!empty($_POST)) {
                $product = new Product();
                if(isset($_POST['imgDelete']['single'])) $product->imgSingleCleaning($_POST['imgDelete']['single']);
                $product->getImg();
                $product->load($_POST);
                $preparedQueryAttr = [];
                foreach($product->attributes as $k=>$v) {
                    is_int($v) 
                    ? array_push($preparedQueryAttr, ["VALUE"=>$v, "INT"=>true, "PARAMVALUE"=>100])
                    : ($k == "content" ? array_push($preparedQueryAttr, ["VALUE"=>$v])
                    : ($k=="description" ? array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>100])
                    : array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>48])));
                }
                try {
                    Db::beginTransaction();
                    Db::getPreparedQuery("UPDATE `product` SET 
                    title=?, alias=?, price=?, old_price=?, status=?, keywords=?,
                    description=?, img=?, hit=?, category_id=?, brand_id=?, content=? WHERE id=?", $preparedQueryAttr);
                    if(!isset($_POST['attrs'])) throw new Exception('');
                    $product->editDetails($product_id, $_POST['attrs'], "`attribute_product`", "attr_id");
                    if(isset($_POST['related'])) {
                        $product->editDetails($product_id, $_POST['related'], "`related_product`","related_id");
                    };
                    if(isset($_POST['imgDelete']['multi'])) $product->imgMultiCleaning($_POST['imgDelete']['multi']);
                    $product->saveGallery($product_id);
                    if(isset($_POST['mod']) && !empty($_POST['mod']))
                        $product->setMods($product_id, $_POST['mod']);
                    Db::commitTransaction();
                    $_SESSION['success'] = "Product successfully updated";
                } catch (\Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] .= "Error updating product";
                } finally {
                    redirect();
                }
            }
            if(isset($_SESSION['single'])) unset($_SESSION['single']);
            if(isset($_SESSION['multi'])) unset($_SESSION['multi']);
            try {
                $product = Db::getPreparedQuery(
                    "SELECT product.*, attribute_product.attr_id AS `attr`, attribute_value.attr_group_id AS `group` FROM `product` 
                    LEFT JOIN `attribute_product` ON attribute_product.product_id=product.id 
                    LEFT JOIN `attribute_value` ON attribute_value.id=attribute_product.attr_id 
                    WHERE product.id=?", 
                    [["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128]]
                );
                $temp = Db::getPreparedQuery(
                    "SELECT gallery.id AS `imgId`, gallery.img, modification.id AS `modId`, modification.title, modification.price FROM `gallery` 
                    RIGHT JOIN `modification` ON gallery.product_id=modification.product_id WHERE gallery.product_id=? OR modification.product_id=?
                    GROUP BY modId
                    UNION
                    SELECT gallery.id AS `imgId`, gallery.img, modification.id AS `modId`, modification.title, modification.price FROM `gallery` 
                    LEFT JOIN `modification` ON gallery.product_id=modification.product_id WHERE gallery.product_id=? OR modification.product_id=? 
                    GROUP BY imgId;", 
                    [
                        ["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128],["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128],
                        ["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128],["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128]
                    ]
                );
                $related = Db::getPreparedQuery(
                    "SELECT product.id, product.title FROM product 
                    LEFT JOIN related_product ON related_product.related_id = product.id 
                    WHERE related_product.product_id=?;",
                    [["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128]]
                );
                $gallery = [];
                $mods = [];
                if(!empty($temp)) {
                    if(!is_array($temp[array_key_first($temp)])) $temp = [$temp];
                    foreach($temp as $k=>$v) {
                        if(!empty($v['img'])) $gallery[$v['imgId']] = $v['img'];
                        if(!empty($v['title'] && !empty($v['price']))) $mods[$v['modId']] = ["mod"=>$v['title'], "price"=>$v['price']];
                    }
                }
                $result = [];
                if(is_array($product[array_key_first($product)])) {
                    foreach($product as $k=>$v) {
                        $result[$v['group']]=$v['attr'];
                    }
                    $product = $product[array_key_first($product)];
                } else if(!empty($product['group']) && !empty($product['attr'])) {
                    $result[$product['group']]=$product['attr'];
                }
                $product['attrs'] = $result;
                unset($product['attr'], $product['group']);
                $temp = Db::getQuery(
                    "SELECT category.parent_id AS `CatParent`, category.id AS `catId`, category.title AS `catTitle`, brand.id AS `brandId`, brand.title AS `brandTitle` 
                    FROM `brand` RIGHT JOIN `category` ON category.id=brand.id
                    UNION 
                    SELECT category.parent_id AS `CatParent`, category.id AS `catId`, category.title AS `catTitle`, brand.id AS `brandId`, brand.title AS `brandTitle` 
                    FROM `brand` LEFT JOIN `category` ON category.id=brand.id;"
                );
                $categories = [];
                $brands = [];
                foreach($temp as $k=>$v) {
                    if($v['catId']) $categories[$v['catId']]=["parent_id"=> $v['CatParent'], "title"=>$v['catTitle']];
                    if($v['brandId']) $brands[$v['brandId']]=["title"=> $v['brandTitle']];
                }
                $this->set(compact('product', 'categories', 'brands', 'gallery', 'mods', 'related'));
                $this->setMeta("Product edit");
            } catch (\Exception $e) {
                $_SESSION['error'] .= "Database connecting error";
                redirect();
            }
        }
        public function addAction () {
            if(!empty($_POST)) {
                $product = new Product();
                $product->getImg();
                $product->load($_POST);
                $preparedQueryAttr = [];
                foreach($product->attributes as $k=>$v) {
                    is_int($v) 
                    ? array_push($preparedQueryAttr, ["VALUE"=>$v, "INT"=>true, "PARAMVALUE"=>100])
                    : ($k == "content" ? array_push($preparedQueryAttr, ["VALUE"=>$v])
                    : ($k=="description" ? array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>100])
                    : array_push($preparedQueryAttr, ["VALUE"=>$v, "PARAMVALUE"=>48])));
                }
                try {
                    Db::beginTransaction();
                    Db::getPreparedQuery(
                        "INSERT INTO `product` (title, alias, price, old_price, status, keywords, description, img, hit, category_id, brand_id, content) 
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                        $preparedQueryAttr
                    );
                    $id = Db::getQuery("SELECT LAST_INSERT_ID();", false, true);
                    
                    if(!isset($_POST['attrs'])) throw new Exception('');
                    $product->editDetails($id, $_POST['attrs'], "`attribute_product`","attr_id", true);
                    if(isset($_POST['related'])) {
                        $product->editDetails($id, $_POST['related'], "`related_product`","related_id", true);
                    };
                    $product->saveGallery($id);
                    if(isset($_POST['mod']) && !empty($_POST['mod']))
                        $product->setMods($id, $_POST['mod']);
                    Db::commitTransaction();
                    $_SESSION['success'] = "Product successfully added";
                } catch (\Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] .= "Error adding product";
                } finally {
                    redirect();
                }
            }
            if(isset($_SESSION['single'])) unset($_SESSION['single']);
            if(isset($_SESSION['multi'])) unset($_SESSION['multi']);
            try {
                $temp = Db::getQuery(
                    "SELECT category.parent_id AS `CatParent`, category.id AS `catId`, category.title AS `catTitle`, brand.id AS `brandId`, brand.title AS `brandTitle` 
                    FROM `brand` RIGHT JOIN `category` ON category.id=brand.id
                    UNION 
                    SELECT category.parent_id AS `CatParent`, category.id AS `catId`, category.title AS `catTitle`, brand.id AS `brandId`, brand.title AS `brandTitle` 
                    FROM `brand` LEFT JOIN `category` ON category.id=brand.id;"
                );
                $categories = [];
                $brands = [];
                foreach($temp as $k=>$v) {
                    if($v['catId']) $categories[$v['catId']]=["parent_id"=> $v['CatParent'], "title"=>$v['catTitle']];
                    if($v['brandId']) $brands[$v['brandId']]=["title"=> $v['brandTitle']];
                }
                $this->set(compact('categories', 'brands'));
                $this->setMeta("Adding new product");
            } catch (\Exception $e) {
                $_SESSION['error'] .= "Database connecting error";
                redirect();
            }
        }
        public function relatedProductAction() {
            $q = $_GET['q'] ?? '';
            $data['items'] = [];
            $products = Db::getPreparedQuery("SELECT id, title FROM `product` WHERE title LIKE ? LIMIT 10", [["VALUE"=>"%$q%", "PARAMVALUE"=>40]], false, true);
            if($products) {
                $i = 0;
                foreach($products as $id=>$title) {
                    $data['items'][$i]['id'] = $id;
                    $data['items'][$i]['text'] = $title['title'];
                    $i++;
                }
            }
            echo json_encode($data);
            die;
        }
        public function addImageAction() {
            if(!empty($_POST) && !empty($_FILES)) {
                if($_POST['name'] == 'multi') {
                    $wmax = App::$app->getProperty('gallery_width');
                    $hmax = App::$app->getProperty('gallery_height');
                } else {
                    $wmax = App::$app->getProperty('img_width');
                    $hmax = App::$app->getProperty('img_height');
                }
                $name = $_POST['name'];
                $formatFiles[$name] = [];
                foreach($_FILES[$name] as $fileKey=>$fileValue) {
                    foreach($fileValue as $k=>$v) {
                        $formatFiles[$name][$k][$fileKey] = $v;
                    }
                }
                $_FILES = $formatFiles;
                $product = new Product();
                $product->uploadImg($name, $wmax, $hmax);
            }
        }
    }