<?php
require_once __DIR__ . '/config/db.php';

echo "Updating database to support Manual Scanned Records...<br>\n";

$queries = [
    "ALTER TABLE prescriptions ADD COLUMN scanned_report_path VARCHAR(255) NULL",
    "ALTER TABLE prescriptions ADD COLUMN scan_timestamp DATETIME NULL",
    "ALTER TABLE prescriptions ADD COLUMN scan_location_data TEXT NULL",

    "ALTER TABLE patient_ultrasounds ADD COLUMN scanned_report_path VARCHAR(255) NULL",
    "ALTER TABLE patient_ultrasounds ADD COLUMN scan_timestamp DATETIME NULL",
    "ALTER TABLE patient_ultrasounds ADD COLUMN scan_location_data TEXT NULL"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> $query<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> $query - " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        // Likely Duplicate column error
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<span style='color:orange'>Skipped:</span> Column already exists ($query)<br>\n";
        }
        else {
            echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
        }
    }
}

echo "<br><strong>Database update completed successfully.</strong>";
?>
