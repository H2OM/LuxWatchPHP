<?php
    namespace app\controllers\admin;

use app\models\admin\Product;
use Exception;
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
        public function editAction() {
            $product_id = $_GET['id'] ?? $_POST['id'] ?? redirect(ADMIN. "/product");
            
            if(!empty($_POST)) {
                $product = new Product();
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
                    Db::commitTransaction();
                    $_SESSION['success'] = "Product successfully updated";
                } catch (\Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] .= "Error updating product";
                } finally {
                    redirect();
                }
            }
            try {
                $product = Db::getPreparedQuery("SELECT product.*, attribute_product.attr_id AS `attr`, attribute_value.attr_group_id AS `group` FROM `product` 
                                                LEFT JOIN `attribute_product` ON attribute_product.product_id=product.id 
                                                LEFT JOIN `attribute_value` ON attribute_value.id=attribute_product.attr_id 
                                                WHERE product.id=?", [["VALUE"=>$product_id, "INT"=>true, "PARAMVALUE"=>128]]);
                
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
                $this->set(compact('product', 'categories', 'brands'));
                $this->setMeta("Product edit");
            } catch (\Exception $e) {
                $_SESSION['error'] .= "Database connecting error";
                redirect();
            }
        }
        public function addAction () {
            if(!empty($_POST)) {
                $product = new Product();
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
                    Db::getPreparedQuery("INSERT INTO `product` (title, alias, price, old_price, status, keywords, description, img, hit, category_id, brand_id, content) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $preparedQueryAttr);
                    $id = Db::getQuery("SELECT LAST_INSERT_ID();", false, true);
                    if(!isset($_POST['attrs'])) throw new Exception('');
                    $product->editDetails($id, $_POST['attrs'], "`attribute_product`","attr_id", true);
                    if(isset($_POST['related'])) {
                        $product->editDetails($id, $_POST['related'], "`related_product`","related_id", true);
                    };
                    Db::commitTransaction();
                    $_SESSION['success'] = "Product successfully added";
                } catch (\Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] .= "Error adding product";
                } finally {
                    redirect();
                }
            }
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
    }