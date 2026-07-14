<?php
/**
 * PredictCup Configuration
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:3000';
define('BASE_URL', $protocol . $host);
define('APP_NAME', 'PredictCup');

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'predictcup_db');
define('DB_USER', 'root');
define('DB_PASS', 'Cl+fwmRGQ1f7');
define('DB_CHARSET', 'utf8mb4');

// Session configuration
define('SESSION_LIFETIME', 604800);
define('SESSION_NAME', 'predictcup_session');

// Upload configuration
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_UPLOAD_SIZE', 5242880);
define('ALLOWED_IMAGE_TYPES', ['gif', 'jpg', 'jpeg', 'png']);

// App configuration
define('DEFAULT_TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(DEFAULT_TIMEZONE);

// Prediction cutoff time
define('PREDICTION_CUTOFF_MINUTES', 0); // Users can predict until exactly match start

// Pagination
define('ITEMS_PER_PAGE', 20);

// Points system
define('POINTS_EXACT_SCORE', 10);  // Both winner and exact score correct
define('POINTS_CORRECT_WINNER', 5); // Only winner correct (not exact score)
define('POINTS_BONUS_STREAK', 2);
define('MAX_POINTS_PER_MATCH', 10);

// Room settings
define('MAX_ROOM_MEMBERS', 50);
define('ROOM_INVITE_CODE_LENGTH', 6);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('REMEMBER_ME_EXPIRY', 2592000);

// Email configuration
define('MAIL_METHOD', 'mail');
define('ADMIN_EMAIL', 'admin@predictcup.com');
define('ADMIN_NAME', 'PredictCup Admin');

// Football-Data.org API configuration
define('FOOTBALL_DATA_API_KEY', '4f91ce6ce13140c8be3751563c26a9c4'); // Add your API key here
define('FOOTBALL_DATA_API_URL', 'https://api.football-data.org/v4/');
define('FOOTBALL_DATA_COMPETITION', 'WC'); // World Cup competition code
define('FOOTBALL_DATA_UPDATE_INTERVAL', 3600); // Update matches every hour

// Environment (development/production)
if (!defined('ENV')) {
    define('ENV', 'development');
}

// Magic quotes compatibility for older PHP versions (removed in PHP 8.0)
// Note: get_magic_quotes_gpc() was removed in PHP 8.0, so we handle it conditionally
if (version_compare(PHP_VERSION, '8.0.0', '<') && function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    function stripslashes_deep($value) {
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
