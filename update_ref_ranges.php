<?php
require_once __DIR__ . '/config/db.php';

echo "Updating database for Complex NABL Reference Ranges...<br>\n";

$queries = [
    // Modify reference_range to TEXT to support multi-line and rich clinical data
    "ALTER TABLE lab_tests_directory MODIFY COLUMN reference_range TEXT NULL"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> Column upgraded successfully.<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
    }
}

echo "<br><strong>Database update completed.</strong>";
?>
