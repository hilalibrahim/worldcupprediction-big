<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 400px; padding: 2rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--text-light);">Welcome Back</h1>
            <p style="color: var(--text-gray);">Sign in to continue predicting!</p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/worldcupprediction-big/login">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required 
                       placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" required 
                       placeholder="Enter your password">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-light); cursor: pointer;">
                    <input type="checkbox" name="remember_me" style="width: auto;">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                Login
            </button>
        </form>

        <div style="text-align: center; color: var(--text-gray); margin-top: 1rem;">
            <p><a href="/worldcupprediction-big/forgot-password" style="color: var(--accent-color);">Forgot password?</a></p>
            <p style="margin-top: 1rem;">Don't have an account? <a href="/worldcupprediction-big/register" style="color: var(--accent-color);">Register</a></p>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
