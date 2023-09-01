<?php 
    namespace app\controllers\admin;

use ErrorException;
use Exception;
use shop\Db;
     
    class FilterController extends AppController {
        public function filtersCleaningAction() {
            $id = $_GET['id'] ?? redirect();
            try {
                if(!isset($_GET['path'])) throw new Exception();
                $sql_table = '';
                switch($_GET['path']) {
                    case "filter":
                        $sql_table = 'attribute_value';
                        break;
                    case "group":
                        $sql_table = 'attribute_group';
                        break;
                    default: 
                        throw new Exception();
                }
                Db::getPreparedQuery("DELETE FROM $sql_table WHERE id=?", [["VALUE"=>$id, "INT"=>true]]);
                $_SESSION['success'] = "Succefully deleting";
            } catch(Exception $e) {
                $_SESSION['error'] = (empty($_SESSION['error']) ? "Unexpected Error": $_SESSION['error']);
            } finally {
                redirect();
            }
        }
        public function groupAddAction() {
            if(isset($_POST['title'])) {
                try {
                    Db::getPreparedQuery("INSERT INTO attribute_group (title) VALUES(?)", [["VALUE"=>$_POST['title'], "PARAMVALUE"=>64]]);
                    $_SESSION['success'] = "New group successfully added";
                } catch(Exception $e) {
                    
                } finally {
                    redirect();
                }
            } else {
                redirect();
            }
        }
        public function filterAddAction() {
            if(isset($_POST['title'], $_POST['group'])) {
                try {
                    Db::getPreparedQuery("INSERT INTO attribute_value (value, attr_group_id) VALUES(?, ?)", [["VALUE"=>$_POST['title'], "PARAMVALUE"=>64],["VALUE"=>$_POST['group'], "INT"=>true]]);
                    $_SESSION['success'] = "New filter successfully added";
                } catch (Exception $e) {
                    
                } finally {
                    redirect();
                }
            } else {
                redirect();
            }
        } 
        public function filterEditAction() {
            if(isset($_GET['groups'])) {
                try {
                    $groups = Db::getQuery("SELECT id, title FROM attribute_group");
                    echo json_encode($groups);
                } catch (Exception $e) {
                    throw new ErrorException("Database error", 500);
                }   
            } else if(isset($_POST['title'], $_POST['titleId'], $_POST['groupId'])) {
                debug($_POST);
                Db::getPreparedQuery("UPDATE attribute_value SET value=?, attr_group_id=? WHERE value=?", [
                    ["VALUE"=>$_POST['title'], "PARAMVALUE"=>64],
                    ["VALUE"=>$_POST['groupId'], "INT"=>true],
                    ["VALUE"=>$_POST['titleId'], "PARAMVALUE"=>64]
                ]);
            } else {
                throw new ErrorException("Args not supported", 400);
            }
            die;
        }
        public function groupEditAction() {
            if(isset($_POST['titleId'], $_POST['title'])) {
                try {
                    Db::getPreparedQuery("UPDATE attribute_group SET title=? WHERE title=?", [["VALUE"=>$_POST['title'], "PARAMVALUE"=>64], ["VALUE"=>$_POST['titleId'], "PARAMVALUE"=>64]]);
                } catch (Exception $e) {
                    throw new ErrorException("Database error", 500);
                }
            } else {
                throw new ErrorException("Args not supported", 400);
            }
            die;
        }
        public function attributeGroupAction() {
            $attrs_group = Db::getQuery(
                "SELECT attribute_group.*, COUNT(attribute_value.value) AS `filters` FROM attribute_group
                LEFT JOIN attribute_value ON attribute_value.attr_group_id=attribute_group.id
                GROUP BY attribute_group.title;"
            );
            $this->setMeta("Filters groups");
            $this->set(compact("attrs_group"));
        }
        public function attributeAction() {
            $filters = Db::getQuery(
                "SELECT attribute_value.id, attribute_value.value AS `filter`, attribute_group.title AS `group`, attribute_group.id AS `groupId`
                FROM attribute_value 
                RIGHT JOIN `attribute_group` ON attribute_value.attr_group_id=attribute_group.id;
            ");
            $groups = [];
            foreach($filters as $filter) {
                $groups[$filter['groupId']] = $filter['group'];
            }
            $this->setMeta("Filters");
            $this->set(compact("filters", "groups"));
        }
    }

