<?php
/**
 * Cron script for automatic match updates from Football-Data.org API
 * Run this script periodically (e.g., every hour) for automatic updates
 */

// Set error logging for cron
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/cron-errors.log');

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Match.php';

// Create logs directory if it doesn't exist
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}

// Log function for cron
function cron_log($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents(__DIR__ . '/logs/cron-updates.log', $logMessage, FILE_APPEND);
    
    // Also echo for manual runs
    if (php_sapi_name() !== 'cli') {
        echo $logMessage;
    }
}

try {
    cron_log("Starting automatic match update...");
    
    $matchModel = new MatchModel();
    
    // Check if API key is configured
    if (empty(FOOTBALL_DATA_API_KEY)) {
        cron_log("ERROR: Football-Data.org API key not configured in config.php");
        exit(1);
    }
    
    // Update schema if needed (first run)
    $matchModel->updateSchemaForAPI();
    cron_log("Database schema checked/updated");
    
    // Fetch and update matches
    $result = $matchModel->autoUpdateMatches();
    
    if ($result['success']) {
        if (isset($result['skipped']) && $result['skipped']) {
            cron_log("INFO: Update skipped - last sync was within update interval");
        } else {
            cron_log("SUCCESS: Matches updated - Imported: {$result['imported']}, Updated: {$result['updated']}, Total: {$result['total']}");
        }
    } else {
        cron_log("ERROR: API update failed - {$result['message']}");
    }
    
    cron_log("Automatic match update completed");
    
} catch (Exception $e) {
    cron_log("EXCEPTION: " . $e->getMessage());
    cron_log("TRACE: " . $e->getTraceAsString());
    exit(1);
}

exit(0);