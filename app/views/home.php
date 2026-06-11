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
        <h2>Prediction Platform Statistics</h2>
        <p>Join thousands of football fans predicting the biggest tournament in the world.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-item">
            <div class="stat-icon">👥</div>
            <div class="stat-number" id="totalUsers">0</div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">🏆</div>
            <div class="stat-number" id="totalRooms">0</div>
            <div class="stat-label">Active Rooms</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">⚽</div>
            <div class="stat-number" id="totalPredictions">0</div>
            <div class="stat-label">Predictions Made</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">📅</div>
            <div class="stat-number" id="matchesPlayed">0</div>
            <div class="stat-label">Matches Played</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Load stats from API
            fetch('/worldcupprediction-big/api/stats')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('totalUsers').textContent = data.total_users;
                    document.getElementById('totalRooms').textContent = data.total_rooms;
                    document.getElementById('totalPredictions').textContent = data.total_predictions;
                    document.getElementById('matchesPlayed').textContent = data.matches_played;
                });
        });
    </script>
</body>
</html>
