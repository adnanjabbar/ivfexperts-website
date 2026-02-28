<?php

// Core Site Info
$siteName = "IVF Experts Pakistan";
$baseUrl = "https://ivfexperts.pk";
$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
$uriPaths = trim($_SERVER['REQUEST_URI'], "/");

// Default SEO Values (Aggressive Pakistan Targeting)
$pageTitle = "Best IVF Specialist in Lahore & Pakistan | Dr. Adnan Jabbar";
$metaDescription = "Top-rated IVF Specialist and Clinical Embryologist in Lahore. High success rates for IVF, ICSI, Gender Selection, and Infertility treatments across Pakistan.";
$ogImage = "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=1200"; // Default Lab Image

// Structured Data Variables
$schemaType = "MedicalWebPage";
$medicalSpecialty = "Reproductive Medicine";

// ==========================================
// DYNAMIC PAGE-SPECIFIC SEO ROUTING
// ==========================================

// Homepage
if (empty($uriPaths) || $uriPaths == 'index.php') {
    $pageTitle = "Best IVF Specialist in Lahore, Pakistan | Gender Selection & ICSI";
    $metaDescription = "Looking for the best IVF specialist in Lahore? Dr. Adnan Jabbar offers world-class IVF, ICSI, PGT Gender Selection, and Male Infertility treatments in Pakistan.";
}
// About
elseif (strpos($uriPaths, 'about') !== false) {
    $pageTitle = "About Dr. Adnan Jabbar | Top Fertility Consultant in Pakistan";
    $metaDescription = "Meet Dr. Adnan Jabbar, a dual-trained Fertility Consultant and Clinical Embryologist serving Lahore, Karachi, Islamabad, and all of Pakistan with ethical IVF care.";
}
// Contact
elseif (strpos($uriPaths, 'contact') !== false) {
    $pageTitle = "Contact IVF Experts Lahore | Book Fertility Consultation";
    $metaDescription = "Schedule an appointment with the best IVF specialist in Lahore. Clinics located in Lahore, Okara, serving patients from Islamabad, Karachi, and Multan.";
}

// ==== MALE INFERTILITY ====
elseif (strpos($uriPaths, 'male-infertility') !== false) {
    $schemaType = "MedicalCondition";
    if (strpos($uriPaths, 'low-sperm-count') !== false) {
        $pageTitle = "Low Sperm Count Treatment in Pakistan | Oligospermia Experts";
        $metaDescription = "Best treatment for low sperm count (Oligospermia) in Lahore. Advanced hormonal, surgical, and ICSI protocols for male infertility in Pakistan.";
    }
    elseif (strpos($uriPaths, 'azoospermia') !== false) {
        $pageTitle = "Azoospermia Treatment (Zero Sperm) in Lahore, Pakistan | Micro-TESE";
        $metaDescription = "Successful Azoospermia (zero sperm) treatment in Pakistan. Dr. Adnan Jabbar specializes in Micro-TESE and ICSI for severe male infertility.";
    }
    elseif (strpos($uriPaths, 'varicocele') !== false) {
        $pageTitle = "Varicocele Repair & Male Infertility Treatment in Pakistan";
        $metaDescription = "Expert Varicocele diagnosis and treatment in Lahore. Improve sperm count, motility, and morphology naturally or through advanced microsurgery.";
    }
    elseif (strpos($uriPaths, 'dna-fragmentation') !== false) {
        $pageTitle = "Sperm DNA Fragmentation Testing & Treatment | Lahore, Pakistan";
        $metaDescription = "High Sperm DNA Fragmentation causes recurrent miscarriages and IVF failure. Get advanced testing and targeted PICSI/ICSI treatments in Lahore.";
    }
    else {
        $pageTitle = "Male Infertility Specialist in Pakistan | Azoospermia & Varicocele";
        $metaDescription = "Leading male infertility expert in Lahore. Treating low sperm count, zero sperm, erectile dysfunction, and offering elite ICSI solutions across Pakistan.";
    }
}

// ==== FEMALE INFERTILITY ====
elseif (strpos($uriPaths, 'female-infertility') !== false) {
    $schemaType = "MedicalCondition";
    if (strpos($uriPaths, 'pcos') !== false) {
        $pageTitle = "Best PCOS Treatment for Pregnancy in Lahore, Pakistan";
        $metaDescription = "Get pregnant with PCOS. Specialized Polycystic Ovary Syndrome treatments, ovulation induction, and PCOS-friendly IVF protocols in Pakistan.";
    }
    elseif (strpos($uriPaths, 'endometriosis') !== false) {
        $pageTitle = "Endometriosis & Infertility Treatment in Lahore, Pakistan";
        $metaDescription = "Advanced Endometriosis treatment for infertility. Maximize your chances of natural pregnancy or IVF success with expert care in Lahore.";
    }
    elseif (strpos($uriPaths, 'blocked-tubes') !== false) {
        $pageTitle = "Blocked Fallopian Tubes Treatment | IVF Alternatives in Pakistan";
        $metaDescription = "Bilateral tubal blockage? Learn about your options for getting pregnant, including advanced IVF protocols for blocked fallopian tubes in Lahore.";
    }
    elseif (strpos($uriPaths, 'diminished-ovarian-reserve') !== false) {
        $pageTitle = "Low AMH & Diminished Ovarian Reserve Treatment Pakistan";
        $metaDescription = "Pregnancy with Low AMH is possible. Individualized ovarian stimulation and advanced reproductive techniques for Diminished Ovarian Reserve in Lahore.";
    }
    else {
        $pageTitle = "Female Infertility Specialist | PCOS & IVF Expert in Lahore";
        $metaDescription = "Comprehensive female infertility treatments in Pakistan. Overcome PCOS, Endometriosis, Low AMH, and unexplainable infertility with Dr. Adnan Jabbar.";
    }
}

// ==== ART PROCEDURES ====
elseif (strpos($uriPaths, 'art-procedures') !== false) {
    $schemaType = "MedicalProcedure";
    if (strpos($uriPaths, 'ivf') !== false) {
        $pageTitle = "Best IVF Center & Treatment Cost in Lahore, Pakistan";
        $metaDescription = "Highest IVF success rates in Lahore. Learn about the In Vitro Fertilization process, affordable costs, and personalized protocols for patients across Pakistan.";
    }
    elseif (strpos($uriPaths, 'icsi') !== false) {
        $pageTitle = "ICSI Treatment in Pakistan | Advanced Male Infertility Solutions";
        $metaDescription = "Intracytoplasmic Sperm Injection (ICSI) experts in Lahore. The ultimate solution for severe male infertility, low sperm motility, and prior IVF failures.";
    }
    elseif (strpos($uriPaths, 'pgt') !== false) {
        $pageTitle = "Gender Selection & PGT-A Testing in Lahore, Pakistan";
        $metaDescription = "Preimplantation Genetic Testing (PGT) for gender selection and chromosomal screening in Pakistan. Ensure a healthy baby and family balancing with 99% accuracy.";
    }
    elseif (strpos($uriPaths, 'iui') !== false) {
        $pageTitle = "IUI Cost & Success Rate in Pakistan | Intrauterine Insemination";
        $metaDescription = "Affordable IUI (Intrauterine Insemination) treatments in Lahore. Safe, effective first-line fertility treatments for couples in Pakistan.";
    }
    else {
        $pageTitle = "IVF, ICSI & Gender Selection Procedures | IVF Experts Pakistan";
        $metaDescription = "State-of-the-art ART procedures including IVF, ICSI, IUI, and Genetic Testing (PGT) in Lahore. World-class embryology laboratory.";
    }
}

// Override variables if defined globally before including seo.php
$pageTitle = isset($customPageTitle) ? $customPageTitle : $pageTitle;
$metaDescription = isset($customMetaDescription) ? $customMetaDescription : $metaDescription;

// Generate breadcrumb array
function generateBreadcrumb()
{
    $uri = trim($_SERVER['REQUEST_URI'], "/");
    if (empty($uri) || $uri == 'index.php') {
        return [
            ["name" => "Home", "url" => "https://ivfexperts.pk"]
        ];
    }

    $segments = explode("/", $uri);
    $breadcrumbs = [
        ["name" => "Home", "url" => "https://ivfexperts.pk"]
    ];

    $path = "";
    foreach ($segments as $segment) {
        $path .= "/" . $segment;
        $name = ucwords(str_replace(["-", ".php"], [" ", ""], $segment));
        $breadcrumbs[] = [
            "name" => $name,
            "url" => "https://ivfexperts.pk" . $path
        ];
    }

    return $breadcrumbs;
}

$breadcrumbs = generateBreadcrumb();
?>