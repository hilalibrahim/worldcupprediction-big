<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link active">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
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
            <a href="<?= BASE_URL ?>/" class="nav-link">Home</a>
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link active">Matches</a>
            <a href="<?= BASE_URL ?>/leaderboard" class="nav-link">Leaderboard</a>
            
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

    <!-- Page Header -->
    <div style="text-align: center; padding: 3rem 1.5rem 1rem;">
        <span style="color: var(--gold); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Match Details</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-top: 0.5rem; color: #E5E5E5; text-transform: uppercase;">Prediction Arena</h1>
        <div style="width: 60px; height: 2px; background: var(--gold); margin: 1rem auto 0;"></div>
    </div>

    <div style="max-width: 900px; margin: 0 auto; padding: 1rem 1.5rem 4rem;">
        <!-- Match Header -->
        <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--glass-border);">
                <span class="badge badge-warning"><?php echo htmlspecialchars($match['stage'] ?? 'Match') ?></span>
                <span style="color: var(--gray); font-size: 0.9rem;">
                    <?php echo formatMatchDate($match['match_date']) ?>
                </span>
            </div>

            <div style="display: flex; align-items: center; justify-content: center; gap: 3rem; margin-bottom: 1.5rem;">
                <div style="text-align: center; flex: 1;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--black-elevated); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-family: var(--font-display); color: var(--gold); font-size: 1.5rem;">
                        <?php echo htmlspecialchars($match['home_short_name'] ?? $match['home_team_name'][0]) ?>
                    </div>
                    <div style="color: #E5E5E5; font-size: 1.2rem; font-weight: 600;"><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                </div>

                <div style="text-align: center;">
                    <?php if ($match['status'] === 'completed'): ?>
                        <div style="font-family: var(--font-display); font-size: 3.5rem; color: var(--gold);">
                            <?php echo $match['home_score'] ?> - <?php echo $match['away_score'] ?>
                        </div>
                    <?php else: ?>
                        <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gray);">VS</div>
                    <?php endif; ?>
                </div>

                <div style="text-align: center; flex: 1;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--black-elevated); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-family: var(--font-display); color: var(--gold); font-size: 1.5rem;">
                        <?php echo htmlspecialchars($match['away_short_name'] ?? $match['away_team_name'][0]) ?>
                    </div>
                    <div style="color: #E5E5E5; font-size: 1.2rem; font-weight: 600;"><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                </div>
            </div>

            <?php if ($match['stadium']): ?>
                <div style="text-align: center; color: var(--gray); font-size: 0.9rem;">
                    🏟️ <?php echo htmlspecialchars($match['stadium']) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isLoggedIn() && $match['status'] !== 'completed'): ?>
            <?php if ($myPrediction): ?>
                <!-- Already predicted -->
                <div style="background: rgba(30, 215, 96, 0.1); border: 1px solid var(--success); border-radius: 12px; padding: 2rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">✅</div>
                    <h3 style="color: var(--success); font-size: 1.5rem; margin-bottom: 1.5rem;">Prediction Locked In</h3>
                    
                    <div style="display: flex; justify-content: center; gap: 3rem;">
                        <div>
                            <div style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Exact Score</div>
                            <div style="font-family: var(--font-display); font-size: 2rem; color: #E5E5E5;">
                                <?php echo $myPrediction['home_score']; ?> - <?php echo $myPrediction['away_score']; ?>
                            </div>
                        </div>
                        <div style="width: 1px; background: rgba(255,255,255,0.1);"></div>
                        <div>
                            <div style="color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Winner</div>
                            <div style="font-size: 1.2rem; color: #E5E5E5; font-weight: 600; line-height: 2rem;">
                                <?php 
                                if ($myPrediction['predicted_winner'] === 'home') {
                                    echo htmlspecialchars($match['home_team_name']) . ' Wins';
                                } elseif ($myPrediction['predicted_winner'] === 'away') {
                                    echo htmlspecialchars($match['away_team_name']) . ' Wins';
                                } else {
                                    echo 'Draw';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif (isMatchLocked($match['match_date'])): ?>
                <!-- Predictions Locked -->
                <div style="background: rgba(255, 77, 79, 0.1); border: 1px solid var(--error); border-radius: 12px; padding: 2rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔒</div>
                    <h3 style="color: var(--error); font-size: 1.5rem; margin-bottom: 0.5rem;">Predictions Closed</h3>
                    <p style="color: var(--gray); margin-bottom: 1rem;">This match is starting soon or has already started.</p>
                </div>
            <?php else: ?>
                <!-- Prediction form -->
                <div style="margin-top: 2rem;">
                    <?php 
                    $timeRemaining = getPredictionTimeRemaining($match['match_date']);
                    if ($timeRemaining !== false):
                    ?>
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <span class="badge badge-warning" style="font-size: 0.85rem; padding: 8px 16px;">
                            ⏳ Closes in <?php echo $timeRemaining; ?> minutes
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= BASE_URL ?>/predict" id="predictForm" style="background: var(--black-card); border: 1px solid var(--gold); border-radius: 12px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(255,142,60,0.05);">
                        <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                        
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <h3 style="font-size: 1.5rem; color: var(--gold); text-transform: uppercase; letter-spacing: 1px;">Make Your Call</h3>
                        </div>
                        
                        <!-- Score Prediction -->
                        <div style="margin-bottom: 2.5rem;">
                            <div style="text-align: center; margin-bottom: 1.5rem;">
                                <div style="color: #E5E5E5; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem;">Exact Score</div>
                                <div style="color: var(--gold); font-size: 0.85rem;">Earn 10 points for the perfect guess</div>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem;">
                                <div style="text-align: center;">
                                    <label style="color: var(--gray); display: block; font-size: 0.85rem; margin-bottom: 0.75rem; text-transform: uppercase; font-weight: 600;"><?php echo htmlspecialchars(substr($match['home_team_name'], 0, 12)) ?></label>
                                    <input type="number" name="home_score" class="score-input" min="0" max="20" value="0">
                                </div>
                                <div style="font-family: var(--font-display); font-size: 2.5rem; color: var(--gray);">-</div>
                                <div style="text-align: center;">
                                    <label style="color: var(--gray); display: block; font-size: 0.85rem; margin-bottom: 0.75rem; text-transform: uppercase; font-weight: 600;"><?php echo htmlspecialchars(substr($match['away_team_name'], 0, 12)) ?></label>
                                    <input type="number" name="away_score" class="score-input" min="0" max="20" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Winner Prediction -->
                        <div style="margin-bottom: 2.5rem; padding-top: 2.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                            <div style="text-align: center; margin-bottom: 1.5rem;">
                                <div style="color: #E5E5E5; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem;">Match Result</div>
                                <div style="color: var(--gold); font-size: 0.85rem;">Earn 5 points for picking the outcome</div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                                <label style="cursor: pointer; position: relative;">
                                    <input type="radio" name="predicted_winner" value="home" checked style="position: absolute; opacity: 0; pointer-events: none;">
                                    <div class="radio-card" style="padding: 1rem; text-align: center; border: 1px solid var(--glass-border); border-radius: 8px; transition: all 0.2s;">
                                        <span style="color: #E5E5E5; font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                                    </div>
                                </label>
                                <label style="cursor: pointer; position: relative;">
                                    <input type="radio" name="predicted_winner" value="draw" style="position: absolute; opacity: 0; pointer-events: none;">
                                    <div class="radio-card" style="padding: 1rem; text-align: center; border: 1px solid var(--glass-border); border-radius: 8px; transition: all 0.2s;">
                                        <span style="color: #E5E5E5; font-weight: 600; font-size: 0.95rem;">Draw</span>
                                    </div>
                                </label>
                                <label style="cursor: pointer; position: relative;">
                                    <input type="radio" name="predicted_winner" value="away" style="position: absolute; opacity: 0; pointer-events: none;">
                                    <div class="radio-card" style="padding: 1rem; text-align: center; border: 1px solid var(--glass-border); border-radius: 8px; transition: all 0.2s;">
                                        <span style="color: #E5E5E5; font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                                    </div>
                                </label>
                            </div>
                            <style>
                                input[type="radio"]:checked + .radio-card {
                                    border-color: var(--gold);
                                    background: rgba(255,142,60,0.1);
                                }
                                input[type="radio"]:checked + .radio-card span {
                                    color: var(--gold);
                                }
                            </style>
                        </div>
                        
                        <button type="submit" class="btn tbc-btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1.2rem;">Lock In Prediction</button>
                    </form>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const homeInput = document.querySelector('input[name="home_score"]');
                            const awayInput = document.querySelector('input[name="away_score"]');
                            const radios = document.querySelectorAll('input[name="predicted_winner"]');
                            
                            function updateWinner() {
                                const h = parseInt(homeInput.value) || 0;
                                const a = parseInt(awayInput.value) || 0;
                                let winner = 'draw';
                                if (h > a) winner = 'home';
                                if (a > h) winner = 'away';
                                
                                radios.forEach(radio => {
                                    radio.checked = (radio.value === winner);
                                });
                            }
                            
                            homeInput.addEventListener('input', updateWinner);
                            awayInput.addEventListener('input', updateWinner);
                            
                            // Prevent manual clicking on radios
                            radios.forEach(radio => {
                                radio.addEventListener('click', (e) => e.preventDefault());
                            });
                            
                            updateWinner();
                        });
                    </script>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Predictions Leaderboard -->
        <?php if ($match['status'] === 'completed' && !empty($predictions)): ?>
            <div style="margin-top: 3rem;">
                <h3 style="color: var(--gold); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem;">Top Predictors</h3>
                <div style="background: var(--black-card); border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Rank</th>
                                <th style="padding: 1rem; text-align: left; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Player</th>
                                <th style="padding: 1rem; text-align: right; color: var(--gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = isset($currentPage) ? ($currentPage - 1) * 20 + 1 : 1;
                            foreach ($predictions as $index => $pred): 
                                $currentRank = $rank++;
                            ?>
                                <tr style="border-bottom: 1px solid var(--glass-border); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 0.85rem 1rem; color: <?php echo $currentRank <= 3 ? 'var(--gold)' : 'var(--gray)' ?>; font-weight: <?php echo $currentRank <= 3 ? '700' : '400' ?>;">
                                        <?php echo $currentRank ?>
                                    </td>
                                    <td style="padding: 0.85rem 1rem; color: #D9D9D9;">
                                        <?php echo htmlspecialchars($pred['username']) ?>
                                        <span style="color: var(--gray); font-size: 0.8rem; margin-left: 0.5rem;">(<?php echo htmlspecialchars($pred['country']) ?>)</span>
                                    </td>
                                    <td style="padding: 0.85rem 1rem; text-align: right; color: var(--gold); font-weight: 600; font-family: var(--font-display); font-size: 1.1rem;">
                                        <?php echo $pred['points'] ?> pts
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
                        <div>
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">← Previous</a>
                            <?php else: ?>
                                <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">← Previous</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="color: var(--gray); font-size: 0.9rem;">
                            Page <span style="color: var(--gold); font-weight: bold;"><?= $currentPage ?></span> of <?= $totalPages ?>
                        </div>
                        
                        <div>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?>" class="btn tbc-btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Next →</a>
                            <?php else: ?>
                                <span class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: rgba(255,255,255,0.05); color: var(--gray); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed; opacity: 0.5;">Next →</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
