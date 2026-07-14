<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link active">Matches</a>
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
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link active">Matches</a>
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
    <div style="text-align: center; padding: 3rem 1.5rem 1rem;">
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Fixtures</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-top: 0.5rem; color: #E5E5E5; text-transform: uppercase;">Today's Matches</h1>
        <div style="width: 60px; height: 2px; background: var(--gold); margin: 1rem auto 0;"></div>
    </div>

    <div style="max-width: 800px; margin: 0 auto; padding: 1rem 1.5rem 4rem;">
        <?php if (!empty($todayMatches)): ?>
            <?php foreach ($todayMatches as $match): ?>
                <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--glass-border)'">

                    <!-- Match Info -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--glass-border);">
                        <span class="badge badge-warning"><?= htmlspecialchars($match['stage'] ?? 'Today') ?></span>
                        <span style="color: var(--gray); font-size: 0.85rem;"><?= formatMatchDate($match['match_date']) ?></span>
                    </div>

                    <!-- Teams -->
                    <div style="display: flex; align-items: center; justify-content: center; gap: 2rem; margin-bottom: 1rem;">
                        <div style="text-align: center; flex: 1;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--black-elevated); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-family: var(--font-display); color: var(--gold); font-size: 1.2rem;">
                                <?= htmlspecialchars($match['home_short_name'] ?? $match['home_team_name'][0]) ?>
                            </div>
                            <div style="color: #E5E5E5; font-size: 0.95rem; font-weight: 500;"><?= htmlspecialchars($match['home_team_name']) ?></div>
                        </div>

                        <div style="text-align: center;">
                            <?php if ($match['status'] === 'completed'): ?>
                                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold);">
                                    <?= $match['home_score'] ?> - <?= $match['away_score'] ?>
                                </div>
                            <?php else: ?>
                                <div style="font-family: var(--font-display); font-size: 2rem; color: var(--gray);">VS</div>
                            <?php endif; ?>
                        </div>

                        <div style="text-align: center; flex: 1;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--black-elevated); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-family: var(--font-display); color: var(--gold); font-size: 1.2rem;">
                                <?= htmlspecialchars($match['away_short_name'] ?? $match['away_team_name'][0]) ?>
                            </div>
                            <div style="color: #E5E5E5; font-size: 0.95rem; font-weight: 500;"><?= htmlspecialchars($match['away_team_name']) ?></div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div style="text-align: center;">
                        <?php if (isLoggedIn()): ?>
                            <a href="<?= BASE_URL ?>/match/<?= $match['id'] ?>" class="btn tbc-btn-primary" style="padding: 10px 28px; font-size: 0.9rem; border-radius: 8px;">Make Prediction</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login" class="btn tbc-btn-primary" style="padding: 10px 28px; font-size: 0.9rem; border-radius: 8px;">Login to Predict</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: var(--gray); padding: 4rem 2rem; background: var(--black-card); border-radius: 12px; border: 1px solid var(--glass-border);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                <p>No matches scheduled for today.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
