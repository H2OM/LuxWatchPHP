<?php
  $order = $data['order'];
  $order_products = $data['order_products'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Order № <?=$order['id'];?></h1>
            <?php if(!$order['status']):?> 
                <a href="<?=ADMIN;?>/order/change?id=<?=$order['id'];?>&status=1" class="btn btn-success btn-xs">Accept</a>
                <?php else:?>
                   <a href="<?=ADMIN;?>/order/change?id=<?=$order['id'];?>&status=0" class="btn btn-default btn-xs">Unstage</a> 
            <?php endif;?>
            <a href="<?=ADMIN;?>/order/delete?id=<?=$order['id'];?>" class="btn btn-danger btn-xs delete">Reject</a> 
        </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>/order">Order list</a></li>
              <li class="breadcrumb-item active">Order № <?=$order['id'];?></li>
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
                            <tbody>
                                <tr>
                                    <td>Order №:</td>
                                    <td><?=$order['id'];?></td>
                                </tr>
                                <tr>
                                    <td>Date of order:</td>
                                    <td><?=$order['date'];?></td>
                                </tr>
                                <tr>
                                    <td>Date of change:</td>
                                    <td><?=$order['update_at'];?></td>
                                </tr>
                                <tr>
                                    <td>Total:</td>
                                    <td><?=$order['qty'];?></td>
                                </tr>
                                <tr>
                                    <td>Total price:</td>
                                    <td><?=$order['sum'] . " " . $order['curr'];?></td>
                                </tr>
                                <tr>
                                    <td>Customer:</td>
                                    <td><?=$order['name'] . " (id: " . $order['user_id'] .")";?></td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <?php
                                        if($order['status']) echo '<td style="background-color: #8ec78e;">Complete</td>';
                                            else echo '<td>On process</td>';
                                    ?>
                                </tr>
                                <tr>
                                    <td>Customer:</td>
                                    <td><?=$order['name'];?></td>
                                </tr>
                                <tr>
                                    <td>Note:</td>
                                    <td><?=$order['note'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h3>Order details</h3>
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Value</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $qty = 0; if(!is_array($order_products[array_key_first($order_products)])) $order_products = [$order_products];
                                        foreach($order_products as $product):?>
                                        <tr>
                                            <td><?=$product['id'];?></td>
                                            <td><?=$product['title'];?></td>
                                            <td><?=$product['qty']; $qty += $product['qty']?></td>
                                            <td><?=$product['price'];?></td>
                                        </tr>
                                    <?php endforeach;?>
                                    <tr class="active">
                                        <td colspan="2">
                                            <b>Result:</b>
                                        </td>
                                        <td><b><?=$qty;?></b></td>
                                        <td><b><?=$order['sum'] . " " . $order['curr'];?></b></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->