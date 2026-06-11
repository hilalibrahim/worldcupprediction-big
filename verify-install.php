<?php
/**
 * PredictCup - Installation Verification Script
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'predictcup_db');

echo "<h1>PredictCup Installation Verification</h1>";

// Check PHP version
echo "<h2>1. PHP Version Check</h2>";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "<p style='color: green;'>✓ PHP " . PHP_VERSION . " (Requirement: PHP 8.0+)</p>";
} else {
    echo "<p style='color: red;'>✗ PHP " . PHP_VERSION . " (Requirement: PHP 8.0+)</p>";
}

// Check MySQLi extension
echo "<h2>2. MySQLi Extension Check</h2>";
if (extension_loaded('mysqli')) {
    echo "<p style='color: green;'>✓ MySQLi extension is loaded</p>";
} else {
    echo "<p style='color: red;'>✗ MySQLi extension is not loaded</p>";
}

// Check PDO MySQL
echo "<h2>3. PDO MySQL Check</h2>";
if (extension_loaded('pdo_mysql')) {
    echo "<p style='color: green;'>✓ PDO MySQL extension is loaded</p>";
} else {
    echo "<p style='color: red;'>✗ PDO MySQL extension is not loaded</p>";
}

// Check file permissions
echo "<h2>4. File Permissions Check</h2>";
$uploadsDir = __DIR__ . '/public/uploads';
if (is_writable($uploadsDir)) {
    echo "<p style='color: green;'>✓ Uploads directory is writable</p>";
} else {
    echo "<p style='color: red;'>✗ Uploads directory is not writable</p>";
}

// Check .htaccess
echo "<h2>5. .htaccess Check</h2>";
if (file_exists(__DIR__ . '/.htaccess')) {
    echo "<p style='color: green;'>✓ .htaccess file exists</p>";
} else {
    echo "<p style='color: red;'>✗ .htaccess file not found</p>";
}

// Database connection
echo "<h2>6. Database Connection</h2>";
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($connection->connect_error) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $connection->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check database exists
    echo "<h2>7. Database Check</h2>";
    $result = $connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Database '" . DB_NAME . "' exists</p>";
        
        // Select database
        $connection->select_db(DB_NAME);
        
        // Check tables
        echo "<h2>8. Database Tables Check</h2>";
        $requiredTables = [
            'users', 'teams', 'matches', 'predictions', 'rooms',
            'room_members', 'achievements', 'notifications', 'password_resets',
            'remember_tokens', 'user_achievements', 'room_invitations', 'user_activities'
        ];
        
        foreach ($requiredTables as $table) {
            $result = $connection->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✓ Table '$table' exists</p>";
            } else {
                echo "<p style='color: red;'>✗ Table '$table' not found</p>";
            }
        }
        
        // Check admin exists
        echo "<h2>9. Admin Account Check</h2>";
        $connection->select_db(DB_NAME);
        $result = $connection->query("SELECT * FROM users WHERE is_admin = 1");
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            echo "<p style='color: green;'>✓ Admin account exists</p>";
            echo "<p>Email: admin@predictcup.com</p>";
            echo "<p>Password: password</p>";
        } else {
            echo "<p style='color: red;'>✗ Admin account not found. Run install.php</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Database '" . DB_NAME . "' not found. Run install.php</p>";
    }
}

// Check required files
echo "<h2>10. Required Files Check</h2>";
$requiredFiles = [
    'index.php',
    'api.php',
    'config/config.php',
    'config/database.php',
    'app/helpers/helpers.php'
];

foreach ($requiredFiles as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ File '$file' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ File '$file' not found</p>";
    }
}

echo "<h2>11. Next Steps</h2>";
echo "<ul>";
echo "<li>Make sure Apache and MySQL are running in XAMPP</li>";
echo "<li>Visit: <a href='http://localhost/worldcupprediction-big'>http://localhost/worldcupprediction-big</a></li>";
echo "<li>Admin login: admin@predictcup.com / password</li>";
echo "<li>Click 'Register Now' to create your account</li>";
echo "</ul>";

echo "<h2>Installation Complete!</h2>";
echo "<p style='color: green;'>If all checks passed, you can start using PredictCup.</p>";

$connection->close();
?>
