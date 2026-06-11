<?php
/**
 * Test Prediction System
 */
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'app/helpers/helpers.php';

// Initialize database
$db = Database::getInstance();

echo "<h1>Prediction Test</h1>";

// Check predictions table structure
echo "<h2>Table Structure</h2>";
$columns = $db->resultSet("DESCRIBE predictions");
echo "<pre>";
print_r($columns);
echo "</pre>";

// Check if prediction_type column exists
$hasPredictionType = false;
foreach ($columns as $col) {
    if ($col['Field'] === 'prediction_type') {
        $hasPredictionType = true;
        break;
    }
}

if (!$hasPredictionType) {
    echo "<h2 style='color: red;'>Missing prediction_type column!</h2>";
    echo "<p>Run this SQL:</p>";
    echo "<pre>ALTER TABLE predictions ADD COLUMN prediction_type ENUM('winner', 'score') DEFAULT 'score' AFTER away_score;
ALTER TABLE predictions ADD COLUMN predicted_winner ENUM('home', 'draw', 'away') DEFAULT NULL AFTER prediction_type;</pre>";
} else {
    echo "<h2 style='color: green;'>Table structure OK</h2>";
}

// Test inserting a prediction directly
echo "<h2>Test Insert</h2>";
$testData = [
    'user_id' => 1,
    'match_id' => 1,
    'prediction_type' => 'score',
    'home_score' => 2,
    'away_score' => 1,
    'predicted_winner' => null,
    'points' => 0,
    'created_at' => date('Y-m-d H:i:s')
];

// Build insert query manually to test
$fields = implode(',', array_keys($testData));
$placeholders = ':' . implode(', :', array_keys($testData));

$params = [];
foreach ($testData as $key => $value) {
    $params[':' . $key] = $value;
}

$sql = "INSERT INTO predictions ({$fields}) VALUES ({$placeholders})";

echo "<p>SQL: $sql</p>";
echo "<pre>Params: ";
print_r($params);
echo "</pre>";

try {
    $result = $db->query($sql, $params);
    if ($result) {
        echo "<p style='color: green;'>Insert successful! ID: " . $db->lastInsertId() . "</p>";
    } else {
        echo "<p style='color: red;'>Insert failed: " . $db->error . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}