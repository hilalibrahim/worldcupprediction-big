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
            $homeScore = (int)($_POST['home_score'] ?? 0);
            $awayScore = (int)($_POST['away_score'] ?? 0);
            $predictedWinner = sanitize($_POST['predicted_winner'] ?? 'draw');
            
            // Both types of predictions together
            $data = [
                'user_id' => getCurrentUserId(),
                'match_id' => $matchId,
                'prediction_type' => 'both',  // Both score and winner
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'predicted_winner' => $predictedWinner
            ];
            
            $result = $this->predictionModel->addPrediction($data);
            
            if ($result['success']) {
                setFlashMessage('success', 'Prediction saved!');
            } else {
                // Show the actual error for debugging
                $errorMsg = $result['message'] ?? 'Unknown error';
                setFlashMessage('error', $errorMsg);
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
            $predictions = (int)($user['predictions'] ?? 0);
            $correct = (int)($user['correct_predictions'] ?? 0);
            $user['accuracy'] = $predictions > 0 
                ? round(($correct / $predictions) * 100, 2) 
                : 0;
        }
        
        include_once __DIR__ . '/../views/leaderboard.php';
    }
    
    public function about() {
        include_once __DIR__ . '/../views/about.php';
    }
}
