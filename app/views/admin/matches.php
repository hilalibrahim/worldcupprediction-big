<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches - Admin - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/admin/dashboard">Admin Dashboard</a>
                <a href="/worldcupprediction-big/admin/users">Users</a>
                <a href="/worldcupprediction-big/admin/teams">Teams</a>
                <a href="/worldcupprediction-big/admin/matches" class="active">Matches</a>
                <a href="/worldcupprediction-big/admin/rooms">Rooms</a>
                <a href="/worldcupprediction-big/admin/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Match Management</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- API Matches Section -->
        <div style="background: var(--glass-bg); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div>
                    <h3 style="color: var(--text-light); margin: 0;">Add Matches from Live API</h3>
                    <p style="color: var(--text-gray); margin: 0.5rem 0 0 0; font-size: 0.875rem;">Select matches from Football-Data.org API</p>
                </div>
                <form method="POST" action="/worldcupprediction-big/admin/matches" style="margin: 0;">
                    <input type="hidden" name="action" value="fetch_api_matches">
                    <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Refresh List</button>
                </form>
            </div>
            
            <?php if (!$apiAvailable): ?>
                <div style="padding: 1rem; background: rgba(255, 255, 0, 0.1); border-left: 4px solid var(--accent-color); color: var(--accent-color);">
                    <p><strong>API Not Configured:</strong> Configure your Football-Data.org API key in config.php</p>
                    <p>Get a free API key from: <a href="https://www.football-data.org/" style="color: var(--accent-color);">football-data.org</a></p>
                </div>
            <?php elseif (empty($apiMatches)): ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-gray);">
                    <p>No new matches available from API.</p>
                    <p>All matches from Football-Data.org are already imported.</p>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 1rem; color: var(--text-gray);">
                    Found <?php echo count($apiMatches); ?> matches available from Football-Data.org
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    <?php foreach ($apiMatches as $match): ?>
                        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 0.5rem; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <strong style="color: var(--text-light);"><?php echo htmlspecialchars($match['homeTeam']); ?> vs <?php echo htmlspecialchars($match['awayTeam']); ?></strong>
                                <span style="color: var(--accent-color); font-size: 0.875rem;">
                                    <?php echo strtoupper($match['status']); ?>
                                </span>
                            </div>
                            <div style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <?php echo date('M j, Y H:i', strtotime($match['utcDate'])); ?>
                            </div>
                            <div style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 1rem;">
                                <?php echo htmlspecialchars($match['venue']); ?> • <?php echo str_replace('_', ' ', $match['stage']); ?>
                            </div>
                            <form method="POST" action="/worldcupprediction-big/admin/matches" style="margin: 0;">
                                <input type="hidden" name="action" value="add_from_api">
                                <input type="hidden" name="api_match_id" value="<?php echo $match['id']; ?>">
                                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.5rem;">Add This Match</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Existing Matches List -->
        <h3 style="color: var(--text-light); margin-bottom: 1rem;">Existing Matches</h3>
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Match</th>
                    <th>Stage</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $match): ?>
                    <tr>
                        <td style="font-size: 0.875rem; color: var(--text-gray);">
                            <?php echo formatMatchDate($match['match_date']) ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                                <span>VS</span>
                                <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                            </div>
                        </td>
                        <td><?php echo $match['stage'] ?? 'Group Stage' ?></td>
                        <td>
                            <span class="badge <?php 
                                echo $match['status'] === 'completed' ? 'badge-success' : 
                                     ($match['status'] === 'scheduled' ? 'badge-warning' : 'badge-success'); 
                            ?>">
                                <?php echo $match['status'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($match['home_score'] !== null): ?>
                                <strong><?php echo $match['home_score'] ?> - <?php echo $match['away_score'] ?></strong>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <?php if ($match['status'] !== 'completed'): ?>
                                    <button class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="toggleResultsForm(<?php echo $match['id'] ?>)">Results</button>
                                <?php endif; ?>
                                <form method="POST" action="/worldcupprediction-big/admin/matches" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $match['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Delete match?');">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Results Form -->
                    <tr id="results-<?php echo $match['id'] ?>" style="display: none; background: rgba(15, 38, 69, 0.5);">
                        <td colspan="6">
                            <form method="POST" action="/worldcupprediction-big/admin/matches">
                                <input type="hidden" name="action" value="enter_results">
                                <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                                    <input type="number" name="home_score" class="form-input" style="width: 80px;" min="0" max="99">
                                    <span>-</span>
                                    <input type="number" name="away_score" class="form-input" style="width: 80px;" min="0" max="99">
                                    <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                                    <button type="submit" class="btn btn-primary">Save Results</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
    <script>
        function toggleResultsForm(matchId) {
            const row = document.getElementById('results-' + matchId);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</body>
</html>