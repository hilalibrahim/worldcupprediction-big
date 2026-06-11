<?php
/**
 * PredictCup Configuration
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base URL
define('BASE_URL', 'http://localhost/worldcupprediction-big');
define('APP_NAME', 'PredictCup');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'predictcup_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session configuration
define('SESSION_LIFETIME', 604800);
define('SESSION_NAME', 'predictcup_session');

// Upload configuration
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_UPLOAD_SIZE', 5242880);
define('ALLOWED_IMAGE_TYPES', ['gif', 'jpg', 'jpeg', 'png']);

// App configuration
define('DEFAULT_TIMEZONE', 'UTC');
date_default_timezone_set(DEFAULT_TIMEZONE);

// Pagination
define('ITEMS_PER_PAGE', 20);

// Points system
define('POINTS_EXACT_SCORE', 5);
define('POINTS_CORRECT_WINNER', 3);
define('POINTS_CORRECT_DIFFERENCE', 2);
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

// Environment (development/production)
if (!defined('ENV')) {
    define('ENV', 'development');
}

// Magic quotes compatibility for older PHP versions (removed in PHP 8.0)
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
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
