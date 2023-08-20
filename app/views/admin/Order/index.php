<?php
  $count = $data['count'];
  $pagination = $data['pagination'];
  $orders = $data['orders'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Order list</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <?php if(isset($_GET['nav']) && !empty($_GET['nav'])):?><li class="breadcrumb-item"><a href="<?=ADMIN ."/" . $_GET['nav'];?>">User list</a></li><?php endif;?>
              <li class="breadcrumb-item active">Order list</li>
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
                                    <th>Customer name</th>
                                    <th>Customer login</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Total price</th>
                                    <th>Date of create</th>
                                    <th>Date of last change</th>
                                    <th>Action</th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php foreach($orders as $k):?>
                                    <tr <?=$k['status'] ? 'style="background-color: #8ec78e;"' : '';?>>
                                        <td><?=$k['id'];?></td>
                                        <td><?=$k['name'];?></td>
                                        <td><?=$k['login'];?></td>
                                        <td><?=$k['status'] ? "Complete" : "On process";?></td>
                                        <td><?=$k['qty'];?></td>
                                        <td><?=$k['sum'] . " " . $k['curr'];?></td>
                                        <td><?=$k['date'];?></td>
                                        <td><?=$k['update_at'];?></td>
                                        <td><a href="<?=ADMIN . "/order/view?id=". $k['id'];?>"><i class='fa fa-fw fa-eye'></i></a></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?=count($orders);?> of <?=$count;?> Orders)</p>
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