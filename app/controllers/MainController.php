<?php
/**
 * PredictCup Main Controller
 */

class MainController {
    private $matchModel;
    private $predictionModel;
    private $userModel;
    private $roomModel;
    private $achievementModel;
    
    public function __construct() {
        $this->matchModel = new MatchModel();
        $this->predictionModel = new Prediction();
        $this->userModel = new User();
        $this->roomModel = new Room();
        $this->achievementModel = new Achievement();
    }
    
    public function index() {
        $topPredictors = $this->userModel->getTopPredictors(5);
        $todayMatches = $this->matchModel->getTodayMatches();
        $upcomingMatches = $this->matchModel->getUpcomingMatches(5);
        $allRooms = $this->roomModel->getAllRooms();
        
        include_once __DIR__ . '/../views/home.php';
    }
    
    public function dashboard() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);
        $stats = $this->userModel->getStats($userId);
        $globalRank = $this->userModel->getGlobalRank($userId);
        $todayMatches = $this->matchModel->getTodayMatches();
        $upcomingMatches = $this->matchModel->getUpcomingMatches(10);
        $topPredictors = $this->userModel->getTopPredictors(5);
        $userRooms = $this->roomModel->getUserRooms($userId);
        
        $this->achievementModel->checkAchievements($userId);
        
        include_once __DIR__ . '/../views/dashboard.php';
    }
    
    public function dailyMatches() {
        $todayMatches = $this->matchModel->getTodayMatches();
        
        if (isLoggedIn()) {
            $userId = getCurrentUserId();
            foreach ($todayMatches as &$match) {
                $match['my_prediction'] = $this->predictionModel->getMatchPrediction($match['id'], $userId);
            }
        }
        
        include_once __DIR__ . '/../views/matches/daily.php';
    }
    
    public function matchDetail($id) {
        $match = $this->matchModel->getMatchById($id);
        
        if (!$match) {
            setFlashMessage('error', 'Match not found');
            redirect(BASE_URL . '/daily-matches');
        }
        
        $predictions = [];
        if ($match['status'] === 'completed') {
            $predictions = $this->predictionModel->getMatchPredictions($id, 20);
        }
        
        $myPrediction = null;
        if (isLoggedIn()) {
            $userId = getCurrentUserId();
            $myPrediction = $this->predictionModel->getMatchPrediction($id, $userId);
        }
        
        include_once __DIR__ . '/../views/matches/detail.php';
    }
    
    public function predict() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        if (isPostRequest()) {
            $matchId = (int)$_POST['match_id'];
            
            // Debug: log the POST data
            error_log("Prediction POST: " . print_r($_POST, true));
            
            // Check if this is a room prediction (uses dynamic field names with match ID)
            if (isset($_POST['prediction_type_' . $matchId])) {
                $predictionType = sanitize($_POST['prediction_type_' . $matchId]);
                $predictedWinner = sanitize($_POST['predicted_winner_' . $matchId] ?? 'draw');
            } else {
                $predictionType = sanitize($_POST['prediction_type'] ?? 'score');
                $predictedWinner = sanitize($_POST['predicted_winner'] ?? 'draw');
            }
            
            error_log("Prediction Type: $predictionType, Winner: $predictedWinner");
            
            $data = [
                'user_id' => getCurrentUserId(),
                'match_id' => $matchId,
                'prediction_type' => $predictionType,
                'home_score' => (int)($_POST['home_score'] ?? 0),
                'away_score' => (int)($_POST['away_score'] ?? 0),
                'predicted_winner' => $predictionType === 'winner' ? $predictedWinner : null
            ];
            
            error_log("Prediction Data: " . print_r($data, true));
            
            $result = $this->predictionModel->addPrediction($data);
            
            error_log("Prediction Result: " . print_r($result, true));
            
            if ($result['success']) {
                setFlashMessage('success', 'Prediction saved successfully!');
            } else {
                setFlashMessage('error', $result['message']);
            }
            
            redirect(BASE_URL . "/match/{$data['match_id']}");
        }
    }
    
    public function leaderboard() {
        $period = sanitize($_GET['period'] ?? 'overall');
        $periods = ['overall', 'weekly', 'monthly'];
        if (!in_array($period, $periods)) {
            $period = 'overall';
        }
        
        $leaderboard = [];
        
        if ($period === 'overall') {
            $leaderboard = $this->userModel->getTopPredictors(100);
        } else {
            $db = Database::getInstance();
            $leaderboard = $db->resultSet("
                SELECT u.id, u.username, u.country, u.points, u.profile_picture,
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
            $user['accuracy'] = $user['predictions'] > 0 
                ? round(($user['correct_predictions'] / $user['predictions']) * 100, 2) 
                : 0;
        }
        
        include_once __DIR__ . '/../views/leaderboard.php';
    }
    
    public function about() {
        include_once __DIR__ . '/../views/about.php';
    }
}
