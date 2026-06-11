<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name']) ?> - Room - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches">Matches</a>
                <a href="/worldcupprediction-big/leaderboard">Leaderboard</a>
                <a href="/worldcupprediction-big/rooms" class="active">Rooms</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/worldcupprediction-big/profile">Profile</a>
                    <a href="/worldcupprediction-big/logout">Logout</a>
                <?php else: ?>
                    <a href="/worldcupprediction-big/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <div style="background: var(--glass-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--glass-border); margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1 style="color: var(--text-light); margin-bottom: 0.5rem;">
                        <?php echo htmlspecialchars($room['name']) ?>
                    </h1>
                    <?php if ($room['description']): ?>
                        <p style="color: var(--text-gray); margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($room['description']) ?>
                        </p>
                    <?php endif; ?>
                    <div style="color: var(--text-gray); font-size: 0.875rem;">
                        <span class="icon">👤</span>
                        Owner: <?php echo htmlspecialchars($room['owner_name']) ?> |
                        <span class="icon">👥</span>
                        <?php echo count($members) ?> members |
                        <span class="icon">🏆</span>
                        Invite code: <?php echo htmlspecialchars($room['invite_code']) ?>
                    </div>
                </div>
                <div>
                    <?php if ($isOwner): ?>
                        <a href="/worldcupprediction-big/rooms/edit/<?php echo $room['id'] ?>" class="btn btn-secondary" style="margin-right: 0.5rem;">Edit Room</a>
                        <a href="/worldcupprediction-big/rooms/members/<?php echo $room['id'] ?>" class="btn btn-secondary" style="margin-right: 0.5rem;">Manage Members</a>
                        <a href="/worldcupprediction-big/rooms/leave/<?php echo $room['id'] ?>" class="btn btn-secondary">Leave Room</a>
                    <?php else: ?>
                        <a href="/worldcupprediction-big/rooms/leave/<?php echo $room['id'] ?>" class="btn btn-secondary">Leave Room</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Room Leaderboard</h3>
                <table class="leaderboard-table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $index => $user): ?>
                            <tr>
                                <td class="rank-<?php echo $index + 1 ?>"><?php echo $index + 1 ?></td>
                                <td><?php echo htmlspecialchars($user['username']) ?></td>
                                <td style="color: var(--accent-color); font-weight: bold;">
                                    <?php echo $user['total_points'] ?> pts
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="stat-card">
                <h3>Make Predictions</h3>
                <?php if (!empty($upcomingMatches)): ?>
                    <?php foreach ($upcomingMatches as $match): 
                        $matchPredictions = $roomPredictions[$match['id']] ?? [];
                        $hasPredicted = !empty($matchPredictions);
                        $isLocked = isMatchLocked($match['match_date']);
                    ?>
                        <div class="match-card" style="<?php echo $hasPredicted ? 'border-color: var(--success);' : '' ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div class="match-time"><?php echo formatMatchDate($match['match_date']) ?></div>
                                <?php if ($hasPredicted): ?>
                                    <span style="color: var(--success); font-size: 0.75rem;">✓ Predicted</span>
                                <?php elseif ($isLocked): ?>
                                    <span style="color: var(--text-gray); font-size: 0.75rem;">🔒 Locked</span>
                                <?php else: ?>
                                    <span style="color: var(--accent-color); font-size: 0.75rem;">Open</span>
                                <?php endif; ?>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div style="text-align: center; flex: 1;">
                                    <div class="team-logo" style="font-size: 1.5rem; background: var(--accent-color); color: var(--bg-dark); margin: 0 auto 0.5rem;">
                                        <?php echo htmlspecialchars($match['home_short_name'] ?? substr($match['home_team_name'], 0, 1)) ?>
                                    </div>
                                    <div style="color: var(--text-light); font-size: 0.875rem;"><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                                </div>
                                <div style="color: var(--text-gray); padding: 0 1rem;">VS</div>
                                <div style="text-align: center; flex: 1;">
                                    <div class="team-logo" style="font-size: 1.5rem; background: var(--accent-color); color: var(--bg-dark); margin: 0 auto 0.5rem;">
                                        <?php echo htmlspecialchars($match['away_short_name'] ?? substr($match['away_team_name'], 0, 1)) ?>
                                    </div>
                                    <div style="color: var(--text-light); font-size: 0.875rem;"><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                                </div>
                            </div>
                            
                            <?php if ($hasPredicted): ?>
                                <div style="text-align: center; padding: 0.5rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                    <span style="color: var(--text-light);">Your prediction: </span>
                                    <strong style="color: var(--accent-color);"><?php echo $matchPredictions['home_score']; ?> - <?php echo $matchPredictions['away_score']; ?></strong>
                                    <?php if ($match['status'] === 'completed'): ?>
                                        <span style="color: var(--success); margin-left: 0.5rem;">(+<?php echo $matchPredictions['points']; ?> pts)</span>
                                    <?php endif; ?>
                                </div>
                            <?php elseif (!$isLocked): ?>
                                <form method="POST" action="/worldcupprediction-big/predict" style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                                    
                                    <!-- Prediction Type Toggle -->
                                    <div style="display: flex; gap: 1rem; font-size: 0.75rem;">
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="prediction_type_<?php echo $match['id']; ?>" value="score" checked onchange="toggleRoomPrediction(<?php echo $match['id']; ?>)">
                                            Score (10pts)
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="prediction_type_<?php echo $match['id']; ?>" value="winner" onchange="toggleRoomPrediction(<?php echo $match['id']; ?>)">
                                            Winner (5pts)
                                        </label>
                                    </div>
                                    
                                    <!-- Score inputs -->
                                    <div id="score_<?php echo $match['id']; ?>" style="display: flex; gap: 0.5rem; align-items: center;">
                                        <input type="number" name="home_score" class="form-input" style="width: 50px; text-align: center;" min="0" max="20" value="0">
                                        <span style="color: var(--text-gray);">-</span>
                                        <input type="number" name="away_score" class="form-input" style="width: 50px; text-align: center;" min="0" max="20" value="0">
                                    </div>
                                    
                                    <!-- Winner inputs (hidden by default) -->
                                    <div id="winner_<?php echo $match['id']; ?>" style="display: none; gap: 0.5rem; align-items: center; font-size: 0.75rem;">
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="predicted_winner_<?php echo $match['id']; ?>" value="home" checked>
                                            <?php echo htmlspecialchars(substr($match['home_team_name'], 0, 3)); ?>
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="predicted_winner_<?php echo $match['id']; ?>" value="draw">Draw
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="predicted_winner_<?php echo $match['id']; ?>" value="away">
                                            <?php echo htmlspecialchars(substr($match['away_team_name'], 0, 3)); ?>
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Predict</button>
                                </form>
                            <?php else: ?>
                                <div style="text-align: center; color: var(--text-gray); font-size: 0.875rem;">
                                    Predictions closed
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-gray);">No upcoming matches to predict.</p>
                <?php endif; ?>
            </div>

            <div class="stat-card">
                <h3>Room Members</h3>
                <?php if (!empty($members)): ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        <?php foreach ($members as $member): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                <div class="team-logo" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                    <?php echo substr($member['username'], 0, 1) ?>
                                </div>
                                <div>
                                    <div style="font-weight: bold; color: var(--text-light);"><?php echo htmlspecialchars($member['username']) ?></div>
                                    <div style="color: var(--text-gray); font-size: 0.875rem;">
                                        <?php echo $member['predictions'] ?> predictions |
                                        <span style="color: var(--accent-color);"><?php echo $member['correct_predictions'] ?> correct</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-gray);">No members yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
<script>
        function toggleRoomPrediction(matchId) {
            const predType = document.querySelector('input[name="prediction_type_' + matchId + '"]:checked').value;
            const scoreDiv = document.getElementById('score_' + matchId);
            const winnerDiv = document.getElementById('winner_' + matchId);
            
            if (predType === 'score') {
                scoreDiv.style.display = 'flex';
                winnerDiv.style.display = 'none';
            } else {
                scoreDiv.style.display = 'none';
                winnerDiv.style.display = 'flex';
            }
        }
    </script>