<?php

/* ======================================================
   REPORT NUMBER GENERATOR
====================================================== */

function generate_report_number($type){

    $year = date("Y");

    if($type === "semen"){
        $prefix = "IVF-S-";
    } elseif($type === "usg"){
        $prefix = "IVF-USG-";
    } else {
        $prefix = "IVF-";
    }

    global $conn;

    $result = $conn->query("
        SELECT COUNT(*) as total 
        FROM semen_reports 
        WHERE YEAR(created_at) = '$year'
    ");

    $row = $result->fetch_assoc();
    $next = str_pad($row['total'] + 1, 4, "0", STR_PAD_LEFT);

    return $prefix.$year."-".$next;
}


/* ======================================================
   WHO 6 CLASSIFICATION ENGINE (CLINICAL GRADE)
====================================================== */

function classify_who6($data){

    $volume        = floatval($data['volume'] ?? 0);
    $concentration = floatval($data['concentration'] ?? 0);
    $progressive   = floatval($data['progressive'] ?? 0);
    $morphology    = floatval($data['morphology'] ?? 0);

    $total_motility = floatval($data['total_motility'] ?? ($data['progressive'] ?? 0));

    /* ================= AZOOSPERMIA ================= */

    if($concentration <= 0){
        return "Azoospermia";
    }

    /* ================= CRYPTOZOOSPERMIA ================= */

    if($concentration > 0 && $concentration < 1){
        return "Cryptozoospermia";
    }

    $flags = [];

    /* ================= VOLUME ================= */

    if($volume < 1.4){
        $flags[] = "Hypospermia";
    }

    /* ================= OLIGOZOOSPERMIA ================= */

    if($concentration < 16){

        if($concentration < 5){
            $flags[] = "Severe Oligozoospermia";
        }
        elseif($concentration < 10){
            $flags[] = "Moderate Oligozoospermia";
        }
        else{
            $flags[] = "Mild Oligozoospermia";
        }
    }

    /* ================= ASTHENOZOOSPERMIA ================= */

    if($progressive < 30){
        $flags[] = "Asthenozoospermia";
    }

    /* ================= TERATOZOOSPERMIA ================= */

    if($morphology < 4){
        $flags[] = "Teratozoospermia";
    }

    /* ================= OAT DETECTION ================= */

    $hasOligo = false;
    $hasAstheno = false;
    $hasTerato = false;

    foreach($flags as $f){
        if(stripos($f, "Oligo") !== false) $hasOligo = true;
        if(stripos($f, "Astheno") !== false) $hasAstheno = true;
        if(stripos($f, "Terato") !== false) $hasTerato = true;
    }

    if($hasOligo && $hasAstheno && $hasTerato){

        if($concentration < 5){
            return "Severe Oligoasthenoteratozoospermia (Severe OAT)";
        }

        return "Oligoasthenoteratozoospermia (OAT)";
    }

    /* ================= NORMOZOOSPERMIA ================= */

    if(empty($flags)){
        return "Normozoospermia";
    }

    return implode(", ", $flags);
}


/* ======================================================
   ABNORMAL CHECK HELPER (FOR PDF COLORING)
====================================================== */

function abnormal($value, $threshold, $type = "min"){

    $value = floatval($value);

    if($type === "min"){
        return $value < $threshold;
    }

    if($type === "max"){
        return $value > $threshold;
    }

    return false;
}


/* ======================================================
   WHO 6 REFERENCE LIMITS (CENTRALIZED)
====================================================== */

function who6_reference(){

    return [
        "volume"          => 1.4,
        "concentration"   => 16,
        "total_count"     => 39,
        "progressive"     => 30,
        "total_motility"  => 42,
        "morphology"      => 4,
        "vitality"        => 54,
        "wbc"             => 1
    ];
}