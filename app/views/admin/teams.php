<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams - Admin - PredictCup</title>
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
                <a href="/worldcupprediction-big/admin/teams" class="active">Teams</a>
                <a href="/worldcupprediction-big/admin/matches">Matches</a>
                <a href="/worldcupprediction-big/admin/rooms">Rooms</a>
                <a href="/worldcupprediction-big/admin/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Team Management</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Add Team Form -->
        <div style="background: var(--glass-bg); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem;">
            <h3 style="color: var(--text-light); margin-bottom: 1rem;">Add New Team</h3>
            <form method="POST" action="/worldcupprediction-big/admin/teams">
                <input type="hidden" name="action" value="add">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div>
                        <label class="form-label">Team Name</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Group</label>
                        <select name="group_letter" class="form-input">
                            <option value="">No Group</option>
                            <option value="A">Group A</option>
                            <option value="B">Group B</option>
                            <option value="C">Group C</option>
                            <option value="D">Group D</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Add Team</button>
            </form>
        </div>

        <!-- Teams List -->
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Country</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td><?php echo $team['id'] ?></td>
                        <td><?php echo htmlspecialchars($team['name']) ?></td>
                        <td><?php echo htmlspecialchars($team['short_name']) ?></td>
                        <td><?php echo htmlspecialchars($team['country']) ?></td>
                        <td><?php echo $team['group_letter'] ?? '-' ?></td>
                        <td>
                            <span class="badge <?php echo $team['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?php echo $team['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="/worldcupprediction-big/admin/teams" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $team['id'] ?>">
                                <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;" onclick="return confirm('Delete this team?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="/worldcupprediction-big/public/js/main.js"></script>
</body>
</html>
