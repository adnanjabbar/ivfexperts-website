<?php
session_start();
if(isset($_POST['login'])){
  if($_POST['username']=="admin" && $_POST['password']=="password123"){
    $_SESSION['admin']=true;
    header("Location: dashboard.php");
  }
}
?>
<form method="post">
<input type="text" name="username" placeholder="Username"><br>
<input type="password" name="password" placeholder="Password"><br>
<button name="login">Login</button>
</form>
