<?php
require_once __DIR__ . '/config/db.php';

echo "<h1>Updating Database for Advised Procedures & Billing (Phase 12)</h1>";

try {
    // 1. Create advised_procedures table
    $sql1 = "CREATE TABLE IF NOT EXISTS `advised_procedures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `patient_id` int(11) NOT NULL,
        `procedure_name` varchar(255) NOT NULL,
        `status` enum('Advised','In Progress','Completed','Cancelled') NOT NULL DEFAULT 'Advised',
        `date_advised` date NOT NULL,
        `notes` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `patient_id` (`patient_id`),
        CONSTRAINT `fk_adv_proc_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql1)) {
        echo "<p>✅ `advised_procedures` table created or already exists.</p>";
    }
    else {
        echo "<p>❌ Error creating `advised_procedures`: " . $conn->error . "</p>";
    }

    // 2. Modify receipts table to add status and link to advising
    $sql2 = "ALTER TABLE `receipts` 
             ADD COLUMN IF NOT EXISTS `status` enum('Paid','Unpaid','Pending','Past Due') NOT NULL DEFAULT 'Paid' AFTER `amount`,
             ADD COLUMN IF NOT EXISTS `advised_procedure_id` int(11) DEFAULT NULL AFTER `status`,
             ADD CONSTRAINT `fk_receipt_adv_proc` FOREIGN KEY IF NOT EXISTS (`advised_procedure_id`) REFERENCES `advised_procedures` (`id`) ON DELETE SET NULL;";

    if ($conn->query($sql2)) {
        echo "<p>✅ `receipts` table altered successfully with `status` and `advised_procedure_id`.</p>";
    }
    else {
        // FK constraint syntax differences sometimes cause alter to fail if it already exists, so we split it for safety
        $conn->query("ALTER TABLE `receipts` ADD COLUMN IF NOT EXISTS `status` enum('Paid','Unpaid','Pending','Past Due') NOT NULL DEFAULT 'Paid' AFTER `amount`");
        $conn->query("ALTER TABLE `receipts` ADD COLUMN IF NOT EXISTS `advised_procedure_id` int(11) DEFAULT NULL AFTER `status`");

        // Check if constraint exists before adding
        $checkFk = $conn->query("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'receipts' AND CONSTRAINT_NAME = 'fk_receipt_adv_proc'");
        if ($checkFk && $checkFk->num_rows == 0) {
            $conn->query("ALTER TABLE `receipts` ADD CONSTRAINT `fk_receipt_adv_proc` FOREIGN KEY (`advised_procedure_id`) REFERENCES `advised_procedures` (`id`) ON DELETE SET NULL");
        }

        echo "<p>✅ `receipts` table structural updates applied.</p>";
    }

}
catch (Exception $e) {
    echo "<p><strong>Critical Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<p>✅ Database update completed!</p>";
?>
