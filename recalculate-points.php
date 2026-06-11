<?php
/**
 * One-time fix: recalculate every user's total points from their predictions.
 * Run this once after the points-update bug fix to correct existing user totals.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance();

echo "Recalculating user points from predictions...\n\n";

$users = $db->resultSet('SELECT id, username FROM users');

if (!$users) {
    echo "No users found.\n";
    exit;
}

foreach ($users as $user) {
    $row = $db->single(
        'SELECT COALESCE(SUM(points), 0) AS total FROM predictions WHERE user_id = ?',
        [(int)$user['id']]
    );
    $total = (int)($row['total'] ?? 0);

    $db->update('users', ['points' => $total], 'id = ' . (int)$user['id']);

    echo str_pad($user['username'], 25) . " => " . $total . " pts\n";
}

echo "\n✅ Done. All user totals recalculated.\n";
