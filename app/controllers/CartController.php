<?php
    namespace app\controllers;

use app\models\Cart;
use Error;
use shop\Db;

    class CartController extends AppController {
        public function addAction() {
            $DATA = json_decode(file_get_contents("php://input"), true);
            $id = $DATA['id'] ?? null;
            $qty = $DATA['qty'] ?? null;
            $mode_id = $DATA['mod'] ?? null;
            $mod = null;
            if($id) {
                $product = Db::getPreparedQuery("SELECT * FROM product WHERE id=?",[["VALUE"=>$id, "PARAMVALUE"=>128, "INT"=>true]]);
                if(!$product) throw new Error();
                if($mode_id) {
                    $mod = Db::getPreparedQuery("SELECT * FROM modification WHERE id =? AND product_id =?", [
                        ["VALUE"=>$mode_id, "PARAMVALUE"=>128, "INT"=>true], 
                        ["VALUE"=>$id, "PARAMVALUE"=>128, "INT"=>true]
                    ]);

                }
            }
            $cart = new Cart();
            $cart->addToCart($product, $qty, $mod);   
            if($this->isAjax()) {
                $this->loadView('cart_modal');
            } 
            redirect();            
        }
        public function showAction() {
            $this->loadView('cart_modal');
        }
        public function deleteAction() {
            $DATA = json_decode(file_get_contents("php://input"), true);
            $id = $DATA['id'] ?? null;
            if(isset($_SESSION['cart'][$id])) {
                $cart = new Cart();
                $cart->deleteItem($id);
            } 
            if($this->isAjax()) {
                $this->loadView('cart_modal');
            } 
            redirect();
        }
        public function clearAction(){
            unset($_SESSION['cart']);
            unset($_SESSION['cart.qty']);
            unset($_SESSION['cart.sum']);
            unset($_SESSION['cart.currency']);
            $this->loadView('cart_modal');
        }
    }