<?php

// Default values
$siteName = "IVF Experts";
$baseUrl = "https://ivfexperts.pk";

$pageTitle = $pageTitle ?? "Fertility Specialist in Lahore | IVF Experts Pakistan";
$metaDescription = $metaDescription ?? "Fertility Specialist & Clinical Embryologist in Lahore providing IVF, ICSI, IUI and structured infertility treatment across Pakistan.";

$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];

// Generate breadcrumb name
function generateBreadcrumb() {
    $uri = trim($_SERVER['REQUEST_URI'], "/");
    if(empty($uri)) {
        return [
            ["name" => "Home", "url" => "https://ivfexperts.pk"]
        ];
    }

    $segments = explode("/", $uri);
    $breadcrumbs = [
        ["name" => "Home", "url" => "https://ivfexperts.pk"]
    ];

    $path = "";
    foreach($segments as $segment) {
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