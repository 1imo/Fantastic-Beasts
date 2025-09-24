<?php
$beastsJson = file_get_contents('beasts.json');
$beasts = json_decode($beastsJson, true);

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

// Add homepage
$url = $xml->addChild('url');
$url->addChild('loc', 'https://fantasticbeasts.world/index.php');
$url->addChild('changefreq', 'weekly');
$url->addChild('priority', '1.0');

// Add beast detail pages
foreach ($beasts as $beast) {
    $url = $xml->addChild('url');
    $url->addChild('loc', 'https://fantasticbeasts.world/beast-details.php?name=' . urlencode($beast['name']));
    $url->addChild('changefreq', 'monthly');
    $url->addChild('priority', '0.8');
}

$xml->asXML('sitemap.xml');

echo 'Sitemap generated successfully!';
?>