<?php
/**
 * Admin Authentication Middleware
 * Included at the top of every protected admin page.
 * Validates the session and redirects unauthenticated users.
 */
session_start();

// Ensure db config is available since most protected pages will need it
require_once __DIR__ . '/../../config/db.php';

// Check if the user is authenticated
if (!isset($_SESSION['admin_id'])) {
    // Prevent redirect loops if included on login.php
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header("Location: /admin/login.php");
        exit();
    }
}
else {
    // If the user IS logged in, prevent them from accessing login.php again
    if (basename($_SERVER['PHP_SELF']) === 'login.php') {
        header("Location: /admin/dashboard.php");
        exit();
    }
}
?>