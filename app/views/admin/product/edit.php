<?php
  $product = $data['product'];
  $categories = $data['categories'];
  $brands = $data['brands'];
  $gallery = $data['gallery'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Editing a product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>/product<?=((isset($_GET['currPage']) && is_int($_GET['currPage']))  ? "?page=" . $_GET['currPage'] : "");?>">Goods list</a></li>
              <li class="breadcrumb-item active">Product <?=$product['title'];?> editing</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="box" style="width: 100%;">
                <form action="<?=ADMIN;?>/product/edit" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="<?=$product['title'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias</label>
                            <input type="text" class="form-control" name="alias" id="alias" value="<?=$product['alias'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price" id="price" value="<?=$product['price'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="OldPrice">Old price</label>
                            <input type="number" class="form-control" name="old_price" id="old_price" value="<?=$product['old_price'];?>">
                        </div>
                        <div class="form-group">
                            <label for="status">
                               Status <input type="checkBox" class="form-control" name="status" id="status" value="1" style="width: 20px; height:20px; " <?=$product['status'] ? "checked" : "";?>> 
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="keywords">Keywords</label>
                            <input type="text" class="form-control" name="keywords" id="keywords" value="<?=$product['keywords'] ?? "";?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" value="<?=$product['description'] ?? "";?>">
                        </div>
                        <div class="form-group">
                            <label for="hit">
                                Hit <input type="checkBox" class="form-control" name="hit" id="hit" value="1" style="width: 20px; height:20px;" <?=$product['hit'] ? "checked" : "";?>>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <?php 
                                $menu = new app\widgets\menu\Menu([
                                    'tpl'=> DIR . '/menu/select.php',
                                    'container' => 'select',
                                    'class'=>'form-control',
                                    'elementCond' => 'selected',
                                    'elementCondId' => $product['category_id'],
                                    'attrs'=>[
                                        'name'=>'category',
                                        'id'=>'id'
                                    ]
                                ], false);
                                $menu->menuHtml = $menu->getMenuHtml($menu->getTree($categories));
                                $menu->output();
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <?php 
                                $menu = new app\widgets\menu\Menu([
                                    'tpl'=> DIR . '/menu/select.php',
                                    'container' => 'select',
                                    'class'=>'form-control',
                                    'elementCond' => 'selected',
                                    'elementCondId' => $product['brand_id'],
                                    'attrs'=>[
                                        'name'=>'brand',
                                        'id'=>'id'
                                    ]
                                ], false);
                                $menu->menuHtml = $menu->getMenuHtml($brands);
                                $menu->output();
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="content">
                                Content <textarea name="content" id="summernote" cols="30" rows="10"><?=$product['content'];?></textarea>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="related">Related products</label>
                            <select name="related[]" class="select2 select2-hidden-accessible" multiple="multiple" style="width: 100%;">
                            </select>
                        </div>
                        <div class="form-group">
                            <?php new \app\widgets\filter\Filter($product['attrs'], DIR . '/filter/admin_filter_tpl.php');?>
                        </div>
                        <div class="form-group">
                            <label for="img">Image from server</label>
                            <input type="text" class="form-control" name="img" id="img" placeholder="Img">
                        </div>
                        <div class="form-group" style="display: flex;">
                            <div class="col-md-4">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Basic image</h3>
                                </div>
                                <div class="card-body" style="display:flex;">
                                    <div  style="align-self: center;">
                                        <label for="single" class="btn btn-success" style="margin:0px;">
                                            Select a file
                                        </label>
                                        <input style="display: none; " type="file" name="file" id="single" data-url="product/add-image" data-name="single">
                                        <p style="margin: 0px;"><small>Recomend size: 125x200</small></p>
                                    </div>
                                    <div class="selectedImages single">
                                    <div class="image-card">
                                        <div class="image-card__image__wrap">
                                            <img src="<?=PATH . "/public/images/" . $product['img'];?>" alt="<?=$product['title'];?>" class="image-card__image">
                                        </div>
                                        <hr class="image-card__hr">
                                        <span class="image-card__desc">
                                            <?php
                                                $_SESSION['single'] = $product['img'];
                                                $img = htmlentities($product['img']);
                                                $img =  strlen($img) < 18 ? $img : substr(substr($img, 0, strrpos($img, '.')), 0, 10) . "..." . substr($img, strrpos($img, '.'), strlen($img));
                                                echo $img;
                                            ?>
                                        </span>
                                    </div>        
                                    </div>
                                </div>

                                <div class="overlay dark" style="display: none;">
                                    <i class="fas fa-2x fa-sync-alt"></i>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Gallery images</h3>
                                    </div>
                                    <div class="card-body" style="display:flex;">
                                        <div style="align-self: center;">
                                            <label for="multi" class="btn btn-success" style="margin:0px;">
                                                Select a file
                                            </label>
                                            <input style="display: none;" type="file" name="file" id="multi" data-url="product/add-image" data-name="multi" multiple>
                                            <p style="margin: 0px;"><small>Recomend size: 700x1000</small></p>
                                        </div>
                                        <div class="selectedImages multi">
                                        <?php foreach($gallery as $k=>$img):?>
                                            <?php $img = $img['img'];?>
                                            <div class="image-card">
                                                <div class="image-card__image__wrap">
                                                    <img src="<?=PATH . "/public/images/" . $img;?>" alt="<?=$product['title'].$k;?>" class="image-card__image">
                                                </div>
                                                <hr class="image-card__hr">
                                                <span class="image-card__desc">
                                                    <?php
                                                        $img = htmlentities($img);
                                                        $img =  strlen($img) < 18 ? $img : substr(substr($img, 0, strrpos($img, '.')), 0, 10) . "..." . substr($img, strrpos($img, '.'), strlen($img));
                                                        echo $img;
                                                    ?>
                                                </span>
                                            </div>
                                        <?php endforeach;?>
                                        </div>
                                    </div>

                                    <div class="overlay dark" style="display: none;">
                                        <i class="fas fa-2x fa-sync-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="box-footer">
                        <input type="hidden" name="id" value="<?=$product['id'];?>">
                        <button type="submit" class="btn btn-primary">Save</button>
                   </div>
                </form>    
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->