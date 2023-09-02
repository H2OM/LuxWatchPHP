<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Перенаправление на страницу оплаты...</p>
    <?php if(!empty($_SESSION['payment'])):?>
        <form id="redirectForm" action="">
            <!-- ////форма из платежной системы -->
        </form>
    <?php endif;?>
    <script>
        setTimeout(()=>{
            document.querySelector('#redirectForm').submit();
        },2000);
    </script> 
</body>
</html>