<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="container" style="max-width: 400px; padding: 2rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--text-light);">Admin Login</h1>
            <p style="color: var(--text-gray);">Enter your credentials to access admin panel</p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/worldcupprediction-big/admin/login">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required 
                       placeholder="username">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" required 
                       placeholder="Enter your password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                Admin Login
            </button>
        </form>

        <div style="text-align: center; color: var(--text-gray);">
            <p><a href="/worldcupprediction-big/login" style="color: var(--accent-color);">Back to User Login</a></p>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
