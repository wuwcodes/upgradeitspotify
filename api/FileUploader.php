<?php
/**
 * FileUploader - Shared file upload handler
 * Handles common upload logic for images with MIME type checking and SVG sanitization
 */

class FileUploader {
    private $config;
    private $uploadedFile;
    private $mimeType;

    /**
     * Configuration structure:
     * [
     *     'field_name' => 'logo',              // Form field name
     *     'allowed_types' => [...],            // Array of MIME types
     *     'max_size' => 2 * 1024 * 1024,      // Max file size in bytes
     *     'upload_dir' => __DIR__ . '/../assets/images/',
     *     'filename' => 'logo',                // Base filename (extension auto-added)
     *     'type_names' => 'PNG, JPG, GIF, SVG, WebP', // Human-readable type names
     *     'extensions' => [...]                // MIME to extension mapping
     * ]
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    public static function initializeRequest(): void {
        session_start();

        require_once __DIR__ . '/cors-helper.php';
        handlePreflight('POST, OPTIONS');
        handleCors('POST, OPTIONS');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::jsonError(405, 'Method not allowed');
        }
    }

    public static function validateAdmin(): void {
        $token = isset($_SERVER['HTTP_X_ADMIN_TOKEN']) ? $_SERVER['HTTP_X_ADMIN_TOKEN'] : '';
        $isValid = false;

        if (!empty($token) && isset($_SESSION['admin_token']) && isset($_SESSION['admin_expiry'])) {
            if ($_SESSION['admin_token'] === $token && $_SESSION['admin_expiry'] > time()) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            self::jsonError(401, 'Unauthorized. Please login to admin panel.');
        }
    }

    public function process(): array {
        $this->validateUpload();
        $this->validateFileType();
        $this->validateFileSize();

        if ($this->mimeType === 'image/svg+xml') {
            $this->sanitizeSVG();
        }

        return $this->saveFile();
    }

    private function validateUpload(): void {
        $fieldName = $this->config['field_name'];

        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];

            $errorCode = $_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE;
            $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error';

            self::jsonError(400, $errorMsg);
        }

        $this->uploadedFile = $_FILES[$fieldName];
    }

    private function validateFileType(): void {
        // SECURITY: Use finfo for actual MIME type, not file extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->mimeType = finfo_file($finfo, $this->uploadedFile['tmp_name']);
        finfo_close($finfo);

        if (!in_array($this->mimeType, $this->config['allowed_types'])) {
            self::jsonError(400, 'Invalid file type. Allowed: ' . $this->config['type_names']);
        }
    }

    private function validateFileSize(): void {
        if ($this->uploadedFile['size'] > $this->config['max_size']) {
            $maxSizeMB = $this->config['max_size'] / (1024 * 1024);
            self::jsonError(400, "File too large. Maximum size is {$maxSizeMB}MB");
        }
    }

    /**
     * Sanitize SVG files using whitelist-based approach
     */
    private function sanitizeSVG(): void {
        $svgContent = file_get_contents($this->uploadedFile['tmp_name']);

        // SECURITY: DOMDocument with LIBXML_NONET prevents XXE attacks
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadXML($svgContent, LIBXML_NONET);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!empty($errors)) {
            self::jsonError(400, 'Invalid SVG file. Please use a valid SVG or try ' . $this->getAlternativeFormats());
        }

        // Check for CDATA sections that might contain scripts
        if (preg_match('/<!\[CDATA\[.*?<script/is', $svgContent) ||
            preg_match('/<!\[CDATA\[.*?javascript:/is', $svgContent)) {
            self::jsonError(400, 'SVG contains potentially unsafe CDATA content.');
        }

        // Whitelist of allowed SVG elements
        $allowedElements = [
            'svg', 'g', 'path', 'rect', 'circle', 'ellipse', 'line', 'polyline',
            'polygon', 'text', 'tspan', 'defs', 'clippath', 'mask', 'use',
            'symbol', 'lineargradient', 'radialgradient', 'stop', 'title', 'desc',
            'image', 'pattern', 'filter', 'fegaussianblur', 'feoffset', 'feblend',
            'fecolormatrix', 'fecomponenttransfer', 'fecomposite', 'feconvolvematrix',
            'fediffuselighting', 'fedisplacementmap', 'feflood', 'femerge', 'femergenode',
            'femorphology', 'fespecularlighting', 'fetile', 'feturbulence'
        ];

        // Check all elements and attributes
        $xpath = new DOMXPath($dom);
        $allElements = $xpath->query('//*');

        foreach ($allElements as $element) {
            $tagName = strtolower($element->nodeName);

            // Check if element is allowed
            if (!in_array($tagName, $allowedElements)) {
                self::jsonError(400, 'SVG contains disallowed element: ' . htmlspecialchars($tagName) . '. Please use ' . $this->getAlternativeFormats() . ' instead.');
            }

            // Check all attributes
            if ($element->hasAttributes()) {
                foreach ($element->attributes as $attr) {
                    $attrName = strtolower($attr->nodeName);
                    $attrValue = $attr->nodeValue;

                    // Block event handlers (case-insensitive check on original attribute name)
                    if (preg_match('/^on/i', $attr->nodeName)) {
                        self::jsonError(400, 'SVG contains event handlers which are not allowed.');
                    }

                    // Block dangerous namespace declarations (xmlns attributes pointing to XHTML)
                    if (preg_match('/^xmlns:/i', $attrName)) {
                        if ($attrValue === 'http://www.w3.org/1999/xhtml') {
                            self::jsonError(400, 'SVG contains potentially unsafe namespace declaration.');
                        }
                    }

                    // Block javascript: and data: URIs in href/xlink:href
                    if (in_array($attrName, ['href', 'xlink:href'])) {
                        if (preg_match('/^\s*(javascript|data|vbscript):/i', $attrValue)) {
                            self::jsonError(400, 'SVG contains potentially unsafe URI scheme.');
                        }
                    }

                    // Block javascript in style attribute
                    if ($attrName === 'style') {
                        if (preg_match('/(javascript|expression|behavior|binding|url\s*\()/i', $attrValue)) {
                            self::jsonError(400, 'SVG style contains potentially unsafe content.');
                        }
                    }
                }
            }
        }
    }

    private function getAlternativeFormats(): string {
        $types = [];
        foreach ($this->config['allowed_types'] as $mimeType) {
            if ($mimeType !== 'image/svg+xml') {
                $ext = $this->config['extensions'][$mimeType] ?? '';
                if ($ext) {
                    $types[] = strtoupper($ext);
                }
            }
        }
        return implode(', ', $types);
    }

    private function saveFile(): array {
        $uploadDir = $this->config['upload_dir'];

        if (!file_exists($uploadDir)) {
            if (!@mkdir($uploadDir, 0755, true)) {
                if (!@mkdir($uploadDir, 0777, true)) {
                    self::jsonError(500, 'Failed to create upload directory. Please create "assets/images" folder manually and set permissions to 755 or 777.');
                }
            }
        }

        if (!is_writable($uploadDir)) {
            @chmod($uploadDir, 0777);
            if (!is_writable($uploadDir)) {
                self::jsonError(500, 'Upload directory is not writable. Please set permissions on "assets/images" folder to 755 or 777.');
            }
        }

        $extension = $this->config['extensions'][$this->mimeType] ?? 'png';
        $filename = $this->config['filename'];
        $targetFile = $uploadDir . $filename . '.' . $extension;

        if (move_uploaded_file($this->uploadedFile['tmp_name'], $targetFile)) {
            $relativePath = './assets/images/' . $filename . '.' . $extension;

            return [
                'success' => true,
                'message' => ucfirst($filename) . ' uploaded successfully',
                'path' => $relativePath
            ];
        } else {
            self::jsonError(500, 'Failed to save uploaded file');
        }
    }

    private static function jsonError(int $code, string $message): void {
        http_response_code($code);
        echo json_encode(['success' => false, 'error' => $message]);
        exit();
    }
}
?>
