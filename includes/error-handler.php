<?php
/**
 * Error Handler - Centralized PHP error logging
 * Include this at the top of every page for consistent error handling
 */

// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === 'error-handler.php') {
    http_response_code(403);
    exit('Direct access not allowed');
}

// Configure error reporting
error_reporting(E_ALL);

// SECURITY: Hide errors from end users in production
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Enable error logging
ini_set('log_errors', '1');

// Set error log path
$logDir = __DIR__ . '/../logs';
$errorLogFile = $logDir . '/php-errors.log';

// Create logs directory if it doesn't exist
if (!file_exists($logDir)) {
    @mkdir($logDir, 0755, true);
}

// Ensure log file is writable
if (!file_exists($errorLogFile)) {
    @touch($errorLogFile);
    @chmod($errorLogFile, 0644);
}

ini_set('error_log', $errorLogFile);

/**
 * Custom error handler
 * Logs errors with timestamp, context, and request information
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // SECURITY: Respects @ operator to avoid logging suppressed errors
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // Map error types to readable names
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE_ERROR',
        E_CORE_WARNING => 'CORE_WARNING',
        E_COMPILE_ERROR => 'COMPILE_ERROR',
        E_COMPILE_WARNING => 'COMPILE_WARNING',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE',
        E_STRICT => 'STRICT',
        E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
        E_DEPRECATED => 'DEPRECATED',
        E_USER_DEPRECATED => 'USER_DEPRECATED'
    ];

    $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'UNKNOWN';

    // Build context information
    $context = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => $errorType,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline,
        'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'CLI',
        'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI',
        'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown'
    ];

    // Format log message
    $logMessage = sprintf(
        "[%s] %s: %s in %s on line %d (URL: %s, Method: %s, IP: %s)\n",
        $context['timestamp'],
        $context['type'],
        $context['message'],
        $context['file'],
        $context['line'],
        $context['url'],
        $context['method'],
        $context['ip']
    );

    // Write to error log
    error_log($logMessage, 3, $GLOBALS['errorLogFile'] ?? ini_get('error_log'));

    // Don't execute PHP internal error handler
    return true;
}

/**
 * Custom exception handler
 * Logs uncaught exceptions with full stack trace
 */
function customExceptionHandler($exception) {
    // Build context information
    $context = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => get_class($exception),
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString(),
        'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'CLI',
        'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI',
        'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown'
    ];

    // Format log message with stack trace
    $logMessage = sprintf(
        "[%s] UNCAUGHT EXCEPTION: %s: %s in %s on line %d\n",
        $context['timestamp'],
        $context['type'],
        $context['message'],
        $context['file'],
        $context['line']
    );
    $logMessage .= sprintf("URL: %s, Method: %s, IP: %s\n", $context['url'], $context['method'], $context['ip']);
    $logMessage .= "Stack trace:\n" . $context['trace'] . "\n";
    $logMessage .= str_repeat('-', 80) . "\n";

    // Write to error log
    error_log($logMessage, 3, $GLOBALS['errorLogFile'] ?? ini_get('error_log'));

    // For AJAX/API requests, return JSON error
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'An internal server error occurred. Please try again later.'
        ]);
    } else {
        // For regular page requests, show generic error page
        http_response_code(500);
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Server Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
        .error-container { background: white; max-width: 600px; margin: 0 auto; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #e74c3c; margin-bottom: 20px; }
        p { color: #666; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>500 - Internal Server Error</h1>
        <p>An unexpected error occurred. Please try again later.</p>
        <p>If the problem persists, please contact support.</p>
    </div>
</body>
</html>';
    }

    exit();
}

/**
 * Shutdown function to catch fatal errors
 */
function handleShutdown() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        customErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
    }
}

// Store error log file path in global for handlers to use
$GLOBALS['errorLogFile'] = $errorLogFile;

// Set custom error handler
set_error_handler('customErrorHandler');

// Set custom exception handler
set_exception_handler('customExceptionHandler');

// Register shutdown function to catch fatal errors
register_shutdown_function('handleShutdown');
