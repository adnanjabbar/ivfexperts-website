<?php
/**
 * Admin Authentication & Session Security
 * Include this at the top of EVERY admin page (except login.php)
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === Security headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// === Check if user is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    // Not logged in → redirect to login
    header("Location: /admin/login.php");
    exit();
}

// === Optional: Session timeout (30 minutes inactivity)
$timeout = 30 * 60; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: /admin/login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time(); // update last activity time

// === Regenerate session ID periodically (every 15 min)
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > 900) { // 15 minutes
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}