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
            <a href="/" class="navbar-brand"><span>⚽</span> PredictCup</a>
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

        <!-- Add Match Form -->
        <div style="background: var(--glass-bg); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem;">
            <h3 style="color: var(--text-light); margin-bottom: 1rem;">Add New Match</h3>
            <form method="POST" action="/worldcupprediction-big/admin/matches">
                <input type="hidden" name="action" value="add">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div>
                        <label class="form-label">Home Team</label>
                        <select name="home_team_id" class="form-input" required>
                            <option value="">Select Home Team</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?php echo $team['id'] ?>">
                                    <?php echo htmlspecialchars($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Away Team</label>
                        <select name="away_team_id" class="form-input" required>
                            <option value="">Select Away Team</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?php echo $team['id'] ?>">
                                    <?php echo htmlspecialchars($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Match Date</label>
                        <input type="datetime-local" name="match_date" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Stadium</label>
                        <input type="text" name="stadium" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Stage</label>
                        <select name="stage" class="form-input">
                            <option value="Group Stage">Group Stage</option>
                            <option value="Round of 16">Round of 16</option>
                            <option value="Quarter Final">Quarter Final</option>
                            <option value="Semi Final">Semi Final</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Add Match</button>
            </form>
        </div>

        <!-- Matches List -->
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
                                <form method="POST" action="/worldcupprediction-big/admin/matches" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $match['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Delete match?');">Del</button>
                                </form>
                                
                                <?php if ($match['status'] !== 'completed'): ?>
                                    <button class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="toggleResultsForm(<?php echo $match['id'] ?>)">Results</button>
                                <?php endif; ?>
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
