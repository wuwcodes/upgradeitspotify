<?php
/**
 * Change Password API
 * Requires admin authentication via session token
 */

// Load error handler for consistent error logging
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

// Verify admin session token
$token = isset($_SERVER['HTTP_X_ADMIN_TOKEN']) ? $_SERVER['HTTP_X_ADMIN_TOKEN'] : '';
$isValid = false;

if (!empty($token) && isset($_SESSION['admin_token'])) {
    if ($_SESSION['admin_token'] === $token && $_SESSION['admin_expiry'] > time()) {
        // Verify IP matches session
        $sessionIp = $_SESSION['admin_ip'] ?? '';
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($sessionIp === $currentIp) {
            $isValid = true;
        }
    }
}

if (!$isValid) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication required. Please log in again.']);
    exit;
}

// Get request body
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$currentPassword = $input['currentPassword'] ?? '';
$newPassword = $input['newPassword'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

if (strlen($newPassword) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'New password must be at least 8 characters']);
    exit;
}

// Load config
$configPath = __DIR__ . '/../config/config.json';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Config file not found']);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);
if ($config === null) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to parse config']);
    exit;
}

// Get current password from config
$storedPassword = $config['adminPassword'] ?? 'admin123';

// Verify current password - supports both bcrypt hash and plaintext
$currentPasswordValid = false;

if (strpos($storedPassword, '$2') === 0) {
    // Password is hashed (bcrypt)
    $currentPasswordValid = password_verify($currentPassword, $storedPassword);
} else {
    // Legacy plaintext password
    $currentPasswordValid = ($currentPassword === $storedPassword);
}

if (!$currentPasswordValid) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
    exit;
}

// Hash the new password with bcrypt
$config['adminPassword'] = password_hash($newPassword, PASSWORD_BCRYPT);

// Save config
if (file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    // Invalidate current session to force re-login with new password
    unset($_SESSION['admin_token']);
    unset($_SESSION['admin_expiry']);
    unset($_SESSION['admin_ip']);

    echo json_encode(['success' => true, 'message' => 'Password changed successfully. Please log in again.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save new password']);
}
