<?php
/**
 * PredictCup API Endpoint
 */

session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers/helpers.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Match.php';
require_once __DIR__ . '/app/models/Prediction.php';
require_once __DIR__ . '/app/models/Room.php';

$action = sanitize($_GET['action'] ?? $_POST['action'] ?? '');

$controller = new ApiController();

switch ($action) {
    case 'today-matches':
        $controller->getTodayMatches();
        break;
        
    case 'upcoming-matches':
        $controller->getUpcomingMatches();
        break;
        
    case 'match-predictions':
        $controller->getMatchPredictions();
        break;
        
    case 'user-predictions':
        $controller->getUserPredictions();
        break;
        
    case 'predictions':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createPrediction();
        } else {
            echo json_encode(['error' => 'Invalid method']);
        }
        break;
        
    case 'leaderboard':
        $controller->getLeaderboard();
        break;
        
    case 'room-leaderboard':
        $controller->getRoomLeaderboard();
        break;
        
    case 'room-members':
        $controller->getRoomMembers();
        break;
        
    case 'notifications':
        $notificationController = new NotificationController();
        $notificationController->getNotifications();
        break;
        
    case 'notifications/mark-read':
        $notificationController = new NotificationController();
        $notificationController->markAsRead();
        break;
        
    case 'notifications/unread-count':
        $notificationController = new NotificationController();
        $notificationController->getUnreadCount();
        break;
        
    case 'stats':
        $db = Database::getInstance();
        $stats = [
            'total_users' => $db->single('SELECT COUNT(*) as count FROM users WHERE is_active = 1')['count'],
            'total_rooms' => $db->single('SELECT COUNT(*) as count FROM rooms')['count'],
            'total_predictions' => $db->single('SELECT COUNT(*) as count FROM predictions')['count'],
            'matches_played' => $db->single('SELECT COUNT(*) as count FROM matches WHERE status = "completed"')['count']
        ];
        echo json_encode($stats);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
