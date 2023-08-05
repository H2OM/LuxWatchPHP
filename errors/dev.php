<?php
   echo <<<ERR
      <h1>Ошибка $responce</h1>
      <p>Код ошибки: $errstr</p>
      <p>Файл: $errfile</p>
      <p>Строка: $errline</p>
   ERR;

?>