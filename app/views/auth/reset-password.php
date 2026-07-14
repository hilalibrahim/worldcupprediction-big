<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>
    <div class="container" style="max-width: 400px; padding: 2rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--text-light);">Set New Password</h1>
            <p style="color: var(--text-gray);">Enter your new password below</p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/reset-password/<?php echo htmlspecialchars($token) ?>">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" required 
                       placeholder="Create a new password" minlength="8">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-input" required 
                       placeholder="Confirm your new password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                Reset Password
            </button>
        </form>

        <div style="text-align: center; color: var(--text-gray);">
            <p><a href="<?= BASE_URL ?>/login" style="color: var(--accent-color);">Back to Login</a></p>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
