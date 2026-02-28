<?php
/**
 * IVF Experts - Database Setup Script
 * WARNING: This script DROPS all existing EMR tables and rebuilds them.
 */

// Include DB config from parent directory
require_once dirname(__DIR__) . '/config/db.php';

echo "<h1>IVF Experts EMR - Database Setup</h1>";
echo "<pre>";

$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

$tables_to_drop = [
    'prescription_items', 'prescriptions', 'patient_ultrasounds',
    'semen_analyses', 'patient_history', 'receipts', 'patients',
    'medications', 'hospitals', 'admin_users', 'settings',
    'ultrasound_templates', 'expenses'
];

foreach ($tables_to_drop as $table) {
    if ($conn->query("DROP TABLE IF EXISTS `$table`")) {
        echo "Dropped `$table` (if existed).<br>";
    }
    else {
        echo "Error dropping `$table`: " . $conn->error . "<br>";
    }
}

$conn->query("SET FOREIGN_KEY_CHECKS = 1;");

$schema = [
    // 1. Admin Users
    "CREATE TABLE admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    // 2. Hospitals (For places of practice)
    "CREATE TABLE hospitals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        logo_path VARCHAR(255),
        address_footer TEXT,
        digital_signature_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // 3. Global Settings (margins, etc)
    "CREATE TABLE settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(50) NOT NULL UNIQUE,
        setting_value TEXT
    )",

    // 4. Medications (Arsenal)
    "CREATE TABLE medications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        med_type ENUM('Injection', 'Tablet', 'Capsule', 'Sachet', 'Syrup', 'Other') DEFAULT 'Other',
        vendor VARCHAR(255),
        price DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // 5. Patients
    "CREATE TABLE patients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        mr_number VARCHAR(50) NOT NULL UNIQUE,
        cnic VARCHAR(20),
        phone VARCHAR(20),
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100),
        spouse_name VARCHAR(100),
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        referring_hospital_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (referring_hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
    )",

    // 6. Patient History (Labs & Notes)
    "CREATE TABLE patient_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        lh VARCHAR(50),
        fsh VARCHAR(50),
        testosterone VARCHAR(50),
        prolactin VARCHAR(50),
        vit_d VARCHAR(50),
        iron VARCHAR(50),
        tb VARCHAR(50),
        clinical_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
    )",

    // 7. Prescriptions
    "CREATE TABLE prescriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        hospital_id INT,
        qrcode_hash VARCHAR(100) UNIQUE NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
    )",

    // 8. Prescription Items
    "CREATE TABLE prescription_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prescription_id INT NOT NULL,
        medication_id INT NOT NULL,
        dosage VARCHAR(100),
        instructions VARCHAR(255),
        FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE,
        FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE RESTRICT
    )",

    // 9. Ultrasound Templates
    "CREATE TABLE ultrasound_templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // 10. Patient Ultrasounds
    "CREATE TABLE patient_ultrasounds (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        hospital_id INT,
        qrcode_hash VARCHAR(100) UNIQUE NOT NULL,
        report_title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
    )",

    // 11. Semen Analyses
    "CREATE TABLE semen_analyses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        hospital_id INT,
        qrcode_hash VARCHAR(100) UNIQUE NOT NULL,
        collection_time DATETIME,
        examination_time DATETIME,
        abstinence_days INT,
        volume DECIMAL(5,2),
        ph DECIMAL(4,2),
        concentration DECIMAL(10,2),
        pr_motility DECIMAL(5,2),
        np_motility DECIMAL(5,2),
        im_motility DECIMAL(5,2),
        normal_morphology DECIMAL(5,2),
        abnormal_morphology DECIMAL(5,2),
        wbc VARCHAR(50),
        agglutination VARCHAR(50),
        auto_diagnosis VARCHAR(255),
        admin_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
    )",

    // 12. Expenses
    "CREATE TABLE expenses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        expense_date DATE NOT NULL,
        category VARCHAR(100),
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // 13. Receipts (Income)
    "CREATE TABLE receipts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        hospital_id INT,
        procedure_name VARCHAR(255) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        receipt_date DATE NOT NULL,
        qrcode_hash VARCHAR(100) UNIQUE NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
    )"
];

echo "<br><b>Creating tables...</b><br>";
foreach ($schema as $sql) {
    if ($conn->query($sql) === TRUE) {
        // Extract table name safely
        preg_match('/CREATE TABLE (\w+)/', $sql, $matches);
        $tableName = $matches[1] ?? 'unknown';
        echo "Table `$tableName` created successfully.<br>";
    }
    else {
        echo "<span style='color:red;'>Error creating table: " . $conn->error . "</span><br>";
        echo "<small>$sql</small><hr>";
    }
}

// Data seeding
echo "<br><b>Seeding initial data...</b><br>";

// 1. Admin User Setup (Default credentials)
$default_user = "admin";
$default_pass = password_hash("Admin@IVF123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
if ($stmt) {
    $stmt->bind_param("ss", $default_user, $default_pass);
    if ($stmt->execute()) {
        echo "Admin user created: <b>$default_user</b> / <b>Admin@IVF123</b><br>";
    }
}

// 2. Starter Hospitals
$hospitals = [
    "IVF Experts",
    "Healthnox Medical Center",
    "AQ Ortho & Gyne Complex",
    "Latif Hospital",
    "Sakina International Hospital"
];
foreach ($hospitals as $hosp) {
    $conn->query("INSERT INTO hospitals (name) VALUES ('" . $conn->real_escape_string($hosp) . "')");
}
echo "Inserted default hospitals.<br>";

// 3. Default Print Settings
$conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('print_margin_top', '40mm')");
$conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('print_margin_bottom', '20mm')");
$conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('print_margin_left', '20mm')");
$conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('print_margin_right', '20mm')");
echo "Inserted default print settings.<br>";

echo "<br><h2 style='color:green;'>Setup Complete!</h2>";
echo "Please delete this script (setup_emr_db.php) after running it in production.";
echo "</pre>";

$conn->close();
?>
