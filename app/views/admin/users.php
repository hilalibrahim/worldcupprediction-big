<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin - PredictCup</title>
    <link rel="stylesheet" href="/worldcupprediction-big/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><img src="/worldcupprediction-big/public/uploads/logo.png" alt="PredictCup Logo" style="height: 40px; margin-right: 10px;"><span>PredictCup</span></a>
            <div class="navbar-menu">
                <a href="/">Home</a>
                <a href="/worldcupprediction-big/admin/dashboard">Admin Dashboard</a>
                <a href="/worldcupprediction-big/admin/users" class="active">Users</a>
                <a href="/worldcupprediction-big/admin/teams">Teams</a>
                <a href="/worldcupprediction-big/admin/matches">Matches</a>
                <a href="/worldcupprediction-big/admin/rooms">Rooms</a>
                <a href="/worldcupprediction-big/admin/api">API</a>
                <a href="/worldcupprediction-big/admin/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">User Management</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Country</th>
                    <th>Points</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id'] ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div class="team-logo" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                    <?php echo substr($user['username'], 0, 1) ?>
                                </div>
                                <?php echo htmlspecialchars($user['username']) ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['country']) ?></td>
                        <td style="color: var(--accent-color); font-weight: bold;">
                            <?php echo $user['points'] ?>
                        </td>
                        <td style="color: var(--text-gray); font-size: 0.875rem;">
                            <?php echo formatDate($user['join_date']) ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $user['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?php echo $user['is_active'] ? 'Active' : 'Banned' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo $user['is_admin'] ? 'badge-warning' : 'badge-success' ?>">
                                <?php echo $user['is_admin'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <form method="POST" action="/worldcupprediction-big/admin/users" style="display: inline;">
                                    <input type="hidden" name="action" value="ban">
                                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Ban this user?');">
                                        <?php echo $user['is_active'] ? 'Ban' : 'Unban' ?>
                                    </button>
                                </form>

                                <form method="POST" action="/worldcupprediction-big/admin/users" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Delete this user?');">Delete</button>
                                </form>

                                <?php if (!$user['is_admin']): ?>
                                    <form method="POST" action="/worldcupprediction-big/admin/users" style="display: inline;">
                                        <input type="hidden" name="action" value="make_admin">
                                        <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                        <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Make this user admin?');">Admin</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="/worldcupprediction-big/admin/users" style="display: inline;">
                                        <input type="hidden" name="action" value="remove_admin">
                                        <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                                        <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Remove admin privileges?');">Remove Admin</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
