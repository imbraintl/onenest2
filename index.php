<?php

// Root index.php - Serves the public folder without Apache config changes
// This allows the app to work without modifying DocumentRoot

// Debug mode - remove this in production
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
    echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
    echo "Current Dir: " . __DIR__ . "\n";
    echo "Public exists: " . (file_exists(__DIR__ . '/public/index.php') ? 'Yes' : 'No') . "\n";
    echo "</pre>";
}

// Check if this is a request for a static file (CSS, JS, images)
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// Remove any query parameters for file extension check
$pathForExtension = strtok($path, '?');

// Handle static files from public directory
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i', $pathForExtension)) {
    $filePath = __DIR__ . '/public' . $pathForExtension;
    
    if (file_exists($filePath)) {
        // Set appropriate content type
        $extension = pathinfo($pathForExtension, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        
        // Set cache headers for static files
        header('Cache-Control: public, max-age=31536000'); // 1 year
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        readfile($filePath);
        exit;
    } else {
        // File not found
        http_response_code(404);
        echo "File not found: " . htmlspecialchars($pathForExtension);
        exit;
    }
}

// Special handling for debug and test files
if ($path === '/debug.php' && file_exists(__DIR__ . '/debug.php')) {
    include __DIR__ . '/debug.php';
    exit;
}

if ($path === '/test_setup.php' && file_exists(__DIR__ . '/test_setup.php')) {
    include __DIR__ . '/test_setup.php';
    exit;
}

// For all other requests, forward to the public/index.php
// This simulates Apache's DocumentRoot pointing to public/

// Update $_SERVER variables to make it appear as if we're in public/
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';

// Change working directory to public
chdir(__DIR__ . '/public');

// Include the actual application entry point
require_once __DIR__ . '/public/index.php';