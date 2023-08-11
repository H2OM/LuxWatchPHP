<?php if(!empty($_SESSION['cart'])):?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Title</th> 
                    <th>Value</th>
                    <th>Price</th>
                    <th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($_SESSION['cart'] as $id=>$item):?>
                   <tr>
                        <td><a href="product/<?=$item['alias'];?>"><img src="images/<?=$item['img'];?>" alt="" style="max-width: 50px;"/></a></td>
                        <td><a href="product/<?=$item['alias'];?>"><?=$item['title'];?></a></td>
                        <td><?=$item['qty'];?></td>
                        <td><?=$item['price'];?></td>
                        <td><a href="/Projects/LuxuryWatchesPHP/public/cart/delete/?id=<?=$id ?>"><span data-id="<?=$id ?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></a></td>
                        <!-- <td><span data-id="<?=$id;?>" class="glyphicon glyphicon-remove text-danger del-item" style="cursor: pointer;" aria-hidden="true"></span></td> -->
                   </tr> 
                <?php endforeach;?>
                <tr>
                    <td>Total number of goods:</td>
                    <td colspan="4" class="text-right cart-qty"><?=$_SESSION['cart.qty'];?></td>
                </tr>
                <tr>
                    <td>Total price:</td>
                    <td colspan="4" class="text-right cart-sum"><?=$_SESSION['cart.currency']['symbol_left'] . $_SESSION['cart.sum'] . " " . $_SESSION['cart.currency']['symbol_right'];?></td>
                </tr>
            </tbody>    
        </table>
    </div>
    <?php else:?>
        <h3>Basket is empty</h3>
<?php endif;?>