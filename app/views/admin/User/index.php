<?php
  $count = $data['count'];
  $pagination = $data['pagination'];
  $allUsers = $data['allUsers'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User list</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">User list</li>
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
                                    <th>Name</th>
                                    <th>Login</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Orders</th>
                                    <th>View orders</th>
                                    <th>Edit</th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php foreach($allUsers as $user):?>
                                  <tr>
                                      <td><?=$user['name'];?></td>
                                      <td><?=$user['login'];?></td>
                                      <td><?=$user['email'];?></td>
                                      <td><?=$user['address'];?></td>
                                      <td><?=$user['orderCount'];?></td>
                                      <td><a href="<?=ADMIN;?>/order?searchByLogin=<?=$user['login'];?>&nav=user"><i class='fa fa-fw fa-eye'></i></a></td>
                                      <td><a href="<?=ADMIN;?>/user/edit?id=<?=$user['id'];?>">Edit</a></td>
                                  </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?=count($allUsers);?> of <?=$count;?> Users)</p>
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