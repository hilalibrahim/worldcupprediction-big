<?php
/**
 * Sync World Cup Data - Complete Data Sync Script
 * Fetches teams and all matches from Football-Data.org API
 * Stores UTC times in database, displays in IST
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║     World Cup Data Sync - Teams & Matches from Football-Data.org   ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

// Check API key
if (empty(FOOTBALL_DATA_API_KEY)) {
    echo "❌ ERROR: API key not configured in config/config.php\n";
    exit(1);
}

echo "🔑 API Key: " . substr(FOOTBALL_DATA_API_KEY, 0, 15) . "...\n";
echo "📍 Times stored: UTC | Times displayed: IST (+5:30)\n\n";

$db = Database::getInstance();
$teamsAdded = 0;
$matchesAdded = 0;
$skipped = 0;

// ========================================
// STEP 1: FETCH AND SYNC TEAMS
// ========================================

echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║ STEP 1: Fetching Teams                                             ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

$teamsUrl = "https://api.football-data.org/v4/competitions/WC/teams";
echo "📡 Fetching teams from: $teamsUrl\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $teamsUrl);
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
    echo "Response: " . substr($response, 0, 300) . "\n";
    exit(1);
}

$teamsData = json_decode($response, true);

if (!isset($teamsData['teams']) || empty($teamsData['teams'])) {
    echo "❌ No teams found in API response\n";
    exit(1);
}

echo "✅ Found " . count($teamsData['teams']) . " teams\n\n";

foreach ($teamsData['teams'] as $team) {
    try {
        // Check if team already exists
        $existing = $db->single(
            'SELECT id FROM teams WHERE api_team_id = ?',
            [$team['id']]
        );
        
        if ($existing) {
            echo "  ⏭️  {$team['name']} (already exists)\n";
            continue;
        }
        
        // Insert team
        $db->insert('teams', [
            'api_team_id' => $team['id'],
            'name' => $team['name'],
            'short_name' => substr($team['name'], 0, 3),
            'country' => $team['name'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        echo "  ✅ {$team['name']}\n";
        $teamsAdded++;
    } catch (Exception $e) {
        echo "  ❌ {$team['name']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Teams synced: $teamsAdded added\n\n";

// ========================================
// STEP 2: FETCH AND SYNC MATCHES
// ========================================

echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║ STEP 2: Fetching Matches                                           ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

$matchesUrl = "https://api.football-data.org/v4/competitions/WC/matches";
echo "📡 Fetching matches from: $matchesUrl\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $matchesUrl);
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
    echo "Response: " . substr($response, 0, 300) . "\n";
    exit(1);
}

$matchesData = json_decode($response, true);

if (!isset($matchesData['matches']) || empty($matchesData['matches'])) {
    echo "❌ No matches found in API response\n";
    exit(1);
}

echo "✅ Found " . count($matchesData['matches']) . " total matches\n\n";

// Group matches by date for display
$matchesByDate = [];
foreach ($matchesData['matches'] as $match) {
    $utcDate = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
    $dateKey = $utcDate->format('Y-m-d');
    
    if (!isset($matchesByDate[$dateKey])) {
        $matchesByDate[$dateKey] = [];
    }
    $matchesByDate[$dateKey][] = $match;
}

ksort($matchesByDate);

// Process matches
foreach ($matchesByDate as $date => $matches) {
    // Convert UTC date to IST for display
    $utcDateObj = new DateTime($date, new DateTimeZone('UTC'));
    $utcDateObj->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $istDate = $utcDateObj->format('F d, Y');
    
    echo "📅 $istDate (UTC: $date) - " . count($matches) . " matches\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    foreach ($matches as $match) {
        try {
            // Parse UTC time from API
            $utcDateTime = new DateTime($match['utcDate'], new DateTimeZone('UTC'));
            
            // Convert to IST for display
            $istDateTime = clone $utcDateTime;
            $istDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $istTime = $istDateTime->format('H:i');
            
            $homeTeam = $match['homeTeam']['name'];
            $awayTeam = $match['awayTeam']['name'];
            
            echo "  $homeTeam vs $awayTeam";
            echo " (" . $istTime . " IST)";
            
            // Check if match already exists
            $existing = $db->single(
                'SELECT id FROM matches WHERE api_match_id = ?',
                [$match['id']]
            );
            
            if ($existing) {
                echo " ⏭️ (exists)\n";
                $skipped++;
                continue;
            }
            
            // Get or create home team
            $homeTeamData = $match['homeTeam'];
            $homeTeamDb = $db->single(
                'SELECT id FROM teams WHERE api_team_id = ?',
                [$homeTeamData['id']]
            );
            
            if (!$homeTeamDb) {
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
                $homeTeamId = $homeTeamDb['id'];
            }
            
            // Get or create away team
            $awayTeamData = $match['awayTeam'];
            $awayTeamDb = $db->single(
                'SELECT id FROM teams WHERE api_team_id = ?',
                [$awayTeamData['id']]
            );
            
            if (!$awayTeamDb) {
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
                $awayTeamId = $awayTeamDb['id'];
            }
            
            // Insert match (store UTC time in database)
            $db->insert('matches', [
                'api_match_id' => $match['id'],
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'match_date' => $match['utcDate'],  // Store UTC time
                'stadium' => $match['venue'] ?? 'Unknown Stadium',
                'stage' => str_replace('_', ' ', $match['stage'] ?? 'Group Stage'),
                'status' => strtolower($match['status']) === 'scheduled' ? 'scheduled' : $match['status'],
                'is_locked' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            echo " ✅\n";
            $matchesAdded++;
        } catch (Exception $e) {
            echo " ❌ Error: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
}

// ========================================
// STEP 3: SUMMARY
// ========================================

$totalMatches = $db->single('SELECT COUNT(*) as count FROM matches');
$totalTeams = $db->single('SELECT COUNT(*) as count FROM teams');
$completedMatches = $db->single('SELECT COUNT(*) as count FROM matches WHERE status = "finished"');
$upcomingMatches = $db->single('SELECT COUNT(*) as count FROM matches WHERE status = "scheduled"');

echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║ SYNC COMPLETE                                                      ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

echo "📊 Matches Summary:\n";
echo "   • Added:       $matchesAdded\n";
echo "   • Skipped:     $skipped (already exist)\n";
echo "   • Total:       {$totalMatches['count']}\n";
echo "   • Completed:   {$completedMatches['count']}\n";
echo "   • Upcoming:    {$upcomingMatches['count']}\n\n";

echo "👥 Teams Summary:\n";
echo "   • Added:       $teamsAdded\n";
echo "   • Total:       {$totalTeams['count']}\n\n";

echo "📝 Time Information:\n";
echo "   • Database stores: UTC times (e.g., 2026-06-15T15:00:00Z)\n";
echo "   • Frontend shows: IST times (e.g., Jun 15, 2026 - 20:30 IST)\n";
echo "   • Conversion: UTC + 5:30 hours = IST\n";
echo "   • Prediction cutoff: 5 minutes before match (IST)\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "✅ Next Steps:\n";
echo "   1. View matches: " . BASE_URL . "/daily-matches\n";
echo "   2. Make predictions\n";
echo "   3. Check dashboard: " . BASE_URL . "/dashboard\n";
echo "   4. View leaderboard: " . BASE_URL . "/leaderboard\n\n";

echo "✨ All World Cup matches are now loaded with correct timings!\n";
echo "   Times stored as UTC, displayed as IST\n\n";

exit(0);
?>
