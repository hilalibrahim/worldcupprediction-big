<?php
$projectDir = __DIR__;
require_once $projectDir . '/config/config.php';
require_once $projectDir . '/config/database.php';
require_once $projectDir . '/app/models/Team.php';
require_once $projectDir . '/app/models/Match.php';

$db = Database::getInstance();
$teamModel = new Team();
$matchModel = new MatchModel();

echo "Clearing old predictions and matches...\n";
// Disable foreign key checks temporarily to truncate
$db->query("SET FOREIGN_KEY_CHECKS = 0");
$db->query("TRUNCATE TABLE predictions");
$db->query("TRUNCATE TABLE matches");
$db->query("SET FOREIGN_KEY_CHECKS = 1");

// Helper to get or create team
function getOrCreateTeam($name, $shortName, $country) {
    global $db, $teamModel;
    $team = $db->single("SELECT id FROM teams WHERE name = ?", [$name]);
    if ($team) return $team['id'];
    
    $teamModel->addTeam([
        'name' => $name,
        'country' => $country,
        'short_name' => $shortName,
        'group_letter' => 'S'
    ]);
    return $db->lastInsertId();
}

$franceId = getOrCreateTeam('France', 'FRA', 'France');
$spainId = getOrCreateTeam('Spain', 'ESP', 'Spain');
$englandId = getOrCreateTeam('England', 'ENG', 'England');
$argentinaId = getOrCreateTeam('Argentina', 'ARG', 'Argentina');
$tbdId = getOrCreateTeam('TBD', 'TBD', 'Unknown');

// Match 1: France vs Spain
$matchModel->addMatch([
    'home_team_id' => $franceId,
    'away_team_id' => $spainId,
    'match_date' => '2026-07-14 19:00:00', // UTC
    'stadium' => 'MetLife Stadium',
    'stage' => 'Semi-Finals'
]);
echo "Added Semi-Final 1: France vs Spain\n";

// Match 2: England vs Argentina
$matchModel->addMatch([
    'home_team_id' => $englandId,
    'away_team_id' => $argentinaId,
    'match_date' => '2026-07-15 19:00:00', // UTC
    'stadium' => 'MetLife Stadium',
    'stage' => 'Semi-Finals'
]);
echo "Added Semi-Final 2: England vs Argentina\n";

// Match 3: Final (TBD vs TBD)
$matchModel->addMatch([
    'home_team_id' => $tbdId,
    'away_team_id' => $tbdId,
    'match_date' => '2026-07-19 19:00:00', // UTC
    'stadium' => 'MetLife Stadium',
    'stage' => 'Final'
]);
echo "Added Final: TBD vs TBD\n";

echo "Seeding completed successfully!\n";
