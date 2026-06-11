<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/dashboard">Dashboard</a>
                <a href="/worldcupprediction-big/daily-matches" class="active">Matches</a>
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

    <div class="container" style="max-width: 1000px; padding: 2rem;">
        <!-- Match Header -->
        <div class="match-card" style="margin-bottom: 2rem;">
            <div class="match-header">
                <span class="badge badge-warning"><?php echo htmlspecialchars($match['stage'] ?? 'Match') ?></span>
                <span style="color: var(--text-gray); font-size: 0.875rem;">
                    <?php echo formatMatchDate($match['match_date']) ?>
                </span>
            </div>

            <div class="match-scores">
                <div class="match-team">
                    <div class="team-logo" style="font-size: 2rem; background: var(--accent-color); color: var(--bg-dark);">
                        <?php echo htmlspecialchars($match['home_short_name'] ?? $match['home_team_name'][0]) ?>
                    </div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                </div>

                <div class="score-display">
                    <?php if ($match['status'] === 'completed'): ?>
                        <div style="font-size: 3rem; font-weight: bold; color: var(--accent-color);">
                            <?php echo $match['home_score'] ?> - <?php echo $match['away_score'] ?>
                        </div>
                    <?php else: ?>
                        <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-gray);">VS</div>
                    <?php endif; ?>
                </div>

                <div class="match-team">
                    <div class="team-logo" style="font-size: 2rem; background: var(--accent-color); color: var(--bg-dark);">
                        <?php echo htmlspecialchars($match['away_short_name'] ?? $match['away_team_name'][0]) ?>
                    </div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                </div>
            </div>

            <?php if ($match['stadium']): ?>
                <div style="text-align: center; color: var(--text-gray); margin-top: 1rem;">
                    <span class="icon">🏟️</span>
                    <?php echo htmlspecialchars($match['stadium']) ?>
                </div>
            <?php endif; ?>

            <?php if (isLoggedIn() && $match['status'] !== 'completed'): ?>
                <?php if ($myPrediction): ?>
                    <!-- Already predicted -->
                    <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: rgba(16, 185, 129, 0.15); border: 2px solid var(--success); border-radius: 1rem;">
                        <span style="color: var(--success); font-size: 1.5rem;">✓</span>
                        <h3 style="color: var(--success); margin: 0.5rem 0;">Your Prediction Submitted</h3>
                        <div style="color: var(--text-light); margin: 1rem 0;">
                            <div style="margin-bottom: 0.5rem;">
                                <strong>Exact Score:</strong> 
                                <span style="color: var(--accent-color); font-size: 1.2rem;">
                                    <?php echo $myPrediction['home_score']; ?> - <?php echo $myPrediction['away_score']; ?>
                                </span>
                            </div>
                            <div>
                                <strong>Winner Prediction:</strong> 
                                <span style="color: var(--accent-color); font-weight: bold;">
                                    <?php 
                                    if ($myPrediction['predicted_winner'] === 'home') {
                                        echo htmlspecialchars($match['home_team_name']) . ' Wins';
                                    } elseif ($myPrediction['predicted_winner'] === 'away') {
                                        echo htmlspecialchars($match['away_team_name']) . ' Wins';
                                    } else {
                                        echo 'Draw';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <p style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 0;">You can only submit one prediction per match</p>
                    </div>
                <?php elseif (isMatchLocked($match['match_date'])): ?>
                    <!-- Predictions Locked -->
                    <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: rgba(239, 68, 68, 0.15); border: 2px solid #ef4444; border-radius: 1rem;">
                        <span style="color: #ef4444; font-size: 1.5rem;">🔒</span>
                        <h3 style="color: #ef4444; margin: 0.5rem 0;">Predictions Locked</h3>
                        <p style="color: var(--text-light); margin: 1rem 0;">Predictions close 5 minutes before match kickoff.</p>
                        <p style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 0;">Match starts at: <strong><?php echo formatMatchDate($match['match_date']); ?></strong></p>
                    </div>
                <?php else: ?>
                    <!-- Prediction form -->
                    <div class="match-actions" style="margin-top: 2rem;">
                        <?php 
                        $timeRemaining = getPredictionTimeRemaining($match['match_date']);
                        if ($timeRemaining !== false):
                        ?>
                        <div style="text-align: center; margin-bottom: 1rem; padding: 0.75rem; background: rgba(255, 198, 0, 0.1); border-radius: 0.5rem;">
                            <span style="color: var(--accent-color); font-weight: bold;">⏰ Predictions close in: <span id="timeRemaining"><?php echo $timeRemaining; ?></span> minutes</span>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="/worldcupprediction-big/predict" id="predictForm">
                            <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                            
                            <div style="background: var(--glass-bg); padding: 1.5rem; border-radius: 1rem;">
                                <h3 style="color: var(--text-light); margin-bottom: 1.5rem; text-align: center;">Make Your Prediction</h3>
                                
                                <!-- Score Prediction -->
                                <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                        <span style="color: var(--accent-color); font-size: 1.5rem;">🎯</span>
                                        <h4 style="color: var(--text-light); margin: 0;">Exact Score (10 pts if correct)</h4>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 1rem; justify-content: center;">
                                        <div style="text-align: center;">
                                            <label style="color: var(--text-light); display: block; font-size: 0.875rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars(substr($match['home_team_name'], 0, 12)) ?></label>
                                            <input type="number" name="home_score" class="score-input" min="0" max="20" value="0" style="width: 60px; text-align: center; font-size: 1.2rem;">
                                        </div>
                                        <span style="color: var(--accent-color); font-size: 1.5rem; font-weight: bold;">-</span>
                                        <div style="text-align: center;">
                                            <label style="color: var(--text-light); display: block; font-size: 0.875rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars(substr($match['away_team_name'], 0, 12)) ?></label>
                                            <input type="number" name="away_score" class="score-input" min="0" max="20" value="0" style="width: 60px; text-align: center; font-size: 1.2rem;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Winner Prediction -->
                                <div style="margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                        <span style="color: var(--accent-color); font-size: 1.5rem;">👑</span>
                                        <h4 style="color: var(--text-light); margin: 0;">Who Wins? (5 pts if correct)</h4>
                                    </div>
                                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                                        <label style="cursor: pointer; padding: 0.75rem 1.5rem; background: rgba(255, 198, 0, 0.1); border: 2px solid transparent; border-radius: 0.5rem; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="radio" name="predicted_winner" value="home" checked style="cursor: pointer;">
                                            <span style="color: var(--text-light); font-weight: bold;"><?php echo htmlspecialchars($match['home_team_name']) ?> Wins</span>
                                        </label>
                                        <label style="cursor: pointer; padding: 0.75rem 1.5rem; background: rgba(255, 198, 0, 0.1); border: 2px solid transparent; border-radius: 0.5rem; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="radio" name="predicted_winner" value="draw" style="cursor: pointer;">
                                            <span style="color: var(--text-light); font-weight: bold;">Draw</span>
                                        </label>
                                        <label style="cursor: pointer; padding: 0.75rem 1.5rem; background: rgba(255, 198, 0, 0.1); border: 2px solid transparent; border-radius: 0.5rem; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="radio" name="predicted_winner" value="away" style="cursor: pointer;">
                                            <span style="color: var(--text-light); font-weight: bold;"><?php echo htmlspecialchars($match['away_team_name']) ?> Wins</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div style="text-align: center; margin-top: 1.5rem;">
                                    <button type="submit" class="btn btn-primary" style="font-size: 1rem; padding: 0.75rem 2rem;">Submit Both Predictions</button>
                                </div>
                                
                                <div style="text-align: center; margin-top: 1rem; color: var(--text-gray); font-size: 0.875rem;">
                                    <p>📊 Fill in BOTH sections above for maximum points</p>
                                    <p style="color: #fca500; font-size: 0.75rem; margin-top: 0.5rem;">⏳ Match time shown in Indian Standard Time (IST)</p>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Predictions Leaderboard -->
        <?php if ($match['status'] === 'completed' && !empty($predictions)): ?>
            <h3 style="color: var(--text-light); margin-bottom: 1rem;">Top Predictors</h3>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>
                        <th>Country</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($predictions as $index => $pred): ?>
                        <tr>
                            <td><?php echo $index + 1 ?></td>
                            <td><?php echo htmlspecialchars($pred['username']) ?></td>
                            <td><?php echo htmlspecialchars($pred['country']) ?></td>
                            <td style="color: var(--accent-color); font-weight: bold;">
                                <?php echo $pred['points'] ?> pts
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        // No need for toggle function - both predictions shown together
    </script>
    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
