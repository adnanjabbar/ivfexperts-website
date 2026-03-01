<?php
require_once __DIR__ . '/config/db.php';

echo "Phase 12: Setting up Advised Procedures & Advanced Billing...<br>\n";

$queries = [
    // 1. Create advised_procedures table
    "CREATE TABLE IF NOT EXISTS `advised_procedures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `patient_id` int(11) NOT NULL,
        `procedure_name` varchar(255) NOT NULL,
        `status` ENUM('Advised', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Advised',
        `date_advised` DATE NOT NULL,
        `notes` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `patient_id` (`patient_id`),
        CONSTRAINT `fk_ap_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 2. Add status column to receipts if missing
    "ALTER TABLE receipts ADD COLUMN `status` ENUM('Paid','Unpaid','Pending','Past Due') DEFAULT 'Paid'",

    // 3. Add advised_procedure_id column to receipts if missing
    "ALTER TABLE receipts ADD COLUMN `advised_procedure_id` INT NULL",

    // 4. Add FK constraint for advised_procedure_id
    "ALTER TABLE receipts ADD CONSTRAINT `fk_receipt_procedure` FOREIGN KEY (`advised_procedure_id`) REFERENCES `advised_procedures`(`id`) ON DELETE SET NULL"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "<span style='color:green'>Success:</span> Query executed.<br>\n";
        }
        else {
            echo "<span style='color:red'>Failed:</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<span style='color:orange'>Skipped:</span> Column already exists.<br>\n";
        }
        elseif (strpos($e->getMessage(), 'already exists') !== false) {
            echo "<span style='color:orange'>Skipped:</span> Already exists.<br>\n";
        }
        else {
            echo "<span style='color:red'>Error:</span> " . $e->getMessage() . "<br>\n";
        }
    }
}

echo "<br><strong>Phase 12 database setup completed.</strong>";
?>
