<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>
    <nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="World Cup Prediction 2026">
        </a>
        <div class="navbar-menu">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">Dashboard</a>
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
        <button class="mobile-menu-btn">☰</button>
        <div class="mobile-dropdown">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">Dashboard</a>
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

    <!-- Page Header -->
    <div style="padding: 2.5rem 5% 1rem; max-width: 1200px; margin: 0 auto;">
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Dashboard</span>
        <h1 style="font-size: 2.5rem; color: #E5E5E5; margin-top: 0.25rem;">Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'Player') ?></h1>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 1rem 5% 4rem;">

        <!-- Stats Row -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Points</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold);"><?= $stats['points'] ?? 0 ?></div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Global Rank</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: #E5E5E5;">#<?= $globalRank ?></div>
            </div>

            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Predictions</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: #E5E5E5;"><?= $stats['total_predictions'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Two-column layout -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

            <!-- Upcoming Matches -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.5rem;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Upcoming Matches</h3>
                <?php if (!empty($upcomingMatches)): ?>
                    <?php foreach ($upcomingMatches as $match): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 0; border-bottom: 1px solid var(--glass-border);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="color: #D9D9D9; font-size: 0.9rem;"><?= htmlspecialchars($match['home_team_name']) ?></span>
                                <span style="color: var(--gold); font-weight: 600; font-size: 0.8rem;">vs</span>
                                <span style="color: #D9D9D9; font-size: 0.9rem;"><?= htmlspecialchars($match['away_team_name']) ?></span>
                            </div>
                            <span style="color: var(--gray); font-size: 0.8rem;"><?= formatMatchDate($match['match_date']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--gray); font-size: 0.9rem;">No upcoming matches.</p>
                <?php endif; ?>
            </div>

            <!-- Top Predictors -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.5rem;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Top Predictors</h3>
                <?php foreach ($topPredictors as $i => $user): ?>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--glass-border);">
                        <span style="color: <?= $i < 3 ? 'var(--gold)' : 'var(--gray)' ?>; font-family: var(--font-display); font-size: 1.1rem; width: 24px;"><?= $i + 1 ?></span>
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--black-elevated); border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--gold); flex-shrink: 0;"><?= substr($user['username'], 0, 1) ?></div>
                        <span style="flex: 1; color: #D9D9D9; font-size: 0.9rem;"><?= htmlspecialchars($user['username']) ?></span>
                        <span style="color: var(--gold); font-weight: 600; font-size: 0.9rem;"><?= $user['points'] ?> pts</span>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Your Rooms -->
        <?php if (!empty($userRooms)): ?>
        <div style="margin-top: 1.5rem;">
            <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Your Rooms</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($userRooms as $room): ?>
                    <a href="<?= BASE_URL ?>/rooms/view/<?= $room['id'] ?>" style="text-decoration: none; background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.25rem; transition: all 0.3s; display: block;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                        <div style="color: #E5E5E5; font-weight: 600; margin-bottom: 0.35rem;"><?= htmlspecialchars($room['name']) ?></div>
                        <div style="color: var(--gray); font-size: 0.85rem;">
                            👥 <?= $room['member_count'] ?> members
                            <?php if (!$room['is_public']): ?> · 🔒 Private<?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
