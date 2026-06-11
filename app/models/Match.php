<?php
/**
 * PredictCup Match Model
 */

class Match {
    private $db;
    private $table = 'matches';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllMatches($status = null, $limit = null) {
        $where = '1=1';
        $params = [];

        if ($status) {
            $where = 'status = ?';
            $params[] = $status;
        }

        $sql = "
            SELECT m.*, 
                   h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo,
                   a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo
            FROM {$this->table} m
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE {$where}
            ORDER BY m.match_date ASC
        ";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->db->resultSet($sql, $params);
    }

    public function getTodayMatches() {
        $today = date('Y-m-d');
        return $this->db->resultSet("
            SELECT m.*, 
                   h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo,
                   a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo
            FROM {$this->table} m
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE DATE(m.match_date) = ?
            ORDER BY m.match_date ASC
        ", [$today]);
    }

    public function getUpcomingMatches($limit = 10) {
        return $this->db->resultSet("
            SELECT m.*, 
                   h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo,
                   a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo
            FROM {$this->table} m
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE m.match_date > NOW()
            ORDER BY m.match_date ASC
            LIMIT ?", [$limit]);
    }

    public function getMatchById($id) {
        return $this->db->single("
            SELECT m.*, 
                   h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo,
                   a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo
            FROM {$this->table} m
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE m.id = ?", [$id]);
    }

    public function addMatch($data) {
        $matchData = [
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'match_date' => $data['match_date'],
            'stadium' => $data['stadium'] ?? '',
            'stage' => $data['stage'] ?? 'Group Stage',
            'status' => 'scheduled'
        ];

        if ($this->db->insert($this->table, $matchData)) {
            return ['success' => true, 'id' => $this->db->lastInsertId()];
        }

        return ['success' => false, 'message' => 'Failed to add match'];
    }

    public function updateMatch($id, $data) {
        $matchData = [
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'match_date' => $data['match_date'],
            'stadium' => $data['stadium'] ?? '',
            'stage' => $data['stage'] ?? 'Group Stage'
        ];

        if ($this->db->update($this->table, $matchData, 'id = ' . (int)$id)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to update match'];
    }

    public function deleteMatch($id) {
        $predictionCount = $this->db->single(
            'SELECT COUNT(*) as count FROM predictions WHERE match_id = ?', 
            [(int)$id]
        );

        if ($predictionCount['count'] > 0) {
            return ['success' => false, 'message' => 'Cannot delete match. Predictions already exist.'];
        }

        if ($this->db->delete($this->table, 'id = ' . (int)$id)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to delete match'];
    }

    public function enterResults($id, $homeScore, $awayScore) {
        $match = $this->getMatchById($id);

        if (!$match) {
            return ['success' => false, 'message' => 'Match not found'];
        }

        if ($match['status'] === 'completed') {
            return ['success' => false, 'message' => 'Match results already entered'];
        }

        $result = $this->db->update($this->table, [
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'status' => 'completed',
            'is_locked' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ' . (int)$id);

        if (!$result) {
            return ['success' => false, 'message' => 'Failed to enter results'];
        }

        $this->calculatePoints($id);

        return ['success' => true];
    }

    public function calculatePoints($matchId) {
        $db = Database::getInstance();
        $match = $this->getMatchById($matchId);

        if (!$match || $match['status'] !== 'completed') {
            return false;
        }

        $predictions = $db->resultSet(
            'SELECT * FROM predictions WHERE match_id = ?', 
            [$matchId]
        );

        foreach ($predictions as $prediction) {
            $points = calculatePoints($prediction, $match);

            $db->update('predictions', [
                'points' => $points,
                'is_correct_winner' => getWinner($prediction['home_score'], $prediction['away_score']) === getWinner($match['home_score'], $match['away_score']) ? 1 : 0,
                'is_correct_diff' => ($prediction['home_score'] - $prediction['away_score']) === ($match['home_score'] - $match['away_score']) ? 1 : 0,
                'is_exact_score' => ($prediction['home_score'] === $match['home_score'] && $prediction['away_score'] === $match['away_score']) ? 1 : 0
            ], 'id = ' . (int)$prediction['id']);

            $db->update('users', ['points = points + ' . $points], 'id = ' . (int)$prediction['user_id']);
        }

        $this->updateRoomLeaderboards($matchId);

        return true;
    }

    public function updateRoomLeaderboards($matchId) {
        $db = Database::getInstance();
        $predictions = $db->resultSet(
            'SELECT DISTINCT user_id FROM predictions WHERE match_id = ?', 
            [$matchId]
        );

        foreach ($predictions as $prediction) {
            $this->recalculateUserPoints($prediction['user_id']);
        }
    }

    public function recalculateUserPoints($userId) {
        $db = Database::getInstance();
        $totalPoints = $db->single(
            'SELECT COALESCE(SUM(points), 0) as total FROM predictions WHERE user_id = ?', 
            [(int)$userId]
        );

        $db->update('users', ['points' => $totalPoints['total']], 'id = ' . (int)$userId);
    }

    public function checkUserPrediction($userId, $matchId) {
        return $this->db->single(
            'SELECT * FROM predictions WHERE user_id = ? AND match_id = ?', 
            [(int)$userId, (int)$matchId]
        );
    }

    public function getUserPredictions($userId, $limit = null) {
        $sql = "
            SELECT p.*, m.match_date, m.home_team_id, m.away_team_id,
                   h.name as home_team_name, a.name as away_team_name
            FROM predictions p
            JOIN matches m ON p.match_id = m.id
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE p.user_id = ?
            ORDER BY m.match_date DESC
        ";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->db->resultSet($sql, [(int)$userId]);
    }

    public function lockMatch($id) {
        return $this->db->update($this->table, ['is_locked' => 1], 'id = ' . (int)$id);
    }

    public function unlockMatch($id) {
        return $this->db->update($this->table, ['is_locked' => 0], 'id = ' . (int)$id);
    }
}
