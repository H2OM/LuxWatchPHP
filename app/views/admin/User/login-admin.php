<div class="login-box">
  <div class="login-logo">
    <b>Admin</b>LTE
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
        <?php 
            if(isset($_SESSION['error'])) {
                echo "<span style='padding: 4px; text-align: center; display: block; margin-bottom: 16px; color: white; font-size: 17px; background-color:red; border-radius: 5px; border: 1px solid rgba(0,0,0,.1);'>" . ucfirst($_SESSION['error']) . "</span>";
                unset($_SESSION['error']);
            }
        ?>
        <?php 
            if(isset($_SESSION['success'])) {
                echo "<span style='padding: 4px; text-align: center; display: block; margin-bottom: 16px; color: white; font-size: 17px; background-color:green; border-radius: 5px; border: 1px solid rgba(0,0,0,.1);'>" . ucfirst($_SESSION['success']) . "</span>";
                unset($_SESSION['success']);
            }
        ?>
      <form action="<?=ADMIN;?>/user/login-admin" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="login" placeholder="login">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>