<?php
/**
 * Fetch Today's Matches from Football-Data.org API
 * This script fetches today's World Cup matches and adds them to the database
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Match.php';
require_once __DIR__ . '/app/models/Team.php';
require_once __DIR__ . '/app/helpers/helpers.php';

echo "=== Fetching Today's Matches from Football-Data.org ===\n\n";

// Check if API key is configured
if (empty(FOOTBALL_DATA_API_KEY)) {
    echo "❌ ERROR: API key not configured in config.php\n";
    echo "Get a free API key from: https://www.football-data.org/client/register\n";
    exit(1);
}

$db = Database::getInstance();

// Fetch matches from API
$apiUrl = FOOTBALL_DATA_API_URL . 'matches?competitions=' . FOOTBALL_DATA_COMPETITION;

echo "📡 Fetching from API: $apiUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ ERROR: API returned HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 500) . "\n";
    exit(1);
}

$data = json_decode($response, true);

if (!isset($data['matches']) || empty($data['matches'])) {
    echo "❌ No matches found in API response\n";
    exit(1);
}

echo "✅ API Response received with " . count($data['matches']) . " total matches\n\n";

// Get today's date in IST
$today = date('Y-m-d', strtotime('now'));
echo "📅 Looking for matches on: $today (IST)\n\n";

$addedCount = 0;
$skippedCount = 0;

foreach ($data['matches'] as $match) {
    // Parse match date and convert to IST
    $matchDateTime = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
    $matchDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $matchDate = $matchDateTime->format('Y-m-d');
    $matchTime = $matchDateTime->format('H:i');
    
    // Only process today's matches
    if ($matchDate !== $today) {
        continue;
    }
    
    echo "Match: {$match['homeTeam']['name']} vs {$match['awayTeam']['name']}\n";
    echo "  Time (IST): $matchDate at $matchTime\n";
    echo "  Status: {$match['status']}\n";
    
    // Check if match already exists
    $existing = $db->single(
        'SELECT id FROM matches WHERE api_match_id = ?',
        [$match['id']]
    );
    
    if ($existing) {
        echo "  ⏭️  Already exists in database\n\n";
        $skippedCount++;
        continue;
    }
    
    // Get or create home team
    $homeTeamData = $match['homeTeam'];
    $homeTeam = $db->single(
        'SELECT id FROM teams WHERE api_team_id = ?',
        [$homeTeamData['id']]
    );
    
    if (!$homeTeam) {
        $db->insert('teams', [
            'api_team_id' => $homeTeamData['id'],
            'name' => $homeTeamData['name'],
            'short_name' => substr($homeTeamData['name'], 0, 3),
            'country' => $homeTeamData['name'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $homeTeamId = $db->lastInsertId();
        echo "  ➕ Created home team: {$homeTeamData['name']}\n";
    } else {
        $homeTeamId = $homeTeam['id'];
    }
    
    // Get or create away team
    $awayTeamData = $match['awayTeam'];
    $awayTeam = $db->single(
        'SELECT id FROM teams WHERE api_team_id = ?',
        [$awayTeamData['id']]
    );
    
    if (!$awayTeam) {
        $db->insert('teams', [
            'api_team_id' => $awayTeamData['id'],
            'name' => $awayTeamData['name'],
            'short_name' => substr($awayTeamData['name'], 0, 3),
            'country' => $awayTeamData['name'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $awayTeamId = $db->lastInsertId();
        echo "  ➕ Created away team: {$awayTeamData['name']}\n";
    } else {
        $awayTeamId = $awayTeam['id'];
    }
    
    // Add match to database
    $db->insert('matches', [
        'api_match_id' => $match['id'],
        'home_team_id' => $homeTeamId,
        'away_team_id' => $awayTeamId,
        'match_date' => $match['utcDate'],
        'stadium' => $match['venue'] ?? 'Unknown Stadium',
        'stage' => str_replace('_', ' ', $match['stage'] ?? 'Group Stage'),
        'status' => strtolower($match['status']) === 'scheduled' ? 'scheduled' : $match['status'],
        'is_locked' => 0,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    echo "  ✅ Match added to database\n\n";
    $addedCount++;
}

echo "\n=== Summary ===\n";
echo "✅ Added: $addedCount matches\n";
echo "⏭️  Skipped: $skippedCount matches (already exist)\n";

if ($addedCount > 0) {
    echo "\n✅ Today's matches have been loaded successfully!\n";
    echo "Go to: " . BASE_URL . "/daily-matches to see them.\n";
} else {
    echo "\n⚠️  No new matches were added.\n";
    echo "Check if there are any matches scheduled for today.\n";
}

exit(0);
?>
