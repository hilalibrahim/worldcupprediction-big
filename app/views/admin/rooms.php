<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Admin - PredictCup</title>
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
                <a href="/worldcupprediction-big/admin/matches">Matches</a>
                <a href="/worldcupprediction-big/admin/rooms" class="active">Rooms</a>
                <a href="/worldcupprediction-big/admin/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Room Management</h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Name</th>
                    <th>Owner</th>
                    <th>Members</th>
                    <th>Invite Code</th>
                    <th>Public</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?php echo $room['id'] ?></td>
                        <td><?php echo htmlspecialchars($room['name']) ?></td>
                        <td><?php echo htmlspecialchars($room['owner_name']) ?></td>
                        <td><?php echo $room['member_count'] ?></td>
                        <td style="font-family: monospace;"><?php echo htmlspecialchars($room['invite_code']) ?></td>
                        <td>
                            <span class="badge <?php echo $room['is_public'] ? 'badge-success' : 'badge-warning' ?>">
                                <?php echo $room['is_public'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="/worldcupprediction-big/admin/rooms">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $room['id'] ?>">
                                <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Delete this room?');">Delete</button>
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
