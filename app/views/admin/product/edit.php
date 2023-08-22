<?php
  $product = $data['product'];
  $categories = $data['categories'];
  $brands = $data['brands'];
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
                            <label for="img">Img</label>
                            <input type="text" class="form-control" name="img" id="img" value="<?=$product['img'];?>" required>
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
                            <?php new \app\widgets\filter\Filter($product['attrs'], DIR . '/filter/admin_filter_tpl.php');?>
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