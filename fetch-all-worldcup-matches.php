<?php
/**
 * Fetch ALL World Cup Matches from Football-Data.org API
 * This script fetches all World Cup matches and adds them to the database
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Match.php';
require_once __DIR__ . '/app/models/Team.php';
require_once __DIR__ . '/app/helpers/helpers.php';

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     Fetching ALL World Cup Matches from Football-Data.org   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Check if API key is configured
if (empty(FOOTBALL_DATA_API_KEY)) {
    echo "❌ ERROR: API key not configured in config.php\n";
    echo "Get a free API key from: https://www.football-data.org/client/register\n";
    exit(1);
}

$db = Database::getInstance();

// Fetch all matches from API
$apiUrl = FOOTBALL_DATA_API_URL . 'competitions/' . FOOTBALL_DATA_COMPETITION . '/matches';

echo "📡 Fetching from API: $apiUrl\n";
echo "🔑 Using API Key: " . substr(FOOTBALL_DATA_API_KEY, 0, 10) . "...\n\n";

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
$curlError = curl_error($ch);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ ERROR: API returned HTTP $httpCode\n";
    if ($curlError) {
        echo "cURL Error: $curlError\n";
    }
    echo "Response: " . substr($response, 0, 500) . "\n";
    exit(1);
}

$data = json_decode($response, true);

if (!isset($data['matches']) || empty($data['matches'])) {
    echo "❌ No matches found in API response\n";
    echo "Response keys: " . implode(', ', array_keys($data)) . "\n";
    exit(1);
}

echo "✅ API Response received\n";
echo "📊 Total matches in API: " . count($data['matches']) . "\n";
echo "🏆 Competition: " . ($data['competition']['name'] ?? 'Unknown') . "\n";
echo "📅 Season: " . ($data['season']['currentMatchDay'] ?? 'N/A') . " matchdays\n\n";

$addedCount = 0;
$skippedCount = 0;
$errorCount = 0;
$matchesByDate = [];

// Group matches by date for display
foreach ($data['matches'] as $match) {
    $matchDateTime = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
    $matchDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $matchDate = $matchDateTime->format('Y-m-d');
    
    if (!isset($matchesByDate[$matchDate])) {
        $matchesByDate[$matchDate] = [];
    }
    $matchesByDate[$matchDate][] = $match;
}

ksort($matchesByDate);

// Process all matches
foreach ($matchesByDate as $date => $matches) {
    echo "📅 " . date('F d, Y', strtotime($date)) . " (" . count($matches) . " matches)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    foreach ($matches as $match) {
        try {
            $matchDateTime = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
            $matchDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $matchTime = $matchDateTime->format('H:i');
            
            echo "  {$match['homeTeam']['name']} vs {$match['awayTeam']['name']} at $matchTime IST";
            
            // Check if match already exists
            $existing = $db->single(
                'SELECT id FROM matches WHERE api_match_id = ?',
                [$match['id']]
            );
            
            if ($existing) {
                echo " ⏭️  (Already exists)\n";
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
            
            echo " ✅\n";
            $addedCount++;
        } catch (Exception $e) {
            echo " ❌ Error: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    echo "\n";
}

// Get final counts
$totalMatches = $db->single('SELECT COUNT(*) as count FROM matches');
$totalTeams = $db->single('SELECT COUNT(*) as count FROM teams');

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    SUMMARY                                  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "✅ Added:    $addedCount matches\n";
echo "⏭️  Skipped:  $skippedCount matches (already exist)\n";
echo "❌ Errors:   $errorCount matches\n\n";

echo "📊 Database Statistics:\n";
echo "   • Total Matches: {$totalMatches['count']}\n";
echo "   • Total Teams: {$totalTeams['count']}\n\n";

if ($addedCount > 0) {
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ SUCCESS! All World Cup matches loaded successfully!      ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n";
    echo "📌 Next Steps:\n";
    echo "   1. Go to: " . BASE_URL . "/daily-matches\n";
    echo "   2. Make predictions for today's matches\n";
    echo "   3. View leaderboard: " . BASE_URL . "/leaderboard\n";
    echo "   4. Create rooms: " . BASE_URL . "/rooms\n\n";
} else if ($skippedCount > 0) {
    echo "⚠️  All matches are already in the database!\n";
    echo "Go to: " . BASE_URL . "/daily-matches to see them.\n\n";
} else {
    echo "⚠️  No matches were added.\n";
}

echo "ℹ️  Matches are displayed in IST (Indian Standard Time)\n";
echo "ℹ️  Predictions close 5 minutes before each match starts\n\n";

exit(0);
?>
