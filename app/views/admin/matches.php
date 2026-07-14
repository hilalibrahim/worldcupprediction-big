<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches - Admin - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link">Admin Panel</a>
            <a href="<?= BASE_URL ?>/admin/matches" class="nav-link active">Manage Matches</a>
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
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link">Admin Panel</a>
            <a href="<?= BASE_URL ?>/admin/matches" class="nav-link active">Manage Matches</a>
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
        <h1 style="font-size: 2.5rem; color: #E5E5E5; margin-top: 0.25rem;">Match Management</h1>
    </div>

    <div style="max-width: 1400px; margin: 0 auto; padding: 1rem 5% 4rem;">

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- API Matches Section -->
        <div style="background: var(--black-card); border: 1px solid var(--glass-border); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="color: var(--gold); margin: 0; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px;">Add Matches from API</h3>
                    <p style="color: var(--gray); margin: 0.5rem 0 0 0; font-size: 0.875rem;">Import matches directly from Football-Data.org</p>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/admin/matches" style="margin: 0;">
                    <input type="hidden" name="action" value="fetch_api_matches">
                    <button type="submit" class="btn tbc-btn-secondary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">Refresh List</button>
                </form>
            </div>
            
            <?php if (!$apiAvailable): ?>
                <div style="padding: 1rem; background: rgba(255, 142, 60, 0.1); border-left: 4px solid var(--gold); color: var(--gold); border-radius: 4px;">
                    <p><strong>API Not Configured:</strong> Configure your Football-Data.org API key in config.php</p>
                    <p style="margin-top: 0.5rem;">Get a free API key from: <a href="https://www.football-data.org/" style="color: #fff; text-decoration: underline;">football-data.org</a></p>
                </div>
            <?php elseif (empty($apiMatches)): ?>
                <div style="text-align: center; padding: 3rem; color: var(--gray); background: var(--black-elevated); border-radius: 8px; border: 1px dashed var(--glass-border);">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">⚽</div>
                    <p>No new matches available from API.</p>
                    <p style="font-size: 0.85rem; margin-top: 0.5rem;">All available matches have already been imported.</p>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 1rem; color: var(--gray); font-size: 0.9rem;">
                    Found <strong><?php echo count($apiMatches); ?></strong> matches available to import
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    <?php foreach ($apiMatches as $match): ?>
                        <div style="background: var(--black-elevated); border: 1px solid var(--glass-border); border-radius: 8px; padding: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                                <strong style="color: #E5E5E5;"><?php echo htmlspecialchars($match['homeTeam']); ?> vs <?php echo htmlspecialchars($match['awayTeam']); ?></strong>
                                <span class="badge badge-warning" style="font-size: 0.75rem; padding: 4px 8px;">
                                    <?php echo strtoupper($match['status']); ?>
                                </span>
                            </div>
                            <div style="color: var(--gray); font-size: 0.85rem; margin-bottom: 0.5rem;">
                                📅 <?php echo date('M j, Y H:i', strtotime($match['utcDate'])); ?>
                            </div>
                            <div style="color: var(--gray); font-size: 0.85rem; margin-bottom: 1.25rem;">
                                🏟️ <?php echo htmlspecialchars($match['venue']); ?> • <?php echo str_replace('_', ' ', $match['stage']); ?>
                            </div>
                            <form method="POST" action="<?= BASE_URL ?>/admin/matches" style="margin: 0;">
                                <input type="hidden" name="action" value="add_from_api">
                                <input type="hidden" name="api_match_id" value="<?php echo $match['id']; ?>">
                                <button type="submit" class="btn tbc-btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.9rem;">Import Match</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Existing Matches List -->
        <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden; padding-bottom: 1rem;">
            <div style="padding: 1.5rem;">
                <h3 style="font-size: 1.2rem; color: var(--gold); margin: 0; text-transform: uppercase; letter-spacing: 1px;">Existing Matches</h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border); border-top: 1px solid var(--glass-border); background: var(--black-elevated);">
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Date</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Match</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Stage</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Status</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Score</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matches as $match): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--gray);">
                                    <?php echo formatMatchDate($match['match_date']) ?>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #E5E5E5; font-weight: 500;">
                                        <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                                        <span style="color: var(--gray); font-size: 0.8rem;">VS</span>
                                        <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                                    </div>
                                </td>
                                <td style="padding: 1rem 1.5rem; color: var(--gray); font-size: 0.9rem;"><?php echo $match['stage'] ?? 'Group Stage' ?></td>
                                <td style="padding: 1rem 1.5rem;">
                                    <span class="badge <?php 
                                        echo $match['status'] === 'completed' ? 'badge-success' : 
                                            ($match['status'] === 'scheduled' ? 'badge-warning' : 'badge-success'); 
                                    ?>">
                                        <?php echo $match['status'] ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <?php if ($match['home_score'] !== null): ?>
                                        <strong style="color: var(--gold); font-family: var(--font-display); font-size: 1.2rem;"><?php echo $match['home_score'] ?> - <?php echo $match['away_score'] ?></strong>
                                    <?php else: ?>
                                        <span style="color: var(--gray);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <?php if ($match['status'] !== 'completed'): ?>
                                            <button class="btn tbc-btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 4px;" onclick="toggleResultsForm(<?php echo $match['id'] ?>)">Results</button>
                                        <?php else: ?>
                                            <button class="btn tbc-btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 4px;" onclick="toggleResultsForm(<?php echo $match['id'] ?>)">Edit</button>
                                        <?php endif; ?>
                                        <form method="POST" action="<?= BASE_URL ?>/admin/matches" style="display: inline; margin: 0;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $match['id'] ?>">
                                            <button type="submit" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 4px; background: rgba(255, 77, 79, 0.1); color: var(--error); border: 1px solid rgba(255, 77, 79, 0.2);" onclick="return confirm('Delete match?');">Del</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Results Form -->
                            <tr id="results-<?php echo $match['id'] ?>" style="display: none; background: rgba(255, 142, 60, 0.05); border-bottom: 2px solid var(--gold);">
                                <td colspan="6" style="padding: 1.5rem;">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/matches" style="margin: 0;">
                                        <input type="hidden" name="action" value="enter_results">
                                        <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                                        
                                        <div style="display: flex; align-items: flex-end; gap: 1.5rem; flex-wrap: wrap;">
                                            <!-- Home Team Score Input -->
                                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                <label style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                    <?php echo htmlspecialchars($match['home_team_name']) ?> (Home)
                                                </label>
                                                <input type="number" name="home_score" class="form-input" style="width: 100px; text-align: center; font-size: 1.25rem; font-family: var(--font-display); background: var(--black-card); border-color: var(--glass-border); border-radius: 6px; padding: 0.5rem;" min="0" max="99" placeholder="0" value="<?php echo $match['home_score'] ?? ''; ?>" required>
                                            </div>

                                            <span style="color: var(--gold); font-size: 1.5rem; font-weight: 700; padding-bottom: 0.5rem;">-</span>

                                            <!-- Away Team Score Input -->
                                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                <label style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                    <?php echo htmlspecialchars($match['away_team_name']) ?> (Away)
                                                </label>
                                                <input type="number" name="away_score" class="form-input" style="width: 100px; text-align: center; font-size: 1.25rem; font-family: var(--font-display); background: var(--black-card); border-color: var(--glass-border); border-radius: 6px; padding: 0.5rem;" min="0" max="99" placeholder="0" value="<?php echo $match['away_score'] ?? ''; ?>" required>
                                            </div>

                                            <!-- Winner Selection -->
                                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                <label style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                    Winner
                                                </label>
                                                <select name="predicted_winner" class="form-input" style="padding: 0.6rem; border-radius: 6px; background: var(--black-card); border: 1px solid var(--glass-border); color: #E5E5E5; min-width: 120px;">
                                                    <option value="home" <?php echo ($match['predicted_winner'] ?? '') === 'home' ? 'selected' : ''; ?>>Home</option>
                                                    <option value="away" <?php echo ($match['predicted_winner'] ?? '') === 'away' ? 'selected' : ''; ?>>Away</option>
                                                    <option value="draw" <?php echo ($match['predicted_winner'] ?? '') === 'draw' ? 'selected' : ''; ?>>Draw</option>
                                                </select>
                                            </div>

                                            <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                                <button type="submit" class="btn tbc-btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem; border-radius: 6px;">Save Results</button>
                                                <button type="button" class="btn tbc-btn-secondary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem; border-radius: 6px;" onclick="toggleResultsForm(<?php echo $match['id'] ?>)">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
    <script>
        function toggleResultsForm(matchId) {
            const row = document.getElementById('results-' + matchId);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</body>
</html>