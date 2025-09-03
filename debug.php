<?php
/**
 * Debug Script - Check what's happening with routing
 * Access: https://onenest.ihostcp.com/debug.php
 */

echo "<h1>OneNest Debug Information</h1>";

// Test 1: Basic PHP
echo "<h2>1. PHP Information</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br><br>";

// Test 2: File Structure
echo "<h2>2. File Structure</h2>";
echo "Root index.php exists: " . (file_exists(__DIR__ . '/index.php') ? "✅ Yes" : "❌ No") . "<br>";
echo "Public index.php exists: " . (file_exists(__DIR__ . '/public/index.php') ? "✅ Yes" : "❌ No") . "<br>";
echo "Root .htaccess exists: " . (file_exists(__DIR__ . '/.htaccess') ? "✅ Yes" : "❌ No") . "<br>";
echo "Public .htaccess exists: " . (file_exists(__DIR__ . '/public/.htaccess') ? "✅ Yes" : "❌ No") . "<br>";
echo "Vendor autoload exists: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? "✅ Yes" : "❌ No") . "<br><br>";

// Test 3: Permissions
echo "<h2>3. Permissions</h2>";
echo "Root directory writable: " . (is_writable(__DIR__) ? "✅ Yes" : "❌ No") . "<br>";
echo "Public directory writable: " . (is_writable(__DIR__ . '/public') ? "✅ Yes" : "❌ No") . "<br><br>";

// Test 4: Apache Modules
echo "<h2>4. Apache Information</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite enabled: " . (in_array('mod_rewrite', $modules) ? "✅ Yes" : "❌ No") . "<br>";
} else {
    echo "Cannot check Apache modules (not running under Apache or function disabled)<br>";
}

// Test 5: Test Routes
echo "<h2>5. Test Routes</h2>";
echo "<p>Try these links to test routing:</p>";
echo "<ul>";
echo "<li><a href='/'>Home</a></li>";
echo "<li><a href='/businesses'>Businesses</a></li>";
echo "<li><a href='/marketplace'>Marketplace</a></li>";
echo "<li><a href='/properties'>Properties</a></li>";
echo "<li><a href='/jobs'>Jobs</a></li>";
echo "<li><a href='/how-it-works'>How It Works</a></li>";
echo "</ul>";

// Test 6: Environment
echo "<h2>6. Environment</h2>";
if (file_exists(__DIR__ . '/.env')) {
    echo ".env file: ✅ Found<br>";
    $envContent = file_get_contents(__DIR__ . '/.env');
    $hasDbConfig = strpos($envContent, 'DB_HOST') !== false;
    echo "Database config: " . ($hasDbConfig ? "✅ Found" : "❌ Missing") . "<br>";
} else {
    echo ".env file: ❌ Missing<br>";
}

echo "<br><h2>7. Manual Test</h2>";
echo "<p>If routes still don't work, try accessing the public folder directly:</p>";
echo "<a href='/public/'>Direct Public Access</a><br>";

echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:40px auto;padding:20px;} h1{color:#009688;} h2{color:#004D40;border-bottom:1px solid #eee;padding-bottom:10px;} a{color:#009688;}</style>";
?>