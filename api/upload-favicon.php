<?php
/**
 * Favicon Upload API
 * Handles favicon image upload for the reseller website
 * Requires admin authentication
 */

// Load error handler for consistent error logging
require_once __DIR__ . '/../includes/error-handler.php';

require_once __DIR__ . '/FileUploader.php';

// Initialize request (CORS, session, method validation)
FileUploader::initializeRequest();

// Validate admin authentication
FileUploader::validateAdmin();

// Configure favicon upload
$config = [
    'field_name' => 'favicon',
    'allowed_types' => [
        'image/png',
        'image/x-icon',
        'image/vnd.microsoft.icon',
        'image/ico',
        'image/svg+xml'
    ],
    'max_size' => 1 * 1024 * 1024, // 1MB
    'upload_dir' => __DIR__ . '/../assets/images/',
    'filename' => 'favicon',
    'type_names' => 'PNG, ICO, SVG',
    'extensions' => [
        'image/png' => 'png',
        'image/x-icon' => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
        'image/ico' => 'ico',
        'image/svg+xml' => 'svg'
    ]
];

// Process upload
$uploader = new FileUploader($config);
$result = $uploader->process();

// Output result
echo json_encode($result);
?>
