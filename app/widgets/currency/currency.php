<?php
    namespace app\widgets\currency;

    use shop\Db;

    class Currency {
        protected $tpl;
        protected $currencies;
        protected $currency;

        public function __construct() {
            $this->tpl =  __DIR__ . '/currency_tpl/currency.php';
        }

        protected function run() {
            $this->getHtml();
        }
        public static function getCurrencies () {
            return Db::getQuery("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC", true);
        }
        public static function getCurrency ($currencies) {
            if(isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)){
                $key = $_COOKIE['currency'];
            } else {
                $key = key($currencies);
            }
            $currency =$currencies[$key];
            $currency['code'] = $key;
            return $currency;
        }
        protected function getHtml () {

        }
    }