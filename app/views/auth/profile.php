<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <!-- Global Loader -->
    <div id="page-loader">
        <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="Loading...">
    </div>
    <nav class="navbar">
    <div class="container">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <img src="<?= BASE_URL ?>/public/uploads/logo.png" alt="World Cup Prediction 2026">
        </a>

        <!-- Desktop Navigation -->
        <div class="navbar-menu">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link active">Profile</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link" style="color: #ff5d5d;">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Login</a>
                <a href="<?= BASE_URL ?>/register" class="nav-cta">Register Free</a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn">
            ☰
        </button>

        <!-- Mobile Navigation Dropdown -->
        <div class="mobile-dropdown">
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link active">Profile</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link" style="color: #ff5d5d;">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Login</a>
                <a href="<?= BASE_URL ?>/register" class="nav-cta">Register Free</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

    <div style="max-width: 1000px; margin: 0 auto; padding: 3rem 1.5rem 4rem;">
        
        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>" style="margin-bottom: 2rem;">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Profile Banner -->
        <div style="background: linear-gradient(135deg, rgba(20,20,20,1) 0%, rgba(10,10,10,1) 100%); border: 1px solid var(--glass-border); border-radius: 16px; padding: 3rem 2rem; display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem; position: relative; overflow: hidden;">
            <!-- Decorative background element -->
            <div style="position: absolute; top: -50%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,142,60,0.05) 0%, transparent 70%); pointer-events: none;"></div>
            
            <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--black-elevated); border: 3px solid var(--gold); box-shadow: 0 0 30px rgba(255,142,60,0.2); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); color: var(--gold); font-size: 3rem; flex-shrink: 0; z-index: 1;">
                <?php echo htmlspecialchars($user['username'][0]) ?>
            </div>
            
            <div style="z-index: 1;">
                <h1 style="font-size: 2.5rem; color: #E5E5E5; margin-bottom: 0.25rem; font-weight: 700;"><?php echo htmlspecialchars($user['username']) ?></h1>
                <div style="color: var(--gray); font-size: 1rem; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($user['email']) ?>
                    <?php if ($user['country']): ?>
                        • <?php echo htmlspecialchars($user['country']) ?>
                    <?php endif; ?>
                </div>
                <div class="badge badge-warning" style="font-size: 0.85rem; padding: 6px 14px;">Member since <?php echo date('M Y', strtotime($user['join_date'])) ?></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            
            <!-- Edit Profile Sidebar -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 2rem; height: fit-content;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Edit Profile</h3>
                
                <form method="POST" action="<?= BASE_URL ?>/profile/update">
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; display: block;">Username</label>
                        <input type="text" name="username" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" value="<?php echo htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; display: block;">Email</label>
                        <input type="email" name="email" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" value="<?php echo htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label" style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; display: block;">Country</label>
                        <input type="text" name="country" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" value="<?php echo htmlspecialchars($user['country'] ?? '') ?>" placeholder="e.g. Brazil, France, England">
                    </div>

                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.05); margin-bottom: 2rem;">
                        <h4 style="color: #E5E5E5; margin-bottom: 1rem; font-size: 1rem;">Change Password</h4>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <input type="password" name="current_password" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" placeholder="Current Password">
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <input type="password" name="new_password" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" placeholder="New Password" minlength="8">
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-input" style="width: 100%; background: var(--black-elevated); border: 1px solid var(--glass-border); padding: 12px; border-radius: 8px; color: #E5E5E5;" placeholder="Confirm New Password">
                        </div>
                    </div>

                    <button type="submit" class="btn tbc-btn-secondary" style="width: 100%; font-size: 0.95rem; padding: 12px;">Save Changes</button>
                </form>
            </div>
            
            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                
                <!-- Performance Stats -->
                <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem;">
                    <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Performance</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div style="background: var(--black-elevated); border: 1px solid var(--glass-border); border-radius: 8px; padding: 1.5rem; text-align: center;">
                            <div style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Points</div>
                            <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold);"><?php echo $stats['points'] ?? 0 ?></div>
                        </div>
                        <div style="background: var(--black-elevated); border: 1px solid var(--glass-border); border-radius: 8px; padding: 1.5rem; text-align: center;">
                            <div style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Predictions</div>
                            <div style="font-family: var(--font-display); font-size: 2.5rem; color: #E5E5E5;"><?php echo $stats['total_predictions'] ?? 0 ?></div>
                        </div>
                        <div style="background: var(--black-elevated); border: 1px solid var(--glass-border); border-radius: 8px; padding: 1.5rem; text-align: center;">
                            <div style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Global Rank</div>
                            <div style="font-family: var(--font-display); font-size: 2.5rem; color: #E5E5E5;">#<?php echo $globalRank ?></div>
                        </div>

                    </div>
                </div>

                <!-- Recent Predictions -->
                <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.2rem; color: var(--gold); margin: 0; text-transform: uppercase; letter-spacing: 1px;">Recent Predictions</h3>
                    </div>
                    
                    <?php if (!empty($recentPredictions)): ?>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php foreach ($recentPredictions as $pred): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--black-elevated); border: 1px solid var(--glass-border); border-radius: 8px;">
                                    <div>
                                        <div style="color: #E5E5E5; font-weight: 500; margin-bottom: 0.25rem;">
                                            <?php echo htmlspecialchars($pred['home_team_name']) ?> vs <?php echo htmlspecialchars($pred['away_team_name']) ?>
                                        </div>
                                        <div style="color: var(--gray); font-size: 0.85rem;">
                                            Predicted: <?php echo $pred['home_score'] ?> - <?php echo $pred['away_score'] ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($pred['status'] === 'completed'): ?>
                                        <div style="text-align: right;">
                                            <span style="color: var(--gold); font-weight: bold; font-size: 1.1rem;">+<?php echo $pred['points'] ?> pts</span>
                                        </div>
                                    <?php else: ?>
                                        <div style="text-align: right;">
                                            <span style="color: var(--gray); font-size: 0.85rem; padding: 4px 8px; background: rgba(255,255,255,0.05); border-radius: 4px;">Pending</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--gray); font-size: 0.9rem;">You haven't made any predictions yet.</p>
                        <a href="<?= BASE_URL ?>/daily-matches" class="btn tbc-btn-primary" style="display: inline-block; margin-top: 1rem; font-size: 0.9rem; padding: 10px 24px;">Make a Prediction</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
