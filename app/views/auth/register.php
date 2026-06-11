<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 400px; padding: 2rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--text-light);">Create Account</h1>
            <p style="color: var(--text-gray);">Join PredictCup and start predicting!</p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/worldcupprediction-big/register">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" required 
                       placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required 
                       placeholder="Enter your email">
            </div>


            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" required 
                       placeholder="Create a strong password" minlength="8">
                <div class="form-text">Minimum 8 characters with uppercase, lowercase, and number</div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" required 
                       placeholder="Confirm your password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                Register
            </button>
        </form>

        <div style="text-align: center; color: var(--text-gray);">
            <p>Already have an account? <a href="/worldcupprediction-big/login" style="color: var(--accent-color);">Login</a></p>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
