<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard" class="active">Leaderboard</a>
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
        <h1>🏆 Global Leaderboard</h1>
        <p style="color: var(--text-gray);">The world's top football predictors</p>
    </div>

    <div class="container" style="max-width: 1000px; padding: 1rem 1.5rem 3rem;">
        <!-- Filter Tabs -->
        <div class="tabs" style="justify-content: center; margin-bottom: 2rem;">
            <a href="/worldcupprediction-big/leaderboard?period=overall" class="tab <?php echo $period === 'overall' ? 'active' : '' ?>">
                Overall
            </a>
            <a href="/worldcupprediction-big/leaderboard?period=weekly" class="tab <?php echo $period === 'weekly' ? 'active' : '' ?>">
                Weekly
            </a>
            <a href="/worldcupprediction-big/leaderboard?period=monthly" class="tab <?php echo $period === 'monthly' ? 'active' : '' ?>">
                Monthly
            </a>
        </div>

        <?php if (!empty($leaderboard)): ?>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Rank</th>
                        <th>User</th>
                        <th>Country</th>
                        <th style="text-align: right;">Points</th>
                        <th style="text-align: right;">Accuracy</th>
                        <th style="text-align: right;">Predictions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $index => $user): ?>
                        <tr>
                            <td class="rank-<?php echo $index + 1 ?>">
                                <?php echo $index + 1 ?>
                                <?php if ($index === 0): ?> 🥇 <?php endif; ?>
                                <?php if ($index === 1): ?> 🥈 <?php endif; ?>
                                <?php if ($index === 2): ?> 🥉 <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div class="team-logo" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                        <?php echo substr($user['username'], 0, 1) ?>
                                    </div>
                                    <?php echo htmlspecialchars($user['username']) ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['country']) ?></td>
                            <td style="text-align: right; color: var(--accent-color); font-weight: bold;">
                                <?php echo $user['total_points'] ?? $user['points'] ?> pts
                            </td>
                            <td style="text-align: right;">
                                <?php echo $user['accuracy'] ?? '0' ?>%
                            </td>
                            <td style="text-align: right;">
                                <?php echo $user['predictions'] ?? 0 ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; color: var(--text-gray); padding: 3rem;">
                <p>No predictions yet. Be the first to make a prediction!</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
