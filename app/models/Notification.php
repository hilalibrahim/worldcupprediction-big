<?php
/**
 * PredictCup Notification Model
 */

class Notification {
    private $db;
    private $table = 'notifications';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getNotifications($userId, $limit = 50, $offset = 0) {
        return $this->db->resultSet("
            SELECT * FROM {$this->table}
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?, ?
        ", [$userId, $offset, $limit]);
    }
    
    public function getUnreadCount($userId) {
        $count = $this->db->single(
            'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        return (int)$count['count'];
    }
    
    public function markAsRead($userId, $notificationId = null) {
        if ($notificationId) {
            return $this->db->update($this->table, ['is_read' => 1], 'id = ? AND user_id = ?', [$notificationId, $userId]);
        }
        return $this->db->update($this->table, ['is_read' => 1], 'user_id = ' . $userId);
    }
    
    public function create($userId, $type, $message, $relatedId = null) {
        return $this->db->insert($this->table, [
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'related_id' => $relatedId,
            'created_at' => date('Y-m-d H:i:s'),
            'is_read' => 0
        ]);
    }
}
