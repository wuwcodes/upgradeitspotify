<?php
/**
 * Admin Authentication API
 * Verifies admin password and creates server-side session
 */

// Constants for configuration
define('SESSION_DURATION', 2 * 60 * 60); // 2 hours in seconds
define('RATE_LIMIT_MAX_ATTEMPTS', 5);
define('RATE_LIMIT_WINDOW_SECONDS', 900); // 15 minutes in seconds
define('RATE_LIMIT_MAX_REQUESTS', 60); // For proxy rate limiting

// Load error handler for consistent error logging
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

$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit();
}

$providedPassword = isset($input['password']) ? $input['password'] : '';

if (empty($providedPassword)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Password required']);
    exit();
}

// SECURITY: Rate limiting prevents brute force attacks
$rateLimitDir = __DIR__ . '/../config';
$rateLimitFile = $rateLimitDir . '/.rate_limit_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$maxAttempts = RATE_LIMIT_MAX_ATTEMPTS;
$windowSeconds = RATE_LIMIT_WINDOW_SECONDS;

// Ensure config directory exists
if (!file_exists($rateLimitDir)) {
    @mkdir($rateLimitDir, 0755, true);
}

// Open with exclusive lock for atomic read-modify-write
$fp = @fopen($rateLimitFile, 'c+');
if ($fp === false) {
    // If we can't open the file, skip rate limiting but log it
    error_log('Rate limit file could not be opened: ' . $rateLimitFile);
    $rateData = ['attempts' => 0, 'first_attempt' => time()];
} else {
    // Acquire exclusive lock (blocks until available)
    if (flock($fp, LOCK_EX)) {
        $content = '';
        $stat = fstat($fp);
        if ($stat['size'] > 0) {
            $content = fread($fp, $stat['size']);
        }

        $rateData = json_decode($content, true);
        if (!$rateData || !isset($rateData['attempts']) || !isset($rateData['first_attempt'])) {
            $rateData = ['attempts' => 0, 'first_attempt' => time()];
        }

        $elapsed = time() - $rateData['first_attempt'];
        if ($elapsed < $windowSeconds && $rateData['attempts'] >= $maxAttempts) {
            $remainingSeconds = $windowSeconds - $elapsed;
            flock($fp, LOCK_UN);
            fclose($fp);
            http_response_code(429);
            echo json_encode([
                'success' => false,
                'error' => 'Too many attempts. Try again in ' . ceil($remainingSeconds / 60) . ' minutes.'
            ]);
            exit();
        }
        if ($elapsed >= $windowSeconds) {
            // Reset window
            $rateData = ['attempts' => 0, 'first_attempt' => time()];
        }

        flock($fp, LOCK_UN);
    } else {
        $rateData = ['attempts' => 0, 'first_attempt' => time()];
    }
    fclose($fp);
}

// Load config to get admin password
$configFile = __DIR__ . '/../config/config.json';
$adminPassword = 'admin123'; // Default password

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    if ($config && isset($config['adminPassword'])) {
        $adminPassword = $config['adminPassword'];
    }
}

// SECURITY: Support bcrypt (secure) and legacy plaintext passwords
$passwordValid = false;
$needsUpgrade = false;

if (strpos($adminPassword, '$2') === 0) {
    $passwordValid = password_verify($providedPassword, $adminPassword);
} else {
    $passwordValid = ($providedPassword === $adminPassword);
    $needsUpgrade = $passwordValid; // Upgrade to hash on successful login
}

// Auto-upgrade plaintext password to bcrypt on successful login
if ($needsUpgrade && file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    if ($config) {
        $config['adminPassword'] = password_hash($providedPassword, PASSWORD_BCRYPT);
        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
    }
}

if ($passwordValid) {
    // Clear rate limit on successful login
    if (file_exists($rateLimitFile)) {
        @unlink($rateLimitFile);
    }

    // SECURITY: Cryptographically secure random token
    $token = bin2hex(random_bytes(32));
    $expiry = time() + SESSION_DURATION;

    // Store session server-side
    $_SESSION['admin_token'] = $token;
    $_SESSION['admin_expiry'] = $expiry;
    $_SESSION['admin_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';

    echo json_encode([
        'success' => true,
        'token' => $token,
        'expiry' => $expiry,
        'message' => 'Authentication successful'
    ]);
} else {
    // Increment rate limit counter with file locking
    $rateData['attempts']++;
    $fp = @fopen($rateLimitFile, 'c');
    if ($fp !== false) {
        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            fwrite($fp, json_encode($rateData));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid password'
    ]);
}
?>
