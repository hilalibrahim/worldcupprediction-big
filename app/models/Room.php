<?php
/**
 * PredictCup Room Model
 */

class Room {
    private $db;
    private $table = 'rooms';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllRooms($publicOnly = true) {
        $where = $publicOnly ? 'WHERE is_public = 1' : '';
        return $this->db->resultSet("
            SELECT r.*, u.username as owner_name, u.country as owner_country,
                   (SELECT COUNT(*) FROM room_members WHERE room_id = r.id) as member_count
            FROM {$this->table} r
            JOIN users u ON r.owner_id = u.id
            {$where}
            ORDER BY r.created_at DESC
        ");
    }
    
    public function getRoomById($id) {
        return $this->db->single("
            SELECT r.*, u.username as owner_name, u.country as owner_country
            FROM {$this->table} r
            JOIN users u ON r.owner_id = u.id
            WHERE r.id = ?", [$id]);
    }
    
    public function getRoomByCode($code) {
        return $this->db->single("
            SELECT r.*, u.username as owner_name
            FROM {$this->table} r
            JOIN users u ON r.owner_id = u.id
            WHERE r.invite_code = ?", [strtoupper($code)]);
    }
    
    public function createRoom($data) {
        $userId = (int)$data['user_id'];
        $roomName = sanitize($data['name']);
        $description = sanitize($data['description'] ?? '');
        $isPublic = isset($data['is_public']) ? 1 : 0;
        $password = $data['password'] ?? '';
        $maxMembers = (int)($data['max_members'] ?? MAX_ROOM_MEMBERS);
        
        // Generate unique invite code
        $code = generateRoomCode();
        
        // Check if code already exists
        $existing = $this->db->single('SELECT id FROM rooms WHERE invite_code = ?', [$code]);
        if ($existing) {
            $code = generateRoomCode(); // Try again
        }
        
        $roomData = [
            'name' => $roomName,
            'description' => $description,
            'is_public' => $isPublic,
            'password_hash' => $password ? password_hash($password, PASSWORD_DEFAULT) : null,
            'invite_code' => $code,
            'owner_id' => $userId,
            'max_members' => $maxMembers
        ];
        
        if ($this->db->insert($this->table, $roomData)) {
            $roomId = $this->db->lastInsertId();
            
            // Add owner as first member
            $this->db->insert('room_members', [
                'room_id' => $roomId,
                'user_id' => $userId,
                'joined_at' => date('Y-m-d H:i:s'),
                'role' => 'owner'
            ]);
            
            return ['success' => true, 'id' => $roomId, 'code' => $code];
        }
        
        return ['success' => false, 'message' => 'Failed to create room'];
    }
    
    public function updateRoom($id, $data) {
        $room = $this->getRoomById($id);
        if (!$room) {
            return ['success' => false, 'message' => 'Room not found'];
        }
        
        $updateData = [
            'name' => sanitize($data['name'] ?? $room['name']),
            'description' => sanitize($data['description'] ?? $room['description']),
            'max_members' => (int)($data['max_members'] ?? $room['max_members'])
        ];
        
        if (!empty($data['password'])) {
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if ($this->db->update($this->table, $updateData, 'id = ' . (int)$id)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to update room'];
    }
    
    public function deleteRoom($id) {
        $room = $this->getRoomById($id);
        if (!$room) {
            return ['success' => false, 'message' => 'Room not found'];
        }
        
        if ($this->db->delete($this->table, 'id = ' . (int)$id)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to delete room'];
    }
    
    public function joinRoom($userId, $code, $password = '') {
        $room = $this->getRoomByCode($code);
        
        if (!$room) {
            return ['success' => false, 'message' => 'Invalid room code'];
        }
        
        if ((int)$room['is_public'] !== 1 && !password_verify($password, $room['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid room password'];
        }
        
        // Check if user already in room
        $member = $this->db->single(
            'SELECT * FROM room_members WHERE room_id = ? AND user_id = ?', 
            [(int)$room['id'], (int)$userId]
        );
        
        if ($member) {
            return ['success' => false, 'message' => 'Already a member of this room'];
        }
        
        // Check member limit
        $memberCount = $this->db->single(
            'SELECT COUNT(*) as count FROM room_members WHERE room_id = ?', 
            [(int)$room['id']]
        );
        
        if ($memberCount['count'] >= $room['max_members']) {
            return ['success' => false, 'message' => 'Room is full'];
        }
        
        if ($this->db->insert('room_members', [
            'room_id' => (int)$room['id'],
            'user_id' => (int)$userId,
            'joined_at' => date('Y-m-d H:i:s'),
            'role' => 'member'
        ])) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to join room'];
    }
    
    public function leaveRoom($userId, $roomId) {
        if ($this->db->delete('room_members', 'room_id = ? AND user_id = ?', [(int)$roomId, (int)$userId])) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to leave room'];
    }
    
    public function removeMember($roomId, $memberId) {
        $room = $this->getRoomById($roomId);
        $currentUser = getCurrentUser();
        
        if ($currentUser['id'] !== $room['owner_id']) {
            return ['success' => false, 'message' => 'Only room owner can remove members'];
        }
        
        if ($this->db->delete('room_members', 'room_id = ? AND user_id = ?', [(int)$roomId, (int)$memberId])) {
            return ['success' => true];
        }
        
        return ['success' => false, 'message' => 'Failed to remove member'];
    }
    
    public function getRoomMembers($roomId) {
        return $this->db->resultSet("
            SELECT rm.*, u.username, u.country, u.points, u.profile_picture,
                   (SELECT COUNT(*) FROM predictions p WHERE p.user_id = u.id) as predictions,
                   (SELECT COUNT(*) FROM predictions p WHERE p.user_id = u.id AND p.points > 0) as correct_predictions
            FROM room_members rm
            JOIN users u ON rm.user_id = u.id
            WHERE rm.room_id = ?
            ORDER BY u.points DESC
        ", [(int)$roomId]);
    }
    
    public function getRoomPredictions($roomId, $limit = null) {
        $sql = "
            SELECT p.*, u.username, u.country, m.match_date,
                   h.name as home_team_name, a.name as away_team_name
            FROM predictions p
            JOIN room_members rm ON p.user_id = rm.user_id
            JOIN users u ON p.user_id = u.id
            JOIN matches m ON p.match_id = m.id
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE rm.room_id = ?
            ORDER BY p.points DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->resultSet($sql, [(int)$roomId]);
    }
    
    public function getRoomLeaderboard($roomId, $period = 'overall') {
        $dateCondition = '';
        
        if ($period === 'weekly') {
            $dateCondition = 'AND p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
        } elseif ($period === 'monthly') {
            $dateCondition = 'AND p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
        }
        
        return $this->db->resultSet("
            SELECT u.id, u.username, u.country, u.profile_picture,
                   COUNT(p.id) as predictions,
                   SUM(p.points) as total_points,
                   AVG(p.points) as avg_points,
                   SUM(CASE WHEN p.points > 0 THEN 1 ELSE 0 END) as correct_predictions
            FROM users u
            JOIN room_members rm ON u.id = rm.user_id
            LEFT JOIN predictions p ON u.id = p.user_id
            WHERE rm.room_id = ? {$dateCondition}
            GROUP BY u.id
            ORDER BY total_points DESC
        ", [(int)$roomId]);
    }
    
    public function getUserRoomPredictions($userId, $roomId) {
        return $this->db->resultSet("
            SELECT p.*, m.match_date, m.status,
                   h.name as home_team_name, a.name as away_team_name
            FROM predictions p
            JOIN room_members rm ON p.user_id = rm.user_id
            JOIN matches m ON p.match_id = m.id
            JOIN teams h ON m.home_team_id = h.id
            JOIN teams a ON m.away_team_id = a.id
            WHERE rm.user_id = ? AND rm.room_id = ?
            ORDER BY m.match_date DESC
        ", [(int)$userId, (int)$roomId]);
    }
}
