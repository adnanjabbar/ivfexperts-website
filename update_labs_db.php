<?php
require_once __DIR__ . '/config/db.php';

echo "<h1>Updating Database for Lab Tests Module (Phase 11)</h1>";

try {
    // 1. Create lab_tests_directory
    $sql1 = "CREATE TABLE IF NOT EXISTS `lab_tests_directory` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `test_name` varchar(255) NOT NULL,
        `reference_range` varchar(255) DEFAULT NULL,
        `unit` varchar(50) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql1)) {
        echo "<p>✅ `lab_tests_directory` table created or already exists.</p>";
    }
    else {
        echo "<p>❌ Error creating `lab_tests_directory`: " . $conn->error . "</p>";
    }

    // 2. Create patient_lab_results
    $sql2 = "CREATE TABLE IF NOT EXISTS `patient_lab_results` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `patient_id` int(11) NOT NULL,
        `lab_city` varchar(100) DEFAULT NULL,
        `lab_name` varchar(255) DEFAULT NULL,
        `lab_mr_number` varchar(100) DEFAULT NULL,
        `test_date` date NOT NULL,
        `test_id` int(11) NOT NULL,
        `result_value` varchar(255) NOT NULL,
        `scanned_report_path` varchar(500) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `patient_id` (`patient_id`),
        KEY `test_id` (`test_id`),
        CONSTRAINT `fk_lab_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_lab_test` FOREIGN KEY (`test_id`) REFERENCES `lab_tests_directory` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql2)) {
        echo "<p>✅ `patient_lab_results` table created or already exists.</p>";
    }
    else {
        echo "<p>❌ Error creating `patient_lab_results`: " . $conn->error . "</p>";
    }

}
catch (Exception $e) {
    echo "<p><strong>Critical Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<p>✅ Database update completed!</p>";
?>
