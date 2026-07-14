<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/" class="nav-link">Main Site</a>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link active">Admin Panel</a>
            <a href="<?= BASE_URL ?>/admin/matches" class="nav-link">Manage Matches</a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-link">Manage Users</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link">Profile</a>
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
            <a href="<?= BASE_URL ?>/" class="nav-link">Main Site</a>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link active">Admin Panel</a>
            <a href="<?= BASE_URL ?>/admin/matches" class="nav-link">Manage Matches</a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-link">Manage Users</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="nav-link">Profile</a>
                <a href="<?= BASE_URL ?>/logout" class="nav-link" style="color: #ff5d5d;">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="nav-link">Login</a>
                <a href="<?= BASE_URL ?>/register" class="nav-cta">Register Free</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

    <div style="padding: 2.5rem 5% 1rem; max-width: 1400px; margin: 0 auto;">
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Admin Area</span>
        <h1 style="font-size: 2.5rem; color: #E5E5E5; margin-top: 0.25rem;">Control Panel</h1>
    </div>

    <div style="max-width: 1400px; margin: 0 auto; padding: 1rem 5% 4rem;">

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Stats Row -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Users</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold);"><?php echo $totalUsers['count'] ?? 0 ?></div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Active Rooms</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gold);"><?php echo $totalRooms['count'] ?? 0 ?></div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Predictions Made</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: #E5E5E5;"><?php echo $totalPredictions['count'] ?? 0 ?></div>
            </div>
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-top: 2px solid var(--gold); border-radius: 12px; padding: 1.5rem;">
                <div style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Completed Matches</div>
                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--success);"><?php echo $matchesPlayed['count'] ?? 0 ?> / <?php echo $totalMatches['count'] ?? 0 ?></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            
            <!-- Activity Table -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; overflow: hidden;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Recent Activity</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Time</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Action</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = Database::getInstance();
                        $activities = $db->resultSet("
                            SELECT ua.*, u.username
                            FROM user_activities ua
                            JOIN users u ON ua.user_id = u.id
                            ORDER BY ua.created_at DESC
                            LIMIT 10
                        ");
                        
                        foreach ($activities as $activity): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1rem; font-size: 0.85rem; color: var(--gray);">
                                    <?php echo formatDate($activity['created_at']) ?>
                                </td>
                                <td style="padding: 1rem; color: #E5E5E5; font-size: 0.9rem;"><?php echo htmlspecialchars($activity['action']) ?></td>
                                <td style="padding: 1rem; color: #E5E5E5; font-size: 0.9rem; font-weight: 500;"><?php echo htmlspecialchars($activity['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Quick Actions -->
            <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Quick Actions</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="<?= BASE_URL ?>/admin/teams" class="btn tbc-btn-secondary" style="width: 100%;">Manage Teams</a>
                    <a href="<?= BASE_URL ?>/admin/matches" class="btn tbc-btn-secondary" style="width: 100%;">Manage Matches</a>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn tbc-btn-secondary" style="width: 100%;">Manage Users</a>
                    <a href="<?= BASE_URL ?>/admin/rooms" class="btn tbc-btn-secondary" style="width: 100%;">Manage Rooms</a>
                </div>
                
                <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <form method="POST" action="<?= BASE_URL ?>/admin/dashboard">
                        <input type="hidden" name="action" value="draw_contest_winner">
                        <button type="submit" class="btn" style="width: 100%; padding: 1.2rem; background: linear-gradient(135deg, #ffd700, #ffb300); color: #000; border: none; font-size: 1.1rem; font-weight: 700; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onclick="return confirm('Draw the overall contest winner from the pool of users tied for 1st place?');">
                            <span>🏆</span> Draw Contest Winner
                        </button>
                    </form>
                </div>
            </div>
            
        </div>

        <!-- Global Predictions Log -->
        <div style="margin-top: 2rem; background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; overflow: hidden;">
            <h3 style="font-size: 1.2rem; color: var(--gold); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Global Predictions Log</h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Time</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">User</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Match</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Predicted Score</th>
                            <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allPredictions)): ?>
                            <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--gray);">No predictions have been made yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($allPredictions as $pred): ?>
                                <tr style="border-bottom: 1px solid var(--glass-border); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1rem; font-size: 0.85rem; color: var(--gray);">
                                        <?php echo formatDate($pred['created_at']) ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div style="color: #E5E5E5; font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($pred['username']) ?></div>
                                        <div style="color: var(--gray); font-size: 0.8rem;"><?php echo htmlspecialchars($pred['country']) ?></div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div style="color: #E5E5E5; font-size: 0.9rem; font-weight: 500;">
                                            <?php echo htmlspecialchars($pred['home_team_name']) ?> vs <?php echo htmlspecialchars($pred['away_team_name']) ?>
                                        </div>
                                        <div style="color: var(--gray); font-size: 0.8rem;">
                                            <?php echo ($pred['match_status'] === 'completed') ? 'Ended (' . $pred['act_home'] . ' - ' . $pred['act_away'] . ')' : ucfirst($pred['match_status']); ?>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span class="badge badge-primary" style="font-size: 0.9rem; font-family: var(--font-display); padding: 6px 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                                            <?php echo $pred['home_score'] ?> - <?php echo $pred['away_score'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?php if ($pred['match_status'] === 'completed'): ?>
                                            <span style="color: var(--gold); font-weight: 700; font-size: 1rem; font-family: var(--font-display);">+<?php echo $pred['points'] ?></span>
                                        <?php else: ?>
                                            <span style="color: var(--gray); font-size: 0.85rem; font-style: italic;">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
                <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
                    <div>
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">← Previous</a>
                        <?php else: ?>
                            <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">← Previous</span>
                        <?php endif; ?>
                    </div>
                    
                    <div style="color: var(--gray); font-size: 0.9rem;">
                        Page <span style="color: var(--gold); font-weight: bold;"><?php echo $currentPage ?></span> of <?php echo $totalPages ?>
                    </div>
                    
                    <div>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Next →</a>
                        <?php else: ?>
                            <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">Next →</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
