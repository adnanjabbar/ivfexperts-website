<?php
require_once __DIR__ . '/config/db.php';

echo "Updating database to support Laboratory Tests & Results...<br>\n";

$queries = [
    // 1. Lab Tests Directory
    "CREATE TABLE IF NOT EXISTS `lab_tests_directory` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `test_name` varchar(255) NOT NULL,
        `reference_range` varchar(255) NULL,
        `unit` varchar(100) NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 2. Patient Lab Results
    "CREATE TABLE IF NOT EXISTS `patient_lab_results` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `patient_id` int(11) NOT NULL,
        `lab_city` varchar(255) NULL,
        `lab_name` varchar(255) NULL,
        `lab_mr_number` varchar(255) NULL,
        `test_date` date NOT NULL,
        `test_id` int(11) NOT NULL,
        `result_value` varchar(255) NOT NULL,
        `scanned_report_path` varchar(255) NULL,
        PRIMARY KEY (`id`),
        KEY `patient_id` (`patient_id`),
        KEY `test_id` (`test_id`),
        CONSTRAINT `fk_plt_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_plt_test` FOREIGN KEY (`test_id`) REFERENCES `lab_tests_directory` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> Executed Table Creation Query.<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
    }
}

echo "<br><strong>Database update completed successfully.</strong>";
?>
