<?php
/**
 * Get Full Configuration for Admin Panel
 * Returns ALL config including sensitive fields (apiKey, adminPassword)
 * REQUIRES valid admin session token
 */

// Load error handler for consistent error logging
require_once __DIR__ . '/../includes/error-handler.php';

session_start();

require_once __DIR__ . '/cors-helper.php';
handlePreflight('GET, POST, OPTIONS');
handleCors('GET, POST, OPTIONS');

// Validate admin session - ONLY accept token from X-Admin-Token header (security best practice)
$token = isset($_SERVER['HTTP_X_ADMIN_TOKEN']) ? $_SERVER['HTTP_X_ADMIN_TOKEN'] : '';

// Validate token against session
$isValid = false;

if (!empty($token) && isset($_SESSION['admin_token']) && isset($_SESSION['admin_expiry'])) {
    if ($_SESSION['admin_token'] === $token && $_SESSION['admin_expiry'] > time()) {
        $isValid = true;
    }
}

if (!$isValid) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized. Please login to admin panel.'
    ]);
    exit();
}

// Token is valid - return full config
$configFile = __DIR__ . '/../config/config.json';

if (file_exists($configFile)) {
    $config = file_get_contents($configFile);
    $decoded = json_decode($config);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo $config;
    } else {
        http_response_code(200);
        echo '{}';
    }
} else {
    http_response_code(200);
    echo '{}';
}
?>
