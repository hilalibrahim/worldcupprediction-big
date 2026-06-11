<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room - PredictCup</title>
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
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Create New Room</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <form method="POST" action="/worldcupprediction-big/rooms/create">
                <div class="form-group">
                    <label class="form-label">Room Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="Enter room name">
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Describe your room..."></textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-light); cursor: pointer;">
                        <input type="checkbox" name="is_public" checked style="width: auto;">
                        Make this room public (searchable)
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Room Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Set password for private room">
                    <div class="form-text">Leave empty to make the room public</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Max Members</label>
                    <select name="max_members" class="form-input">
                        <option value="10">10 members</option>
                        <option value="25" selected>25 members</option>
                        <option value="50">50 members</option>
                        <option value="100">100 members</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Create Room
                </button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="/worldcupprediction-big/rooms/join" style="color: var(--accent-color);">Join existing room</a>
            </div>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
