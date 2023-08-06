<?php
    $DATA = json_decode(file_get_contents("php://input"), true);
    if(isset($DATA['curr']) && !empty($DATA['curr'])) {
        echo 1;
        $tmp = json_decode(file_get_contents('currencies.json',true));
        foreach($tmp as $k=>$v) {
            if($k == $DATA['curr']) {
                setcookie('currency', $k, time() +3600, '/');
            }   
        }
    }