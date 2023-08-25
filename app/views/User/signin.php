
<div class="register">
		<div class="container">
            <?php if(!isset($_SESSION['user'])):?>
            <div class="register-top heading">
				<h2>LOGIN</h2>
			</div>
			<div class="register-main">
                <form action="" method="post" action="user/signin" id="login" role="form">
                        <div class="col-md-6 account-left">
                            <input type="text" placeholder="Login" name="login" tabindex="4" required>
                            <input placeholder="Password" name="password" type="password" tabindex="4" required>
                        </div>
                        <div class="address submit">
                            <input type="submit" value="Submit">
                        </div>
                </form>
                <div class="clearfix"></div>
			</div>
            <?php else: $_SESSION['success'] = "You already login";?>
            <?php endif;?>
        </div>
	</div>