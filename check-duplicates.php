<?php
/**
 * Check for duplicate matches in database
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  Duplicate Matches Diagnostic                                  ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$db = Database::getInstance();

// Check total matches
$total = $db->single('SELECT COUNT(*) as count FROM matches');
echo "📊 Total matches in database: {$total['count']}\n\n";

// Check today's matches
$today = date('Y-m-d');
$todayCount = $db->single(
    'SELECT COUNT(*) as count FROM matches WHERE DATE(match_date) = ?', 
    [$today]
);
echo "📅 Matches scheduled for today (" . $today . "): {$todayCount['count']}\n\n";

// Check for exact duplicates (same home and away teams, same date)
$duplicates = $db->resultSet("
    SELECT home_team_id, away_team_id, DATE(match_date) as match_date, COUNT(*) as count
    FROM matches
    GROUP BY home_team_id, away_team_id, DATE(match_date)
    HAVING count > 1
");

if (!empty($duplicates)) {
    echo "⚠️  DUPLICATE MATCHES FOUND:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($duplicates as $dup) {
        $home = $db->single('SELECT name FROM teams WHERE id = ?', [$dup['home_team_id']]);
        $away = $db->single('SELECT name FROM teams WHERE id = ?', [$dup['away_team_id']]);
        echo "  {$home['name']} vs {$away['name']} on {$dup['match_date']}: {$dup['count']} times\n";
    }
    echo "\n❌ ACTION NEEDED: Run cleanup script below\n\n";
} else {
    echo "✅ No duplicate matches found\n\n";
}

// List today's matches
$todayMatches = $db->resultSet("
    SELECT m.id, m.api_match_id, h.name as home_team, a.name as away_team, m.match_date
    FROM matches m
    JOIN teams h ON m.home_team_id = h.id
    JOIN teams a ON m.away_team_id = a.id
    WHERE DATE(m.match_date) = ?
    ORDER BY m.match_date ASC
", [$today]);

if (!empty($todayMatches)) {
    echo "📋 Today's matches:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($todayMatches as $idx => $match) {
        echo "  " . ($idx + 1) . ". {$match['home_team']} vs {$match['away_team']}\n";
        echo "     Time: {$match['match_date']}\n";
        echo "     DB ID: {$match['id']}, API ID: {$match['api_match_id']}\n";
    }
} else {
    echo "📋 No matches scheduled for today\n";
}

echo "\n";

// Cleanup command if needed
if (!empty($duplicates)) {
    echo "🔧 CLEANUP INSTRUCTIONS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Run this SQL to remove duplicates (keep only latest):\n\n";
    echo "DELETE m1 FROM matches m1\n";
    echo "INNER JOIN (\n";
    echo "  SELECT home_team_id, away_team_id, DATE(match_date) as match_date, MAX(id) as max_id\n";
    echo "  FROM matches\n";
    echo "  GROUP BY home_team_id, away_team_id, DATE(match_date)\n";
    echo "  HAVING COUNT(*) > 1\n";
    echo ") m2\n";
    echo "ON m1.home_team_id = m2.home_team_id\n";
    echo "AND m1.away_team_id = m2.away_team_id\n";
    echo "AND DATE(m1.match_date) = m2.match_date\n";
    echo "WHERE m1.id < m2.max_id;\n\n";
}

?>
