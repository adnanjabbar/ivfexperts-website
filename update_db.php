<?php
require 'config/db.php';

$sql = "ALTER TABLE semen_analyses 
        ADD COLUMN appearance VARCHAR(50) DEFAULT 'Normal', 
        ADD COLUMN liquefaction VARCHAR(50) DEFAULT 'Complete', 
        ADD COLUMN viscosity VARCHAR(50) DEFAULT 'Normal', 
        ADD COLUMN vitality DECIMAL(5,2), 
        ADD COLUMN round_cells VARCHAR(50), 
        ADD COLUMN debris VARCHAR(50)";

if ($conn->query($sql) === TRUE) {
    echo "Database updated successfully";
}
else {
    echo "Error updating database: " . $conn->error;
}
?>
