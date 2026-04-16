<?php
/**
 * CORS Helper - Secure CORS handling for API endpoints
 *
 * This validates the Origin header and only allows same-origin requests
 * or requests from the same host. This prevents CSRF attacks from external sites.
 */

function handleCors(string $allowedMethods = 'POST, OPTIONS'): bool {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? '';

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $expectedOrigin = $protocol . '://' . $host;

    // For same-origin requests, Origin header may be absent
    // In that case, we allow the request (browser enforces same-origin)
    if (empty($origin)) {
        // No origin header - likely same-origin request or non-browser client
        header('Content-Type: application/json');
        return true;
    }

    $originParts = parse_url($origin);
    $originHost = $originParts['host'] ?? '';

    // SECURITY: Allow www/non-www variations to prevent blocking legitimate requests
    if ($originHost === $host || $originHost === str_replace('www.', '', $host) || 'www.' . $originHost === $host) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Methods: ' . $allowedMethods);
        header('Access-Control-Allow-Headers: Content-Type, X-Admin-Token');
        header('Access-Control-Allow-Credentials: true');
        return true;
    }

    // SECURITY: Development exception - only allow if both are localhost
    $localhostPatterns = ['localhost', '127.0.0.1', '::1'];
    $originIsLocalhost = false;
    $hostIsLocalhost = false;

    foreach ($localhostPatterns as $pattern) {
        if (strpos($originHost, $pattern) !== false) $originIsLocalhost = true;
        if (strpos($host, $pattern) !== false) $hostIsLocalhost = true;
    }

    if ($originIsLocalhost && $hostIsLocalhost) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Methods: ' . $allowedMethods);
        header('Access-Control-Allow-Headers: Content-Type, X-Admin-Token');
        header('Access-Control-Allow-Credentials: true');
        return true;
    }

    // Origin doesn't match - potential CSRF attack
    // Still set content-type but don't allow cross-origin
    header('Content-Type: application/json');
    return false;
}

/**
 * Handle OPTIONS preflight request
 */
function handlePreflight(string $allowedMethods = 'POST, OPTIONS'): void {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        handleCors($allowedMethods);
        http_response_code(200);
        exit();
    }
}
