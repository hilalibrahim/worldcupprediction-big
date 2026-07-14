<?php

class MatchModel {
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
        $sql = "SELECT m.*, h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo, a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo FROM {$this->table} m JOIN teams h ON m.home_team_id = h.id JOIN teams a ON m.away_team_id = a.id WHERE {$where} ORDER BY m.match_date ASC";
        if ($limit) $sql .= " LIMIT " . (int)$limit;
        return $this->db->resultSet($sql, $params);
    }

    public function getTodayMatches() {
        $today = date('Y-m-d');
        return $this->db->resultSet("SELECT m.*, h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo, a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo FROM {$this->table} m JOIN teams h ON m.home_team_id = h.id JOIN teams a ON m.away_team_id = a.id WHERE DATE(m.match_date) = ? ORDER BY m.match_date ASC", [$today]);
    }

    public function getUpcomingMatches($limit = 10) {
        return $this->db->resultSet("SELECT m.*, h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo, a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo FROM {$this->table} m JOIN teams h ON m.home_team_id = h.id JOIN teams a ON m.away_team_id = a.id WHERE m.match_date > NOW() ORDER BY m.match_date ASC LIMIT ?", [$limit]);
    }

    public function getMatchById($id) {
        return $this->db->single("SELECT m.*, h.name as home_team_name, h.short_name as home_short_name, h.logo as home_logo, a.name as away_team_name, a.short_name as away_short_name, a.logo as away_logo FROM {$this->table} m JOIN teams h ON m.home_team_id = h.id JOIN teams a ON m.away_team_id = a.id WHERE m.id = ?", [$id]);
    }

    public function addMatch($data) {
        $matchData = ['home_team_id' => $data['home_team_id'], 'away_team_id' => $data['away_team_id'], 'match_date' => $data['match_date'], 'stadium' => $data['stadium'] ?? '', 'stage' => $data['stage'] ?? 'Group Stage', 'status' => 'scheduled'];
        if ($this->db->insert($this->table, $matchData)) return ['success' => true, 'id' => $this->db->lastInsertId()];
        return ['success' => false, 'message' => 'Failed to add match'];
    }

    public function updateMatch($id, $data) {
        $matchData = ['home_team_id' => $data['home_team_id'], 'away_team_id' => $data['away_team_id'], 'match_date' => $data['match_date'], 'stadium' => $data['stadium'] ?? '', 'stage' => $data['stage'] ?? 'Group Stage'];
        if ($this->db->update($this->table, $matchData, 'id = ' . (int)$id)) return ['success' => true];
        return ['success' => false, 'message' => 'Failed to update match'];
    }

    public function deleteMatch($id) {
        $predictionCount = $this->db->single('SELECT COUNT(*) as count FROM predictions WHERE match_id = ?', [(int)$id]);
        if ($predictionCount['count'] > 0) return ['success' => false, 'message' => 'Cannot delete match. Predictions already exist.'];
        if ($this->db->delete($this->table, 'id = ' . (int)$id)) return ['success' => true];
        return ['success' => false, 'message' => 'Failed to delete match'];
    }

    public function enterResults($id, $homeScore, $awayScore) {
        $match = $this->getMatchById($id);
        if (!$match) return ['success' => false, 'message' => 'Match not found'];
        $result = $this->db->update($this->table, ['home_score' => $homeScore, 'away_score' => $awayScore, 'status' => 'completed', 'is_locked' => 1, 'updated_at' => date('Y-m-d H:i:s')], 'id = ' . (int)$id);
        if (!$result) return ['success' => false, 'message' => 'Failed to enter results'];
        $this->calculatePoints($id);
        $this->checkAndCreateFinal();
        return ['success' => true];
    }

    public function calculatePoints($matchId) {
        $db = Database::getInstance();
        $match = $this->getMatchById($matchId);
        if (!$match || $match['status'] !== 'completed') return false;

        $actHome = (int)$match['home_score'];
        $actAway = (int)$match['away_score'];
        $actualWinner = getWinner($actHome, $actAway);

        // 1. Bulk update all predictions for this match (O(1) execution)
        $sql = "UPDATE predictions SET
            is_exact_score = IF(home_score = :actHome AND away_score = :actAway, 1, 0),
            is_correct_winner = IF(predicted_winner = :actualWinner, 1, 0),
            is_correct_diff = IF((home_score - away_score) = (:actHome - :actAway), 1, 0),
            points = IF(home_score = :actHome AND away_score = :actAway, :pointsExact, 0) + 
                     IF(predicted_winner = :actualWinner, :pointsWinner, 0)
            WHERE match_id = :matchId";
            
        $db->query($sql, [
            ':actHome' => $actHome,
            ':actAway' => $actAway,
            ':actualWinner' => $actualWinner,
            ':pointsExact' => POINTS_EXACT_SCORE,
            ':pointsWinner' => POINTS_CORRECT_WINNER,
            ':matchId' => $matchId
        ]);

        // 2. Bulk recalculate total points for all users who predicted this match
        $this->updateRoomLeaderboards($matchId);
        return true;
    }

    public function updateRoomLeaderboards($matchId) {
        $db = Database::getInstance();
        
        // Single bulk query to update all affected users (Avoids N+1 query problem)
        $sql = "UPDATE users u
                SET u.points = (
                    SELECT COALESCE(SUM(points), 0) 
                    FROM predictions 
                    WHERE user_id = u.id
                )
                WHERE u.id IN (SELECT DISTINCT user_id FROM predictions WHERE match_id = :matchId)";
                
        $db->query($sql, [':matchId' => $matchId]);
    }

    public function recalculateUserPoints($userId) {
        $db = Database::getInstance();
        $totalPoints = $db->single('SELECT COALESCE(SUM(points), 0) as total FROM predictions WHERE user_id = ?', [(int)$userId]);
        $db->update('users', ['points' => $totalPoints['total']], 'id = ' . (int)$userId);
    }

    public function checkUserPrediction($userId, $matchId) {
        return $this->db->single('SELECT * FROM predictions WHERE user_id = ? AND match_id = ?', [(int)$userId, (int)$matchId]);
    }

    public function getUserPredictions($userId, $limit = null) {
        $sql = "SELECT p.*, m.match_date, m.home_team_id, m.away_team_id, h.name as home_team_name, a.name as away_team_name FROM predictions p JOIN matches m ON p.match_id = m.id JOIN teams h ON m.home_team_id = h.id JOIN teams a ON m.away_team_id = a.id WHERE p.user_id = ? ORDER BY m.match_date DESC";
        if ($limit) $sql .= " LIMIT " . (int)$limit;
        return $this->db->resultSet($sql, [(int)$userId]);
    }

    public function lockMatch($id) {
        return $this->db->update($this->table, ['is_locked' => 1], 'id = ' . (int)$id);
    }

    public function unlockMatch($id) {
        return $this->db->update($this->table, ['is_locked' => 0], 'id = ' . (int)$id);
    }

    // Football-Data.org API Integration Methods
    
    /**
     * Fetch matches from Football-Data.org API
     */
    public function fetchMatchesFromAPI($daysFromNow = 7) {
        if (empty(FOOTBALL_DATA_API_KEY)) {
            return ['success' => false, 'message' => 'API key not configured'];
        }
        
        $apiUrl = FOOTBALL_DATA_API_URL . 'matches?competitions=' . FOOTBALL_DATA_COMPETITION;
        $headers = [
            'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'API request failed with code: ' . $httpCode];
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['matches'])) {
            return ['success' => false, 'message' => 'Invalid API response format'];
        }
        
        $matches = $data['matches'];
        $importedCount = 0;
        $updatedCount = 0;
        
        foreach ($matches as $matchData) {
            $result = $this->syncMatchFromAPI($matchData);
            if ($result['success']) {
                if ($result['action'] === 'created') {
                    $importedCount++;
                } else {
                    $updatedCount++;
                }
            }
        }
        
        return [
            'success' => true,
            'imported' => $importedCount,
            'updated' => $updatedCount,
            'total' => count($matches)
        ];
    }
    
    /**
     * Sync individual match from API data
     */
    public function syncMatchFromAPI($matchData) {
        // Extract match data from API response
        $apiMatchId = $matchData['id'];
        $homeTeam = $matchData['homeTeam'];
        $awayTeam = $matchData['awayTeam'];
        $score = $matchData['score'];
        
        // Check if match already exists by API ID
        $existingMatch = $this->db->single(
            "SELECT id FROM {$this->table} WHERE api_match_id = ?", 
            [$apiMatchId]
        );
        
        // Get or create teams
        $homeTeamId = $this->getOrCreateTeam($homeTeam);
        $awayTeamId = $this->getOrCreateTeam($awayTeam);
        
        // Determine match status
        $status = 'scheduled';
        if (isset($matchData['status'])) {
            switch ($matchData['status']) {
                case 'FINISHED':
                    $status = 'completed';
                    break;
                case 'IN_PLAY':
                case 'PAUSED':
                    $status = 'live';
                    break;
                case 'POSTPONED':
                case 'SUSPENDED':
                    $status = 'postponed';
                    break;
            }
        }
        
        // Prepare match data
        $matchRecord = [
            'api_match_id' => $apiMatchId,
            'home_team_id' => $homeTeamId,
            'away_team_id' => $awayTeamId,
            'match_date' => date('Y-m-d H:i:s', strtotime($matchData['utcDate'])),
            'stadium' => $matchData['venue'] ?? 'Unknown',
            'stage' => $matchData['stage'] ?? 'Group Stage',
            'status' => $status,
            'home_score' => $score['fullTime']['home'] ?? null,
            'away_score' => $score['fullTime']['away'] ?? null,
            'is_locked' => ($status === 'completed' || $status === 'live') ? 1 : 0,
            'last_api_sync' => date('Y-m-d H:i:s')
        ];
        
        if ($existingMatch) {
            // Update existing match
            $this->db->update($this->table, $matchRecord, 'id = ' . (int)$existingMatch['id']);
            return ['success' => true, 'action' => 'updated', 'match_id' => $existingMatch['id']];
        } else {
            // Create new match
            $this->db->insert($this->table, $matchRecord);
            $matchId = $this->db->lastInsertId();
            
            // If match is completed, calculate points
            if ($status === 'completed' && isset($score['fullTime'])) {
                $this->calculatePoints($matchId);
            }
            
            return ['success' => true, 'action' => 'created', 'match_id' => $matchId];
        }
    }
    
    /**
     * Get team by API ID or create if not exists
     */
    private function getOrCreateTeam($teamData) {
        $apiTeamId = $teamData['id'];
        
        // Check if team exists by API ID
        $existingTeam = $this->db->single(
            "SELECT id FROM teams WHERE api_team_id = ?", 
            [$apiTeamId]
        );
        
        if ($existingTeam) {
            return $existingTeam['id'];
        }
        
        // Create new team
        $teamRecord = [
            'api_team_id' => $apiTeamId,
            'name' => $teamData['name'],
            'short_name' => $teamData['shortName'] ?? substr($teamData['name'], 0, 3),
            'country' => $teamData['area']['name'] ?? 'Unknown',
            'logo' => $teamData['crest'] ?? null,
            'is_active' => 1
        ];
        
        $this->db->insert('teams', $teamRecord);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update database schema for API integration
     */
    public function updateSchemaForAPI() {
        $queries = [
            // Add api_match_id to matches table
            "ALTER TABLE matches ADD COLUMN IF NOT EXISTS api_match_id INT UNIQUE",
            "ALTER TABLE matches ADD COLUMN IF NOT EXISTS last_api_sync DATETIME",
            
            // Add api_team_id to teams table  
            "ALTER TABLE teams ADD COLUMN IF NOT EXISTS api_team_id INT UNIQUE",
            
            // Add indexes
            "CREATE INDEX IF NOT EXISTS idx_api_match_id ON matches(api_match_id)",
            "CREATE INDEX IF NOT EXISTS idx_api_team_id ON teams(api_team_id)"
        ];
        
        foreach ($queries as $query) {
            $this->db->query($query);
        }
        
        return ['success' => true, 'message' => 'Schema updated for API integration'];
    }
    
    /**
     * Auto-update matches from API (called via cron or manual trigger)
     */
    public function autoUpdateMatches() {
        // Check if we need to update (based on interval)
        $lastUpdate = $this->db->single(
            "SELECT MAX(last_api_sync) as last_sync FROM {$this->table}"
        );
        
        $shouldUpdate = true;
        if ($lastUpdate && $lastUpdate['last_sync']) {
            $lastUpdateTime = strtotime($lastUpdate['last_sync']);
            $currentTime = time();
            $shouldUpdate = ($currentTime - $lastUpdateTime) > FOOTBALL_DATA_UPDATE_INTERVAL;
        }
        
        if (!$shouldUpdate) {
            return ['success' => true, 'message' => 'Update not needed yet', 'skipped' => true];
        }
        
        return $this->fetchMatchesFromAPI();
    }

    /**
     * Check if both semi-finals are completed and auto-create the Final match if not exists
     */
    public function checkAndCreateFinal() {
        $semis = $this->db->resultSet("SELECT * FROM {$this->table} WHERE stage IN ('Semi-Final', 'Semi-Finals') AND status = 'completed'");
        
        // Ensure both semi-finals are completed
        if (count($semis) >= 2) {
            // Check if final already exists
            $finalMatch = $this->db->single("SELECT id FROM {$this->table} WHERE stage = 'Final'");
            if (!$finalMatch) {
                // Determine the winner of semi 1
                $semi1 = $semis[0];
                $winner1 = getWinner($semi1['home_score'], $semi1['away_score']);
                $team1Id = ($winner1 === 'home') ? $semi1['home_team_id'] : $semi1['away_team_id'];
                
                // Determine the winner of semi 2
                $semi2 = $semis[1];
                $winner2 = getWinner($semi2['home_score'], $semi2['away_score']);
                $team2Id = ($winner2 === 'home') ? $semi2['home_team_id'] : $semi2['away_team_id'];
                
                // For a draw in semi-finals, we just fallback to home team for demo simplicity since we don't have penalty tracking
                if ($winner1 === 'draw') $team1Id = $semi1['home_team_id'];
                if ($winner2 === 'draw') $team2Id = $semi2['home_team_id'];
                
                // Create Final
                $this->addMatch([
                    'home_team_id' => $team1Id,
                    'away_team_id' => $team2Id,
                    'match_date' => '2026-07-19 19:00:00', // UTC for Mon, 20 Jul, 12:30 am IST
                    'stadium' => 'MetLife Stadium',
                    'stage' => 'Final'
                ]);
            }
        }
    }
}
