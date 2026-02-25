<?php
session_start();

$hashed_password = password_hash("StrongPassword123!", PASSWORD_DEFAULT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === "admin" && password_verify($_POST['password'], $hashed_password)) {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    }
}
?>

<form method="post">
<h2>Admin Login</h2>
<input type="text" name="username" placeholder="Username" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<button type="submit">Login</button>
</form>