<?php
/**
 * Minimal test - bypasses all configuration
 */
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Minimal Test</title></head>";
echo "<body>";
echo "<h1>Minimal Test - Direct PHP</h1>";

// Test 1: Can we require config.php?
echo "<h2>Test 1: Loading config.php</h2>";
try {
    require_once __DIR__ . '/config/config.php';
    echo "<p style='color: green;'>✓ config.php loaded successfully</p>";
    echo "<p>BASE_URL: " . BASE_URL . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ config.php failed: " . $e->getMessage() . "</p>";
}

// Test 2: Can we create a router?
echo "<h2>Test 2: Creating Router</h2>";
try {
    require_once __DIR__ . '/app/controllers/Router.php';
    $router = new Router();
    echo "<p style='color: green;'>✓ Router created successfully</p>";
    
    // Manually test the route matching
    $testUri = '/worldcupprediction-big/';
    $parsed = parse_url($testUri, PHP_URL_PATH);
    $clean = str_replace('/worldcupprediction-big', '', $parsed);
    $clean = trim($clean, '/');
    if (empty($clean)) $clean = '';
    
    echo "<p>Test URI: '$testUri'</p>";
    echo "<p>Parsed: '$parsed'</p>";
    echo "<p>Clean: '$clean'</p>";
    
    // Test if it matches the empty route
    if ($clean === '') {
        echo "<p style='color: green;'>✓ Empty URI would match homepage route</p>";
    } else {
        echo "<p style='color: red;'>✗ Empty URI test failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Router creation failed: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test 3: What's the actual request URI?
echo "<h2>Test 3: Current Request</h2>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p>SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";
echo "<p>You accessed this via: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";

echo "<hr>";
echo "<h2>Quick Fixes to Try:</h2>";
echo "<ol>";
echo "<li>Make sure XAMPP Apache is running (green 'Run' button in XAMPP Control Panel)</li>";
echo "<li>Make sure MySQL is also running</li>";
echo "<li>Access <a href='http://localhost/worldcupprediction-big/test.php'>test.php</a> first</li>";
echo "<li>If test.php works but homepage doesn't, the issue is with routing</li>";
echo "<li>Try accessing <a href='http://localhost/worldcupprediction-big/index.php'>index.php directly</a></li>";
echo "<li>If index.php shows debug info, routing is working but not matching</li>";
echo "<li>If index.php shows 404, check .htaccess and mod_rewrite</li>";
echo "</ol>";

echo "<p><strong>If nothing works, try:</strong></p>";
echo "<pre>";
echo "1. Stop XAMPP Apache\n";
echo "2. Delete .htaccess file\n";
echo "3. Start XAMPP Apache\n";
echo "4. Access index.php directly: http://localhost/worldcupprediction-big/index.php\n";
echo "5. If that works, the issue is with .htaccess/mod_rewrite\n";
echo "</pre>";

echo "</body>";
echo "</html>";
?>