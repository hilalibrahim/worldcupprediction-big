<?php
/**
 * PredictCup Notification Controller
 */

class NotificationController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getNotifications() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        $userId = getCurrentUserId();
        $notifications = $this->db->resultSet("
            SELECT * FROM notifications 
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 50
        ", [$userId]);
        
        $this->db->update('notifications', ['is_read' => 1], 'user_id = ' . $userId);
        
        echo json_encode($notifications);
    }
    
    public function markAsRead() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $userId = getCurrentUserId();
        $notificationId = (int)$_POST['notification_id'] ?? 0;
        
        if ($notificationId > 0) {
            $this->db->update('notifications', ['is_read' => 1], 'id = ? AND user_id = ?', [$notificationId, $userId]);
            echo json_encode(['success' => true]);
        } else {
            $this->db->update('notifications', ['is_read' => 1], 'user_id = ' . $userId);
            echo json_encode(['success' => true, 'all' => true]);
        }
    }
    
    public function getUnreadCount() {
        if (!isLoggedIn()) {
            echo json_encode(['count' => 0]);
            return;
        }
        
        $userId = getCurrentUserId();
        $count = $this->db->single(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        
        echo json_encode(['count' => $count['count'] ?? 0]);
    }
}
