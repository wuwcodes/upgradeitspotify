<?php
/**
 * API Proxy - Keeps API key secure on server side
 * Browser JS calls this proxy, which then calls upgrader.cc with the API key
 * Includes rate limiting to prevent abuse
 */

// Check for required PHP extensions before loading anything else
if (!function_exists('curl_init')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Server configuration error: cURL extension is not installed. Please contact your hosting provider.']);
    exit();
}

if (!function_exists('json_encode')) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo 'Server configuration error: JSON extension is not installed.';
    exit();
}

require_once __DIR__ . '/../includes/error-handler.php';

require_once __DIR__ . '/cors-helper.php';
handlePreflight('GET, POST, OPTIONS');
handleCors('GET, POST, OPTIONS');

// Rate limiting - 120 requests per minute per IP
$rateLimitDir = __DIR__ . '/../config';
$rateLimitFile = $rateLimitDir . '/.proxy_rate_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$maxRequests = 120;
$windowSeconds = 60;

if (!file_exists($rateLimitDir)) {
    @mkdir($rateLimitDir, 0755, true);
}

// Auto-cleanup stale rate limit files older than 5 minutes (1 in 50 chance per request)
if (mt_rand(1, 50) === 1) {
    $staleFiles = glob($rateLimitDir . '/.proxy_rate_*');
    if ($staleFiles) {
        $staleThreshold = time() - 300;
        foreach ($staleFiles as $file) {
            if (filemtime($file) < $staleThreshold) {
                @unlink($file);
            }
        }
    }
    $staleAdminFiles = glob($rateLimitDir . '/.rate_limit_*');
    if ($staleAdminFiles) {
        $adminStaleThreshold = time() - 1800; // 30 minutes for admin auth files
        foreach ($staleAdminFiles as $file) {
            if (filemtime($file) < $adminStaleThreshold) {
                @unlink($file);
            }
        }
    }
}

// SECURITY: Exclusive file lock prevents rate limit race conditions
$fp = @fopen($rateLimitFile, 'c+');
if ($fp !== false && flock($fp, LOCK_EX)) {
    $content = '';
    $stat = fstat($fp);
    if ($stat['size'] > 0) {
        $content = fread($fp, $stat['size']);
    }

    $rateData = json_decode($content, true);
    if (!$rateData || !isset($rateData['count']) || !isset($rateData['start'])) {
        $rateData = ['count' => 0, 'start' => time()];
    }

    $elapsed = time() - $rateData['start'];
    if ($elapsed >= $windowSeconds) {
        $rateData = ['count' => 1, 'start' => time()];
    } else {
        $rateData['count']++;
        if ($rateData['count'] > $maxRequests) {
            flock($fp, LOCK_UN);
            fclose($fp);
            http_response_code(429);
            echo json_encode(['success' => false, 'error' => 'Too many requests. Please try again later.']);
            exit();
        }
    }

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($rateData));
    flock($fp, LOCK_UN);
    fclose($fp);
}

$configFile = __DIR__ . '/../config/config.json';
$apiKey = '';

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    if ($config && isset($config['apiKey'])) {
        $apiKey = $config['apiKey'];
    }
}

if (empty($apiKey) || $apiKey === 'YOUR_API_KEY_HERE') {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'API key not configured']);
    exit();
}

// SECURITY: Whitelist allowed endpoints to prevent proxy abuse
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$allowedEndpoints = ['stock', 'info', 'upgrade', 'renew', 'queue', 'features'];

if (!in_array($endpoint, $allowedEndpoints)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid endpoint']);
    exit();
}

$apiBaseUrl = 'https://upgrader.cc/api';
$apiUrl = $apiBaseUrl . '/' . $endpoint;

$params = $_GET;
unset($params['endpoint']);

$postData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = file_get_contents('php://input');
}

$ch = curl_init();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($params)) {
    $apiUrl .= '?' . http_build_query($params);
}

// SECURITY: API key sent server-side only, never exposed to browser
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'X-API-Key: ' . $apiKey,
        'Accept: application/json'
    ]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    if ($postData) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
}

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to connect to API']);
    exit();
}

http_response_code($httpCode);
echo $response;
?>
