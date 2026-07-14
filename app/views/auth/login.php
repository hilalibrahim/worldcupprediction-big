<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>

    <div style="width: 100%; max-width: 420px; padding: 2rem;">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <a href="<?= BASE_URL ?>/">
                <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="PredictCup" style="height: 50px; width: auto; margin-bottom: 1.5rem;">
            </a>
            <h1 style="font-size: 2.5rem; letter-spacing: 2px; margin-bottom: 0.5rem;">WELCOME BACK</h1>
            <p style="color: var(--gray); font-size: 0.95rem;">Sign in to lock in your predictions</p>
        </div>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: var(--radius); padding: 2rem;">
            <form method="POST" action="<?= BASE_URL ?>/login">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--gray); cursor: pointer; font-size: 0.9rem;">
                        <input type="checkbox" name="remember_me" style="width: auto; accent-color: var(--gold);">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn tbc-btn-primary" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 8px;">
                    Sign In
                </button>
            </form>
        </div>

        <div style="text-align: center; color: var(--gray); margin-top: 1.5rem; font-size: 0.9rem;">
            <p>Don't have an account? <a href="<?= BASE_URL ?>/register" style="color: var(--gold); font-weight: 600;">Register</a></p>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
