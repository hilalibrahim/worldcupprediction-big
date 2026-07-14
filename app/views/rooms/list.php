<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - PredictCup</title>
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

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: var(--text-light);">Rooms</h1>
            <div>
                <a href="<?= BASE_URL ?>/rooms/create" class="btn btn-primary">Create Room</a>
                <a href="<?= BASE_URL ?>/rooms/join" class="btn btn-secondary">Join Room</a>
            </div>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- User's Rooms -->
        <div style="margin-bottom: 3rem;">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">Your Rooms</h2>
            <?php if (!empty($userRooms)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    <?php foreach ($userRooms as $room): ?>
                        <div class="room-card" style="background: var(--glass-bg); border: 1px solid var(--accent-color);">
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
                                <p style="color: var(--text-gray); font-size: 0.875rem; margin: 1rem 0;">
                                    <?php echo htmlspecialchars($room['description']) ?>
                                </p>
                            <?php endif; ?>

                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?= BASE_URL ?>/rooms/view/<?php echo $room['id'] ?>" class="btn btn-primary" style="flex: 1;">
                                    View Room
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="stat-card" style="text-align: center; padding: 2rem;">
                    <p style="color: var(--text-gray); margin-bottom: 1rem;">You haven't joined any rooms yet.</p>
                    <a href="<?= BASE_URL ?>/rooms/join" class="btn btn-primary">Join a Room</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Public Rooms -->
        <div>
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">Public Rooms</h2>
            
            <?php if (!empty($allRooms)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    <?php foreach ($allRooms as $room): ?>
                        <div class="room-card" style="background: var(--glass-bg);">
                            <div class="room-header">
                                <div>
                                    <div class="room-name"><?php echo htmlspecialchars($room['name']) ?></div>
                                    <div style="color: var(--text-gray); font-size: 0.875rem;">
                                        Created by <?php echo htmlspecialchars($room['owner_name']) ?>
                                    </div>
                                </div>
                                <div style="color: var(--text-gray); font-weight: bold;">
                                    <?php echo $room['member_count'] ?> members
                                </div>
                            </div>
                            
                            <?php if ($room['description']): ?>
                                <p style="color: var(--text-gray); font-size: 0.875rem; margin: 1rem 0;">
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
                <div class="stat-card" style="text-align: center; padding: 2rem;">
                    <p style="color: var(--text-gray); margin-bottom: 1rem;">No public rooms yet.</p>
                    <a href="<?= BASE_URL ?>/rooms/create" class="btn btn-primary">Create the first room</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>