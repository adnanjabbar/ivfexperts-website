<?php
// Set the correct header so browsers and search engines treat this as XML
header("Content-Type: text/xml;charset=iso-8859-1");

// Base URL of your website
$baseUrl = "https://ivfexperts.pk";

// Array of all public pages on the website
$pages = [
    // Main Pages
    '/',
    '/about/',
    '/contact/',

    // Male Infertility
    '/male-infertility/',
    '/male-infertility/low-sperm-count.php',
    '/male-infertility/azoospermia.php',
    '/male-infertility/varicocele.php',
    '/male-infertility/dna-fragmentation.php',

    // Female Infertility
    '/female-infertility/',
    '/female-infertility/pcos.php',
    '/female-infertility/endometriosis.php',
    '/female-infertility/diminished-ovarian-reserve.php',
    '/female-infertility/blocked-tubes.php',

    // ART Procedures
    '/art-procedures/',
    '/art-procedures/ivf.php',
    '/art-procedures/icsi.php',
    '/art-procedures/pgt.php',
    '/art-procedures/iui.php'
];

// Current date and time in the correct format for sitemaps (W3C Datetime)
$currentDate = date('Y-m-d\TH:i:sP');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $page) {
    // Determine the priority and change frequency based on the page type
    $priority = '0.80';
    $changefreq = 'weekly';

    if ($page === '/') {
        $priority = '1.00';
        $changefreq = 'daily';
    }
    elseif (strpos($page, '/male-infertility/') !== false || strpos($page, '/female-infertility/') !== false || strpos($page, '/art-procedures/') !== false) {
        // Condition and procedure pages are highly important for SEO
        $priority = '0.90';
    }
    elseif ($page === '/contact/') {
        $priority = '0.70';
        $changefreq = 'monthly';
    }

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . $page) . "</loc>\n";
    echo "    <lastmod>" . $currentDate . "</lastmod>\n";
    echo "    <changefreq>" . $changefreq . "</changefreq>\n";
    echo "    <priority>" . $priority . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
?>
