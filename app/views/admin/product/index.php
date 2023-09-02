<?php
  $count = $data['count'];
  $pagination = $data['pagination'];
  $products = $data['products'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Goods list</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">Goods list</li>
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
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php foreach($products as $product):?>
                                    <tr <?=($product['status'] ? "style: background-color: '#c9c9c9'" : "");?>>
                                        <td><?=$product['id'];?></td>
                                        <td><?=$product['cat'];?></td>
                                        <td><?=$product['brand'];?></td>
                                        <td><?=$product['title'];?></td>
                                        <td><?=$product['price'];?></td>
                                        <td><?=$product['status'] ? "Active" : "Unactive";?></td>
                                        <td><a href="<?=ADMIN . "/product/edit?id=". $product['id'];?>&currPage=<?=$pagination->currentPage;?>"><i class='fa fa-fw fa-eye'></i></a></td>
                                        <td><a href="<?=ADMIN . "/product/delete?id=". $product['id'];?>" style="color: red; text-decoration:underline;">Delete</a></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?=count($products) + ($pagination->currentPage-1) * $pagination->perpage;?> of <?=$count;?> Goods)</p>
                        <div class="align" style="display:flex; justify-content:center;">
                            <?php if($pagination->countPages > 1): ?>
                                <?=$pagination;?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->