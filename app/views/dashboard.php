<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><span>⚽</span> PredictCup</a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard" class="active">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms">Rooms</a>
                <a href="/worldcupprediction-big/profile">Profile</a>
                <a href="/worldcupprediction-big/logout">Logout</a>
            </div>
            <div class="user-menu">
                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                <img src="/worldcupprediction-big/public/images/avatars/user.png" class="avatar" alt="Avatar">
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1400px; padding: 2rem;">
        <!-- User Summary -->
        <div class="dashboard-grid" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <h3>Total Points</h3>
                <div class="stat-value" style="color: var(--accent-color);"><?php echo $stats['points'] ?? 0 ?></div>
                <div class="stat-label">Your current score</div>
            </div>
            <div class="stat-card">
                <h3>Global Rank</h3>
                <div class="stat-value" style="color: var(--accent-color);">#{php echo $globalRank ?></div>
                <div class="stat-label">Rank among all users</div>
            </div>
            <div class="stat-card">
                <h3>Joined Rooms</h3>
                <div class="stat-value"><?php echo count($userRooms) ?></div>
                <div class="stat-label">Active room memberships</div>
            </div>
            <div class="stat-card">
                <h3>Accuracy</h3>
                <div class="stat-value" style="color: var(--success);"><?php echo calculateUserAccuracy($userId) ?>%</div>
                <div class="stat-label">Prediction accuracy</div>
            </div>
            <div class="stat-card">
                <h3>Predictions Made</h3>
                <div class="stat-value"><?php echo $stats['total_predictions'] ?? 0 ?></div>
                <div class="stat-label">Total predictions</div>
            </div>
            <div class="stat-card">
                <h3>Current Streak</h3>
                <div class="stat-value" style="color: var(--accent-color);">0</div>
                <div class="stat-label">Correct predictions in a row</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Today's Matches -->
            <div class="stat-card">
                <h3>Today's Matches</h3>
                <?php if (!empty($todayMatches)): ?>
                    <?php foreach ($todayMatches as $match): ?>
                        <div class="match-card">
                            <div class="match-team">
                                <div class="team-logo"><?php echo substr($match['home_team_name'], 0, 1) ?></div>
                                <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                            </div>
                            <div class="match-details">
                                <div class="match-time"><?php echo formatMatchDate($match['match_date']) ?></div>
                                <div class="match-stadium"><?php echo htmlspecialchars($match['stadium'] ?? '') ?></div>
                            </div>
                            <div class="match-team">
                                <div class="team-logo"><?php echo substr($match['away_team_name'], 0, 1) ?></div>
                                <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray">No matches scheduled for today.</p>
                <?php endif; ?>
            </div>

            <!-- Upcoming Matches -->
            <div class="stat-card">
                <h3>Upcoming Matches</h3>
                <?php if (!empty($upcomingMatches)): ?>
                    <?php foreach ($upcomingMatches as $match): ?>
                        <div class="match-card">
                            <div class="match-team">
                                <div class="team-logo"><?php echo substr($match['home_team_name'], 0, 1) ?></div>
                                <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                            </div>
                            <div class="match-details">
                                <div class="match-time"><?php echo formatMatchDate($match['match_date']) ?></div>
                                <div class="match-stadium"><?php echo htmlspecialchars($match['stadium'] ?? '') ?></div>
                            </div>
                            <div class="match-team">
                                <div class="team-logo"><?php echo substr($match['away_team_name'], 0, 1) ?></div>
                                <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray">No upcoming matches.</p>
                <?php endif; ?>
            </div>

            <!-- Top Predictors -->
            <div class="stat-card">
                <h3>Top Predictors</h3>
                <?php foreach ($topPredictors as $user): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div class="team-logo" style="width: 32px; height: 32px; font-size: 0.875rem;">
                            <?php echo substr($user['username'], 0, 1) ?>
                        </div>
                        <div style="flex: 1;">
                            <strong><?php echo htmlspecialchars($user['username']) ?></strong>
                            <div style="color: var(--text-gray); font-size: 0.875rem;"><?php echo $user['country'] ?? '' ?></div>
                        </div>
                        <div style="color: var(--accent-color); font-weight: bold;">
                            <?php echo $user['points'] ?> pts
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Joined Rooms -->
            <div class="stat-card">
                <h3>Your Rooms</h3>
                <?php if (!empty($userRooms)): ?>
                    <?php foreach ($userRooms as $room): ?>
                        <a href="/worldcupprediction-big/rooms/view/<?php echo $room['id'] ?>" 
                           class="room-card" style="display: block; text-decoration: none;">
                            <div style="font-weight: bold; color: var(--text-light); margin-bottom: 0.5rem;">
                                <?php echo htmlspecialchars($room['name']) ?>
                            </div>
                            <div style="color: var(--text-gray); font-size: 0.875rem;">
                                <span class="icon">👥</span>
                                <span><?php echo $room['member_count'] ?> members</span>
                                <?php if (!$room['is_public']): ?>
                                    <span class="icon" style="margin-left: 1rem;">🔒</span>
                                    <span>Private</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray">You haven't joined any rooms yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
