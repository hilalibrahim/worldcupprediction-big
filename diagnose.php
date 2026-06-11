<?php
/**
 * PredictCup Diagnosis Script
 * Use this to diagnose issues with the application
 */

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>PredictCup Diagnosis</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; }";
echo ".success { color: green; background: #e8f5e8; padding: 10px; margin: 10px 0; border-left: 4px solid green; }";
echo ".error { color: red; background: #ffebee; padding: 10px; margin: 10px 0; border-left: 4px solid red; }";
echo ".warning { color: orange; background: #fff3e0; padding: 10px; margin: 10px 0; border-left: 4px solid orange; }";
echo ".info { color: blue; background: #e3f2fd; padding: 10px; margin: 10px 0; border-left: 4px solid blue; }";
echo "pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<h1>PredictCup Diagnosis</h1>";

// Test 1: Basic PHP
echo "<div class='success'>Test 1: PHP is working (version " . phpversion() . ")</div>";

// Test 2: Check if config.php loads
$configLoaded = false;
try {
    require_once __DIR__ . '/config/config.php';
    $configLoaded = true;
    echo "<div class='success'>Test 2: config.php loaded successfully</div>";
} catch (Exception $e) {
    echo "<div class='error'>Test 2: config.php failed to load: " . $e->getMessage() . "</div>";
}

// Test 3: Check if helpers.php loads
if ($configLoaded) {
    try {
        require_once __DIR__ . '/app/helpers/helpers.php';
        echo "<div class='success'>Test 3: helpers.php loaded successfully</div>";
    } catch (Exception $e) {
        echo "<div class='error'>Test 3: helpers.php failed to load: " . $e->getMessage() . "</div>";
    }
}

// Test 4: Check if database.php loads
if ($configLoaded) {
    try {
        require_once __DIR__ . '/config/database.php';
        echo "<div class='success'>Test 4: database.php loaded successfully</div>";
    } catch (Exception $e) {
        echo "<div class='error'>Test 4: database.php failed to load: " . $e->getMessage() . "</div>";
    }
}

// Test 5: Check if models load
if ($configLoaded) {
    $models = ['User.php', 'Team.php', 'Match.php', 'Prediction.php', 'Room.php', 'Achievement.php', 'Notification.php'];
    $allModelsLoaded = true;
    foreach ($models as $model) {
        $modelPath = __DIR__ . '/app/models/' . $model;
        if (file_exists($modelPath)) {
            try {
                require_once $modelPath;
                echo "<div class='success'>Test 5: Model $model loaded successfully</div>";
            } catch (Exception $e) {
                echo "<div class='error'>Test 5: Model $model failed to load: " . $e->getMessage() . "</div>";
                $allModelsLoaded = false;
            }
        } else {
            echo "<div class='warning'>Test 5: Model $model not found at $modelPath</div>";
            $allModelsLoaded = false;
        }
    }
}

// Test 6: Check if Router.php loads
if ($configLoaded) {
    try {
        require_once __DIR__ . '/app/controllers/Router.php';
        echo "<div class='success'>Test 6: Router.php loaded successfully</div>";
    } catch (Exception $e) {
        echo "<div class='error'>Test 6: Router.php failed to load: " . $e->getMessage() . "</div>";
    }
}

// Test 7: Check server environment
echo "<div class='info'>Test 7: Server Environment</div>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "BASE_URL constant: " . (defined('BASE_URL') ? BASE_URL : 'Not defined') . "\n";
echo "</pre>";

// Test 8: Check if mod_rewrite is working
echo "<div class='info'>Test 8: mod_rewrite test</div>";
if (isset($_SERVER['REDIRECT_STATUS'])) {
    echo "<div class='success'>mod_rewrite appears to be working (REDIRECT_STATUS = " . $_SERVER['REDIRECT_STATUS'] . ")</div>";
} else {
    echo "<div class='warning'>mod_rewrite may not be active (no REDIRECT_STATUS)</div>";
}

// Test 9: Test router instantiation
if ($configLoaded && $allModelsLoaded) {
    try {
        $router = new Router();
        echo "<div class='success'>Test 9: Router instantiated successfully</div>";
        
        // Test route matching
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $parsed_uri = parse_url($uri, PHP_URL_PATH);
        $clean_uri = str_replace('/worldcupprediction-big', '', $parsed_uri);
        $clean_uri = trim($clean_uri, '/');
        if (empty($clean_uri)) {
            $clean_uri = '';
        }
        
        echo "<div class='info'>URI Analysis:</div>";
        echo "<pre>";
        echo "Original URI: $uri\n";
        echo "Parsed URI: $parsed_uri\n";
        echo "Clean URI: '$clean_uri'\n";
        echo "</pre>";
        
    } catch (Exception $e) {
        echo "<div class='error'>Test 9: Router failed to instantiate: " . $e->getMessage() . "</div>";
        echo "<pre>Error trace:\n" . $e->getTraceAsString() . "</pre>";
    }
}

// Test 10: Check file permissions
echo "<div class='info'>Test 10: Key file permissions</div>";
$keyFiles = [
    __DIR__ . '/.htaccess',
    __DIR__ . '/index.php',
    __DIR__ . '/config/config.php',
    __DIR__ . '/public/uploads/'
];
foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        $writable = is_writable($file);
        echo "<div class='" . ($writable ? 'success' : 'warning') . "'>";
        echo "$file: Permissions $perms, " . ($writable ? 'writable' : 'not writable');
        echo "</div>";
    } else {
        echo "<div class='warning'>$file: Does not exist</div>";
    }
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If you see errors above, fix them first</li>";
echo "<li>Try accessing <a href='test.php'>test.php</a> to verify PHP works</li>";
echo "<li>Try accessing <a href='simple-test.php'>simple-test.php</a> for a simple test</li>";
echo "<li>Try accessing <a href='index.php'>index.php</a> directly (should show debug info)</li>";
echo "<li>Try accessing <a href='./'>Home page</a> (might show 404 if routing not working)</li>";
echo "<li>Make sure XAMPP Apache and MySQL are running</li>";
echo "<li>Import the database from predictcup.sql if not already done</li>";
echo "</ol>";

echo "<p><strong>Admin Login:</strong> admin@predictcup.com / password</p>";

echo "</body>";
echo "</html>";
?>