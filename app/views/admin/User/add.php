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
              <li class="breadcrumb-item active">New user</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form method="post" action="<?=PATH;?>/user/signup" role="form">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="login">Login</label>
                            <input class="form-control" name="login" id="login" type="text" value="<?= $_SESSION['form_data']['login'] ?? '' ?>" required>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="password">Password</label>
                            <input class="form-control" name="password" id="password" type="password" data-minlength="6" data-error="The password must contain at least 6 characters" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="email">Email</label>
                            <input class="form-control" name="email" id="email" type="email" value="<?= $_SESSION['form_data']['email'] ?? '' ?>" required>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="name">Name</label>
                            <input class="form-control" name="name" id="name" type="text" value="<?= $_SESSION['form_data']['name'] ?? '' ?>" required>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="address">Phone</label>
                            <input class="form-control" name="address" id="address" value="<?= $_SESSION['form_data']['address']  ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
                <?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->