<?php
/**
 * Delete Asset API
 * Handles logo/favicon deletion for the reseller website
 * Requires admin authentication
 */

require_once __DIR__ . '/../includes/error-handler.php';

session_start();

require_once __DIR__ . '/cors-helper.php';
handlePreflight('POST, OPTIONS');
handleCors('POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$token = isset($_SERVER['HTTP_X_ADMIN_TOKEN']) ? $_SERVER['HTTP_X_ADMIN_TOKEN'] : '';
$isValid = false;

if (!empty($token) && isset($_SESSION['admin_token']) && isset($_SESSION['admin_expiry'])) {
    if ($_SESSION['admin_token'] === $token && $_SESSION['admin_expiry'] > time()) {
        $sessionIp = $_SESSION['admin_ip'] ?? '';
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($sessionIp === $currentIp) {
            $isValid = true;
        }
    }
}

if (!$isValid) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$type = $input['type'] ?? '';

// SECURITY: Validate asset type against whitelist to prevent path traversal
if (!in_array($type, ['logo', 'favicon'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid asset type']);
    exit;
}

$configPath = __DIR__ . '/../config/config.json';
$assetsPath = __DIR__ . '/../assets/images/';

if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'error' => 'Config file not found']);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    echo json_encode(['success' => false, 'error' => 'Failed to parse config']);
    exit;
}

$currentPath = $config['branding'][$type] ?? '';

if ($currentPath && !empty($currentPath)) {
    // SECURITY: basename() prevents directory traversal attacks
    $filePath = $assetsPath . basename($currentPath);

    $exactPath = __DIR__ . '/../' . ltrim($currentPath, './');
    if (file_exists($filePath) && is_file($filePath)) {
        @unlink($filePath);
    }
    if (file_exists($exactPath) && is_file($exactPath)) {
        @unlink($exactPath);
    }
}

$config['branding'][$type] = '';

if (file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    echo json_encode(['success' => true, 'message' => ucfirst($type) . ' deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update config']);
}
