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
        $this->matchModel = new Match();
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
            } elseif ($action === 'delete') {
                $result = $this->matchModel->deleteMatch((int)$_POST['id']);
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Match deleted successfully' : $result['message']);
            } elseif ($action === 'enter_results') {
                $result = $this->matchModel->enterResults(
                    (int)$_POST['match_id'],
                    (int)$_POST['home_score'],
                    (int)$_POST['away_score']
                );
                setFlashMessage($result['success'] ? 'success' : 'error', $result['success'] ? 'Results entered successfully' : $result['message']);
            }
            
            redirect(BASE_URL . '/admin/matches');
        }
        
        include_once __DIR__ . '/../views/admin/matches.php';
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
}
