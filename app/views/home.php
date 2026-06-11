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
            <a href="/" class="navbar-brand">
                <span>⚽</span> PredictCup
            </a>
            <div class="navbar-menu">
                <a href="/" class="active">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms">Rooms</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/worldcupprediction-big/profile">Profile</a>
                    <a href="/worldcupprediction-big/logout">Logout</a>
                    <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                <?php else: ?>
                    <a href="/worldcupprediction-big/login">Login</a>
                    <a href="/worldcupprediction-big/register" class="btn btn-primary">Register Now</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Predict Every Match. Win Every Room.</h1>
            <p>Challenge friends, family, and football fans worldwide. Make predictions, earn points, and climb the leaderboard!</p>
            <div class="hero-buttons">
                <a href="/worldcupprediction-big/register" class="btn btn-primary">Register Now</a>
                <a href="/worldcupprediction-big/leaderboard" class="btn btn-secondary">View Leaderboard</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number" id="totalUsers">0</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="totalRooms">0</div>
                <div class="stat-label">Active Rooms</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="totalPredictions">0</div>
                <div class="stat-label">Predictions Made</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="matchesPlayed">0</div>
                <div class="stat-label">Matches Played</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 style="text-align: center; color: var(--text-light); margin-bottom: 3rem;">Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚽</div>
                    <h3>Match Predictions</h3>
                    <p>Predict match outcomes correctly to earn points. Exact scores give you the most points!</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Private Rooms</h3>
                    <p>Create or join private rooms with friends. Compete in exclusive prediction leagues.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>Global Rankings</h3>
                    <p>Climb the global leaderboard and showcase your prediction skills against football fans worldwide.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏅</div>
                    <h3>Achievement Badges</h3>
                    <p>Earn badges for reaching milestones like 10 correct predictions, Prediction Master, and more.</p>
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
