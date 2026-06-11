<?php
/**
 * Automatically Fix Duplicate Matches
 * Removes duplicate matches from database, keeping only the latest
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  Duplicate Matches Fixer - AUTO FIX                            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$db = Database::getInstance();

// First, check for duplicates
$duplicates = $db->resultSet("
    SELECT home_team_id, away_team_id, DATE(match_date) as match_date, COUNT(*) as count
    FROM matches
    GROUP BY home_team_id, away_team_id, DATE(match_date)
    HAVING count > 1
");

if (empty($duplicates)) {
    echo "✅ No duplicates found! All is good.\n\n";
    exit(0);
}

echo "⚠️  Found " . count($duplicates) . " duplicate match(es):\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($duplicates as $dup) {
    $home = $db->single('SELECT name FROM teams WHERE id = ?', [$dup['home_team_id']]);
    $away = $db->single('SELECT name FROM teams WHERE id = ?', [$dup['away_team_id']]);
    echo "{$home['name']} vs {$away['name']} on {$dup['match_date']}: {$dup['count']} times\n";
}

echo "\n🔧 Running cleanup...\n\n";

// Run the cleanup query
try {
    $sql = "DELETE m1 FROM matches m1
            INNER JOIN (
              SELECT home_team_id, away_team_id, DATE(match_date) as match_date, MAX(id) as max_id
              FROM matches
              GROUP BY home_team_id, away_team_id, DATE(match_date)
              HAVING COUNT(*) > 1
            ) m2
            ON m1.home_team_id = m2.home_team_id
            AND m1.away_team_id = m2.away_team_id
            AND DATE(m1.match_date) = m2.match_date
            WHERE m1.id < m2.max_id";
    
    $db->query($sql);
    
    echo "✅ Cleanup completed!\n\n";
    
    // Verify
    $newDuplicates = $db->resultSet("
        SELECT COUNT(*) as count
        FROM (
            SELECT home_team_id, away_team_id, DATE(match_date)
            FROM matches
            GROUP BY home_team_id, away_team_id, DATE(match_date)
            HAVING COUNT(*) > 1
        ) as dupes
    ");
    
    if ($newDuplicates[0]['count'] == 0) {
        echo "✅ Verification: No duplicates remaining!\n\n";
        
        // Show final count
        $total = $db->single('SELECT COUNT(*) as count FROM matches');
        echo "📊 Total matches now: {$total['count']}\n";
        echo "   (Duplicates removed successfully)\n\n";
        
        echo "✨ All matches are now unique!\n";
        echo "   Refresh your page to see the fix.\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error during cleanup: " . $e->getMessage() . "\n\n";
}

?>
