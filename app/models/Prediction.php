<?php
/**
 * PredictCup Prediction Model
 */

class Prediction {
    private $db;
    private $table = 'predictions';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function addPrediction($data) {
        $userId = (int)$data['user_id'];
        $matchId = (int)$data['match_id'];
        $predictionType = $data['prediction_type'] ?? 'both';
        $homeScore = (int)($data['home_score'] ?? 0);
        $awayScore = (int)($data['away_score'] ?? 0);
        $predictedWinner = $data['predicted_winner'] ?? 'draw';
        
        // Check if prediction already exists
        $existing = $this->db->single(
            'SELECT * FROM predictions WHERE user_id = ? AND match_id = ?', 
            [$userId, $matchId]
        );
        
        if ($existing) {
            return ['success' => false, 'message' => 'Prediction already exists'];
        }
        
        // Check if match is locked
        $match = $this->db->single('SELECT * FROM matches WHERE id = ?', [$matchId]);
        
        if (!$match) {
            return ['success' => false, 'message' => 'Match not found'];
        }
        
        // Check if match is locked (5 minutes before match start or already started)
        if (isMatchLocked($match['match_date'])) {
            return ['success' => false, 'message' => 'Predictions are locked for this match. Predictions close 5 minutes before match start.'];
        }
        
        if ($match['is_locked'] || $match['status'] === 'completed') {
            return ['success' => false, 'message' => 'Predictions are locked for this match'];
        }
        
        // Use direct SQL insert with both score and winner
        $predictedWinnerVal = !empty($predictedWinner) ? "'" . $predictedWinner . "'" : "'draw'";
        
        $sql = "INSERT INTO predictions (user_id, match_id, home_score, away_score, points, created_at, prediction_type, predicted_winner) 
                VALUES ($userId, $matchId, $homeScore, $awayScore, 0, NOW(), 'both', $predictedWinnerVal)";
        
        try {
            $this->db->query($sql);
            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (Exception $e) {
            // If prediction_type column doesn't exist, try basic insert
            if (strpos($e->getMessage(), 'prediction_type') !== false) {
                $sql = "INSERT INTO predictions (user_id, match_id, home_score, away_score, points, created_at) 
                        VALUES ($userId, $matchId, $homeScore, $awayScore, 0, NOW())";
                try {
                    $this->db->query($sql);
                    return ['success' => true, 'id' => $this->db->lastInsertId()];
                } catch (Exception $e2) {
                    return ['success' => false, 'message' => 'Failed to add prediction: ' . $e2->getMessage()];
                }
            }
            return ['success' => false, 'message' => 'Failed to add prediction: ' . $e->getMessage()];
        }
    }
    
    public function updatePrediction($data) {
        $userId = (int)$data['user_id'];
        $matchId = (int)$data['match_id'];
        $homeScore = (int)$data['home_score'];
        $awayScore = (int)$data['away_score'];
        $predictionId = (int)$data['prediction_id'];
        
        // Check if prediction exists
        $prediction = $this->db->single(
            'SELECT * FROM predictions WHERE id = ? AND user_id = ?', 
            [$predictionId, $userId]
        );
        
        if (!$prediction) {
            return ['success' => false, 'message' => 'Prediction not found'];
        }
        
        // Check if match is locked
        $match = $this->db->single('SELECT * FROM matches WHERE id = ?', [$matchId]);
        
        if ($match['is_locked'] || $match['status'] === 'completed') {
            return ['success' => false, 'message' => 'Predictions are locked for this match'];
        }
        
        $result = $this->db->update($this->table, [
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ' . $predictionId);
        
        if ($result) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to update prediction'];
    }
    
    public function deletePrediction($id) {
        $userId = getCurrentUserId();
        
        $prediction = $this->db->single(
            'SELECT * FROM predictions WHERE id = ? AND user_id = ?', 
            [(int)$id, $userId]
        );
        
        if (!$prediction) {
            return ['success' => false, 'message' => 'Prediction not found'];
        }
        
        $match = $this->db->single('SELECT * FROM matches WHERE id = ?', [(int)$prediction['match_id']]);
        
        if ($match['is_locked'] || $match['status'] === 'completed') {
            return ['success' => false, 'message' => 'Cannot delete prediction - match is locked'];
        }
        
        if ($this->db->delete($this->table, 'id = ' . (int)$id)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to delete prediction'];
    }
    
    public function getPrediction($id) {
        return $this->db->single(
            'SELECT p.*, m.match_date, m.home_team_id, m.away_team_id,
                    h.name as home_team_name, a.name as away_team_name
             FROM predictions p
             JOIN matches m ON p.match_id = m.id
             JOIN teams h ON m.home_team_id = h.id
             JOIN teams a ON m.away_team_id = a.id
             WHERE p.id = ?', 
            [(int)$id]
        );
    }
    
    public function getMatchPrediction($matchId, $userId) {
        return $this->db->single(
            'SELECT * FROM predictions WHERE match_id = ? AND user_id = ?', 
            [(int)$matchId, (int)$userId]
        );
    }
    
    public function getMatchPredictions($matchId, $limit = null) {
        $sql = "
            SELECT p.*, u.username, u.country
            FROM predictions p
            JOIN users u ON p.user_id = u.id
            WHERE p.match_id = ? AND u.is_admin = 0
            ORDER BY p.points DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->resultSet($sql, [(int)$matchId]);
    }
    
    public function getMatchPredictionsPaginated($matchId, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "
            SELECT p.*, u.username, u.country
            FROM predictions p
            JOIN users u ON p.user_id = u.id
            WHERE p.match_id = ? AND u.is_admin = 0
            ORDER BY p.points DESC, p.created_at ASC
            LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
            
        $predictions = $this->db->resultSet($sql, [(int)$matchId]);
        
        $countResult = $this->db->single("SELECT COUNT(p.id) as total FROM predictions p JOIN users u ON p.user_id = u.id WHERE p.match_id = ? AND u.is_admin = 0", [(int)$matchId]);
        $total = $countResult['total'] ?? 0;
        
        return [
            'predictions' => $predictions,
            'total' => $total,
            'total_pages' => ceil($total / $limit)
        ];
    }
    
    public function getUserPredictions($userId) {
        return $this->db->resultSet("
            SELECT p.*, m.match_date, m.status,
                   m.home_team_id, m.away_team_id,
                   h.name as home_team_name, h.logo as home_logo,
                   a.name as away_team_name, a.logo as away_logo
            FROM predictions p
            JOIN matches m ON p.match_id = m.id
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE p.user_id = ?
            ORDER BY m.match_date DESC
        ", [(int)$userId]);
    }
    
    public function getUserCorrectPredictions($userId) {
        return $this->db->single("
            SELECT COUNT(*) as count, COALESCE(SUM(points), 0) as total_points
            FROM predictions 
            WHERE user_id = ? AND points > 0
        ", [(int)$userId]);
    }
    

    public function getMatchLeaderboard($matchId) {
        return $this->db->resultSet("
            SELECT p.*, u.username, u.country, u.points as user_points
            FROM predictions p
            JOIN users u ON p.user_id = u.id
            WHERE p.match_id = ? AND u.is_admin = 0
            ORDER BY p.points DESC, p.created_at ASC
        ", [(int)$matchId]);
    }
    
    public function getAllPredictionsPaginated($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "
            SELECT p.*, u.username, u.country,
                   m.match_date, m.status as match_status, m.home_score as act_home, m.away_score as act_away,
                   h.name as home_team_name, a.name as away_team_name
            FROM predictions p
            JOIN users u ON p.user_id = u.id
            JOIN matches m ON p.match_id = m.id
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            ORDER BY p.created_at DESC
            LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
            
        $predictions = $this->db->resultSet($sql);
        
        $countResult = $this->db->single("SELECT COUNT(*) as total FROM predictions");
        $total = $countResult['total'] ?? 0;
        
        return [
            'predictions' => $predictions,
            'total' => $total,
            'total_pages' => ceil($total / $limit)
        ];
    }
}
