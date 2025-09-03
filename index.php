<?php

// Root index.php - Serves the public folder without Apache config changes
// This allows the app to work without modifying DocumentRoot

// Check if this is a request for a static file (CSS, JS, images)
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// Handle static files from public directory
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i', $path)) {
    $filePath = __DIR__ . '/public' . $path;
    
    if (file_exists($filePath)) {
        // Set appropriate content type
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
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
        echo "File not found: " . htmlspecialchars($path);
        exit;
    }
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