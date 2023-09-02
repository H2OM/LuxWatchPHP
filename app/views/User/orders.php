<?php
    $orders = $data['orders'];
?>
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
                                    <th style="width: 20%;">ID</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Total price</th>
                                    <th style="width: 20%;">Date of create</th>
                                    <th style="width: 20%;">Date of last change</th>
                                </tr>   
                            </thead>
                            <tbody>
                                <?php foreach($orders as $k):?>
                                    <tr <?=$k['status'] ? 'style="background-color: #8ec78e;"' : '';?>>
                                        <td><?=$k['id'];?></td>
                                        <td><?=$k['status'] ? "Complete" : "On process";?></td>
                                        <td><?=$k['sum'] . " " . $k['currency'];?></td>
                                        <td><?=$k['date'];?></td>
                                        <td><?=$k['update_at'];?></td>
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