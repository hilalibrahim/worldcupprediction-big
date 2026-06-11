<?php
/**
 * PredictCup Helper Functions
 */

// Securely redirect to another page
function redirect($url = '') {
    if (empty($url)) {
        $url = BASE_URL;
    }
    header('Location: ' . $url);
    exit();
}

// Generate CSRF token
function generateCsrfToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// Verify CSRF token
function verifyCsrfToken($token) {
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

// Sanitize input data
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate password strength
function isValidPassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}

// Generate random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Generate unique room invite code
function generateRoomCode() {
    return strtoupper(substr(md5(uniqid()), 0, ROOM_INVITE_CODE_LENGTH));
}

// Format date to human readable format
function formatDate($date, $format = 'Y-m-d H:i:s') {
    if (empty($date)) {
        return '';
    }
    return date($format, strtotime($date));
}

// Format match date for display (convert UTC to Indian timezone)
function formatMatchDate($date) {
    if (empty($date)) {
        return 'TBD';
    }
    // First, create DateTime object treating input as UTC
    $dateObj = new DateTime($date, new DateTimeZone('UTC'));
    // Then convert to IST (Asia/Kolkata is UTC+5:30)
    $dateObj->setTimezone(new DateTimeZone('Asia/Kolkata'));
    return $dateObj->format('M d, Y - H:i') . ' IST';
}

// Calculate points for prediction
function calculatePoints($prediction, $actual) {
    $points = 0;
    
    $predType = $prediction['prediction_type'] ?? 'both';
    $predHome = (int)$prediction['home_score'];
    $predAway = (int)$prediction['away_score'];
    $actHome = (int)$actual['home_score'];
    $actAway = (int)$actual['away_score'];
    
    $actualWinner = getWinner($actHome, $actAway);
    $predictedWinner = !empty($prediction['predicted_winner']) 
        ? $prediction['predicted_winner']
        : getWinner($predHome, $predAway);
    
    // Winner-only prediction type
    if ($predType === 'winner') {
        if ($predictedWinner === $actualWinner) {
            return POINTS_CORRECT_WINNER; // 5 points
        }
        return 0;
    }
    
    // Both or Score type: check exact score first
    $isExactScore = ($predHome === $actHome && $predAway === $actAway);
    
    if ($isExactScore) {
        $points += POINTS_EXACT_SCORE; // +10 for exact score
    }
    
    // For 'both' type, ALWAYS check winner prediction (even if score was exact)
    if ($predType === 'both') {
        if ($predictedWinner === $actualWinner) {
            $points += POINTS_CORRECT_WINNER; // +5 for correct winner
        }
        return $points; // Returns 0, 5, 10, or 15
    }
    
    // For 'score' type, only add winner points if score wasn't exact
    if (!$isExactScore && $predictedWinner === $actualWinner) {
        $points += POINTS_CORRECT_WINNER; // +5 for correct winner
    }
    
    return min($points, MAX_POINTS_PER_MATCH);
}

// Determine match winner based on scores
function getWinner($home, $away) {
    if ($home > $away) return 'home';
    if ($away > $home) return 'away';
    return 'draw';
}

// Check if match is locked for predictions (5 minutes before match time)
function isMatchLocked($matchDate) {
    // Convert UTC from database to IST for comparison
    $matchDateTime = new DateTime($matchDate, new DateTimeZone('UTC'));
    $matchDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    
    $now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    
    // Calculate cutoff time (5 minutes before match)
    $cutoffTime = clone $matchDateTime;
    $cutoffTime->modify('-' . PREDICTION_CUTOFF_MINUTES . ' minutes');
    
    // Match is locked if current time is >= cutoff time
    return $now >= $cutoffTime;
}

// Check if user can still predict (returns remaining minutes or false)
function getPredictionTimeRemaining($matchDate) {
    // Convert UTC from database to IST for comparison
    $matchDateTime = new DateTime($matchDate, new DateTimeZone('UTC'));
    $matchDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    
    $now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    
    // Calculate cutoff time (5 minutes before match)
    $cutoffTime = clone $matchDateTime;
    $cutoffTime->modify('-' . PREDICTION_CUTOFF_MINUTES . ' minutes');
    
    // If already locked
    if ($now >= $cutoffTime) {
        return false;
    }
    
    // Calculate minutes remaining
    $interval = $now->diff($cutoffTime);
    $minutesRemaining = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
    
    return max(0, $minutesRemaining);
}

// Upload profile picture
function uploadProfilePicture($file, $userId) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileType, ALLOWED_IMAGE_TYPES)) {
        return false;
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return false;
    }
    
    $newFileName = "user_{$userId}_profile.{$fileType}";
    $targetPath = UPLOAD_DIR . $newFileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $newFileName;
    }
    
    return false;
}

// Create notification
function createNotification($userId, $type, $message, $relatedId = null) {
    $db = Database::getInstance();
    $db->insert('notifications', [
        'user_id' => $userId,
        'type' => $type,
        'message' => $message,
        'related_id' => $relatedId,
        'created_at' => date('Y-m-d H:i:s'),
        'is_read' => 0
    ]);
}

// Get user by ID
function getUserById($userId) {
    $db = Database::getInstance();
    return $db->single('SELECT * FROM users WHERE id = ?', [$userId]);
}

// Get user by email
function getUserByEmail($email) {
    $db = Database::getInstance();
    return $db->single('SELECT * FROM users WHERE email = ?', [$email]);
}

// Get team by ID
function getTeamById($teamId) {
    $db = Database::getInstance();
    return $db->single('SELECT * FROM teams WHERE id = ?', [$teamId]);
}

// Get match by ID
function getMatchById($matchId) {
    $db = Database::getInstance();
    return $db->single('SELECT * FROM matches WHERE id = ?', [$matchId]);
}

// Get room by ID
function getRoomById($roomId) {
    $db = Database::getInstance();
    return $db->single('SELECT * FROM rooms WHERE id = ?', [$roomId]);
}

// Check if user is in room
function isUserInRoom($roomId, $userId) {
    $db = Database::getInstance();
    $member = $db->single(
        'SELECT * FROM room_members WHERE room_id = ? AND user_id = ?', 
        [$roomId, $userId]
    );
    return (bool)$member;
}

// Calculate user accuracy
function calculateUserAccuracy($userId) {
    $db = Database::getInstance();
    $stats = $db->single(
        'SELECT COUNT(*) as total, SUM(CASE WHEN points > 0 THEN 1 ELSE 0 END) as correct 
         FROM predictions WHERE user_id = ?', [$userId]
    );
    
    if (!$stats || $stats['total'] == 0) {
        return 0;
    }
    return round(($stats['correct'] / $stats['total']) * 100, 2);
}

// Log activity
function logActivity($userId, $action, $details = '') {
    $db = Database::getInstance();
    $db->insert('user_activities', [
        'user_id' => $userId,
        'action' => $action,
        'details' => $details,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

// Check for admin
function isAdmin() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['is_admin']) && 
           (int)$_SESSION['is_admin'] === 1;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return getUserById($_SESSION['user_id']);
}

// Flash message helper
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    $message = $_SESSION['flash_message'] ?? null;
    unset($_SESSION['flash_message']);
    return $message;
}

// Check if POST request
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Check if AJAX request
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Generate QR code
function generateQrCode($data, $size = 200) {
    return "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($data) . "&size={$size}x{$size}";
}

// Get paginated results
function getPagedResults($query, $params = [], $page = 1, $perPage = ITEMS_PER_PAGE) {
    $offset = ($page - 1) * $perPage;
    $db = Database::getInstance();
    
    $total = $db->single(
        str_replace('*', 'COUNT(*) as count', preg_replace('/\bLIMIT\b[^;]+$/i', '', $query)), 
        $params
    );
    
    $results = $db->resultSet($query . " LIMIT {$offset}, {$perPage}", $params);
    
    return [
        'data' => $results,
        'total' => $total ? $total['count'] : 0,
        'page' => $page,
        'perPage' => $perPage,
        'totalPages' => ceil($total['count'] / $perPage)
    ];
}
