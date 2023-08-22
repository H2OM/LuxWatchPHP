<?php
    namespace app\controllers\admin;

use app\models\Category;
use shop\Db;

    class CategoryController extends AppController {
        public function indexAction() {
            $this->setMeta("Category list");
        }
        public function  deleteAction() {
            $id = $_GET['id'];
            $errors = '';
            $children = Db::getPreparedQuery('SELECT COUNT(*) FROM category WHERE parent_id=?',[["VALUE"=>$id, "INT"=>true, "PARAMVALUE"=>100]], true);
            if($children) {
                $errors .= '<li>Deleting injected, this category have subcategories</li>';
            }
            $products = Db::getPreparedQuery('SELECT COUNT(*) FROM product WHERE category_id=?',[["VALUE"=>$id, "INT"=>true, "PARAMVALUE"=>100]], true);
            if($products) {
                $errors .= '<li>Deleting injected, this category have products</li>';
            }
            if($errors) {
                $_SESSION['error'] = "<ul>$errors</ul>";
                redirect();
            }
            try {
                Db::beginTransaction();
                Db::getPreparedQuery('DELETE FROM category WHERE id=?',[["VALUE"=>$id, "INT"=>true, "PARAMVALUE"=>100]]);
                $_SESSION['success'] = "Success deleting category";
                Db::rollbackTransaction();
            } catch (\PDOException $e) {
                $_SESSION['error'] .= "Error with deleting from data base";
            } finally {
                redirect();
            }
        }
        public function addAction() {
            if(!empty($_POST)) {
                $category = new Category();
                $category->load($_POST);
                try {
                    Db::getPreparedQuery("INSERT INTO `category` (title, alias, parent_id, keywords, description) VALUES(?, ?, ?, ?, ?)", [
                        ["VALUE"=>$category->attributes['title'], "PARAMVALUE"=>42],
                        ["VALUE"=>$category->attributes['title'] . $category->attributes['parent_id'], "PARAMVALUE"=>42],
                        ["VALUE"=>$category->attributes['parent_id'],"INT"=>true, "PARAMVALUE"=>100],
                        ["VALUE"=>$category->attributes['keywords'], "PARAMVALUE"=>128],
                        ["VALUE"=>$category->attributes['description'], "PARAMVALUE"=>128]
                    ]);
                    $_SESSION['success'] = "Category `". $category->attributes['title'] . "` success added!";
                } catch (\PDOException $e) {
                    $_SESSION['error'] .= "<br>Something wrong with inserting in data base (code: " . $e->getCode(). ")";
                } finally {
                    redirect();
                }
            }
            $this->setMeta("New category");
        }
        public function editAction() {
            if(!empty($_POST)) {
                $id = $_POST['id'];
                $category = new Category();
                $category->load($_POST);
                try {
                    Db::getPreparedQuery("UPDATE `category` SET title=?, alias=?, parent_id=?, keywords=?, description=? WHERE id=?", [
                        ["VALUE"=>$category->attributes['title'], "PARAMVALUE"=>42],
                        ["VALUE"=>$category->attributes['title'] . $category->attributes['parent_id'], "PARAMVALUE"=>42],
                        ["VALUE"=>$category->attributes['parent_id'],"INT"=>true, "PARAMVALUE"=>100],
                        ["VALUE"=>$category->attributes['keywords'], "PARAMVALUE"=>128],
                        ["VALUE"=>$category->attributes['description'], "PARAMVALUE"=>128],
                        ["VALUE"=>$id,"INT"=>true, "PARAMVALUE"=>100]
                    ]);
                    $_SESSION['success'] = "Category `". $category->attributes['title'] . "` success update!";
                } catch (\PDOException $e) {
                    $_SESSION['error'] .= "<br>Something wrong with inserting in data base (code: " . $e->getCode(). ")";
                } finally {
                    redirect();
                }
            }
            $id = $_GET['id'];
            $category = (Db::getQuery("SELECT * FROM category WHERE id=$id"))[0];
            $this->setMeta("Editing a category - {$category['title']}");
            $this->set(compact('category'));
        }
    }