<?php
    namespace app\controllers;

    use app\widgets\currency\Currency;
    

    class CurrencyController {
        public function changeAction() {
            $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
            if($currency) {
                foreach(json_decode(file_get_contents('currencies.json',true)) as $k=>$v) {
                    if($currency == $k) {
                        setcookie('currency', $k, time() +3600, '/');
                    }
                }
            }
            redirect();
        }
    }