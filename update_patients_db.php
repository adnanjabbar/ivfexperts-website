<?php
require_once __DIR__ . '/config/db.php';

echo "Expanding Patient Registration with Spouse Details...<br>\n";

$queries = [
    "ALTER TABLE patients ADD COLUMN `spouse_age` INT NULL AFTER `spouse_name`",
    "ALTER TABLE patients ADD COLUMN `spouse_gender` ENUM('Male','Female','Other') NULL AFTER `spouse_age`",
    "ALTER TABLE patients ADD COLUMN `spouse_cnic` VARCHAR(20) NULL AFTER `spouse_gender`",
    "ALTER TABLE patients ADD COLUMN `spouse_phone` VARCHAR(20) NULL AFTER `spouse_cnic`",
    "ALTER TABLE patients ADD COLUMN `patient_age` INT NULL AFTER `last_name`",
    "ALTER TABLE patients ADD COLUMN `date_of_birth` DATE NULL AFTER `patient_age`",
    "ALTER TABLE patients ADD COLUMN `blood_group` VARCHAR(10) NULL AFTER `date_of_birth`",
    "ALTER TABLE patients ADD COLUMN `address` TEXT NULL AFTER `phone`",
    "ALTER TABLE patients ADD COLUMN `email` VARCHAR(255) NULL AFTER `address`",
    "ALTER TABLE patients ADD COLUMN `spouse_linked_patient_id` INT NULL AFTER `spouse_phone`"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> Column added.<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<span style='color:orange'>Skipped:</span> Column already exists.<br>\n";
        }
        else {
            echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
        }
    }
}

echo "<br><strong>Patient registration expansion completed.</strong>";
?>
