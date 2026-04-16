<?php
/**
 * Save Configuration API
 * Saves configuration to server (requires admin authentication)
 */

require_once __DIR__ . '/../includes/error-handler.php';

session_start();

require_once __DIR__ . '/cors-helper.php';
handlePreflight('POST, OPTIONS');
handleCors('POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// SECURITY: Validate token, expiry, and IP to prevent session hijacking
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
    echo json_encode(['success' => false, 'error' => 'Unauthorized. Please login to admin panel.']);
    exit();
}

$input = file_get_contents('php://input');
$config = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit();
}

// Validate color values to prevent XSS via CSS
if (isset($config['colors']) && is_array($config['colors'])) {
    foreach ($config['colors'] as $key => $value) {
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid color format for ' . $key . '. Use hex format like #RRGGBB']);
            exit();
        }
    }
}

$configDir = __DIR__ . '/../config';
$configFile = $configDir . '/config.json';

if (!file_exists($configDir)) {
    if (!@mkdir($configDir, 0755, true)) {
        if (!@mkdir($configDir, 0777, true)) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to create config directory. Please create a "config" folder manually and set permissions to 755 or 777.',
                'storage' => 'none'
            ]);
            exit();
        }
    }
}

if (!is_writable($configDir)) {
    @chmod($configDir, 0777);
    if (!is_writable($configDir)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Config directory is not writable. Please set permissions on the "config" folder to 755 or 777.',
            'storage' => 'none'
        ]);
        exit();
    }
}

if (file_exists($configFile) && !is_writable($configFile)) {
    @chmod($configFile, 0666);
    if (!is_writable($configFile)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Config file exists but is not writable. Please set permissions on config/config.json to 644 or 666.',
            'storage' => 'none'
        ]);
        exit();
    }
}

$jsonContent = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$result = @file_put_contents($configFile, $jsonContent);

if ($result !== false) {
    @chmod($configFile, 0644);
    // SECURITY: .htaccess prevents direct web access to config
    $htaccessFile = $configDir . '/.htaccess';
    if (!file_exists($htaccessFile)) {
        $htaccessContent = <<<'HTACCESS'
# Deny all access to this directory
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>
HTACCESS;
        @file_put_contents($htaccessFile, $htaccessContent);
    }
    echo json_encode([
        'success' => true,
        'message' => 'Configuration saved successfully',
        'storage' => 'server'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save configuration file. Please check that PHP has write permissions.',
        'storage' => 'none'
    ]);
}
?>
