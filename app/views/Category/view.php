<?php 
    $curr = \shop\App::$app->getProperty('currency');
    $products = $data['products'];
    $breadcrumbs = $data['breadcrumbs'];
    $pagination = $data['pagination'];
    $total = $data['total'];
?>
    
    <div class="breadcrumbs">
        <div class="container">
            <div class="breadcrumbs-main">
                <ol class="breadcrumb">
                    
                    <?=$breadcrumbs;?>
                </ol>
            </div>
        </div>
    </div>
    <div class="prdt"> 
		<div class="container">
			<div class="prdt-top">
				<div class="col-md-9 prdt-left">
                    <?php if(!empty($products)&& count($products) !==0):?>
                    <?php if(!array_key_exists("0", $products)) $products = [$products];?>
					<div class="product-one">
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
					</div>
                    <?php else: ?>
                        <h3>Nothing in this category, yet...</h3>
                    <?php endif;?>
				</div>	
				<div class="col-md-3 prdt-right">
					<div class="w_sidebar">
						<?php new \app\widgets\filter\Filter();?>
						
						
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>