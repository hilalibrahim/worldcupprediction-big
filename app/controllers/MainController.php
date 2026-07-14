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
            foreach ($todayMatches as $key => $match) {
                $todayMatches[$key]['my_prediction'] = $this->predictionModel->getMatchPrediction($match['id'], $userId);
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
        $totalPages = 1;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        if ($match['status'] === 'completed') {
            if ($currentPage < 1) $currentPage = 1;
            $limit = 20;
            
            $paginatedData = $this->predictionModel->getMatchPredictionsPaginated($id, $currentPage, $limit);
            $predictions = $paginatedData['predictions'];
            $totalPages = $paginatedData['total_pages'];
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
            $predictedWinner = getWinner($homeScore, $awayScore);
            
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
        $period = 'overall';
        
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        $limit = 20;
        
        $paginatedData = $this->userModel->getTopPredictorsPaginated($currentPage, $limit);
        $leaderboard = $paginatedData['predictors'];
        $totalPages = $paginatedData['total_pages'];
        
        $rank = ($currentPage - 1) * $limit + 1;
        foreach ($leaderboard as &$user) {
            $user['rank'] = $rank++;
        }
        
        include_once __DIR__ . '/../views/leaderboard.php';
    }
    
    public function about() {
        include_once __DIR__ . '/../views/about.php';
    }

    public function predictions() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }

        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);
        $predictions = $this->predictionModel->getUserPredictions($userId);
        $stats = $this->predictionModel->getUserCorrectPredictions($userId);

        // Add match results to predictions
        foreach ($predictions as &$pred) {
            $pred['home_score_actual'] = $pred['home_score'] ?? null;
            $pred['away_score_actual'] = $pred['away_score'] ?? null;
            $pred['is_correct'] = $pred['points'] > 0;
        }

        include_once __DIR__ . '/../views/predictions.php';
    }
}
