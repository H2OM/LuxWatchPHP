<?php
  $user = $data['user'];
 ?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Editing a user</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>/user">User list</a></li>
              <li class="breadcrumb-item active">Editing a user</li>
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
                <form action="<?=ADMIN;?>/user/edit" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" name="login" id="login" value="<?=h($user['login']);?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password" placeholder="Enter a new password (not necessary)">
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?=h($user['name']);?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?=h($user['email']);?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Number</label>
                            <input type="number" class="form-control" name="address" id="address" value="<?=h($user['address']);?>" required>
                        </div>
                   </div>
                   <div class="box-footer">
                        <input type="hidden" name="id" value="<?=$user['id'];?>">
                        <button type="submit" class="btn btn-primary">Save</button>
                   </div>
                </form>    
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->