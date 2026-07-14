<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body data-flash-type="<?php echo $_SESSION['flash_type'] ?? '' ?>" data-flash-message="<?php echo $_SESSION['flash_message'] ?? '' ?>">
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>
    <!-- Navigation -->
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

    <!-- Hero Section Revamp (TBC Dark Vibe) -->
<section class="hero tbc-hero">
    <div class="hero-container" style="max-width: 1000px;">
        
        <div class="hero-content" style="text-align: center; margin: 0 auto; z-index: 10;">
            
            <span class="eyebrow" style="display: inline-block; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em; color: var(--gold); margin-bottom: 1.5rem;">
                The Final Three Matches
            </span>

            <h1 style="color: #E5E5E5; font-size: clamp(3.5rem, 6vw, 6.5rem); font-weight: normal; line-height: 1.05; margin-bottom: 1.5rem; text-transform: uppercase;">
                Predict. Win. Dominate.
            </h1>

            <p style="font-size: 1.25rem; color: #9C9C9C; font-weight: 200; max-width: 650px; margin: 0 auto 2.5rem; line-height: 1.7;">
                The Semi-Finals are set. The stakes have never been higher. 
                Lock in your exact scores before kickoff and architect your victory.
            </p>

            <div class="hero-buttons" style="justify-content: center; display: flex; gap: 1rem; align-items: center;">
                <?php if (!isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/register" class="btn tbc-btn-primary">
                        START PREDICTING
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/daily-matches" class="btn tbc-btn-primary">
                        PREDICT SEMI-FINALS
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/leaderboard" class="btn tbc-btn-secondary">
                    Explore Leaderboard
                </a>
            </div>
        </div>
        
        <!-- Subtle Glow Background -->
        <div style="position: absolute; left: 50%; top: 0; height: 400px; width: 400px; transform: translateX(-50%); border-radius: 50%; background: rgba(255,107,53,0.06); filter: blur(140px); z-index: 1; pointer-events: none;"></div>

    </div>
</section>

    <!-- Stats Section -->
<section class="stats" style="padding: 5rem 5%; border-bottom: 1px solid var(--glass-border);">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Live Statistics</span>
            <h2 style="font-size: clamp(2rem, 4vw, 3rem); margin-top: 0.75rem; color: #E5E5E5;">The Numbers Don't Lie</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 2rem; text-align: center;">
                <div id="totalUsers" style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); margin-bottom: 0.25rem;">
                    <span class="stat-loader">⏳</span>
                </div>
                <div style="color: #D9D9D9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Active Players</div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 2rem; text-align: center;">
                <div id="totalRooms" style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); margin-bottom: 0.25rem;">
                    <span class="stat-loader">⏳</span>
                </div>
                <div style="color: #D9D9D9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Rooms</div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 2rem; text-align: center;">
                <div id="totalPredictions" style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); margin-bottom: 0.25rem;">
                    <span class="stat-loader">⏳</span>
                </div>
                <div style="color: #D9D9D9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Predictions</div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 2rem; text-align: center;">
                <div id="matchesPlayed" style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); margin-bottom: 0.25rem;">
                    <span class="stat-loader">⏳</span>
                </div>
                <div style="color: #D9D9D9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Matches Played</div>
            </div>
        </div>
    </div>
</section>

    <!-- Features Section -->
<section style="padding: 5rem 5%;">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">How It Works</span>
            <h2 style="font-size: clamp(2rem, 4vw, 3rem); margin-top: 0.75rem; color: #E5E5E5;">Your World Cup Journey</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; transition: all 0.3s; position: relative;">
                <div style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); opacity: 0.3; margin-bottom: 1rem;">01</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.75rem; color: #E5E5E5;">Match Predictions</h3>
                <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.7;">Predict exact scores before kickoff. The more accurate, the more points you earn.</p>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; transition: all 0.3s;">
                <div style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); opacity: 0.3; margin-bottom: 1rem;">02</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.75rem; color: #E5E5E5;">Private Rooms</h3>
                <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.7;">Create rooms and invite friends, family, or colleagues to compete together.</p>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; transition: all 0.3s;">
                <div style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); opacity: 0.3; margin-bottom: 1rem;">03</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.75rem; color: #E5E5E5;">Leaderboard</h3>
                <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.7;">Watch your position rise after every match and compete for the top spot.</p>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; transition: all 0.3s;">
                <div style="font-family: var(--font-display); font-size: 3rem; color: var(--gold); opacity: 0.3; margin-bottom: 1rem;">04</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.75rem; color: #E5E5E5;">Achievements</h3>
                <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.7;">Unlock badges for winning streaks and exact score predictions.</p>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>PredictCup</h4>
                    <p>The ultimate football prediction platform.</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <a href="/">Home</a><br>
                    <a href="<?= BASE_URL ?>/leaderboard">Leaderboard</a><br>
                    <a href="<?= BASE_URL ?>/rooms">Rooms</a>
                </div>
                <div>
                    <h4>Support</h4>
                    <a href="<?= BASE_URL ?>/about">About</a><br>
                    <a href="<?= BASE_URL ?>/contact">Contact</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 PredictCup. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
    <script>
        // Animate number counting
        function animateCounter(element, target) {
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16);
            let current = 0;

            const counter = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(counter);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Load stats from API
            fetch('/worldcupprediction-big/api/stats')
                .then(res => res.json())
                .then(data => {
                    // Animate each stat with a delay
                    setTimeout(() => {
                        animateCounter(document.getElementById('totalUsers'), parseInt(data.total_users) || 0);
                    }, 200);
                    
                    setTimeout(() => {
                        animateCounter(document.getElementById('totalRooms'), parseInt(data.total_rooms) || 0);
                    }, 400);
                    
                    setTimeout(() => {
                        animateCounter(document.getElementById('totalPredictions'), parseInt(data.total_predictions) || 0);
                    }, 600);
                    
                    setTimeout(() => {
                        animateCounter(document.getElementById('matchesPlayed'), parseInt(data.matches_played) || 0);
                    }, 800);
                })
                .catch(err => {
                    console.error('Failed to load stats:', err);
                    // Set default values on error
                    document.getElementById('totalUsers').textContent = '0';
                    document.getElementById('totalRooms').textContent = '0';
                    document.getElementById('totalPredictions').textContent = '0';
                    document.getElementById('matchesPlayed').textContent = '0';
                });
        });
    </script>
</body>
</html>
