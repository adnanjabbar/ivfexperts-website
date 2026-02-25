<?php
header("Content-Type: application/xml; charset=utf-8");

$pages = [
"",
"male-infertility/",
"female-infertility/",
"art-procedures/"
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

foreach($pages as $page){
echo "<url>";
echo "<loc>https://ivfexperts.pk/$page</loc>";
echo "<priority>0.8</priority>";
echo "</url>";
}

echo "</urlset>";