<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Matches - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches" class="active">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms">Rooms</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/worldcupprediction-big/profile">Profile</a>
                    <a href="/worldcupprediction-big/logout">Logout</a>
                <?php else: ?>
                    <a href="/worldcupprediction-big/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="page-hero">
        <h1>⚽ Today's Matches</h1>
        <p style="color: var(--text-gray);">Make your predictions before kickoff and climb the leaderboard</p>
    </div>

    <div class="container" style="max-width: 1000px; padding: 1rem 1.5rem 3rem;">
        <?php if (!empty($todayMatches)): ?>
            <?php foreach ($todayMatches as $match): ?>
                <div class="match-card">
                    <div class="match-header">
                        <span class="badge badge-warning">Today</span>
                        <span style="color: var(--text-gray); font-size: 0.875rem;">
                            <?php echo formatMatchDate($match['match_date']) ?>
                        </span>
                    </div>

                    <div class="match-scores">
                        <div class="match-team">
                            <div class="team-logo" style="color: var(--accent-color); font-size: 1.5rem;">
                                <?php echo htmlspecialchars($match['home_short_name'] ?? $match['home_team_name'][0]) ?>
                            </div>
                            <div><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                        </div>

                        <div class="score-display">
                            <span class="score">VS</span>
                        </div>

                        <div class="match-team">
                            <div class="team-logo" style="color: var(--accent-color); font-size: 1.5rem;">
                                <?php echo htmlspecialchars($match['away_short_name'] ?? $match['away_team_name'][0]) ?>
                            </div>
                            <div><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                        </div>
                    </div>

                    <?php if (isLoggedIn()): ?>
                        <div class="match-actions" style="margin-top: 1rem;">
                            <a href="/worldcupprediction-big/match/<?php echo $match['id'] ?>" 
                               class="btn btn-primary">Make Prediction</a>
                        </div>
                    <?php else: ?>
                        <div class="match-actions" style="margin-top: 1rem;">
                            <a href="/worldcupprediction-big/login" class="btn btn-primary">Login to Predict</a>
                        </div>
                    <?php endif; ?>

                    <?php if ($match['status'] === 'completed'): ?>
                        <div class="match-scores" style="margin-top: 1rem;">
                            <span style="color: var(--accent-color); font-size: 3rem; font-weight: bold;">
                                <?php echo $match['home_score'] ?>
                            </span>
                            <span>-</span>
                            <span style="color: var(--accent-color); font-size: 3rem; font-weight: bold;">
                                <?php echo $match['away_score'] ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: var(--text-gray); padding: 3rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                <p>No matches scheduled for today.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
    <script>
        // Initialize any daily match specific functionality
    </script>
</body>
</html>
