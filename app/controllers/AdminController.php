<?php
/**
 * PredictCup Admin Controller
 */

class AdminController {
    private $userModel;
    private $teamModel;
    private $matchModel;
    private $roomModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->teamModel = new Team();
        $this->matchModel = new MatchModel();
        $this->roomModel = new Room();
    }
    
    public function login() {
        if (isAdmin()) {
            redirect(BASE_URL . '/admin/dashboard');
        }
        
        if (isPostRequest()) {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->getUserByEmail($email);
            
            if ($user && password_verify($password, $user['password']) && (int)$user['is_admin'] === 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = 1;
                $_SESSION['logged_in'] = true;
                
                setFlashMessage('success', 'Admin login successful');
                redirect(BASE_URL . '/admin/dashboard');
            } else {
                setFlashMessage('error', 'Invalid admin credentials');
                redirect(BASE_URL . '/admin/login');
            }
        }
        
        include_once __DIR__ . '/../views/admin/login.php';
    }
    
    public function logout() {
        session_destroy();
        setFlashMessage('success', 'Logged out successfully');
        redirect(BASE_URL . '/admin/login');
    }
    
    public function dashboard() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $db = Database::getInstance();
        
        $totalUsers = $db->single('SELECT COUNT(*) as count FROM users WHERE is_active = 1');
        $totalRooms = $db->single('SELECT COUNT(*) as count FROM rooms');
        $totalPredictions = $db->single('SELECT COUNT(*) as count FROM predictions');
        $totalMatches = $db->single('SELECT COUNT(*) as count FROM matches');
        $matchesPlayed = $db->single('SELECT COUNT(*) as count FROM matches WHERE status = "completed"');
        
        include_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function teams() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $teams = $this->teamModel->getAllTeams();
        
        if (isPostRequest()) {
            $action = sanitize($_POST['action'] ?? '');
            
            if ($action === 'add') {
                $data = [
                    'name' => sanitize($_POST['name'] ?? ''),
                    'country' => sanitize($_POST['country'] ?? ''),
                    'group_letter' => sanitize($_POST['group_letter'] ?? '')
                ];
                
                $result = $this->teamModel->addTeam($data);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Team added successfully' : $result['message']);
            } elseif ($action === 'delete') {
                $result = $this->teamModel->deleteTeam((int)$_POST['id']);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Team deleted successfully' : $result['message']);
            }
            
            redirect(BASE_URL . '/admin/teams');
        }
        
        include_once __DIR__ . '/../views/admin/teams.php';
    }
    
    public function matches() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $matches = $this->matchModel->getAllMatches();
        $teams = $this->teamModel->getAllTeams();
        
        // Fetch available matches from API (if key is configured)
        $apiMatches = [];
        $apiAvailable = false;
        
        if (!empty(FOOTBALL_DATA_API_KEY)) {
            $apiMatches = $this->getAvailableMatchesFromAPI();
            $apiAvailable = !empty($apiMatches);
        }
        
        if (isPostRequest()) {
            $action = sanitize($_POST['action'] ?? '');
            
            if ($action === 'add') {
                $data = [
                    'home_team_id' => (int)$_POST['home_team_id'],
                    'away_team_id' => (int)$_POST['away_team_id'],
                    'match_date' => sanitize($_POST['match_date'] ?? ''),
                    'stadium' => sanitize($_POST['stadium'] ?? ''),
                    'stage' => sanitize($_POST['stage'] ?? 'Group Stage')
                ];
                
                $result = $this->matchModel->addMatch($data);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Match added successfully' : $result['message']);
            } elseif ($action === 'add_from_api') {
                // Add match from API selection
                $result = $this->addMatchFromAPI((int)$_POST['api_match_id']);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Match added from API successfully' : $result['message']);
            } elseif ($action === 'delete') {
                $result = $this->matchModel->deleteMatch((int)$_POST['id']);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Match deleted successfully' : $result['message']);
            } elseif ($action === 'enter_results') {
                // Note: predicted_winner is auto-calculated from scores, so we ignore it here
                $result = $this->matchModel->enterResults(
                    (int)$_POST['match_id'],
                    (int)$_POST['home_score'],
                    (int)$_POST['away_score']
                );
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Results entered successfully' : $result['message']);
            } elseif ($action === 'fetch_api_matches') {
                // Refresh API matches list
                $apiMatches = $this->getAvailableMatchesFromAPI(true);
                $apiAvailable = !empty($apiMatches);
                setFlashMessage('info', 'API matches list refreshed');
            }
            
            redirect(BASE_URL . '/admin/matches');
        }
        
        include_once __DIR__ . '/../views/admin/matches.php';
    }
    
    /**
     * Get available matches from API that are not already in database
     */
    private function getAvailableMatchesFromAPI($forceRefresh = false) {
        static $cachedMatches = null;
        static $lastFetchTime = 0;
        
        // Return cached results if available and not forcing refresh
        if (!$forceRefresh && $cachedMatches !== null && (time() - $lastFetchTime) < 300) {
            return $cachedMatches;
        }
        
        if (empty(FOOTBALL_DATA_API_KEY)) {
            return [];
        }
        
        $apiUrl = FOOTBALL_DATA_API_URL . 'matches?competitions=' . FOOTBALL_DATA_COMPETITION;
        $headers = [
            'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
            'Content-Type: ' . 'application/json'
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
            return [];
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['matches'])) {
            return [];
        }
        
        // Get existing API match IDs from database
        $db = Database::getInstance();
        $existingMatches = $db->resultSet("SELECT api_match_id FROM matches WHERE api_match_id IS NOT NULL");
        $existingIds = array_column($existingMatches, 'api_match_id');
        
        // Filter matches that are not already in database
        $availableMatches = [];
        foreach ($data['matches'] as $match) {
            if (!in_array($match['id'], $existingIds)) {
                $availableMatches[] = [
                    'id' => $match['id'],
                    'homeTeam' => $match['homeTeam']['name'] ?? 'Unknown',
                    'awayTeam' => $match['awayTeam']['name'] ?? 'Unknown',
                    'utcDate' => $match['utcDate'],
                    'status' => $match['status'] ?? 'SCHEDULED',
                    'stage' => $match['stage'] ?? 'GROUP_STAGE',
                    'venue' => $match['venue'] ?? 'Unknown Stadium'
                ];
            }
        }
        
        // Cache the results
        $cachedMatches = $availableMatches;
        $lastFetchTime = time();
        
        return $availableMatches;
    }
    
    /**
     * Add match from API selection
     */
    private function addMatchFromAPI($apiMatchId) {
        if (empty(FOOTBALL_DATA_API_KEY)) {
            return ['success' => false, 'message' => 'API key not configured'];
        }
        
        // Fetch specific match from API
        $apiUrl = FOOTBALL_DATA_API_URL . 'matches/' . $apiMatchId;
        $headers = [
            'X-Auth-Token: ' . FOOTBALL_DATA_API_KEY,
            'Content-Type: ' . 'application/json'
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
            return ['success' => false, 'message' => 'Failed to fetch match from API'];
        }
        
        $matchData = json_decode($response, true);
        
        // Use the existing sync method from MatchModel
        return $this->matchModel->syncMatchFromAPI($matchData);
    }
    
    public function users() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $db = Database::getInstance();
        $users = $db->resultSet("SELECT * FROM users ORDER BY points DESC");
        
        if (isPostRequest()) {
            $action = sanitize($_POST['action'] ?? '');
            
            if ($action === 'ban') {
                $db->update('users', ['is_active' => 0], 'id = ' . (int)$_POST['id']);
                setFlashMessage('success', 'User banned successfully');
            } elseif ($action === 'delete') {
                $db->delete('users', 'id = ' . (int)$_POST['id']);
                setFlashMessage('success', 'User deleted successfully');
            } elseif ($action === 'make_admin') {
                $db->update('users', ['is_admin' => 1], 'id = ' . (int)$_POST['id']);
                setFlashMessage('success', 'User promoted to admin');
            } elseif ($action === 'remove_admin') {
                $db->update('users', ['is_admin' => 0], 'id = ' . (int)$_POST['id']);
                setFlashMessage('success', 'Admin privileges removed');
            }
            
            redirect(BASE_URL . '/admin/users');
        }
        
        include_once __DIR__ . '/../views/admin/users.php';
    }
    
    public function rooms() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $rooms = $this->roomModel->getAllRooms(false);
        
        if (isPostRequest()) {
            $action = sanitize($_POST['action'] ?? '');
            
            if ($action === 'delete') {
                $result = $this->roomModel->deleteRoom((int)$_POST['id']);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Room deleted successfully' : $result['message']);
            }
            
            redirect(BASE_URL . '/admin/rooms');
        }
        
        include_once __DIR__ . '/../views/admin/rooms.php';
    }
    
    /**
     * API Management
     */
    public function api() {
        if (!isAdmin()) {
            redirect(BASE_URL . '/admin/login');
        }
        
        $apiStatus = [];
        $apiResult = null;
        
        // Check API configuration
        $apiStatus['key_configured'] = !empty(FOOTBALL_DATA_API_KEY);
        $apiStatus['api_url'] = FOOTBALL_DATA_API_URL;
        $apiStatus['competition'] = FOOTBALL_DATA_COMPETITION;
        $apiStatus['update_interval'] = FOOTBALL_DATA_UPDATE_INTERVAL;
        
        // Get last sync time
        $db = Database::getInstance();
        $lastSync = $db->single("SELECT MAX(last_api_sync) as last_sync FROM matches");
        $apiStatus['last_sync'] = $lastSync['last_sync'] ?? 'Never';
        
        // Handle API actions
        if (isPostRequest()) {
            $action = sanitize($_POST['action'] ?? '');
            
            if ($action === 'update_schema') {
                $result = $this->matchModel->updateSchemaForAPI();
                setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            } elseif ($action === 'fetch_matches') {
                $result = $this->matchModel->fetchMatchesFromAPI();
                $apiResult = $result;
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Matches fetched successfully' : $result['message']);
            } elseif ($action === 'auto_update') {
                $result = $this->matchModel->autoUpdateMatches();
                $apiResult = $result;
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Auto-update completed' : $result['message']);
            } elseif ($action === 'update_key') {
                // In a real app, this would update config.php
                // For now, just show instructions
                setFlashMessage('info', 'To update API key, edit config/config.php and set FOOTBALL_DATA_API_KEY constant');
            }
        }
        
        include_once __DIR__ . '/../views/admin/api.php';
    }
}
