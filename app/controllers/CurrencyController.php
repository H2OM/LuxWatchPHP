<?php
    namespace app\controllers;

    use app\widgets\currency\Currency;
    

    class CurrencyController {
        public function changeAction() {
            echo 1;
            $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
            if($currency) {
                foreach(Currency::getCurrencies() as $k=>$v) {
                    if($currency == $k) {
                        setcookie('currency', $k, time() +3600, '/');
                    }
                }
            }
            redirect();
        }
    }