<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room - PredictCup</title>
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
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Edit Room</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <form method="POST" action="/worldcupprediction-big/rooms/edit/<?php echo $room['id'] ?>">
                <div class="form-group">
                    <label class="form-label">Room Name</label>
                    <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($room['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3"><?php echo htmlspecialchars($room['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Room Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave empty to keep current password or set new password">
                </div>

                <div class="form-group">
                    <label class="form-label">Max Members</label>
                    <select name="max_members" class="form-input">
                        <option value="10" <?php echo $room['max_members'] == 10 ? 'selected' : '' ?>>10 members</option>
                        <option value="25" <?php echo $room['max_members'] == 25 ? 'selected' : '' ?>>25 members</option>
                        <option value="50" <?php echo $room['max_members'] == 50 ? 'selected' : '' ?>>50 members</option>
                        <option value="100" <?php echo $room['max_members'] == 100 ? 'selected' : '' ?>>100 members</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Update Room
                </button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="/worldcupprediction-big/rooms/view/<?php echo $room['id'] ?>" style="color: var(--accent-color);">Back to Room</a>
            </div>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
