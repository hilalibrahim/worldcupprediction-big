<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - PredictCup</title>
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
                <a href="/worldcupprediction-big/rooms">Rooms</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/worldcupprediction-big/profile">Profile</a>
                    <a href="/worldcupprediction-big/logout">Logout</a>
                <?php else: ?>
                    <a href="/worldcupprediction-big/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 900px; padding: 2rem;">
        <h1 style="color: var(--text-light); margin-bottom: 2rem; text-align: center;">About PredictCup</h1>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border); margin-bottom: 2rem;">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">Welcome to PredictCup!</h2>
            <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">
                PredictCup is the ultimate football prediction platform where fans can test their knowledge, 
                compete with friends, and earn points for correct predictions.
            </p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Join prediction rooms, challenge your friends, and climb the global leaderboard to prove 
                you're the ultimate football expert!
            </p>
        </div>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border); margin-bottom: 2rem;">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">How It Works</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">1️⃣</div>
                    <h3 style="color: var(--text-light);">Register</h3>
                    <p style="color: var(--text-gray); font-size: 0.875rem;">Create your account and start predicting</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">2️⃣</div>
                    <h3 style="color: var(--text-light);">Join Rooms</h3>
                    <p style="color: var(--text-gray); font-size: 0.875rem;">Create or join prediction rooms with friends</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">3️⃣</div>
                    <h3 style="color: var(--text-light);">Predict</h3>
                    <p style="color: var(--text-gray); font-size: 0.875rem;">Make predictions for football matches</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">4️⃣</div>
                    <h3 style="color: var(--text-light);">Earn Points</h3>
                    <p style="color: var(--text-gray); font-size: 0.875rem;">Earn points and climb the leaderboard</p>
                </div>
            </div>
        </div>

        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <h2 style="color: var(--text-light); margin-bottom: 1rem;">Features</h2>
            <ul style="list-style: none; color: var(--text-light);">
                <li style="margin-bottom: 0.5rem;">✓ Real-time match predictions</li>
                <li style="margin-bottom: 0.5rem;">✓ Private and public prediction rooms</li>
                <li style="margin-bottom: 0.5rem;">✓ Global and room-specific leaderboards</li>
                <li style="margin-bottom: 0.5rem;">✓ Achievement badges for milestones</li>
                <li style="margin-bottom: 0.5rem;">✓ Automatic points calculation</li>
                <li style="margin-bottom: 0.5rem;">✓ Admin panel for match management</li>
            </ul>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
