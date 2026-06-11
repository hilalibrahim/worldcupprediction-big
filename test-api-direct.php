<?php
/**
 * Direct API Test - Test Football-Data.org API Connection
 * This script tests the API endpoint directly and shows the response
 */

require_once __DIR__ . '/config/config.php';

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        Direct API Test - Football-Data.org                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Check API key
if (empty(FOOTBALL_DATA_API_KEY)) {
    echo "❌ ERROR: API key not configured!\n";
    echo "Please set FOOTBALL_DATA_API_KEY in config/config.php\n";
    exit(1);
}

echo "🔑 API Key: " . substr(FOOTBALL_DATA_API_KEY, 0, 15) . "...\n";
echo "🌐 Base URL: " . FOOTBALL_DATA_API_URL . "\n\n";

// Test the endpoint
$url = "https://api.football-data.org/v4/competitions/WC/matches";

echo "📡 Testing API Endpoint:\n";
echo "   URL: $url\n\n";

echo "⏳ Sending request...\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_VERBOSE, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 RESPONSE STATUS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "HTTP Status Code: ";
if ($httpCode === 200) {
    echo "✅ 200 OK\n\n";
} else {
    echo "❌ $httpCode\n\n";
    if ($curlError) {
        echo "cURL Error: $curlError\n";
    }
}

if ($httpCode !== 200) {
    echo "\n❌ API request failed!\n";
    echo "Response preview: " . substr($response, 0, 500) . "\n";
    exit(1);
}

// Parse response
$data = json_decode($response, true);

if (!$data) {
    echo "❌ Failed to parse JSON response\n";
    exit(1);
}

echo "✅ Response received and parsed successfully!\n\n";

// Show competition info
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🏆 COMPETITION INFO\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (isset($data['competition'])) {
    echo "Name:     " . ($data['competition']['name'] ?? 'N/A') . "\n";
    echo "Code:     " . ($data['competition']['code'] ?? 'N/A') . "\n";
    echo "Area:     " . ($data['competition']['area']['name'] ?? 'N/A') . "\n";
}

// Show season info
if (isset($data['season'])) {
    echo "\nSeason Info:\n";
    echo "  ID:                " . ($data['season']['id'] ?? 'N/A') . "\n";
    echo "  Start Date:        " . ($data['season']['startDate'] ?? 'N/A') . "\n";
    echo "  End Date:          " . ($data['season']['endDate'] ?? 'N/A') . "\n";
    echo "  Current Matchday:  " . ($data['season']['currentMatchDay'] ?? 'N/A') . "\n";
}

// Show matches
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "⚽ MATCHES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (!isset($data['matches']) || empty($data['matches'])) {
    echo "❌ No matches found in API response\n";
    echo "Response keys: " . implode(', ', array_keys($data)) . "\n";
    exit(1);
}

echo "✅ Found " . count($data['matches']) . " matches\n\n";

// Show first 5 matches as example
echo "📋 First 5 Matches:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$count = 0;
foreach ($data['matches'] as $match) {
    if ($count >= 5) break;
    $count++;
    
    $utcDate = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
    $utcDate->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $istTime = $utcDate->format('Y-m-d H:i IST');
    
    echo "$count. " . $match['homeTeam']['name'] . " vs " . $match['awayTeam']['name'] . "\n";
    echo "   Date (IST): $istTime\n";
    echo "   Status: " . $match['status'] . "\n";
    echo "   Stage: " . $match['stage'] . "\n";
    echo "   Venue: " . ($match['venue'] ?? 'N/A') . "\n\n";
}

// Statistics
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 MATCH STATISTICS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$statusCounts = [];
$stageCounts = [];

foreach ($data['matches'] as $match) {
    $status = $match['status'];
    $stage = $match['stage'];
    
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
    $stageCounts[$stage] = ($stageCounts[$stage] ?? 0) + 1;
}

echo "By Status:\n";
foreach ($statusCounts as $status => $count) {
    echo "  $status: $count\n";
}

echo "\nBy Stage:\n";
foreach ($stageCounts as $stage => $count) {
    echo "  $stage: $count\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ API TEST SUCCESSFUL!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Your API is working correctly!\n\n";

echo "Next Steps:\n";
echo "1. Run: http://localhost/worldcupprediction-big/fetch-all-worldcup-matches.php\n";
echo "2. View: http://localhost/worldcupprediction-big/daily-matches\n\n";

exit(0);
?>
