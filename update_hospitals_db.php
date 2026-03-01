<?php
require 'config/db.php';

$sql = "ALTER TABLE hospitals 
        ADD COLUMN margin_top VARCHAR(20) DEFAULT '20mm',
        ADD COLUMN margin_bottom VARCHAR(20) DEFAULT '20mm',
        ADD COLUMN margin_left VARCHAR(20) DEFAULT '20mm',
        ADD COLUMN margin_right VARCHAR(20) DEFAULT '20mm';";

if ($conn->query($sql) === TRUE) {
    echo "Hospitals table altered successfully.";
}
else {
    echo "Error altering table: " . $conn->error;
}
?>
