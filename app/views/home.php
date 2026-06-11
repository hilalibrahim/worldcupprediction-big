<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body data-flash-type="<?php echo $_SESSION['flash_type'] ?? '' ?>" data-flash-message="<?php echo $_SESSION['flash_message'] ?? '' ?>">
    <!-- Navigation -->
<nav class="navbar">
    <div class="container">

        <a href="/worldcupprediction-big" class="navbar-brand">
            <img src="/worldcupprediction-big/public/uploads/logo.png" alt="World Cup Prediction 2026">
            <div class="brand-text">
                <span class="brand-name">PredictCup</span>
                <span class="brand-subtitle">World Cup 2026</span>
            </div>
        </a>

        <div class="navbar-menu">

            <a href="/worldcupprediction-big" class="active">Home</a>
            <a href="/worldcupprediction-big/dashboard">Dashboard</a>
            <a href="/worldcupprediction-big/daily-matches">Matches</a>
            <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
            <a href="/worldcupprediction-big/rooms">Rooms</a>

            <?php if (isLoggedIn()): ?>

                <span class="user-badge">
                    👋 <?php echo htmlspecialchars($_SESSION['username'] ?? '') ?>
                </span>

                <a href="/worldcupprediction-big/profile">Profile</a>

                <a href="/worldcupprediction-big/logout" class="logout-btn">
                    Logout
                </a>

            <?php else: ?>

                <a href="/worldcupprediction-big/login">
                    Login
                </a>

                <a href="/worldcupprediction-big/register" class="register-btn">
                    Register Free
                </a>

            <?php endif; ?>

        </div>

    </div>
</nav>

    <!-- Hero Section -->
<section class="hero">
    <div class="hero-container">

        <div class="hero-content">

            <div class="hero-brand">
                <img src="/worldcupprediction-big/public/uploads/logo.png" alt="World Cup Prediction 2026 Logo">
                <span>World Cup Prediction 2026</span>
            </div>

            <h1>Predict Every Match.<br>Win Every Room.</h1>

            <p>
                Challenge friends, family, and football fans worldwide.
                Make predictions, earn points, and climb the leaderboard
                during the biggest football event on Earth.
            </p>

            <div class="hero-buttons">
                <a href="/worldcupprediction-big/register" class="btn btn-primary">
                    Register Now
                </a>

                <a href="/worldcupprediction-big/leaderboard" class="btn btn-secondary">
                    View Leaderboard
                </a>
            </div>

            <div class="powered-by">
                Powered By
                <img src="/worldcupprediction-big/public/uploads/logo.png" alt="Logo">
            </div>

        </div>

        <div class="hero-image">
            <img src="/worldcupprediction-big/public/uploads/hero.png" alt="World Cup 2026">
        </div>

    </div>
</section>

    <!-- Stats Section -->
 <section class="stats">
    <div class="stats-header">
        <h2>Platform Statistics</h2>
        <p>Join the fun of predicting the biggest tournament in the world.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-item">
            <div class="stat-icon-bg">
                <span class="stat-icon">👥</span>
            </div>
            <div class="stat-content">
                <div class="stat-number" id="totalUsers">
                    <span class="stat-loader">⏳</span>
                </div>
                <div class="stat-label">Active Predictors</div>
                <div class="stat-description">Players worldwide</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-bg" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <span class="stat-icon">🏆</span>
            </div>
            <div class="stat-content">
                <div class="stat-number" id="totalRooms">
                    <span class="stat-loader">⏳</span>
                </div>
                <div class="stat-label">Prediction Rooms</div>
                <div class="stat-description">Active competitions</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-bg" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <span class="stat-icon">⚽</span>
            </div>
            <div class="stat-content">
                <div class="stat-number" id="totalPredictions">
                    <span class="stat-loader">⏳</span>
                </div>
                <div class="stat-label">Predictions Made</div>
                <div class="stat-description">Total guesses</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-bg" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <span class="stat-icon">📅</span>
            </div>
            <div class="stat-content">
                <div class="stat-number" id="matchesPlayed">
                    <span class="stat-loader">⏳</span>
                </div>
                <div class="stat-label">Matches Completed</div>
                <div class="stat-description">Results entered</div>
            </div>
        </div>

    </div>
</section>

    <!-- Features Section -->
<section class="features">
    <div class="container">

        <div class="section-header">
            <span class="section-tag">PLAY • PREDICT • WIN</span>

            <h2>Your World Cup Journey Starts Here</h2>

            <p>
                Make predictions, join rooms, challenge friends and family,
                track your score, and compete for the top spot on the leaderboard
                throughout World Cup 2026.
            </p>
        </div>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon">01</div>
                <h3>Match Predictions</h3>
                <p>
                    Predict match winners, draws, and exact scores.
                    The more accurate your predictions, the more points you earn.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">02</div>
                <h3>Private Rooms</h3>
                <p>
                    Create your own prediction room and invite friends,
                    family, colleagues, or football fans to compete together.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">03</div>
                <h3>Leaderboard Rankings</h3>
                <p>
                    Watch your position rise after every match and
                    compete to become the ultimate prediction champion.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">04</div>
                <h3>Achievement Badges</h3>
                <p>
                    Unlock special badges for winning streaks,
                    accurate score predictions, and prediction milestones.
                </p>
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
                    <a href="/worldcupprediction-big/leaderboard">Leaderboard</a><br>
                    <a href="/worldcupprediction-big/rooms">Rooms</a>
                </div>
                <div>
                    <h4>Support</h4>
                    <a href="/worldcupprediction-big/about">About</a><br>
                    <a href="/worldcupprediction-big/contact">Contact</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 PredictCup. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
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
