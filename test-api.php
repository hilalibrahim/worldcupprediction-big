<?php
/**
 * Test Football-Data.org API Integration
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Match.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Test API Integration - PredictCup</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #000; color: #fff; }";
echo ".success { color: #0f0; padding: 10px; margin: 10px 0; border-left: 4px solid #0f0; }";
echo ".error { color: #f00; padding: 10px; margin: 10px 0; border-left: 4px solid #f00; }";
echo ".info { color: #00f; padding: 10px; margin: 10px 0; border-left: 4px solid #00f; }";
echo "pre { background: #111; padding: 10px; border: 1px solid #333; overflow: auto; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<h1>Football-Data.org API Integration Test</h1>";

try {
    $matchModel = new MatchModel();
    
    // Check API configuration
    echo "<div class='info'>Checking API configuration...</div>";
    
    if (empty(FOOTBALL_DATA_API_KEY)) {
        echo "<div class='error'>✗ API key not configured in config.php</div>";
        echo "<p>Please add your Football-Data.org API key to config.php:</p>";
        echo "<pre>";
        echo "define('FOOTBALL_DATA_API_KEY', 'your-api-key-here');";
        echo "</pre>";
        echo "<p>Get a free API key from: <a href='https://www.football-data.org/' style='color: #fff;'>https://www.football-data.org/</a></p>";
    } else {
        echo "<div class='success'>✓ API key configured</div>";
        
        // Test direct API connection
        echo "<div class='info'>Testing direct API connection...</div>";
        
        $apiUrl = FOOTBALL_DATA_API_URL . 'matches?competitions=' . FOOTBALL_DATA_COMPETITION;
        $headers = [
            'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            echo "<div class='success'>✓ API connection successful (HTTP $httpCode)</div>";
            
            $data = json_decode($response, true);
            
            if (isset($data['matches'])) {
                $matchCount = count($data['matches']);
                echo "<div class='success'>✓ Found $matchCount matches in API response</div>";
                
                // Show first match as example
                if ($matchCount > 0) {
                    $firstMatch = $data['matches'][0];
                    echo "<div class='info'>Sample match from API:</div>";
                    echo "<pre>";
                    echo json_encode($firstMatch, JSON_PRETTY_PRINT);
                    echo "</pre>";
                }
            } else {
                echo "<div class='error'>✗ Invalid API response format</div>";
                echo "<pre>" . htmlspecialchars($response) . "</pre>";
            }
        } else {
            echo "<div class='error'>✗ API request failed (HTTP $httpCode)</div>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
        
        // Test database schema update
        echo "<div class='info'>Testing database schema update...</div>";
        
        $schemaResult = $matchModel->updateSchemaForAPI();
        
        if ($schemaResult['success']) {
            echo "<div class='success'>✓ Database schema updated successfully</div>";
            
            // Test match fetching
            echo "<div class='info'>Testing match fetching and syncing...</div>";
            
            $fetchResult = $matchModel->fetchMatchesFromAPI();
            
            if ($fetchResult['success']) {
                echo "<div class='success'>✓ Match fetching successful!</div>";
                echo "<pre>";
                echo "Imported: " . $fetchResult['imported'] . " matches\n";
                echo "Updated: " . $fetchResult['updated'] . " matches\n";
                echo "Total in API: " . $fetchResult['total'] . " matches";
                echo "</pre>";
            } else {
                echo "<div class='error'>✗ Match fetching failed: " . $fetchResult['message'] . "</div>";
            }
        } else {
            echo "<div class='error'>✗ Schema update failed: " . $schemaResult['message'] . "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li><a href='update-api-schema.php' style='color: #fff;'>Run schema update script</a></li>";
echo "<li><a href='admin/login' style='color: #fff;'>Go to Admin Panel</a> → API Management</li>";
echo "<li>Setup cron job for automatic updates (optional)</li>";
echo "</ol>";

echo "<p><a href='./' style='color: #fff;'>← Back to Home</a></p>";
echo "</body>";
echo "</html>";
?>