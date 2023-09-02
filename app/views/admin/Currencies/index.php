<?php
  $currencies = $data['currencies'];
  
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Currencies</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">Currencies</li>
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
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Symbol left</th>
                                    <th>Symbol right</th>
                                    <th>Value</th>
                                    <th>Base</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php foreach($currencies as $currency):?>
                                    <tr>
                                        <td><?=$currency['title'];?></td>
                                        <td><?=$currency['code'];?></td>
                                        <td><?=$currency['symbol_left'];?></td>
                                        <td><?=$currency['symbol_right'];?></td>
                                        <td><?=$currency['value'];?></td>
                                        <td><?=$currency['base'] ? "BASE" : "";?></td>
                                        <td><a href="<?=ADMIN . "/currencies/edit?id=". $currency['id'];?>&name=<?=$currency['title'];?>"><i class='fa fa-fw fa-eye'></i></a></td>
                                        <td><a href="<?=ADMIN . "/currencies/delete?id=". $currency['id'];?>" style="color: red; text-decoration:underline;">Delete</a></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->