<?php
// Redirect root /admin/ to login or dashboard
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}