<?php
/**
 * Update database schema for Football-Data.org API integration
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Match.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Update API Schema - PredictCup</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #000; color: #fff; }";
echo ".success { color: #0f0; padding: 10px; margin: 10px 0; border-left: 4px solid #0f0; }";
echo ".error { color: #f00; padding: 10px; margin: 10px 0; border-left: 4px solid #f00; }";
echo ".info { color: #00f; padding: 10px; margin: 10px 0; border-left: 4px solid #00f; }";
echo "pre { background: #111; padding: 10px; border: 1px solid #333; overflow: auto; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<h1>Update Database Schema for API Integration</h1>";

try {
    $matchModel = new MatchModel();
    
    echo "<div class='info'>Updating database schema...</div>";
    
    $result = $matchModel->updateSchemaForAPI();
    
    if ($result['success']) {
        echo "<div class='success'>✓ Schema updated successfully!</div>";
        
        // Test API connection if key is configured
        if (!empty(FOOTBALL_DATA_API_KEY)) {
            echo "<div class='info'>Testing API connection...</div>";
            
            $apiResult = $matchModel->fetchMatchesFromAPI();
            
            if ($apiResult['success']) {
                echo "<div class='success'>✓ API connection successful!</div>";
                echo "<pre>";
                echo "Imported: " . $apiResult['imported'] . " matches\n";
                echo "Updated: " . $apiResult['updated'] . " matches\n";
                echo "Total in API response: " . $apiResult['total'] . " matches";
                echo "</pre>";
            } else {
                echo "<div class='error'>✗ API connection failed: " . $apiResult['message'] . "</div>";
                echo "<p>Make sure you have added your Football-Data.org API key to config.php</p>";
                echo "<p>Get an API key from: <a href='https://www.football-data.org/' style='color: #fff;'>https://www.football-data.org/</a></p>";
            }
        } else {
            echo "<div class='error'>✗ API key not configured in config.php</div>";
            echo "<p>To enable automatic match fetching, add your Football-Data.org API key:</p>";
            echo "<pre>";
            echo "1. Get API key from https://www.football-data.org/\n";
            echo "2. Open config/config.php\n";
            echo "3. Find line: define('FOOTBALL_DATA_API_KEY', '');\n";
            echo "4. Add your key: define('FOOTBALL_DATA_API_KEY', 'your-api-key-here');";
            echo "</pre>";
        }
    } else {
        echo "<div class='error'>✗ Schema update failed: " . $result['message'] . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>Get API key from Football-Data.org (free tier available)</li>";
echo "<li>Add API key to config/config.php</li>";
echo "<li>Run this script again to fetch matches</li>";
echo "<li>Setup cron job for automatic updates (optional)</li>";
echo "</ol>";

echo "<p><a href='./' style='color: #fff;'>← Back to Home</a></p>";
echo "</body>";
echo "</html>";
?>