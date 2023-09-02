<div class="container">
    <div class="register-top heading">
        <h2>Change personal data</h2>
    </div>
    <div class="box" style="width: 100%;">
        <form action="user/edit" method="post">
            <div class="box-body">
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" class="form-control" name="login" id="login" value="<?=h($_SESSION['user']['login']);?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" name="password" id="password" placeholder="Enter a new password (not necessary)">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?=h($_SESSION['user']['name']);?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?=h($_SESSION['user']['email']);?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Number</label>
                    <input type="number" class="form-control" name="address" id="address" value="<?=h($_SESSION['user']['address']);?>" required>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>    
    </div>
</div>  