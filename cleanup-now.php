<?php
/**
 * Instant Duplicate Cleanup - No Questions Asked
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "Removing duplicate matches...\n\n";

$db = Database::getInstance();

try {
    // Simple approach: Keep highest ID for each match combination
    $sql = "DELETE m FROM matches m
            WHERE EXISTS (
              SELECT 1 FROM matches m2 
              WHERE m.home_team_id = m2.home_team_id 
              AND m.away_team_id = m2.away_team_id 
              AND DATE(m.match_date) = DATE(m2.match_date)
              AND m.id < m2.id
            )";
    
    $db->query($sql);
    
    echo "✅ Cleanup complete!\n\n";
    
    // Verify
    $count = $db->single('SELECT COUNT(*) as total FROM matches');
    echo "Total matches now: " . $count['total'] . "\n";
    
    echo "\n✅ Duplicates removed. Go to daily-matches to verify!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
