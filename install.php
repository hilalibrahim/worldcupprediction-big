<?php
/**
 * PredictCup - Installation Script
 * Database setup and initialization
 */

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'predictcup_db');

// Database connection (without selecting database)
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Starting PredictCup Installation...\n\n";

// Read SQL file
$sqlFile = __DIR__ . '/predictcup.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: " . $sqlFile);
}

$sql = file_get_contents($sqlFile);

// Split SQL into statements
$statements = preg_split('/;(?=(?:[^\'"]*[\'"][^\'"]*[\'"])*[^\'"]*$)/', $sql);

// Execute each statement
set_time_limit(0);
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement) && $statement !== "\n" && strpos($statement, '--') !== 0) {
        if (!$connection->query($statement)) {
            echo "Error executing statement: " . $connection->error . "\n";
        }
    }
}

echo "\nInstallation Complete!\n\n";
echo "Database: " . DB_NAME . "\n";
echo "Admin Login:\n";
echo "  Email: admin@predictcup.com\n";
echo "  Password: password\n";
echo "\nYou can now visit: http://localhost/predictcup\n";

$connection->close();
