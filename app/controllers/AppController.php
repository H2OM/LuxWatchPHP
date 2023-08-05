<?php
    namespace app\controllers;

    use shop\base\Controller;
    use app\models\AppModel;
    use app\widgets\currency\Currency;
    use shop\App;

    class AppController extends Controller {
        public function __construct($route) {
            parent::__construct($route);
            new AppModel();
            setcookie('currency', 'RUB', time()+ 60*60);
            App::$app->setProperty('currencies', Currency::getCurrencies());
            App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));  
            debug(App::$app->getProperties());
        }
    }