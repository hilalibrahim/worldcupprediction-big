<?php
/**
 * PredictCup API Controller
 */

class ApiController {
    private $matchModel;
    private $predictionModel;
    private $roomModel;
    private $userModel;
    
    public function __construct() {
        $this->matchModel = new MatchModel();
        $this->predictionModel = new Prediction();
        $this->roomModel = new Room();
        $this->userModel = new User();
    }
    
    public function handleRequest($action = null) {
        $action = sanitize($action ?: ($_GET['action'] ?? ''));
        
        switch ($action) {
            case 'today-matches':
                $this->getTodayMatches();
                break;
            case 'upcoming-matches':
                $this->getUpcomingMatches();
                break;
            case 'match-predictions':
                $this->getMatchPredictions();
                break;
            case 'user-predictions':
                $this->getUserPredictions();
                break;
            case 'predictions':
                $this->createPrediction();
                break;
            case 'leaderboard':
                $this->getLeaderboard();
                break;
            case 'room-leaderboard':
                $this->getRoomLeaderboard();
                break;
            case 'room-members':
                $this->getRoomMembers();
                break;
            case 'notifications':
                $this->getNotifications();
                break;
            case 'notifications/mark-read':
                $this->markNotificationsAsRead();
                break;
            case 'notifications/unread-count':
                $this->getUnreadCount();
                break;
            case 'stats':
                $this->getStats();
                break;
            default:
                echo json_encode(['error' => 'Invalid action']);
                break;
        }
    }
    
    public function getTodayMatches() {
        $matches = $this->matchModel->getTodayMatches();
        echo json_encode($matches);
    }
    
    public function getUpcomingMatches() {
        $limit = (int)($_GET['limit'] ?? 10);
        $matches = $this->matchModel->getUpcomingMatches($limit);
        echo json_encode($matches);
    }
    
    public function getMatchPredictions() {
        $matchId = (int)$_GET['match_id'];
        $predictions = $this->predictionModel->getMatchPredictions($matchId, 20);
        echo json_encode($predictions);
    }
    
    public function getUserPredictions() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $userId = getCurrentUserId();
        $predictions = $this->predictionModel->getUserPredictions($userId);
        echo json_encode($predictions);
    }
    
    public function createPrediction() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $data = [
            'user_id' => getCurrentUserId(),
            'match_id' => (int)$_POST['match_id'] ?? 0,
            'home_score' => (int)$_POST['home_score'] ?? 0,
            'away_score' => (int)$_POST['away_score'] ?? 0
        ];
        
        $result = $this->predictionModel->addPrediction($data);
        echo json_encode($result);
    }
    
    public function getLeaderboard() {
        $period = sanitize($_GET['period'] ?? 'overall');
        $leaderboard = [];
        
        if ($period === 'overall') {
            $leaderboard = $this->userModel->getTopPredictors(100);
        } else {
            $db = Database::getInstance();
            $leaderboard = $db->resultSet("
                SELECT u.id, u.username, u.country, u.points,
                       COUNT(p.id) as predictions,
                       SUM(p.points) as total_points,
                       AVG(p.points) as avg_points,
                       SUM(CASE WHEN p.points > 0 THEN 1 ELSE 0 END) as correct_predictions
                FROM users u
                LEFT JOIN predictions p ON u.id = p.user_id
                WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                GROUP BY u.id
                ORDER BY total_points DESC
                LIMIT 100
            ");
        }
        
        $rank = 1;
        foreach ($leaderboard as &$user) {
            $user['rank'] = $rank++;
        }
        
        echo json_encode($leaderboard);
    }
    
    public function getRoomLeaderboard() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $roomId = (int)$_GET['room_id'];
        $period = sanitize($_GET['period'] ?? 'overall');
        
        $leaderboard = $this->roomModel->getRoomLeaderboard($roomId, $period);
        
        $rank = 1;
        foreach ($leaderboard as &$user) {
            $user['rank'] = $rank++;
        }
        
        echo json_encode($leaderboard);
    }
    
    public function getRoomMembers() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $roomId = (int)$_GET['room_id'];
        $members = $this->roomModel->getRoomMembers($roomId);
        echo json_encode($members);
    }
    
    public function getNotifications() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $userId = getCurrentUserId();
        $db = Database::getInstance();
        $notifications = $db->resultSet("
            SELECT * FROM notifications 
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 50
        ", [$userId]);
        
        $db->update('notifications', ['is_read' => 1], 'user_id = ' . $userId);
        
        echo json_encode($notifications);
    }
    
    public function markNotificationsAsRead() {
        if (!isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $userId = getCurrentUserId();
        $notificationId = (int)$_POST['notification_id'] ?? 0;
        $db = Database::getInstance();
        
        if ($notificationId > 0) {
            $db->update('notifications', ['is_read' => 1], 'id = ? AND user_id = ?', [$notificationId, $userId]);
            echo json_encode(['success' => true]);
        } else {
            $db->update('notifications', ['is_read' => 1], 'user_id = ' . $userId);
            echo json_encode(['success' => true, 'all' => true]);
        }
    }
    
    public function getUnreadCount() {
        if (!isLoggedIn()) {
            echo json_encode(['count' => 0]);
            return;
        }
        
        $userId = getCurrentUserId();
        $db = Database::getInstance();
        $count = $db->single(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        
        echo json_encode(['count' => $count['count'] ?? 0]);
    }
    
    public function getStats() {
        $db = Database::getInstance();
        $stats = [
            'total_users' => $db->single('SELECT COUNT(*) as count FROM users WHERE is_active = 1')['count'],
            'total_rooms' => $db->single('SELECT COUNT(*) as count FROM rooms')['count'],
            'total_predictions' => $db->single('SELECT COUNT(*) as count FROM predictions')['count'],
            'matches_played' => $db->single('SELECT COUNT(*) as count FROM matches WHERE status = "completed"')['count']
        ];
        echo json_encode($stats);
    }
}
