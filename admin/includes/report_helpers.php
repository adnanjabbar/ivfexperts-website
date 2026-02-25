<?php

function classify_who6($data) {

    $volume = $data['volume'];
    $concentration = $data['concentration'];
    $progressive = $data['progressive'];
    $morphology = $data['morphology'];

    if ($concentration == 0) {
        return "Azoospermia";
    }

    $flags = [];

    if ($volume < 1.4) {
        $flags[] = "Low Volume";
    }

    if ($concentration < 16) {
        if ($concentration < 5) {
            $flags[] = "Severe Oligozoospermia";
        } elseif ($concentration < 10) {
            $flags[] = "Moderate Oligozoospermia";
        } else {
            $flags[] = "Mild Oligozoospermia";
        }
    }

    if ($progressive < 30) {
        $flags[] = "Asthenozoospermia";
    }

    if ($morphology < 4) {
        $flags[] = "Teratozoospermia";
    }

    if (count($flags) === 0) {
        return "Normozoospermia";
    }

    if (in_array("Severe Oligozoospermia", $flags) && 
        in_array("Asthenozoospermia", $flags) &&
        in_array("Teratozoospermia", $flags)) {
        return "Oligoasthenoteratozoospermia (OAT)";
    }

    return implode(", ", $flags);
}

function abnormal($value, $threshold, $type = "min") {

    if ($type === "min") {
        return $value < $threshold;
    }

    if ($type === "max") {
        return $value > $threshold;
    }

    return false;
}