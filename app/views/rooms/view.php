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
                                <div style="text-align: center; padding: 0.75rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                    <div style="color: var(--success); font-weight: bold; margin-bottom: 0.5rem;">✓ Your Predictions</div>
                                    <div style="color: var(--text-light); margin-bottom: 0.25rem;">
                                        <strong>Score:</strong> <span style="color: var(--accent-color);"><?php echo $matchPredictions['home_score']; ?> - <?php echo $matchPredictions['away_score']; ?></span>
                                    </div>
                                    <div style="color: var(--text-light);">
                                        <strong>Winner:</strong> <span style="color: var(--accent-color);">
                                            <?php 
                                            if ($matchPredictions['predicted_winner'] === 'home') {
                                                echo htmlspecialchars($match['home_team_name']);
                                            } elseif ($matchPredictions['predicted_winner'] === 'away') {
                                                echo htmlspecialchars($match['away_team_name']);
                                            } else {
                                                echo 'Draw';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if ($match['status'] === 'completed'): ?>
                                        <div style="color: var(--success); margin-top: 0.5rem; font-size: 0.875rem;">+<?php echo $matchPredictions['points']; ?> pts</div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif (!$isLocked): ?>
                                <form method="POST" action="/worldcupprediction-big/predict" style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                                    
                                    <!-- Score Prediction -->
                                    <div style="padding: 0.75rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                        <div style="font-size: 0.75rem; color: var(--text-gray); margin-bottom: 0.5rem;">🎯 Exact Score (10pts)</div>
                                        <div style="display: flex; gap: 0.5rem; align-items: center; justify-content: center;">
                                            <input type="number" name="home_score" class="form-input" style="width: 50px; text-align: center; font-size: 0.875rem;" min="0" max="20" value="0" required>
                                            <span style="color: var(--text-gray); font-weight: bold;">-</span>
                                            <input type="number" name="away_score" class="form-input" style="width: 50px; text-align: center; font-size: 0.875rem;" min="0" max="20" value="0" required>
                                        </div>
                                    </div>
                                    
                                    <!-- Winner Prediction -->
                                    <div style="padding: 0.75rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                        <div style="font-size: 0.75rem; color: var(--text-gray); margin-bottom: 0.5rem;">👑 Who Wins? (5pts)</div>
                                        <div style="display: flex; gap: 0.5rem; align-items: center; justify-content: center; flex-wrap: wrap;">
                                            <label style="cursor: pointer; font-size: 0.75rem;">
                                                <input type="radio" name="predicted_winner" value="home" checked>
                                                <?php echo htmlspecialchars(substr($match['home_team_name'], 0, 5)); ?>
                                            </label>
                                            <label style="cursor: pointer; font-size: 0.75rem;">
                                                <input type="radio" name="predicted_winner" value="draw">
                                                Draw
                                            </label>
                                            <label style="cursor: pointer; font-size: 0.75rem;">
                                                <input type="radio" name="predicted_winner" value="away">
                                                <?php echo htmlspecialchars(substr($match['away_team_name'], 0, 5)); ?>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem; width: 100%;">Submit Both</button>
                                </form>
                            <?php else: ?>
                                <div style="text-align: center; color: var(--text-gray); font-size: 0.875rem; padding: 0.75rem; background: var(--glass-bg); border-radius: 0.5rem;">
                                    🔒 Predictions closed
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