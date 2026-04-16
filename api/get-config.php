<?php
/**
 * Get Configuration API
 * Returns the saved configuration from JSON file
 * SECURITY: Sensitive fields (apiKey, adminPassword) are NOT returned to browser
 */

// Load error handler for consistent error logging
require_once __DIR__ . '/../includes/error-handler.php';

require_once __DIR__ . '/cors-helper.php';
handlePreflight('GET, OPTIONS');
handleCors('GET, OPTIONS');

$configFile = __DIR__ . '/../config/config.json';

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);

    if ($config !== null) {
        // Add flag indicating if API key is configured (without exposing the actual key)
        $config['apiKeyConfigured'] = !empty($config['apiKey']) && $config['apiKey'] !== 'YOUR_API_KEY_HERE';

        // Remove sensitive fields before sending to browser
        unset($config['apiKey']);
        unset($config['adminPassword']);

        echo json_encode($config);
    } else {
        http_response_code(200);
        echo '{}';
    }
} else {
    http_response_code(200);
    echo '{}';
}
?>
