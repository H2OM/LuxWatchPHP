
<div class="div container">
    <h2>Hello, <?=$_SESSION['user']['name'];?></h2>
    <ul>
        <li>Your login: <?=$_SESSION['user']['login'];?></li>
        <li>Your email: <?=$_SESSION['user']['email'];?></li>
    </ul>
    <form action="" method="post" action="user/changeEmail" role="form" id="ChangeMailForm">
        <label>
            Type new email for change: <input type="email" name="email" minlength="4" required> <input type="submit" value="SUBMIT">
        </label>
    </form>
</div>