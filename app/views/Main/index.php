<!--banner-starts-->
    <div class="bnr" id="home">
		<div  id="top" class="callbacks_container">
			<ul class="rslides" id="slider4">
			    <li>
					<img src="images/bnr-1.jpg" alt=""/>
				</li>
				<li>
					<img src="images/bnr-2.jpg" alt=""/>
				</li>
				<li>
					<img src="images/bnr-3.jpg" alt=""/>
				</li>
			</ul>
		</div>
		<div class="clearfix"> </div>
	</div>
	<!--banner-ends--> 
	<!--Slider-Starts-Here-->
				
			<!--End-slider-script-->
	<!--about-starts-->
    <?php if($data['brands']): ?>
	<div class="about"> 
		<div class="container">
			<div class="about-top grid-1">
                <?php foreach($data['brands'] as $brand): ?>
                    <div class="col-md-4 about-left">
                        <figure class="effect-bubba">
                            <img class="img-responsive" src="images/<?echo $brand['img'];?>" alt="<?echo $brand['title'];?>"/>
                            <figcaption>
                                <h2><?echo $brand['title'];?></h2>
                                <p><?echo $brand['description'];?></p>	
                            </figcaption>			
                        </figure>
                    </div>
                <?php endforeach;?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
    <?php endif; ?>
	<!--about-end-->
	<!--product-starts-->
    <?php if($data['hits']):?>
	<?php $curr = \shop\App::$app->getProperty('currency');?>
	<div class="product"> 
		<div class="container">
			<div class="product-top">
				<div class="product-one">
                    <?php foreach($data['hits'] as $hit):?>
					<div class="col-md-3 product-left">
						<div class="product-main simpleCart_shelfItem">
							<a href="product/<?php echo $hit['alias'];?>" class="mask"><img class="img-responsive zoom-img" src="images/<?php echo $hit['img'];?>" alt="<?php echo $hit['title'];?>" /></a>
							<div class="product-bottom">
								<h3><a href="product/<?php echo $hit['alias'];?>" class="mask"><?php echo $hit['title'];?></a></h3>
								<p>Explore Now</p>
								<h4>
                                    <a data-id="<?=$hit['id'];?>" class="add-to-cart-link" href="cart/add?id=<?php echo $hit['id'];?>"><i></i></a> 
                                    <span class=" item_price"><?=$curr['symbol_left'];?><?php echo $hit['price'] * $curr['value'];?><?=' ' . $curr['symbol_right'];?></span>
                                    <?php if($hit['old_price']):?>
                                        <small><del><?=$curr['symbol_left'];?><?php echo $hit['old_price']* $curr['value'];?><?=' ' . $curr['symbol_right'];?></del></small>    
                                    <?php endif;?>
                                </h4>
							</div>
                            <?php if($hit['old_price']):?>
                                <div class="srch" style="display: block;">
								    <span><?php echo round((($hit['price']*100)/$hit['old_price'])-100,1);?>%</span>
							    </div>
                            <?php endif;?>
							
						</div>
					</div>
                    <?php endforeach;?>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
    <?php endif;?>
	<!--product-end-->