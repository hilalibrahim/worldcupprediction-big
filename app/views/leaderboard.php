<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link active">Leaderboard</a>
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
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link active">Leaderboard</a>
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
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Rankings</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-top: 0.5rem; color: #E5E5E5; text-transform: uppercase;">Global Leaderboard</h1>
        <div style="width: 60px; height: 2px; background: var(--gold); margin: 1rem auto 0;"></div>
    </div>

    <div style="max-width: 900px; margin: 0 auto; padding: 1rem 1.5rem 4rem;">

        <?php if (!empty($leaderboard)): ?>

            <!-- Top 3 Podium -->
            <?php if (count($leaderboard) >= 3): ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                <?php foreach (array_slice($leaderboard, 0, 3) as $i => $top): ?>
                <div style="background: var(--black-card); border: 1px solid <?= $i === 0 ? 'var(--gold)' : 'var(--glass-border)' ?>; border-radius: 12px; padding: 1.5rem; text-align: center; <?= $i === 0 ? 'box-shadow: 0 0 20px rgba(255,142,60,0.15);' : '' ?>">
                    <div style="font-family: var(--font-display); font-size: 2rem; color: <?= $i === 0 ? 'var(--gold)' : ($i === 1 ? '#c0c0c0' : '#cd7f32') ?>; margin-bottom: 0.5rem;">
                        <?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : '🥉') ?>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--black-elevated); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-family: var(--font-display); color: var(--gold); font-size: 1.2rem;">
                        <?= substr($top['username'], 0, 1) ?>
                    </div>
                    <div style="color: #E5E5E5; font-weight: 600; margin-bottom: 0.25rem;"><?= htmlspecialchars($top['username']) ?></div>
                    <div style="font-family: var(--font-display); font-size: 1.5rem; color: var(--gold);"><?= $top['total_points'] ?? $top['points'] ?> pts</div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Full Table -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Rank</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Player</th>
                            <th style="padding: 1rem; text-align: right; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $index => $user): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 0.85rem 1rem; color: <?= $index < 3 ? 'var(--gold)' : 'var(--gray)' ?>; font-weight: <?= $index < 3 ? '700' : '400' ?>;">
                                    <?= $index + 1 ?>
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--black-elevated); border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); color: var(--gold); font-size: 0.85rem; flex-shrink: 0;">
                                            <?= substr($user['username'], 0, 1) ?>
                                        </div>
                                        <span style="color: #D9D9D9;"><?= htmlspecialchars($user['username']) ?></span>
                                    </div>
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: right; color: var(--gold); font-weight: 600; font-family: var(--font-display); font-size: 1.1rem;">
                                    <?= $user['total_points'] ?? $user['points'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
                    <div>
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">← Previous</a>
                        <?php else: ?>
                            <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">← Previous</span>
                        <?php endif; ?>
                    </div>
                    
                    <div style="color: var(--gray); font-size: 0.9rem;">
                        Page <span style="color: var(--gold); font-weight: bold;"><?= $currentPage ?></span> of <?= $totalPages ?>
                    </div>
                    
                    <div>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Next →</a>
                        <?php else: ?>
                            <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">Next →</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div style="text-align: center; color: var(--gray); padding: 4rem 2rem; background: var(--black-card); border-radius: 12px; border: 1px solid var(--glass-border);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏆</div>
                <p>No predictions yet. Be the first to make a prediction!</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
