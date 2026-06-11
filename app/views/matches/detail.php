<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><span>⚽</span> PredictCup</a>
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

    <div class="container" style="max-width: 1000px; padding: 2rem;">
        <!-- Match Header -->
        <div class="match-card" style="margin-bottom: 2rem;">
            <div class="match-header">
                <span class="badge badge-warning"><?php echo htmlspecialchars($match['stage'] ?? 'Match') ?></span>
                <span style="color: var(--text-gray); font-size: 0.875rem;">
                    <?php echo formatMatchDate($match['match_date']) ?>
                </span>
            </div>

            <div class="match-scores">
                <div class="match-team">
                    <div class="team-logo" style="font-size: 2rem; background: var(--accent-color); color: var(--bg-dark);">
                        <?php echo htmlspecialchars($match['home_short_name'] ?? $match['home_team_name'][0]) ?>
                    </div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                </div>

                <div class="score-display">
                    <?php if ($match['status'] === 'completed'): ?>
                        <div style="font-size: 3rem; font-weight: bold; color: var(--accent-color);">
                            <?php echo $match['home_score'] ?> - <?php echo $match['away_score'] ?>
                        </div>
                    <?php else: ?>
                        <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-gray);">VS</div>
                    <?php endif; ?>
                </div>

                <div class="match-team">
                    <div class="team-logo" style="font-size: 2rem; background: var(--accent-color); color: var(--bg-dark);">
                        <?php echo htmlspecialchars($match['away_short_name'] ?? $match['away_team_name'][0]) ?>
                    </div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                </div>
            </div>

            <?php if ($match['stadium']): ?>
                <div style="text-align: center; color: var(--text-gray); margin-top: 1rem;">
                    <span class="icon">🏟️</span>
                    <?php echo htmlspecialchars($match['stadium']) ?>
                </div>
            <?php endif; ?>

            <?php if (isLoggedIn() && $match['status'] !== 'completed'): ?>
                <div class="match-actions" style="margin-top: 2rem;">
                    <form method="POST" action="/worldcupprediction-big/predict" id="predictForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken() ?>">
                        <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                        
                        <div style="display: flex; align-items: center; gap: 1rem; justify-content: center; margin-bottom: 1rem;">
                            <input type="number" name="home_score" class="score-input" min="0" max="99" value="0" required>
                            <span style="color: var(--text-light);">-</span>
                            <input type="number" name="away_score" class="score-input" min="0" max="99" value="0" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Prediction</button>
                    </form>
                </div>

                <?php if ($myPrediction): ?>
                    <div style="text-align: center; margin-top: 1rem; padding: 1rem; background: rgba(16, 185, 129, 0.2); border-radius: 0.5rem;">
                        <span style="color: var(--success);">✓</span>
                        Your prediction: <?php echo $myPrediction['home_score'] ?> - <?php echo $myPrediction['away_score'] ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Predictions Leaderboard -->
        <?php if ($match['status'] === 'completed' && !empty($predictions)): ?>
            <h3 style="color: var(--text-light); margin-bottom: 1rem;">Top Predictors</h3>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>
                        <th>Country</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($predictions as $index => $pred): ?>
                        <tr>
                            <td><?php echo $index + 1 ?></td>
                            <td><?php echo htmlspecialchars($pred['username']) ?></td>
                            <td><?php echo htmlspecialchars($pred['country']) ?></td>
                            <td style="color: var(--accent-color); font-weight: bold;">
                                <?php echo $pred['points'] ?> pts
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
