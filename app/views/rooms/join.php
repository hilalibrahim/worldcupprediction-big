<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Room - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/worldcupprediction-big">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms" class="active">Rooms</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/worldcupprediction-big/profile">Profile</a>
                    <a href="/worldcupprediction-big/logout">Logout</a>
                <?php else: ?>
                    <a href="/worldcupprediction-big/login">Login</a>
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
            <form method="POST" action="/worldcupprediction-big/rooms/join">
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
                <a href="/worldcupprediction-big/rooms/create" style="color: var(--accent-color);">Create new room</a>
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

                            <form method="POST" action="/worldcupprediction-big/rooms/join">
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

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
