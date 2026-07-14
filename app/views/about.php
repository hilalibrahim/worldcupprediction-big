<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
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

    <div style="padding: 2.5rem 5% 1rem; text-align: center; max-width: 900px; margin: 0 auto;">
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">PredictCup</span>
        <h1 style="font-size: 2.5rem; color: #E5E5E5; margin-top: 0.25rem;">About The Platform</h1>
        <div style="width: 60px; height: 2px; background: var(--gold); margin: 1rem auto 0;"></div>
    </div>

    <div style="max-width: 900px; margin: 0 auto; padding: 1rem 5% 4rem;">
        
        <div style="background: var(--black-card); padding: 2rem; border-radius: 12px; border: 1px solid var(--glass-border); margin-bottom: 2rem;">
            <h2 style="color: var(--gold); margin-bottom: 1rem; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Welcome to PredictCup!</h2>
            <p style="color: #D9D9D9; line-height: 1.7; margin-bottom: 1rem; font-size: 1.05rem;">
                PredictCup is the ultimate football prediction platform where fans can test their knowledge, 
                compete with friends, and earn points for correct predictions.
            </p>
            <p style="color: #D9D9D9; line-height: 1.7; font-size: 1.05rem;">
                Join prediction rooms, challenge your friends, and climb the global leaderboard to prove 
                you're the ultimate football expert!
            </p>
        </div>

        <div style="background: var(--black-card); padding: 2rem; border-radius: 12px; border: 1px solid var(--glass-border); margin-bottom: 2rem;">
            <h2 style="color: var(--gold); margin-bottom: 1.5rem; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">How It Works</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: var(--black-elevated); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold); margin-bottom: 0.5rem; opacity: 0.5;">01</div>
                    <h3 style="color: #E5E5E5; font-size: 1.1rem; margin-bottom: 0.5rem;">Register</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.5;">Create your account and start predicting</p>
                </div>
                <div style="background: var(--black-elevated); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold); margin-bottom: 0.5rem; opacity: 0.5;">02</div>
                    <h3 style="color: #E5E5E5; font-size: 1.1rem; margin-bottom: 0.5rem;">Join Rooms</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.5;">Create or join prediction rooms with friends</p>
                </div>
                <div style="background: var(--black-elevated); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold); margin-bottom: 0.5rem; opacity: 0.5;">03</div>
                    <h3 style="color: #E5E5E5; font-size: 1.1rem; margin-bottom: 0.5rem;">Predict</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.5;">Make predictions for football matches</p>
                </div>
                <div style="background: var(--black-elevated); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold); margin-bottom: 0.5rem; opacity: 0.5;">04</div>
                    <h3 style="color: #E5E5E5; font-size: 1.1rem; margin-bottom: 0.5rem;">Earn Points</h3>
                    <p style="color: var(--gray); font-size: 0.85rem; line-height: 1.5;">Earn points and climb the leaderboard</p>
                </div>
            </div>
        </div>

        <div style="background: var(--black-card); padding: 2rem; border-radius: 12px; border: 1px solid var(--glass-border);">
            <h2 style="color: var(--gold); margin-bottom: 1.5rem; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Features</h2>
            <ul style="list-style: none; color: #D9D9D9; padding: 0;">
                <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Real-time match predictions</li>
                <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Private and public prediction rooms</li>
                <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Global and room-specific leaderboards</li>
                <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Achievement badges for milestones</li>
                <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Automatic points calculation</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><span style="color: var(--gold);">✓</span> Admin panel for match management</li>
            </ul>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
