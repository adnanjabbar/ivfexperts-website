<?php
require_once("../../config/db.php");

/* ==============================
   MR NUMBER GENERATOR
   ============================== */
function generate_mr_number() {
    global $conn;

    $year = date("Y");
    $prefix = "IVF-" . $year;

    $result = $conn->query("SELECT COUNT(*) as total FROM patients WHERE mr_number LIKE '$prefix%'");
    $row = $result->fetch_assoc();
    $next = $row['total'] + 1;

    return $prefix . "-" . str_pad($next, 4, "0", STR_PAD_LEFT);
}

/* ==============================
   REPORT NUMBER GENERATOR
   ============================== */
function generate_report_number($type) {
    global $conn;

    $year = date("Y");

    $check = $conn->prepare("SELECT * FROM report_counters WHERE report_type=? AND year=?");
    $check->bind_param("si", $type, $year);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {
        $conn->query("INSERT INTO report_counters (report_type, year, counter) VALUES ('$type', $year, 1)");
        $counter = 1;
    } else {
        $row = $res->fetch_assoc();
        $counter = $row['counter'] + 1;
        $conn->query("UPDATE report_counters SET counter=$counter WHERE report_type='$type' AND year=$year");
    }

    $prefix = $type === "semen" ? "IVF-S" : "IVF-USG";

    return $prefix . "-" . $year . "-" . str_pad($counter, 4, "0", STR_PAD_LEFT);
}