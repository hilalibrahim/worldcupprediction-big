<?php
/**
 * PredictCup Auth Controller
 */

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function register() {
        if (isPostRequest()) {
            $data = [
                'username' => sanitize($_POST['username'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'country' => sanitize($_POST['country'] ?? 'Global')
            ];
            
            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                setFlashMessage('error', 'All fields are required');
                redirect(BASE_URL . '/register');
            }
            
            $result = $this->userModel->register($data);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
                redirect(BASE_URL . '/login');
            } else {
                setFlashMessage('error', $result['message']);
                redirect(BASE_URL . '/register');
            }
        }
        
        include_once __DIR__ . '/../views/auth/register.php';
    }
    
    public function login() {
        if (isLoggedIn()) {
            redirect(BASE_URL . '/dashboard');
        }
        
        if (isPostRequest()) {
            $data = [
                'email' => sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'remember_me' => isset($_POST['remember_me']) ? 1 : 0
            ];
            
            if (empty($data['email']) || empty($data['password'])) {
                setFlashMessage('error', 'Email and password are required');
                redirect(BASE_URL . '/login');
            }
            
            $result = $this->userModel->login($data);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
                redirect($result['redirect']);
            } else {
                setFlashMessage('error', $result['message']);
                redirect(BASE_URL . '/login');
            }
        }
        
        include_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function logout() {
        if (isLoggedIn()) {
            $this->userModel->logout();
        }
        setFlashMessage('success', 'Logged out successfully');
        redirect(BASE_URL);
    }
    
    public function forgotPassword() {
        if (isPostRequest()) {
            $email = sanitize($_POST['email'] ?? '');
            
            if (empty($email)) {
                setFlashMessage('error', 'Email is required');
                redirect(BASE_URL . '/forgot-password');
            }
            
            if (!isValidEmail($email)) {
                setFlashMessage('error', 'Invalid email format');
                redirect(BASE_URL . '/forgot-password');
            }
            
            $result = $this->userModel->forgotPassword($email);
            
            setFlashMessage($result['success'] ? 'success' : 'error', $result['message']);
            redirect(BASE_URL . '/forgot-password');
        }
        
        include_once __DIR__ . '/../views/auth/forgot-password.php';
    }
    
    public function resetPassword($token) {
        if (isPostRequest()) {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($password) || empty($confirmPassword)) {
                setFlashMessage('error', 'Password and confirmation are required');
                redirect(BASE_URL . "/reset-password/$token");
            }
            
            if ($password !== $confirmPassword) {
                setFlashMessage('error', 'Passwords do not match');
                redirect(BASE_URL . "/reset-password/$token");
            }
            
            $result = $this->userModel->resetPassword($token, $password);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
                redirect(BASE_URL . '/login');
            } else {
                setFlashMessage('error', $result['message']);
                redirect(BASE_URL . "/reset-password/$token");
            }
        }
        
        $token = sanitize($token);
        include_once __DIR__ . '/../views/auth/reset-password.php';
    }
    
    public function profile() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . '/login');
        }
        
        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);
        $stats = $this->userModel->getStats($userId);
        
        $globalRank = $this->userModel->getGlobalRank($userId);
        
        // Fetch recent predictions
        require_once __DIR__ . '/../models/Prediction.php';
        $predictionModel = new Prediction();
        $recentPredictions = array_slice($predictionModel->getUserPredictions($userId), 0, 5);
        
        if (isPostRequest()) {
            $data = [
                'username' => sanitize($_POST['username'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'country' => sanitize($_POST['country'] ?? ''),
                'current_password' => $_POST['current_password'] ?? '',
                'new_password' => $_POST['new_password'] ?? ''
            ];
            
            $result = $this->userModel->updateProfile($data, $userId);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
            } else {
                setFlashMessage('error', $result['message']);
            }
        }
        
        include_once __DIR__ . '/../views/auth/profile.php';
    }
}
