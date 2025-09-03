<?php
/**
 * Test Setup Script
 * Run this file to verify your PHP/MySQL setup is working
 * Access: https://onenest.ihostcp.com/test_setup.php
 */

echo "<h1>OneNest Setup Test</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Required: 8.1+ " . (version_compare(PHP_VERSION, '8.1.0') >= 0 ? "✅ OK" : "❌ UPGRADE NEEDED") . "<br><br>";

// Test 2: Required Extensions
echo "<h2>2. PHP Extensions</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✅ Loaded" : "❌ Missing") . "<br>";
}
echo "<br>";

// Test 3: File Permissions
echo "<h2>3. File Permissions</h2>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Is writable: " . (is_writable(__DIR__) ? "✅ Yes" : "❌ No") . "<br>";
echo "Composer autoload: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? "✅ Found" : "❌ Missing - Run 'composer install'") . "<br>";
echo ".env file: " . (file_exists(__DIR__ . '/.env') ? "✅ Found" : "❌ Missing - Copy .env.example to .env") . "<br><br>";

// Test 4: Database Connection
echo "<h2>4. Database Connection</h2>";
if (file_exists(__DIR__ . '/.env')) {
    // Load environment variables manually for testing
    $envFile = file_get_contents(__DIR__ . '/.env');
    $envLines = explode("\n", $envFile);
    $env = [];
    foreach ($envLines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
    
    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $env['DB_HOST'] ?? 'localhost',
            $env['DB_PORT'] ?? '3306',
            $env['DB_DATABASE'] ?? 'onenest'
        );
        
        $pdo = new PDO($dsn, $env['DB_USERNAME'] ?? 'root', $env['DB_PASSWORD'] ?? '');
        echo "Database connection: ✅ Success<br>";
        echo "Database: " . ($env['DB_DATABASE'] ?? 'onenest') . "<br>";
        
        // Test if tables exist
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables found: " . count($tables) . "<br>";
        if (count($tables) > 0) {
            echo "Sample tables: " . implode(', ', array_slice($tables, 0, 5)) . "<br>";
        } else {
            echo "❌ No tables found - Run the migration SQL file<br>";
        }
        
    } catch (PDOException $e) {
        echo "Database connection: ❌ Failed<br>";
        echo "Error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ .env file not found<br>";
}

echo "<br><h2>5. Next Steps</h2>";
echo "<ol>";
echo "<li>If all tests pass, delete this file: <code>rm test_setup.php</code></li>";
echo "<li>Visit your site: <a href='/'>https://onenest.ihostcp.com</a></li>";
echo "<li>Register a new account to test the system</li>";
echo "</ol>";

echo "<br><h2>6. Troubleshooting</h2>";
echo "<p>If you still get 'Forbidden' errors:</p>";
echo "<ul>";
echo "<li>Run: <code>chmod -R 755 /path/to/onenest</code></li>";
echo "<li>Run: <code>chown -R www-data:www-data /path/to/onenest</code></li>";
echo "<li>Ensure Apache has <code>mod_rewrite</code> enabled</li>";
echo "</ul>";

echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:40px auto;padding:20px;} h1{color:#009688;} h2{color:#004D40;border-bottom:1px solid #eee;padding-bottom:10px;} code{background:#f4f4f4;padding:2px 6px;border-radius:4px;}</style>";
?>