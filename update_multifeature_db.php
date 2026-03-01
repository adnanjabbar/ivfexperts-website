<?php
require_once __DIR__ . '/config/db.php';

echo "<h3>Multi-Feature Database Migration</h3><br>\n";

$queries = [
    // 1. Redesign patient_history table - add new columns for diagnosis, medication, advice, next visit
    "ALTER TABLE patient_history ADD COLUMN `diagnosis` TEXT NULL AFTER `clinical_notes`",
    "ALTER TABLE patient_history ADD COLUMN `medication` TEXT NULL AFTER `diagnosis`",
    "ALTER TABLE patient_history ADD COLUMN `advice` TEXT NULL AFTER `medication`",
    "ALTER TABLE patient_history ADD COLUMN `next_visit` DATE NULL AFTER `advice`",
    "ALTER TABLE patient_history ADD COLUMN `is_finalized` TINYINT(1) DEFAULT 0 AFTER `next_visit`",

    // 2. Patient registration - add marital status, GPA, years married
    "ALTER TABLE patients ADD COLUMN `marital_status` ENUM('Single','Married','Divorced','Widowed') DEFAULT 'Single' AFTER `gender`",
    "ALTER TABLE patients ADD COLUMN `gravida` INT DEFAULT 0 AFTER `marital_status`",
    "ALTER TABLE patients ADD COLUMN `para` INT DEFAULT 0 AFTER `gravida`",
    "ALTER TABLE patients ADD COLUMN `abortions` INT DEFAULT 0 AFTER `para`",
    "ALTER TABLE patients ADD COLUMN `years_married` INT NULL AFTER `abortions`",

    // 3. Blog / CMS table
    "CREATE TABLE IF NOT EXISTS `blog_posts` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(500) NOT NULL,
        `slug` VARCHAR(500) NOT NULL,
        `excerpt` TEXT NULL,
        `content` LONGTEXT NOT NULL,
        `featured_image` VARCHAR(500) NULL,
        `category` VARCHAR(100) DEFAULT 'General',
        `tags` VARCHAR(500) NULL,
        `meta_title` VARCHAR(300) NULL,
        `meta_description` VARCHAR(500) NULL,
        `status` ENUM('Draft','Published') DEFAULT 'Draft',
        `author` VARCHAR(200) DEFAULT 'Dr. Adnan Jabbar',
        `published_at` DATETIME NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            // Extract useful info from the query
            if (strpos($query, 'CREATE TABLE') !== false) {
                echo "<span style='color:green'>✓</span> Table created successfully.<br>\n";
            }
            else {
                echo "<span style='color:green'>✓</span> Column added successfully.<br>\n";
            }
        }
        else {
            echo "<span style='color:red'>✗</span> " . $conn->error . "<br>\n";
        }
    }
    catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "<span style='color:orange'>⊘</span> Already exists, skipped.<br>\n";
        }
        else {
            echo "<span style='color:red'>✗</span> " . $e->getMessage() . "<br>\n";
        }
    }
}

echo "<br><strong>All migrations completed.</strong>";
?>
