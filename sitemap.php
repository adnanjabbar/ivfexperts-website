<?php
// Set the correct header so browsers and search engines treat this as XML
header("Content-Type: text/xml;charset=iso-8859-1");

// Base URL of your website
$baseUrl = "https://ivfexperts.pk";

// Define which directories we want to scan for public treatment pages
$directoriesToScan = [
    'male-infertility',
    'female-infertility',
    'art-procedures',
    'stemcell',
    'doctors'
];

// Start with standard root-level pages
$pages = [
    '/' => 'daily',
    '/about/' => 'monthly',
    '/contact/' => 'monthly'
];

// Scan predefined directories dynamically
foreach ($directoriesToScan as $dir) {
    if (is_dir(__DIR__ . '/' . $dir)) {
        // Automatically add the index of the directory
        $pages['/' . $dir . '/'] = 'weekly';

        // Find all PHP files in the directory
        $files = glob(__DIR__ . '/' . $dir . '/*.php');
        if ($files !== false) {
            foreach ($files as $file) {
                $filename = basename($file);
                // Skip index.php as it's already added at the folder level above
                if ($filename !== 'index.php') {
                    $pages['/' . $dir . '/' . $filename] = 'weekly';
                }
            }
        }
    }
}

// Global fallback date if filemtime fails
$fallbackDate = date('Y-m-d\TH:i:sP');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $pageUrl => $changefreq) {
    // Determine priority based on frequency/layer
    $priority = '0.80';
    if ($changefreq === 'daily') {
        $priority = '1.00';
    }
    elseif ($changefreq === 'weekly') {
        $priority = '0.90';
    }
    elseif ($changefreq === 'monthly') {
        $priority = '0.70';
    }

    // Attempt to get accurate last modified time by checking the actual file in the filesystem
    $lastmod = $fallbackDate;

    // Resolve URL to physical file path to read modification time
    if ($pageUrl === '/') {
        $filePath = __DIR__ . '/index.php';
    }
    elseif (substr($pageUrl, -1) === '/') {
        // e.g. /male-infertility/ -> /male-infertility/index.php
        $filePath = rtrim(__DIR__ . $pageUrl, '/') . '/index.php';
    }
    else {
        // e.g. /male-infertility/azoospermia.php
        $filePath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $pageUrl);
    }

    if (file_exists($filePath) && is_file($filePath)) {
        $lastmod = date('Y-m-d\TH:i:sP', filemtime($filePath));
    }

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . $pageUrl) . "</loc>\n";
    echo "    <lastmod>" . $lastmod . "</lastmod>\n";
    echo "    <changefreq>" . $changefreq . "</changefreq>\n";
    echo "    <priority>" . $priority . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
?>
