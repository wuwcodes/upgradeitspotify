<?php
/**
 * Logo Upload API
 * Handles logo image upload for the reseller website
 * Requires admin authentication
 */

// Load error handler for consistent error logging
require_once __DIR__ . '/../includes/error-handler.php';

require_once __DIR__ . '/FileUploader.php';

// Initialize request (CORS, session, method validation)
FileUploader::initializeRequest();

// Validate admin authentication
FileUploader::validateAdmin();

// Configure logo upload
$config = [
    'field_name' => 'logo',
    'allowed_types' => [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/svg+xml',
        'image/webp'
    ],
    'max_size' => 2 * 1024 * 1024, // 2MB
    'upload_dir' => __DIR__ . '/../assets/images/',
    'filename' => 'logo',
    'type_names' => 'PNG, JPG, GIF, SVG, WebP',
    'extensions' => [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
        'image/webp' => 'webp'
    ]
];

// Process upload
$uploader = new FileUploader($config);
$result = $uploader->process();

// Output result
echo json_encode($result);
?>
