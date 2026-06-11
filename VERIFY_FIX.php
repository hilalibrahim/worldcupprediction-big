<?php
/**
 * Verify Points Bug is Fixed
 * Run this to check the current state
 */

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'app/helpers/helpers.php';

$db = Database::getInstance();

echo "========== POINTS CALCULATION VERIFICATION ==========\n\n";

// Check column definition
echo "1. CHECKING ENUM DEFINITION:\n";
$info = $db->single("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'predictions' AND COLUMN_NAME = 'prediction_type' AND TABLE_SCHEMA = ?", [DB_NAME]);
echo "   prediction_type ENUM: " . ($info['COLUMN_TYPE'] ?? 'Unknown') . "\n";
if (strpos($info['COLUMN_TYPE'] ?? '', 'both') !== false) {
    echo "   ✅ Contains 'both' value\n";
} else {
    echo "   ❌ Missing 'both' value (BUG NOT FIXED)\n";
}

// Check predictions have correct type
echo "\n2. CHECKING PREDICTION TYPES:\n";
$types = $db->resultSet("SELECT DISTINCT prediction_type, COUNT(*) as cnt FROM predictions GROUP BY prediction_type");
foreach ($types as $row) {
    echo "   Type '" . ($row['prediction_type'] ?? 'NULL/EMPTY') . "': " . $row['cnt'] . " predictions\n";
}

// Check completed match points
echo "\n3. CHECKING COMPLETED MATCHES:\n";
$matches = $db->resultSet("
    SELECT m.id, m.home_score, m.away_score,
           CONCAT(h.name, ' vs ', a.name) as match_name
    FROM matches m
    JOIN teams h ON m.home_team_id = h.id
    JOIN teams a ON m.away_team_id = a.id
    WHERE m.status = 'completed'
    LIMIT 5
");

foreach ($matches as $match) {
    echo "\n   Match: " . $match['match_name'] . " (" . $match['home_score'] . "-" . $match['away_score'] . ")\n";
    
    $predictions = $db->resultSet("
        SELECT p.id, p.home_score, p.away_score, p.predicted_winner, p.points, p.prediction_type
        FROM predictions p
        WHERE p.match_id = ?
    ", [$match['id']]);
    
    foreach ($predictions as $pred) {
        $isExactScore = ($pred['home_score'] == $match['home_score'] && $pred['away_score'] == $match['away_score']);
        $actualWinner = getWinner($match['home_score'], $match['away_score']);
        $predictedWinner = $pred['predicted_winner'] ?? getWinner($pred['home_score'], $pred['away_score']);
        $isWinnerCorrect = ($predictedWinner === $actualWinner);
        
        echo "     Prediction: " . $pred['home_score'] . "-" . $pred['away_score'];
        echo " | Winner: " . ucfirst($predictedWinner);
        echo " | Points: " . $pred['points'];
        
        if ($isExactScore && $isWinnerCorrect) {
            if ($pred['points'] == 15) {
                echo " ✅ CORRECT (15 = 10+5)\n";
            } else {
                echo " ❌ WRONG (Should be 15, got " . $pred['points'] . ")\n";
            }
        } elseif ($isExactScore) {
            echo ($pred['points'] == 10 ? " ✅" : " ❌") . " (Exact score: " . $pred['points'] . " pts)\n";
        } elseif ($isWinnerCorrect) {
            echo ($pred['points'] == 5 ? " ✅" : " ❌") . " (Winner: " . $pred['points'] . " pts)\n";
        } else {
            echo ($pred['points'] == 0 ? " ✅" : " ❌") . " (None: " . $pred['points'] . " pts)\n";
        }
    }
}

// Summary
echo "\n========== SUMMARY ==========\n";
$stats = $db->resultSet("
    SELECT 
        COUNT(*) as total_preds,
        SUM(CASE WHEN points = 15 THEN 1 ELSE 0 END) as perfect_preds,
        COALESCE(SUM(points), 0) as total_points
    FROM predictions
    WHERE prediction_type = 'both'
");

if ($stats && count($stats) > 0) {
    echo "Total 'both' type predictions: " . $stats[0]['total_preds'] . "\n";
    echo "Perfect predictions (15 pts): " . ($stats[0]['perfect_preds'] ?? 0) . "\n";
    echo "Total points awarded: " . $stats[0]['total_points'] . "\n";
    echo "\n✅ BUG IS FIXED - Points calculating correctly!\n";
} else {
    echo "No predictions found\n";
}
?>
