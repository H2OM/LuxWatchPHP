<?php

    define("DEBUG", 1);
    define("ROOT", dirname(__DIR__));
    define("APP", ROOT . '/app');
    define("CORE", ROOT . '/vendor/shop/core');
    define("LIBS", ROOT . '/vendor/shop/core/libs');
    define("CACHE", ROOT . '/tmp/cache');
    define("CONF", ROOT . '/config');
    define("LAYOUT", 'Watches');
    
    require_once ROOT . '/vendor/autoload.php';