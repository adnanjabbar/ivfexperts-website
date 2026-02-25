<?php

function classify_semen($count, $motility, $morphology) {

    if ($count == 0) {
        return "Azoospermia";
    }

    $conditions = [];

    if ($count < 15) {
        $conditions[] = "Oligospermia";
    }

    if ($motility < 40) {
        $conditions[] = "Asthenozoospermia";
    }

    if ($morphology < 4) {
        $conditions[] = "Teratozoospermia";
    }

    if (count($conditions) == 0) {
        return "Normozoospermia";
    }

    if (count($conditions) > 1) {
        return "OAT Syndrome";
    }

    return implode(", ", $conditions);
}
?>