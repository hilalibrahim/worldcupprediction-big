<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Room - PredictCup</title>
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

    <div class="container" style="max-width: 600px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Join Room</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <form method="POST" action="<?= BASE_URL ?>/rooms/join">
                <div class="form-group">
                    <label class="form-label">Room Code</label>
                    <input type="text" name="invite_code" class="form-input" required placeholder="Enter room code" id="roomCodeInput">
                    <div class="form-text">Example: WC26-K7P9</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Room Password (If Private)</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter room password">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Join Room
                </button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="<?= BASE_URL ?>/rooms/create" style="color: var(--accent-color);">Create new room</a>
            </div>
        </div>

        <!-- Public Rooms List -->
        <div style="margin-top: 2rem;">
            <h3 style="color: var(--text-light); margin-bottom: 1rem;">Public Rooms</h3>
            
            <?php if (!empty($allRooms)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                    <?php foreach ($allRooms as $room): ?>
                        <div class="room-card">
                            <div class="room-header">
                                <div>
                                    <div class="room-name"><?php echo htmlspecialchars($room['name']) ?></div>
                                    <div style="color: var(--text-gray); font-size: 0.875rem;">
                                        Created by <?php echo htmlspecialchars($room['owner_name']) ?>
                                    </div>
                                </div>
                                <div style="color: var(--accent-color); font-weight: bold;">
                                    <?php echo $room['member_count'] ?> members
                                </div>
                            </div>
                            
                            <?php if ($room['description']): ?>
                                <p style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 1rem;">
                                    <?php echo htmlspecialchars($room['description']) ?>
                                </p>
                            <?php endif; ?>

                            <form method="POST" action="<?= BASE_URL ?>/rooms/join">
                                <input type="hidden" name="invite_code" value="<?php echo htmlspecialchars($room['invite_code']) ?>">
                                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                                    Join Room
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: var(--text-gray); text-align: center;">No public rooms yet. Create one!</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
