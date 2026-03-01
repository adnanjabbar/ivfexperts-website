<?php
require 'config/db.php';

$success = true;

// 1. Alter Hospitals table for Digital Letterhead Graphics
$sql1 = "ALTER TABLE hospitals ADD COLUMN letterhead_image_path VARCHAR(255);";
if (!$conn->query($sql1)) {
    echo "Notice: " . $conn->error . "<br>";
}
else {
    echo "Hospitals table updated with Letterhead path.<br>";
}

// 2. Create Prescription Diagnoses table for multiple ICD/CPT codes
$sql2 = "CREATE TABLE IF NOT EXISTS prescription_diagnoses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    type ENUM('ICD', 'CPT', 'SNOMED') NOT NULL,
    code VARCHAR(100),
    description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
)";

if (!$conn->query($sql2)) {
    echo "Error creating prescription_diagnoses: " . $conn->error . "<br>";
    $success = false;
}
else {
    echo "Prescription Diagnoses table created/verified.<br>";
}

if ($success) {
    echo "<strong>Database Phase 10 Migration Complete.</strong>";
}
?>
