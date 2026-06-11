<?php
/**
 * PredictCup - World Cup Prediction Platform
 * Main Entry Point
 * 
 * @author PredictCup Team
 * @version 1.0.0
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

// Load helpers
require_once __DIR__ . '/app/helpers/helpers.php';

// Load database
require_once __DIR__ . '/config/database.php';

// Load models
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Team.php';
require_once __DIR__ . '/app/models/Match.php';
require_once __DIR__ . '/app/models/Prediction.php';
require_once __DIR__ . '/app/models/Room.php';
require_once __DIR__ . '/app/models/Achievement.php';
require_once __DIR__ . '/app/models/Notification.php';

// Load controllers
require_once __DIR__ . '/app/controllers/Router.php';

// Initialize router and run
$router = new Router();
$router->run();
