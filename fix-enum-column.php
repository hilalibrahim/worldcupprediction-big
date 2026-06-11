<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = Database::getInstance();

echo "Fixing prediction_type ENUM column...\n\n";

// First check current state
$info = $db->resultSet("DESCRIBE predictions WHERE Field = 'prediction_type'");
echo "Current column definition:\n";
echo "Type: " . ($info[0]['Type'] ?? 'unknown') . "\n\n";

// Modify the column to include 'both' as an option
$conn = $db->getConnection();
$stmt = $conn->prepare("ALTER TABLE predictions MODIFY COLUMN prediction_type ENUM('winner', 'score', 'both') DEFAULT 'both'");
$result = $stmt->execute();

echo "ALTER TABLE result: " . ($result ? 'Success' : 'Failed') . "\n\n";

// Now update all predictions
$stmt2 = $conn->prepare("UPDATE predictions SET prediction_type = 'both' WHERE prediction_type = '' OR prediction_type IS NULL OR prediction_type = 'score'");
$result2 = $stmt2->execute();
echo "UPDATE result: " . ($result2 ? 'Success' : 'Failed') . "\n";
echo "Rows affected: " . $stmt2->rowCount() . "\n\n";

// Verify
$check = $db->resultSet("SELECT DISTINCT prediction_type, COUNT(*) as cnt FROM predictions GROUP BY prediction_type");
echo "Predictions by type after fix:\n";
foreach ($check as $row) {
    echo "  Type '" . ($row['prediction_type'] ?? 'NULL') . "': " . $row['cnt'] . " predictions\n";
}

echo "\n✅ Done! Now recalculating points...\n";

// Recalculate points for all completed matches
require_once 'app/helpers/helpers.php';
require_once 'app/models/Match.php';

$matchModel = new MatchModel();
$matches = $db->resultSet("SELECT id FROM matches WHERE status = 'completed'");

$recalc_count = 0;
foreach ($matches as $match) {
    if ($matchModel->calculatePoints($match['id'])) {
        $recalc_count++;
    }
}

echo "Recalculated points for " . $recalc_count . " completed matches\n";
echo "\n✅ All fixed!\n";
?>
