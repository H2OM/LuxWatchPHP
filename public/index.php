<?php
    require_once dirname(__DIR__) . '/config/init.php';
    require_once CONF . '/routes.php';
    require_once LIBS . '/functions.php';

    new \shop\App();
    // debug(\shop\App::$app->getProperties());

    if(isset($_GET['clear'])) {
        file_put_contents(ROOT . '/tmp/errors.log', "");
    }

