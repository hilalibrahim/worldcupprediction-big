<?php
/**
 * PredictCup Team Model
 */

class Team {
    private $db;
    private $table = 'teams';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllTeams($isActive = null) {
        $where = '';
        $params = [];
        
        if ($isActive !== null) {
            $where = 'WHERE is_active = ?';
            $params = [$isActive];
        }
        
        return $this->db->resultSet("SELECT * FROM {$this->table} {$where} ORDER BY name", $params);
    }
    
    public function getTeamById($id) {
        return $this->db->single("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }
    
    public function addTeam($data) {
        $teamData = [
            'name' => $data['name'],
            'short_name' => $data['short_name'] ?? substr(strtoupper($data['name']), 0, 3),
            'country' => $data['country'],
            'logo' => $data['logo'] ?? null,
            'group_letter' => $data['group_letter'] ?? null,
            'is_active' => $data['is_active'] ?? 1
        ];
        
        if ($this->db->insert($this->table, $teamData)) {
            return ['success' => true, 'id' => $this->db->lastInsertId()];
        }
        
        return ['success' => false, 'message' => 'Failed to add team'];
    }
    
    public function updateTeam($id, $data) {
        $teamData = [
            'name' => $data['name'],
            'short_name' => $data['short_name'] ?? substr(strtoupper($data['name']), 0, 3),
            'country' => $data['country'],
            'is_active' => $data['is_active'] ?? 1
        ];
        
        if ($data['logo']) {
            $teamData['logo'] = $data['logo'];
        }
        
        if ($data['group_letter']) {
            $teamData['group_letter'] = $data['group_letter'];
        }
        
        if ($this->db->update($this->table, $teamData, 'id = ' . (int)$id)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to update team'];
    }
    
    public function deleteTeam($id) {
        $matchCount = $this->db->single(
            'SELECT COUNT(*) as count FROM matches WHERE home_team_id = ? OR away_team_id = ?', 
            [$id, $id]
        );
        
        if ($matchCount['count'] > 0) {
            return ['success' => false, 'message' => 'Cannot delete team. It is used in matches.'];
        }
        
        if ($this->db->delete($this->table, 'id = ' . (int)$id)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to delete team'];
    }
    
    public function getTeamsByGroup($group) {
        return $this->db->resultSet(
            "SELECT * FROM {$this->table} WHERE group_letter = ? ORDER BY name", 
            [$group]
        );
    }
}
