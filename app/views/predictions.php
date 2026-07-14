<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Predictions - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        .prediction-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .prediction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 59, 119, 0.15);
        }

        .prediction-card.correct {
            border-left: 4px solid #22c55e;
        }

        .prediction-card.incorrect {
            border-left: 4px solid #ef4444;
        }

        .prediction-card.pending {
            border-left: 4px solid #f59e0b;
        }

        .prediction-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .prediction-match {
            flex: 1;
            min-width: 200px;
        }

        .prediction-match h3 {
            margin: 0;
            color: var(--text-light);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .match-date {
            color: var(--text-gray);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .match-stage {
            color: var(--text-gray);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        .prediction-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-correct {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .status-incorrect {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .prediction-details {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .detail-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-label {
            color: var(--text-gray);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: var(--text-light);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .score-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .score-display .vs {
            color: var(--text-gray);
            font-size: 1rem;
        }

        .points-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent-color), #1e3a8a);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(37, 59, 119, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-gray);
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border: 1px solid var(--glass-border);
            background: transparent;
            color: var(--text-gray);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .filter-tab:hover {
            color: var(--text-light);
            border-color: var(--accent-color);
        }

        .filter-tab.active {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        @media (max-width: 768px) {
            .prediction-details {
                grid-template-columns: 1fr;
            }

            .prediction-header {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
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

    <div class="container" style="max-width: 1000px; padding: 2rem;">
        <div style="margin-bottom: 3rem;">
            <h1 style="color: var(--text-light); margin: 0 0 0.5rem 0;">My Predictions</h1>
            <p style="color: var(--text-gray); margin: 0;">Track all your World Cup predictions and performance</p>
        </div>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo count($predictions); ?></div>
                <div class="stat-label">Total Predictions</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['count'] ?? 0; ?></div>
                <div class="stat-label">Correct Predictions</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['total_points'] ?? 0; ?></div>
                <div class="stat-label">Total Points</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterPredictions('all')">All</button>
            <button class="filter-tab" onclick="filterPredictions('correct')">Correct</button>
            <button class="filter-tab" onclick="filterPredictions('incorrect')">Incorrect</button>
            <button class="filter-tab" onclick="filterPredictions('pending')">Pending</button>
        </div>

        <!-- Predictions List -->
        <div id="predictions-container">
            <?php if (empty($predictions)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <h3 style="color: var(--text-light);">No Predictions Yet</h3>
                    <p>Start making predictions to see them here!</p>
                    <a href="<?= BASE_URL ?>/daily-matches" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">View Matches</a>
                </div>
            <?php else: ?>
                <?php foreach ($predictions as $pred): ?>
                    <div class="prediction-card <?php 
                        if ($pred['status'] === 'completed') {
                            echo $pred['is_correct'] ? 'correct' : 'incorrect';
                        } else {
                            echo 'pending';
                        }
                    ?>" data-filter="<?php 
                        if ($pred['status'] === 'completed') {
                            echo $pred['is_correct'] ? 'correct' : 'incorrect';
                        } else {
                            echo 'pending';
                        }
                    ?>">
                        <div class="prediction-header">
                            <div class="prediction-match">
                                <h3>
                                    <?php echo htmlspecialchars($pred['home_team_name']); ?>
                                    <span style="color: var(--text-gray);">vs</span>
                                    <?php echo htmlspecialchars($pred['away_team_name']); ?>
                                </h3>
                                <div class="match-date">
                                    <?php echo formatMatchDate($pred['match_date']); ?>
                                </div>
                                <div class="match-stage">
                                    <?php echo isset($pred['stage']) ? htmlspecialchars($pred['stage']) : 'Group Stage'; ?>
                                </div>
                            </div>
                            <div class="prediction-status">
                                <div class="status-badge <?php 
                                    if ($pred['status'] === 'completed') {
                                        echo $pred['is_correct'] ? 'status-correct' : 'status-incorrect';
                                    } else {
                                        echo 'status-pending';
                                    }
                                ?>">
                                    <?php 
                                        if ($pred['status'] === 'completed') {
                                            echo $pred['is_correct'] ? '✓ Correct' : '✗ Incorrect';
                                        } else {
                                            echo '⏱ Pending';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="prediction-details">
                            <div class="detail-section">
                                <div class="detail-label">Your Prediction</div>
                                <div class="score-display">
                                    <span><?php echo $pred['home_score']; ?></span>
                                    <span class="vs">-</span>
                                    <span><?php echo $pred['away_score']; ?></span>
                                </div>
                            </div>

                            <?php if ($pred['status'] === 'completed'): ?>
                                <div class="detail-section">
                                    <div class="detail-label">Actual Score</div>
                                    <div class="score-display" style="color: #22c55e;">
                                        <span><?php echo isset($pred['home_score_actual']) ? $pred['home_score_actual'] : '-'; ?></span>
                                        <span class="vs">-</span>
                                        <span><?php echo isset($pred['away_score_actual']) ? $pred['away_score_actual'] : '-'; ?></span>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <div class="detail-label">Points Earned</div>
                                    <span class="points-badge"><?php echo $pred['points']; ?> pts</span>
                                </div>
                            <?php else: ?>
                                <div class="detail-section">
                                    <div class="detail-label">Predicted Winner</div>
                                    <div class="detail-value" style="text-transform: capitalize;">
                                        <?php 
                                            $winner = $pred['predicted_winner'] ?? 'draw';
                                            if ($winner === 'home') {
                                                echo htmlspecialchars($pred['home_team_name']);
                                            } elseif ($winner === 'away') {
                                                echo htmlspecialchars($pred['away_team_name']);
                                            } else {
                                                echo 'Draw';
                                            }
                                        ?>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <div class="detail-label">Match Status</div>
                                    <span class="points-badge" style="background: linear-gradient(135deg, #f59e0b, #d97706);">Upcoming</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterPredictions(filter) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter cards
            const cards = document.querySelectorAll('.prediction-card');
            cards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-filter') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
