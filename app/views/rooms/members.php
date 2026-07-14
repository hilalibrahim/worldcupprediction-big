<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Members - PredictCup</title>
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
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
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
            <a href="<?= BASE_URL ?>/daily-matches" class="nav-link">Matches</a>
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

    <div class="container" style="max-width: 900px; padding: 2rem;">
        <h2 style="color: var(--text-light); margin-bottom: 2rem;">Room Members - <?php echo htmlspecialchars($room['name']) ?></h2>

        <?php if ($flashMessage = getFlashMessage()): ?>
            <div class="alert alert-<?php echo $flashMessage['type'] ?>">
                <?php echo htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($isOwner): ?>
            <div style="background: rgba(251, 191, 36, 0.1); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid var(--accent-color);">
                <span class="badge badge-warning">Owner Only</span>
                Only room owners can remove members
            </div>
        <?php endif; ?>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Country</th>
                    <th>Joined</th>
                    <th>Predictions</th>
                    <th>Points</th>
                    <?php if ($isOwner): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div class="team-logo" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                    <?php echo substr($member['username'], 0, 1) ?>
                                </div>
                                <?php echo htmlspecialchars($member['username']) ?>
                                <?php if ($member['role'] === 'owner'): ?>
                                    <span class="badge badge-warning">Owner</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($member['country']) ?></td>
                        <td style="color: var(--text-gray); font-size: 0.875rem;">
                            <?php echo formatDate($member['joined_at']) ?>
                        </td>
                        <td><?php echo $member['predictions'] ?></td>
                        <td style="color: var(--accent-color); font-weight: bold;">
                            <?php echo $member['correct_predictions'] ?> correct | 
                            <span style="color: var(--text-light);">
                                <?php echo $member['points'] ?> pts
                            </span>
                        </td>
                        <?php if ($isOwner): ?>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/rooms/members/<?php echo $room['id'] ?>">
                                    <input type="hidden" name="remove_member" value="1">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;" onclick="return confirm('Remove <?php echo htmlspecialchars($member['username']) ?> from room?');">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
