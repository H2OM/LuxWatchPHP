<?php 
    namespace app\controllers\admin;

use ErrorException;
use Exception;
use shop\Db;
     
    class CurrenciesController extends AppController {
        public function indexAction() {
            $currencies = Db::getQuery("SELECT * FROM currency");
            $this->set(compact("currencies"));
            $this->setMeta("Currencies");
        }
        public function addAction() {
            if(!empty($_POST)) {
                try {
                    // debug($_POST);
                    Db::beginTransaction();
                    if(!isset($_POST['title'], $_POST['code'], $_POST['value'], $_POST['symbol'], $_POST['symbolpos'])) throw new ErrorException("", 400);
                    if(isset($_POST['base'])) Db::getQuery("UPDATE currency SET base='0' WHERE base='1'");
                    $symbol_type = '';
                    switch($_POST['symbolpos']) {
                        case "right":
                            $symbol_type = "symbol_left, symbol_right";
                            break;
                        case "left":
                            $symbol_type = "symbol_right, symbol_left";
                            break;
                        default: 
                            throw new ErrorException("", 400);
                    }
                    Db::getPreparedQuery("INSERT INTO currency (title, code, value, base, $symbol_type) VALUES(?, ?, ?, '". (isset($_POST['base']) ? '1' : '0') ."', '', ?)",[
                        ["VALUE"=>$_POST['title'], "PARAMVALUE"=>42],
                        ["VALUE"=>$_POST['code'], "PARAMVALUE"=>3],
                        ["VALUE"=>$_POST['value'], "INT"=>true],
                        ["VALUE"=>$_POST['symbol'], "PARAMVALUE"=>10]
                    ]);
                    Db::commitTransaction();
                    $_SESSION['success'] = "Currensy successfully added";
                } catch (Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] .= "Arguments not supported";
                } finally {
                    redirect();
                }
            }
            $this->setMeta("Currency edit");
        }
        public function editAction() {
            $id = $_POST['id']  ?? $_GET['id'] ?? redirect(ADMIN. "/currencies");
            if(!empty($_POST)) {
                try {
                    Db::beginTransaction();
                    if(!isset($_POST['title'], $_POST['code'], $_POST['value'], $_POST['symbol'], $_POST['symbolpos'])) throw new ErrorException("", 400);
                    if(isset($_POST['base'])) Db::getQuery("UPDATE currency SET base='0' WHERE base='1'");
                    $symbol_type = '';
                    switch($_POST['symbolpos']) {
                        case "right":
                            $symbol_type = "symbol_right=?, symbol_left=''";
                            break;
                        case "left":
                            $symbol_type = "symbol_left=?, symbol_right=''";
                            break;
                        default: 
                            throw new ErrorException("", 400);
                    }
                    Db::getPreparedQuery("UPDATE currency SET title=?, code=?, value=?, base='". (isset($_POST['base']) ? '1' : '0') ."', $symbol_type WHERE id=?",[
                        ["VALUE"=>$_POST['title'], "PARAMVALUE"=>42],
                        ["VALUE"=>$_POST['code'], "PARAMVALUE"=>3],
                        ["VALUE"=>$_POST['value'], "INT"=>true],
                        ["VALUE"=>$_POST['symbol'], "PARAMVALUE"=>10],
                        ["VALUE"=>$id, "INT"=>true]
                    ]);
                    Db::commitTransaction();
                    $_SESSION['success'] = "Currensy successfully updated";
                } catch (Exception $e) {
                    Db::rollbackTransaction();
                    $_SESSION['error'] = "Arguments not supported";
                } finally {
                    redirect();
                }
            }
            $currency = Db::getPreparedQuery("SELECT * FROM currency WHERE id=?", [["VALUE"=>$id, "INT"=>true]]);
            $this->setMeta("Currency edit");
            $this->set(compact("currency"));
        }
        public function deleteAction() {
            $id = $_GET['id'] ?? redirect();
            try {
                Db::getPreparedQuery("DELETE FROM `currency` WHERE id=?", [["VALUE"=>$id, "INT"=>true]]);
                $_SESSION['success'] = "Successfully deleted";
            } catch(Exception $e) {
                $_SESSION['error'] .= "Error deleting currency";
            } finally {
                redirect();
            }
        }
        
    }