<?php
/**
 * PredictCup User Model
 */

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function register($data) {
        if ($this->getUserByEmail($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        
        if ($this->getUserByUsername($data['username'])) {
            return ['success' => false, 'message' => 'Username already taken'];
        }
        
        if (!isValidPassword($data['password'])) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase, and number'];
        }
        
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $passwordHash,
            'country' => $data['country'] ?? 'Global',
            'profile_picture' => null,
            'points' => 0,
            'is_active' => 1,
            'join_date' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert($this->table, $userData)) {
            $userId = $this->db->lastInsertId();
            createNotification($userId, 'welcome', 'Welcome to PredictCup!');
            return ['success' => true, 'message' => 'Registration successful. Please login.', 'user_id' => $userId];
        }
        
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
    
    public function login($data) {
        $user = $this->getUserByEmail($data['email']);
        
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        if ((int)$user['is_active'] !== 1) {
            return ['success' => false, 'message' => 'Account is not active'];
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['country'] = $user['country'];
        $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        if (!empty($data['remember_me'])) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + REMEMBER_ME_EXPIRY);
            
            $this->db->insert('remember_tokens', [
                'user_id' => $user['id'],
                'token' => $token,
                'expiry' => $expiry
            ]);
            
            setcookie('remember_token', $token, time() + REMEMBER_ME_EXPIRY, '/', '', true, true);
        }
        
        $this->db->update($this->table, ['last_login' => date('Y-m-d H:i:s')], 'id = ' . $user['id']);
        logActivity($user['id'], 'login', 'User logged in successfully');
        
        return ['success' => true, 'message' => 'Login successful', 'redirect' => BASE_URL . '/dashboard'];
    }
    
    public function logout() {
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        session_start();
        return true;
    }
    
    public function getUserById($userId) {
        return $this->db->single('SELECT * FROM users WHERE id = ?', [$userId]);
    }
    
    public function getUserByEmail($email) {
        return $this->db->single('SELECT * FROM users WHERE email = ?', [$email]);
    }
    
    public function getUserByUsername($username) {
        return $this->db->single('SELECT * FROM users WHERE username = ?', [$username]);
    }
    
    public function updateProfile($data, $userId) {
        $user = $this->getUserById($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        if ($data['email'] !== $user['email'] && $this->getUserByEmail($data['email'])) {
            return ['success' => false, 'message' => 'Email already in use'];
        }
        
        if ($data['username'] !== $user['username'] && $this->getUserByUsername($data['username'])) {
            return ['success' => false, 'message' => 'Username already taken'];
        }
        
        $updateData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'country' => $data['country'] ?? ''
        ];
        
        if (!empty($data['current_password'])) {
            if (!password_verify($data['current_password'], $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            if (!isValidPassword($data['new_password'])) {
                return ['success' => false, 'message' => 'New password must be at least 8 characters with uppercase, lowercase, and number'];
            }
            
            $updateData['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
        }
        
        if ($this->db->update($this->table, $updateData, 'id = ' . $userId)) {
            $_SESSION['username'] = $updateData['username'];
            $_SESSION['email'] = $updateData['email'];
            $_SESSION['country'] = $updateData['country'];
            return ['success' => true, 'message' => 'Profile updated successfully'];
        }
        
        return ['success' => false, 'message' => 'Profile update failed'];
    }
    
    public function forgotPassword($email) {
        $user = $this->getUserByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'If email exists, reset link will be sent'];
        }
        
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', time() + 3600);
        
        $this->db->insert('password_resets', [
            'user_id' => $user['id'],
            'token' => $token,
            'expires_at' => $expiry,
            'is_used' => 0
        ]);
        
        return ['success' => true, 'message' => 'Reset link sent to your email'];
    }
    
    public function resetPassword($token, $newPassword) {
        $reset = $this->db->single(
            'SELECT * FROM password_resets WHERE token = ? AND is_used = 0',
            [$token]
        );
        
        if (!$reset || strtotime($reset['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Invalid or expired reset token'];
        }
        
        if (!isValidPassword($newPassword)) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase, and number'];
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->db->update($this->table, ['password' => $passwordHash], 'id = ' . $reset['user_id'])) {
            $this->db->update('password_resets', ['is_used' => 1], 'id = ' . $reset['id']);
            return ['success' => true, 'message' => 'Password reset successful. Please login.'];
        }
        
        return ['success' => false, 'message' => 'Password reset failed'];
    }
    
    public function updatePoints($userId, $points) {
        return $this->db->update($this->table, ['points = points + ' . (int)$points], 'id = ' . (int)$userId);
    }
    
    public function getStats($userId) {
        return $this->db->single("
            SELECT 
                u.points, u.join_date, u.profile_picture,
                COUNT(p.id) as total_predictions,
                SUM(CASE WHEN p.points > 0 THEN 1 ELSE 0 END) as correct_predictions,
                AVG(COALESCE(p.points, 0)) as avg_points
            FROM users u
            LEFT JOIN predictions p ON p.user_id = u.id
            WHERE u.id = ?
            GROUP BY u.id
        ", [$userId]);
    }
    
    public function getGlobalRank($userId) {
        $user = $this->getUserById($userId);
        if (!$user) return 0;
        
        $rank = $this->db->single(
            'SELECT COUNT(*) as count FROM users WHERE points > ?', [$user['points']]
        );
        return (int)$rank['count'] + 1;
    }
    
    public function getTopPredictors($limit = 10) {
        return $this->db->resultSet("
            SELECT id, username, country, points, profile_picture
            FROM users WHERE is_active = 1
            ORDER BY points DESC LIMIT ?", [$limit]);
    }
}
