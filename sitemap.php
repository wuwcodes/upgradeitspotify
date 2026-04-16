<?php
/**
 * Dynamic Sitemap Generator
 * Generates sitemap.xml with proper absolute URLs based on current domain
 */

// Load error handler for consistent error logging
require_once __DIR__ . '/includes/error-handler.php';

header('Content-Type: application/xml; charset=utf-8');

// Get the base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = $protocol . '://' . $host;

// Define pages with their properties
$pages = [
    ['path' => '/index.php', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['path' => '/upgrade.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['path' => '/renew.php', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['path' => '/info.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
  <url>
    <loc><?= htmlspecialchars($baseUrl . $page['path'], ENT_XML1) ?></loc>
    <changefreq><?= $page['changefreq'] ?></changefreq>
    <priority><?= $page['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
