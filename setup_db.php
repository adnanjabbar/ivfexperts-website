<?php
/**
 * ONE-TIME DATABASE SETUP SCRIPT
 * Run this once in the browser to create the tables, then DELETE THIS FILE.
 */

// Include the existing database connection
require_once 'config/db.php';

echo "<h2>Starting Database Setup...</h2>";

// 1. Create `admins` table
$sql_admins = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('superadmin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_admins) === TRUE) {
    echo "<p style='color:green;'>Table 'admins' created successfully.</p>";
}
else {
    echo "<p style='color:red;'>Error creating 'admins' table: " . $conn->error . "</p>";
}

// 2. Create `leads` table
$sql_leads = "CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    inquiry_type VARCHAR(50) NOT NULL,
    message TEXT,
    status ENUM('new', 'contacted', 'consultation_booked', 'closed') DEFAULT 'new',
    utm_source VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_leads) === TRUE) {
    echo "<p style='color:green;'>Table 'leads' created successfully.</p>";
}
else {
    echo "<p style='color:red;'>Error creating 'leads' table: " . $conn->error . "</p>";
}

// 3. Create `site_settings` table
$sql_settings = "CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
)";

if ($conn->query($sql_settings) === TRUE) {
    echo "<p style='color:green;'>Table 'site_settings' created successfully.</p>";
}
else {
    echo "<p style='color:red;'>Error creating 'site_settings' table: " . $conn->error . "</p>";
}

// 4. Create Initial Superadmin Account
$username = 'dr_adnan';
$email = 'dr.adnan.jabbar@ivfexperts.pk';
// Temporary password (MUST BE CHANGED LATER)
$raw_password = 'IVPAdmin2024!';

$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

// Check if admin already exists
$check_admin = $conn->query("SELECT id FROM admins WHERE username = '$username'");
if ($check_admin->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO admins (username, password_hash, email, role) VALUES (?, ?, ?, 'superadmin')");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<p style='color:green; font-weight:bold;'>Superadmin created successfully!</p>";
        echo "<p><strong>Username:</strong> " . $username . "</p>";
        echo "<p><strong>Password:</strong> " . $raw_password . "</p>";
        echo "<p style='color:red;'>PLEASE COPY THESE CREDENTIALS AND DELETE THIS FILE IMMEDIATELY.</p>";
    }
    else {
        echo "<p style='color:red;'>Error creating superadmin: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
else {
    echo "<p style='color:orange;'>Superadmin '$username' already exists in the database. Skipping creation.</p>";
}

$conn->close();
echo "<h2>Setup Complete!</h2>";
?>
