<?php
require_once __DIR__ . '/config/db.php';

echo "Phase 13: Setting up Asset & Inventory Tracking...<br>\n";

$queries = [
    "CREATE TABLE IF NOT EXISTS `assets` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `type` ENUM('Fixed', 'Disposable') DEFAULT 'Fixed',
        `category` varchar(100) NULL,
        `barcode_string` varchar(100) NULL,
        `purchase_date` DATE NULL,
        `purchase_price` DECIMAL(10,2) NULL DEFAULT 0.00,
        `stock_quantity` INT DEFAULT 0,
        `minimum_threshold` INT DEFAULT 0,
        `location` varchar(255) NULL,
        `notes` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> Assets table created.<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "<span style='color:orange'>Skipped:</span> Table already exists.<br>\n";
        }
        else {
            echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
        }
    }
}

echo "<br><strong>Phase 13 database setup completed.</strong>";
?>
