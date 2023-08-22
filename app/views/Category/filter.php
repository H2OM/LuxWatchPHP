<?php 
    $curr = \shop\App::$app->getProperty('currency');
    
    // $products = $data['products'];
    // $pagination = $data['pagination'];
    // $total = $data['total'];
?>
<?php if(!empty($products)&& count($products) !==0):?>
<?php if(!array_key_exists("0", $products)) $products = [$products];?>
    <?php foreach($products as $product):?>
    <div class="col-md-4 product-left p-left">
        <div class="product-main simpleCart_shelfItem">
            <a href="product/<?=$product['alias'];?>" class="mask"><img class="img-responsive zoom-img" src="images/<?=$product['img'];?>" alt="" /></a>
            <div class="product-bottom">
                <h3><?=$product['title'];?></h3>
                <p>Explore Now</p>
                <h4>
                    <a class="item_add add-to-cart-link" href="cart/add?id=<?=$product['id'];?>" data-id="<?=$product['id'];?>"><i></i></a> 
                    <span class=" item_price"><?=$curr['symbol_left'];?><?php echo $product['price'] * $curr['value'];?><?=' ' . $curr['symbol_right'];?></span>
                    <?php if($product['old_price']):?>
                        <del><?=$curr['symbol_left'];?><?php echo $product['old_price']* $curr['value'];?><?=' ' . $curr['symbol_right'];?></del>  
                    <?php endif;?>
                </h4>
            </div>
            <div class="srch srch1">
                <span>-50%</span>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <div class="clearfix"></div>
    <div class="text-center">
        <p><?=(($pagination->currentPage*3) <= $total ? $pagination->currentPage*3 : $total) . " goods of: $total";?></p>
        <?php if($pagination->countPages > 1):?>
            <?=$pagination;?>
        <?php endif;?>
    </div>
<?php else: ?>
    <h3>Not found...</h3>
<?php endif;?>
