<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Management - PredictCup Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        .api-status-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-good { background-color: var(--success); }
        .status-warning { background-color: var(--accent-color); }
        .status-bad { background-color: var(--error); }
        
        .api-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .api-result {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .config-instructions {
            background: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--accent-color);
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>
    <nav class="navbar">
    <div class="container">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="World Cup Prediction 2026">
        </a>

        <!-- Desktop Navigation -->
        <div class="navbar-menu">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link">Profile</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link" style="color: #ff5d5d;">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Login</a>
                <a href="<?= BASE_URL ?>/register" class="nav-cta">Register Free</a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn">
            ☰
        </button>

        <!-- Mobile Navigation Dropdown -->
        <div class="mobile-dropdown">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link">Profile</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link" style="color: #ff5d5d;">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Login</a>
                <a href="<?= BASE_URL ?>/register" class="nav-cta">Register Free</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

    <div class="container" style="padding: 2rem;">
        <h1 style="color: var(--text-light); margin-bottom: 2rem;">Football-Data.org API Management</h1>
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'success'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
        
        <!-- API Status -->
        <div class="api-status-card">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">API Status</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div>
                    <strong>API Key:</strong>
                    <span class="<?php echo $apiStatus['key_configured'] ? 'status-good' : 'status-bad'; ?> status-indicator"></span>
                    <?php echo $apiStatus['key_configured'] ? 'Configured' : 'Not Configured'; ?>
                </div>
                
                <div>
                    <strong>API URL:</strong> <?php echo $apiStatus['api_url']; ?>
                </div>
                
                <div>
                    <strong>Competition:</strong> <?php echo $apiStatus['competition']; ?> (World Cup)
                </div>
                
                <div>
                    <strong>Update Interval:</strong> <?php echo floor($apiStatus['update_interval'] / 3600); ?> hours
                </div>
                
                <div>
                    <strong>Last Sync:</strong> <?php echo $apiStatus['last_sync']; ?>
                </div>
            </div>
            
            <?php if (!$apiStatus['key_configured']): ?>
                <div class="config-instructions">
                    <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">API Key Required</h3>
                    <p>To use Football-Data.org API, you need to:</p>
                    <ol style="margin-left: 1.5rem;">
                        <li>Get a free API key from <a href="https://www.football-data.org/" target="_blank" style="color: var(--accent-color);">football-data.org</a></li>
                        <li>Edit <code>config/config.php</code> file</li>
                        <li>Set your API key: <code>define('FOOTBALL_DATA_API_KEY', 'your-key-here');</code></li>
                    </ol>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- API Actions -->
        <div class="api-status-card">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">API Actions</h2>
            
            <form method="POST" action="">
                <div class="api-actions">
                    <button type="submit" name="action" value="update_schema" class="btn btn-secondary">
                        Update Database Schema
                    </button>
                    
                    <button type="submit" name="action" value="fetch_matches" class="btn btn-primary" 
                        <?php echo !$apiStatus['key_configured'] ? 'disabled' : ''; ?>>
                        Fetch Matches from API
                    </button>
                    
                    <button type="submit" name="action" value="auto_update" class="btn btn-secondary"
                        <?php echo !$apiStatus['key_configured'] ? 'disabled' : ''; ?>>
                        Auto-Update Matches
                    </button>
                    
                    <button type="submit" name="action" value="update_key" class="btn btn-secondary">
                        Update API Key
                    </button>
                </div>
            </form>
            
            <?php if (!$apiStatus['key_configured']): ?>
                <p style="color: var(--accent-color); margin-top: 1rem;">
                    ⚠️ API actions disabled until API key is configured
                </p>
            <?php endif; ?>
        </div>
        
        <!-- API Results -->
        <?php if ($apiResult): ?>
            <div class="api-result">
                <h3 style="color: var(--text-light); margin-bottom: 1rem;">API Result</h3>
                
                <?php if ($apiResult['success']): ?>
                    <div style="color: var(--success); margin-bottom: 1rem;">
                        ✓ Operation completed successfully
                    </div>
                    
                    <?php if (isset($apiResult['imported'])): ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div>
                                <strong>Imported:</strong> <?php echo $apiResult['imported']; ?> matches
                            </div>
                            <div>
                                <strong>Updated:</strong> <?php echo $apiResult['updated']; ?> matches
                            </div>
                            <div>
                                <strong>Total in API:</strong> <?php echo $apiResult['total']; ?> matches
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($apiResult['skipped']) && $apiResult['skipped']): ?>
                        <div style="color: var(--accent-color); margin-top: 1rem;">
                            ⏸️ Update skipped - Last sync was within update interval
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div style="color: var(--error);">
                        ✗ Operation failed: <?php echo $apiResult['message']; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- API Documentation -->
        <div class="api-status-card">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">How It Works</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div>
                    <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">Automatic Updates</h3>
                    <p>The system can automatically fetch World Cup matches from Football-Data.org API.</p>
                    <p>When enabled, it will:</p>
                    <ul style="margin-left: 1.5rem;">
                        <li>Fetch upcoming matches</li>
                        <li>Update match results automatically</li>
                        <li>Calculate user points for completed matches</li>
                        <li>Sync every <?php echo floor(FOOTBALL_DATA_UPDATE_INTERVAL / 3600); ?> hours</li>
                    </ul>
                </div>
                
                <div>
                    <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">Setup Cron Job (Optional)</h3>
                    <p>For automatic updates, add this to your crontab:</p>
                    <pre style="background: rgba(0,0,0,0.5); padding: 1rem; border-radius: 0.5rem;">
# Update matches every hour
0 * * * * php /path/to/worldcupprediction-big/cron-update-matches.php</pre>
                    
                    <p>Or create a scheduled task on Windows to run:</p>
                    <pre style="background: rgba(0,0,0,0.5); padding: 1rem; border-radius: 0.5rem;">
php C:\xampp\htdocs\worldcupprediction-big\cron-update-matches.php</pre>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>