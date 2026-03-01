<?php
require 'config/db.php';

$success = true;

// Alter Prescriptions table
$sql1 = "ALTER TABLE prescriptions 
        ADD COLUMN presenting_complaint TEXT,
        ADD COLUMN icd_code VARCHAR(100),
        ADD COLUMN icd_disease VARCHAR(255),
        ADD COLUMN cpt_code VARCHAR(100),
        ADD COLUMN cpt_procedure VARCHAR(255),
        ADD COLUMN revisit_date DATE;";

if (!$conn->query($sql1)) {
    echo "Error altering prescriptions: " . $conn->error . "<br>";
    $success = false;
}
else {
    echo "Prescriptions table updated.<br>";
}

// Alter Prescription Items table
$sql2 = "ALTER TABLE prescription_items 
        ADD COLUMN usage_frequency VARCHAR(50),
        ADD COLUMN duration VARCHAR(50);";

if (!$conn->query($sql2)) {
    echo "Error altering prescription_items: " . $conn->error . "<br>";
// It will throw an error if already exists, which is fine
}
else {
    echo "Prescription Items table updated.<br>";
}

if ($success)
    echo "Database migration complete.";
?>
