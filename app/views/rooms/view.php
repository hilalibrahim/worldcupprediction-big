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
            <a href="/" class="navbar-brand"><span>⚽</span> PredictCup</a>
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
                <h3>Upcoming Matches</h3>
                <?php if (!empty($upcomingMatches)): ?>
                    <?php foreach ($upcomingMatches as $match): ?>
                        <div class="match-card">
                            <div class="match-team">
                                <div class="team-logo" style="font-size: 1.25rem; background: var(--accent-color); color: var(--bg-dark);">
                                    <?php echo htmlspecialchars($match['home_short_name'] ?? substr($match['home_team_name'], 0, 1)) ?>
                                </div>
                                <span><?php echo htmlspecialchars($match['home_team_name']) ?></span>
                            </div>
                            <div class="match-details">
                                <div class="match-time"><?php echo formatMatchDate($match['match_date']) ?></div>
                            </div>
                            <div class="match-team">
                                <div class="team-logo" style="font-size: 1.25rem; background: var(--accent-color); color: var(--bg-dark);">
                                    <?php echo htmlspecialchars($match['away_short_name'] ?? substr($match['away_team_name'], 0, 1)) ?>
                                </div>
                                <span><?php echo htmlspecialchars($match['away_team_name']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-gray);">No upcoming matches.</p>
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
