<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/admin/dashboard" class="active">Admin Dashboard</a>
                <a href="/worldcupprediction-big/admin/users">Users</a>
                <a href="/worldcupprediction-big/admin/teams">Teams</a>
                <a href="/worldcupprediction-big/admin/matches">Matches</a>
                <a href="/worldcupprediction-big/admin/rooms">Rooms</a>
                <a href="/worldcupprediction-big/admin/api">API</a>
                <a href="/worldcupprediction-big/admin/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1400px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Admin Dashboard</h2>

        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo $totalUsers['count'] ?? 0 ?>
                </div>
                <div class="stat-label">Registered users</div>
            </div>
            <div class="stat-card">
                <h3>Total Rooms</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo $totalRooms['count'] ?? 0 ?>
                </div>
                <div class="stat-label">Active rooms</div>
            </div>
            <div class="stat-card">
                <h3>Total Predictions</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo $totalPredictions['count'] ?? 0 ?>
                </div>
                <div class="stat-label">Predictions made</div>
            </div>
            <div class="stat-card">
                <h3>Total Matches</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    <?php echo $totalMatches['count'] ?? 0 ?>
                </div>
                <div class="stat-label">All matches</div>
            </div>
            <div class="stat-card">
                <h3>Matches Played</h3>
                <div class="stat-value" style="color: var(--success);">
                    <?php echo $matchesPlayed['count'] ?? 0 ?>
                </div>
                <div class="stat-label">Completed matches</div>
            </div>
            <div class="stat-card">
                <h3>Room Invites</h3>
                <div class="stat-value" style="color: var(--accent-color);">
                    0
                </div>
                <div class="stat-label">Pending invitations</div>
            </div>
        </div>

        <div class="dashboard-grid" style="margin-top: 2rem;">
            <div class="stat-card">
                <h3>Recent Activity</h3>
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                            <th>User</th>
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
                            <tr>
                                <td style="font-size: 0.875rem; color: var(--text-gray);">
                                    <?php echo formatDate($activity['created_at']) ?>
                                </td>
                                <td><?php echo htmlspecialchars($activity['action']) ?></td>
                                <td><?php echo htmlspecialchars($activity['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="stat-card">
                <h3>Quick Actions</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <a href="/worldcupprediction-big/admin/teams" class="btn btn-secondary">Manage Teams</a>
                    <a href="/worldcupprediction-big/admin/matches" class="btn btn-secondary">Manage Matches</a>
                    <a href="/worldcupprediction-big/admin/users" class="btn btn-secondary">Manage Users</a>
                    <a href="/worldcupprediction-big/admin/rooms" class="btn btn-secondary">Manage Rooms</a>
                </div>
            </div>
        </div>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
