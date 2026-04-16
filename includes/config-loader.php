<?php
/**
 * Config Loader - Loads configuration from config.json
 * Include this at the top of every page to get $config variable
 */

// Load error handler first for consistent error logging
require_once __DIR__ . '/error-handler.php';

// SECURITY: Prevent direct web access to configuration
if (basename($_SERVER['PHP_SELF']) === 'config-loader.php') {
    http_response_code(403);
    exit('Direct access not allowed');
}

// Load config from JSON file
$configPath = __DIR__ . '/../config/config.json';
$config = [];

if (file_exists($configPath)) {
    $configData = file_get_contents($configPath);
    $config = json_decode($configData, true) ?: [];
}

// Default values
$defaults = [
    'previewMode' => false,
    'adminPassword' => 'admin123',
    'apiKey' => 'YOUR_API_KEY_HERE',
    'apiBaseUrl' => 'https://upgrader.cc/api',
    'branding' => [
        'siteName' => 'Spotify Premium Upgrades',
        'tagline' => 'Upgrade Your Spotify Account to Premium',
        'supportEmail' => 'support@yourdomain.com',
        'logo' => './assets/images/logo.png',
        'favicon' => './assets/images/favicon.png'
    ],
    'deliveryInstructions' => 'You will receive an upgrade key. Copy this key and paste it in Step 2 below.',
    'colors' => [
        'primary' => '#1DB954',
        'primaryDark' => '#1ed760',
        'background' => '#121212',
        'surface' => '#181818',
        'text' => '#ffffff',
        'textSecondary' => '#b3b3b3'
    ],
    'features' => [
        ['title' => 'Instant Activation', 'description' => 'Get Premium access within 5-10 minutes of submission'],
        ['title' => 'Secure Service', 'description' => 'Bank-level encryption and secure payment processing'],
        ['title' => '24/7 Support', 'description' => 'Round-the-clock customer support for all your needs'],
        ['title' => 'Free Replacements', 'description' => 'Unlimited free replacements if any issues occur'],
        ['title' => 'No Ads', 'description' => 'Enjoy uninterrupted music without any advertisements'],
        ['title' => 'Offline Listening', 'description' => 'Download and listen to your favorite tracks offline'],
        ['title' => 'Unlimited Skips', 'description' => 'Skip as many songs as you want, anytime'],
        ['title' => 'High Quality Audio', 'description' => 'Stream in high-quality audio up to 320kbps']
    ],
    'packages' => [
        [
            'id' => '1-key',
            'name' => '1 Key',
            'quantity' => 1,
            'description' => 'Perfect for a single account upgrade',
            'price' => '14.99',
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ],
        [
            'id' => '5-keys',
            'name' => '5 Keys',
            'quantity' => 5,
            'description' => 'Best for small groups or resellers',
            'price' => '59.99',
            'popular' => true,
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ],
        [
            'id' => '10-keys',
            'name' => '10 Keys',
            'quantity' => 10,
            'description' => 'Popular choice for resellers',
            'price' => '99.99',
            'enabled' => true,
            'paymentLinks' => ['stripe' => '', 'paypal' => '', 'crypto' => '']
        ]
    ]
];

// Deep merge function
function array_merge_deep($defaults, $config) {
    $merged = $defaults;
    foreach ($config as $key => $value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            // For indexed arrays (features, packages), replace entirely
            if (array_keys($value) === range(0, count($value) - 1)) {
                $merged[$key] = $value;
            } else {
                $merged[$key] = array_merge_deep($merged[$key], $value);
            }
        } else {
            $merged[$key] = $value;
        }
    }
    return $merged;
}

// Merge with defaults
$config = array_merge_deep($defaults, $config);

// Helper function to safely get config values
function cfg($key, $default = '') {
    global $config;
    $keys = explode('.', $key);
    $value = $config;
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $default;
        }
    }
    return $value;
}

// SECURITY: Prevents XSS by escaping HTML special characters
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Get site name (with fallback)
$siteName = !empty($config['branding']['siteName']) ? $config['branding']['siteName'] : 'Spotify Premium Upgrades';
$tagline = $config['branding']['tagline'] ?? '';
$supportEmail = $config['branding']['supportEmail'] ?? '';

// Only set logo/favicon if file actually exists (prevents 404 errors)
$logoPath = $config['branding']['logo'] ?? '';
$faviconPath = $config['branding']['favicon'] ?? '';

// Convert relative path to absolute for file_exists check
$basePath = dirname(__DIR__) . '/';
$logo = '';
$favicon = '';

if ($logoPath && file_exists($basePath . ltrim($logoPath, './'))) {
    $logo = $logoPath;
}
if ($faviconPath && file_exists($basePath . ltrim($faviconPath, './'))) {
    $favicon = $faviconPath;
} elseif ($logo) {
    $favicon = $logo; // Fallback to logo if favicon doesn't exist
}

// Colors
$primaryColor = $config['colors']['primary'] ?? '#1DB954';
$primaryDark = $config['colors']['primaryDark'] ?? '#1ed760';
$bgColor = $config['colors']['background'] ?? '#ffffff';
$surfaceColor = $config['colors']['surface'] ?? '#ffffff';
$textColor = $config['colors']['text'] ?? '#191414';
$textSecondary = $config['colors']['textSecondary'] ?? '#666666';
