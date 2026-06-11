<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms">Rooms</a>
                <a href="/worldcupprediction-big/profile" class="active">Profile</a>
                <a href="/worldcupprediction-big/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 900px; padding: 2rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="team-logo" style="width: 80px; height: 80px; margin: 0 auto; font-size: 2rem;">
                <?php echo substr($user['username'], 0, 1) ?>
            </div>
            <h2 style="color: var(--text-light); margin-top: 1rem;">
                <?php echo htmlspecialchars($user['username']) ?>
            </h2>
            <p style="color: var(--text-gray);">
                <?php echo htmlspecialchars($user['email']) ?> | 
                <?php echo htmlspecialchars($user['country']) ?>
            </p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-grid" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <h3>Total Points</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo $stats['points'] ?? 0 ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Total Predictions</h3>
                <div class="stat-value" style="color: var(--text-light);">
                    <?php echo $stats['total_predictions'] ?? 0 ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Correct Predictions</h3>
                <div class="stat-value" style="color: var(--success);">
                    <?php echo $stats['correct_predictions'] ?? 0 ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Accuracy</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo calculateUserAccuracy($userId) ?>%
                </div>
            </div>
        </div>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <h3 style="color: var(--text-light); margin-bottom: 1.5rem;">Update Profile</h3>
            <form method="POST" action="/worldcupprediction-big/profile">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country" class="form-input">
                        <option value="Global" <?php echo $user['country'] == 'Global' ? 'selected' : '' ?>>Global</option>
                        <option value="Argentina" <?php echo $user['country'] == 'Argentina' ? 'selected' : '' ?>>Argentina</option>
                        <option value="France" <?php echo $user['country'] == 'France' ? 'selected' : '' ?>>France</option>
                        <option value="Brazil" <?php echo $user['country'] == 'Brazil' ? 'selected' : '' ?>>Brazil</option>
                        <option value="Germany" <?php echo $user['country'] == 'Germany' ? 'selected' : '' ?>>Germany</option>
                        <option value="Spain" <?php echo $user['country'] == 'Spain' ? 'selected' : '' ?>>Spain</option>
                        <option value="Portugal" <?php echo $user['country'] == 'Portugal' ? 'selected' : '' ?>>Portugal</option>
                        <option value="England" <?php echo $user['country'] == 'England' ? 'selected' : '' ?>>England</option>
                        <option value="Belgium" <?php echo $user['country'] == 'Belgium' ? 'selected' : '' ?>>Belgium</option>
                        <option value="Netherlands" <?php echo $user['country'] == 'Netherlands' ? 'selected' : '' ?>>Netherlands</option>
                        <option value="Italy" <?php echo $user['country'] == 'Italy' ? 'selected' : '' ?>>Italy</option>
                    </select>
                </div>

                <h4 style="color: var(--text-light); margin-top: 1.5rem; margin-bottom: 1rem;">Change Password</h4>
                
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input" placeholder="Enter your current password">
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-input" placeholder="Enter new password (min 8 chars)">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
