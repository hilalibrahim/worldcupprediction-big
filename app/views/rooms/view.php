<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name']) ?> - Room - PredictCup</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        :root {
            --r-gold: #ffd230;
            --r-gold-deep: #d4a017;
            --r-blue: #253b77;
            --r-blue-light: #3f67d0;
        }

        .rv-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        /* ===== HERO ===== */
        .rv-hero {
            position: relative;
            border-radius: 28px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
            background:
                radial-gradient(circle at 0% 0%, rgba(63, 103, 208, 0.25), transparent 45%),
                radial-gradient(circle at 100% 100%, rgba(255, 210, 48, 0.12), transparent 45%),
                linear-gradient(145deg, #0d0d0d, #161616);
            border: 1px solid rgba(255, 255, 255, 0.08);
            animation: rvFade 0.6s ease;
        }

        .rv-hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .rv-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(255, 210, 48, 0.12);
            border: 1px solid rgba(255, 210, 48, 0.3);
            border-radius: 50px;
            color: var(--r-gold);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .rv-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.05;
            letter-spacing: -1px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
        }

        .rv-desc {
            color: rgba(255, 255, 255, 0.65);
            font-size: 1.05rem;
            max-width: 560px;
            line-height: 1.6;
        }

        .rv-owner {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .rv-owner-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--r-blue-light), var(--r-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 1.1rem;
            border: 2px solid rgba(255, 210, 48, 0.4);
        }

        .rv-owner-text small {
            display: block;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rv-owner-text strong {
            color: #fff;
            font-size: 1rem;
        }

        /* Invite code card in hero */
        .rv-invite {
            background: rgba(0, 0, 0, 0.35);
            border: 1px dashed rgba(255, 210, 48, 0.4);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            text-align: center;
            min-width: 180px;
        }

        .rv-invite small {
            display: block;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.5rem;
        }

        .rv-invite-code {
            font-family: 'Courier New', monospace;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--r-gold);
            letter-spacing: 4px;
        }

        /* ===== STATS STRIP ===== */
        .rv-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .rv-stat {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .rv-stat:hover {
            border-color: var(--r-gold);
            transform: translateY(-4px);
        }

        .rv-stat-icon { font-size: 1.5rem; margin-bottom: 0.4rem; }
        .rv-stat-num { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; }
        .rv-stat-label { color: rgba(255, 255, 255, 0.55); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.35rem; }

        /* ===== ACTIONS + SHARE ===== */
        .rv-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
        }

        .rv-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

        .rv-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.3rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .rv-btn-primary {
            background: linear-gradient(135deg, var(--r-gold), var(--r-gold-deep));
            color: #000;
            box-shadow: 0 6px 18px rgba(255, 210, 48, 0.28);
        }
        .rv-btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 26px rgba(255, 210, 48, 0.4); }

        .rv-btn-ghost {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .rv-btn-ghost:hover { border-color: var(--r-gold); color: var(--r-gold); }

        .rv-btn-danger {
            background: rgba(255, 77, 79, 0.12);
            color: #ff6b6d;
            border: 1px solid rgba(255, 77, 79, 0.3);
        }
        .rv-btn-danger:hover { background: rgba(255, 77, 79, 0.2); }

        .rv-share {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex: 1;
            min-width: 280px;
            max-width: 480px;
        }

        .rv-share-input {
            flex: 1;
            padding: 0.7rem 1rem;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.82rem;
            font-family: monospace;
        }
        .rv-share-input:focus { outline: none; border-color: var(--r-gold); }

        .rv-copy-toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--r-gold);
            color: #000;
            padding: 0.85rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            opacity: 0;
            pointer-events: none;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(255, 210, 48, 0.4);
        }
        .rv-copy-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ===== TABS ===== */
        .rv-tabs {
            display: flex;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            padding: 0.4rem;
            margin-bottom: 2rem;
            position: sticky;
            top: 88px;
            z-index: 50;
            backdrop-filter: blur(16px);
        }

        .rv-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.85rem 1rem;
            background: transparent;
            border: none;
            border-radius: 50px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .rv-tab:hover { color: #fff; }

        .rv-tab.active {
            background: linear-gradient(135deg, var(--r-gold), var(--r-gold-deep));
            color: #000;
            box-shadow: 0 4px 16px rgba(255, 210, 48, 0.3);
        }

        .rv-tab-count {
            background: rgba(0, 0, 0, 0.2);
            padding: 0.1rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
        }
        .rv-tab:not(.active) .rv-tab-count { background: rgba(255, 255, 255, 0.1); }

        /* ===== PANELS ===== */
        .rv-panel { display: none; animation: rvFade 0.4s ease; }
        .rv-panel.active { display: block; }

        .rv-panel-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* ===== LEADERBOARD ===== */
        .rv-podium {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .rv-podium-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.5rem 1rem;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }
        .rv-podium-card:hover { transform: translateY(-6px); }

        .rv-podium-card.gold { border-color: rgba(255, 210, 48, 0.5); background: rgba(255, 210, 48, 0.08); order: 2; transform: scale(1.05); }
        .rv-podium-card.silver { border-color: rgba(192, 192, 192, 0.4); order: 1; margin-top: 1.5rem; }
        .rv-podium-card.bronze { border-color: rgba(205, 127, 50, 0.4); order: 3; margin-top: 1.5rem; }
        .rv-podium-card.gold:hover { transform: scale(1.05) translateY(-6px); }

        .rv-podium-medal { font-size: 2rem; margin-bottom: 0.5rem; }

        .rv-podium-avatar {
            width: 60px; height: 60px;
            border-radius: 50%;
            margin: 0 auto 0.75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; font-weight: 800; color: #000;
            background: linear-gradient(135deg, var(--r-gold), var(--r-gold-deep));
        }
        .rv-podium-card.silver .rv-podium-avatar { background: linear-gradient(135deg, #e8e8e8, #b0b0b0); }
        .rv-podium-card.bronze .rv-podium-avatar { background: linear-gradient(135deg, #e0a872, #cd7f32); color: #fff; }

        .rv-podium-name { color: #fff; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; word-break: break-word; }
        .rv-podium-pts { color: var(--r-gold); font-size: 1.4rem; font-weight: 800; }
        .rv-podium-sub { color: rgba(255, 255, 255, 0.5); font-size: 0.78rem; }

        .rv-rank-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            margin-bottom: 0.6rem;
            transition: all 0.25s ease;
        }
        .rv-rank-row:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 210, 48, 0.3); transform: translateX(4px); }

        .rv-rank-num {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;
            flex-shrink: 0;
        }

        .rv-rank-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--r-blue-light), var(--r-blue));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .rv-rank-info { flex: 1; }
        .rv-rank-name { color: #fff; font-weight: 600; }
        .rv-rank-stat { color: rgba(255, 255, 255, 0.5); font-size: 0.8rem; }
        .rv-rank-pts { color: var(--r-gold); font-weight: 800; font-size: 1.1rem; }

        /* ===== MATCH CARDS ===== */
        .rv-match {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }
        .rv-match:hover { border-color: rgba(255, 210, 48, 0.4); box-shadow: 0 14px 40px rgba(0, 0, 0, 0.4); }

        .rv-match-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .rv-match-time { color: var(--r-gold); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }

        .rv-pill {
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .rv-pill.predicted { background: rgba(30, 215, 96, 0.18); color: #1ed760; }
        .rv-pill.open { background: rgba(255, 210, 48, 0.18); color: var(--r-gold); }
        .rv-pill.locked { background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.5); }

        .rv-teams {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .rv-team { display: flex; flex-direction: column; align-items: center; gap: 0.6rem; text-align: center; }

        .rv-crest {
            width: 64px; height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(63, 103, 208, 0.35), rgba(255, 210, 48, 0.15));
            border: 2px solid rgba(255, 210, 48, 0.5);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 800; color: var(--r-gold);
            transition: transform 0.3s ease;
        }
        .rv-team:hover .rv-crest { transform: scale(1.08); }
        .rv-team-name { color: #fff; font-weight: 600; font-size: 0.92rem; }

        .rv-vs { color: rgba(255, 255, 255, 0.4); font-weight: 800; font-size: 1rem; }

        /* prediction display */
        .rv-pred-result {
            background: rgba(255, 210, 48, 0.08);
            border: 1px solid rgba(255, 210, 48, 0.25);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
        }
        .rv-pred-result-label { color: rgba(255, 255, 255, 0.55); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .rv-pred-score { font-size: 2rem; font-weight: 800; color: var(--r-gold); letter-spacing: 0.5rem; }
        .rv-pred-winner { color: #fff; margin-top: 0.5rem; font-size: 0.9rem; }
        .rv-pred-winner b { color: var(--r-gold); }
        .rv-pred-points { margin-top: 0.75rem; color: #1ed760; font-weight: 700; }

        /* prediction form */
        .rv-form { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .rv-form-box {
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.1rem;
        }
        .rv-form-box-label { color: rgba(255, 255, 255, 0.55); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; }

        .rv-score-row { display: flex; align-items: center; justify-content: center; gap: 0.6rem; }
        .rv-score-row input {
            width: 56px; height: 52px;
            background: #1a1a1a;
            border: 2px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #fff; font-size: 1.4rem; font-weight: 800; text-align: center;
            transition: all 0.25s ease;
        }
        .rv-score-row input:focus { outline: none; border-color: var(--r-gold); box-shadow: 0 0 0 3px rgba(255, 210, 48, 0.2); }
        .rv-score-dash { color: rgba(255, 255, 255, 0.4); font-weight: 800; }

        .rv-winner-opts { display: flex; flex-direction: column; gap: 0.45rem; }
        .rv-winner-opt {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 0.8rem;
            background: #1a1a1a;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .rv-winner-opt:hover { border-color: rgba(255, 210, 48, 0.5); }
        .rv-winner-opt input { accent-color: var(--r-gold); cursor: pointer; }
        .rv-winner-opt span { color: #fff; font-size: 0.85rem; font-weight: 500; }

        .rv-submit {
            grid-column: 1 / -1;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--r-gold), var(--r-gold-deep));
            color: #000;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .rv-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(255, 210, 48, 0.35); }

        .rv-locked {
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            padding: 1.25rem;
            background: rgba(0, 0, 0, 0.25);
            border-radius: 14px;
        }

        /* ===== MEMBERS ===== */
        .rv-members {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }
        .rv-member {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .rv-member:hover { transform: translateY(-6px); border-color: rgba(255, 210, 48, 0.4); }
        .rv-member-avatar {
            width: 58px; height: 58px;
            border-radius: 50%;
            margin: 0 auto 0.85rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, var(--r-blue-light), var(--r-blue));
            border: 2px solid rgba(255, 210, 48, 0.3);
        }
        .rv-member-name { color: #fff; font-weight: 700; margin-bottom: 0.75rem; }
        .rv-member-stats { display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; }
        .rv-chip {
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(255, 210, 48, 0.12);
            color: var(--r-gold);
        }
        .rv-chip.green { background: rgba(30, 215, 96, 0.12); color: #1ed760; }

        /* ===== EMPTY ===== */
        .rv-empty { text-align: center; padding: 3rem 1rem; color: rgba(255, 255, 255, 0.5); }
        .rv-empty-icon { font-size: 3rem; margin-bottom: 0.75rem; }

        @keyframes rvFade { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .rv-stats { grid-template-columns: repeat(2, 1fr); }
            .rv-hero-top { flex-direction: column; }
            .rv-invite { width: 100%; }
        }
        @media (max-width: 640px) {
            .rv-wrap { padding: 1.25rem 1rem 3rem; }
            .rv-hero { padding: 1.75rem; }
            .rv-podium { grid-template-columns: 1fr; }
            .rv-podium-card.gold, .rv-podium-card.silver, .rv-podium-card.bronze { order: 0; margin-top: 0; transform: none; }
            .rv-podium-card.gold:hover { transform: translateY(-6px); }
            .rv-form { grid-template-columns: 1fr; }
            .rv-tab span:not(.rv-tab-count) { display: none; }
            .rv-tab { font-size: 1.2rem; }
            .rv-toolbar { flex-direction: column; align-items: stretch; }
            .rv-share { max-width: none; }
        }
    </style>
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

    <?php
        $totalPredictions = array_sum(array_map(function($m) use ($roomPredictions) {
            return isset($roomPredictions[$m['id']]) ? 1 : 0;
        }, $upcomingMatches ?? []));
        $matchCount = count($upcomingMatches ?? []);
        $memberCount = count($members ?? []);
    ?>

    <div class="rv-wrap">
        <!-- HERO -->
        <div class="rv-hero">
            <div class="rv-hero-top">
                <div>
                    <span class="rv-hero-badge">🏆 Prediction Room</span>
                    <h1 class="rv-title"><?php echo htmlspecialchars($room['name']) ?></h1>
                    <?php if (!empty($room['description'])): ?>
                        <p class="rv-desc"><?php echo htmlspecialchars($room['description']) ?></p>
                    <?php endif; ?>
                    <div class="rv-owner">
                        <div class="rv-owner-avatar"><?php echo strtoupper(substr($room['owner_name'], 0, 1)) ?></div>
                        <div class="rv-owner-text">
                            <small>Room Owner</small>
                            <strong><?php echo htmlspecialchars($room['owner_name']) ?></strong>
                        </div>
                    </div>
                </div>

                <div class="rv-invite">
                    <small>Invite Code</small>
                    <div class="rv-invite-code"><?php echo htmlspecialchars($room['invite_code']) ?></div>
                </div>
            </div>

            <!-- STATS -->
            <div class="rv-stats">
                <div class="rv-stat">
                    <div class="rv-stat-icon">👥</div>
                    <div class="rv-stat-num"><?php echo $memberCount ?></div>
                    <div class="rv-stat-label">Members</div>
                </div>
                <div class="rv-stat">
                    <div class="rv-stat-icon">⚽</div>
                    <div class="rv-stat-num"><?php echo $matchCount ?></div>
                    <div class="rv-stat-label">Matches</div>
                </div>
                <div class="rv-stat">
                    <div class="rv-stat-icon">🎯</div>
                    <div class="rv-stat-num"><?php echo $totalPredictions ?></div>
                    <div class="rv-stat-label">Predictions</div>
                </div>
                <div class="rv-stat">
                    <div class="rv-stat-icon">🥇</div>
                    <div class="rv-stat-num"><?php echo !empty($leaderboard) ? ($leaderboard[0]['total_points'] ?? 0) : 0 ?></div>
                    <div class="rv-stat-label">Top Score</div>
                </div>
            </div>
        </div>

        <!-- TOOLBAR: Actions + Share -->
        <div class="rv-toolbar">
            <div class="rv-actions">
                <?php if ($isOwner): ?>
                    <a href="<?= BASE_URL ?>/rooms/edit/<?php echo $room['id'] ?>" class="rv-btn rv-btn-primary">✎ Edit Room</a>
                    <a href="<?= BASE_URL ?>/rooms/members/<?php echo $room['id'] ?>" class="rv-btn rv-btn-ghost">⚙ Manage</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/rooms/leave/<?php echo $room['id'] ?>" class="rv-btn rv-btn-danger">Leave Room</a>
            </div>

            <div class="rv-share">
                <input type="text" class="rv-share-input" id="roomLink" readonly
                    value="<?php echo htmlspecialchars(BASE_URL . '/rooms/join/' . $room['invite_code']); ?>">
                <button class="rv-btn rv-btn-primary" onclick="copyRoomLink()">📋 Copy</button>
                <button class="rv-btn rv-btn-ghost" onclick="shareRoom()">📤</button>
            </div>
        </div>

        <!-- TABS -->
        <div class="rv-tabs">
            <button class="rv-tab active" data-tab="leaderboard" onclick="switchTab('leaderboard', this)">
                <span>🏆</span><span>Leaderboard</span>
            </button>
            <button class="rv-tab" data-tab="matches" onclick="switchTab('matches', this)">
                <span>⚽</span><span>Matches</span> <span class="rv-tab-count"><?php echo $matchCount ?></span>
            </button>
            <button class="rv-tab" data-tab="members" onclick="switchTab('members', this)">
                <span>👥</span><span>Members</span> <span class="rv-tab-count"><?php echo $memberCount ?></span>
            </button>
        </div>

        <!-- LEADERBOARD PANEL -->
        <div id="leaderboard" class="rv-panel active">
            <h2 class="rv-panel-title">🏆 Room Leaderboard</h2>

            <?php if (!empty($leaderboard)): ?>
                <?php if ($currentPage == 1): ?>
                    <?php
                        $top3 = array_slice($leaderboard, 0, 3);
                        $rest = array_slice($leaderboard, 3);
                        $medals = ['🥇', '🥈', '🥉'];
                        $classes = ['gold', 'silver', 'bronze'];
                    ?>
                    <?php if (count($top3) > 0): ?>
                        <div class="rv-podium">
                            <?php foreach ($top3 as $i => $user): ?>
                                <div class="rv-podium-card <?php echo $classes[$i] ?>">
                                    <div class="rv-podium-medal"><?php echo $medals[$i] ?></div>
                                    <div class="rv-podium-avatar"><?php echo strtoupper(substr($user['username'], 0, 1)) ?></div>
                                    <div class="rv-podium-name"><?php echo htmlspecialchars($user['username']) ?></div>
                                    <div class="rv-podium-pts"><?php echo $user['total_points'] ?? 0 ?> pts</div>
                                    <div class="rv-podium-sub"><?php echo $user['correct_predictions'] ?? 0 ?> correct</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php $rest = $leaderboard; ?>
                <?php endif; ?>

                <?php foreach ($rest as $user): ?>
                    <div class="rv-rank-row">
                        <div class="rv-rank-num"><?php echo $user['rank'] ?></div>
                        <div class="rv-rank-avatar"><?php echo strtoupper(substr($user['username'], 0, 1)) ?></div>
                        <div class="rv-rank-info">
                            <div class="rv-rank-name"><?php echo htmlspecialchars($user['username']) ?></div>
                            <div class="rv-rank-stat"><?php echo $user['correct_predictions'] ?? 0 ?> correct predictions</div>
                        </div>
                        <div class="rv-rank-pts"><?php echo $user['total_points'] ?? 0 ?></div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.5rem;">
                        <div>
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>" class="rv-btn rv-btn-ghost">← Previous</a>
                            <?php else: ?>
                                <span class="rv-btn" style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed;">← Previous</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="color: rgba(255,255,255,0.5); font-size: 0.9rem;">
                            Page <span style="color: var(--r-gold); font-weight: bold;"><?= $currentPage ?></span> of <?= $totalPages ?>
                        </div>
                        
                        <div>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?>" class="rv-btn rv-btn-ghost">Next →</a>
                            <?php else: ?>
                                <span class="rv-btn" style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border: 1px solid rgba(255,255,255,0.1); cursor: not-allowed;">Next →</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="rv-empty">
                    <div class="rv-empty-icon">📊</div>
                    <p>No predictions yet. Be the first to climb the ranks!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- MATCHES PANEL -->
        <div id="matches" class="rv-panel">
            <h2 class="rv-panel-title">⚽ Upcoming Matches</h2>

            <?php if (!empty($upcomingMatches)): ?>
                <?php foreach (array_slice($upcomingMatches, 0, 5) as $match):
                    $matchPredictions = $roomPredictions[$match['id']] ?? null;
                    $hasPredicted = !empty($matchPredictions);
                    $isLocked = isMatchLocked($match['match_date']);
                ?>
                    <div class="rv-match">
                        <div class="rv-match-top">
                            <div class="rv-match-time">🕐 <?php echo formatMatchDate($match['match_date']) ?></div>
                            <div class="rv-pill <?php echo $hasPredicted ? 'predicted' : ($isLocked ? 'locked' : 'open') ?>">
                                <?php echo $hasPredicted ? '✓ Predicted' : ($isLocked ? '🔒 Locked' : '◯ Open') ?>
                            </div>
                        </div>

                        <div class="rv-teams">
                            <div class="rv-team">
                                <div class="rv-crest"><?php echo htmlspecialchars($match['home_short_name'] ?? substr($match['home_team_name'], 0, 1)) ?></div>
                                <div class="rv-team-name"><?php echo htmlspecialchars($match['home_team_name']) ?></div>
                            </div>
                            <div class="rv-vs">VS</div>
                            <div class="rv-team">
                                <div class="rv-crest"><?php echo htmlspecialchars($match['away_short_name'] ?? substr($match['away_team_name'], 0, 1)) ?></div>
                                <div class="rv-team-name"><?php echo htmlspecialchars($match['away_team_name']) ?></div>
                            </div>
                        </div>

                        <?php if ($hasPredicted): ?>
                            <div class="rv-pred-result">
                                <div class="rv-pred-result-label">Your Prediction</div>
                                <div class="rv-pred-score"><?php echo $matchPredictions['home_score'] ?> : <?php echo $matchPredictions['away_score'] ?></div>
                                <div class="rv-pred-winner">Winner: <b><?php
                                    if ($matchPredictions['predicted_winner'] === 'home') echo htmlspecialchars($match['home_team_name']);
                                    elseif ($matchPredictions['predicted_winner'] === 'away') echo htmlspecialchars($match['away_team_name']);
                                    else echo 'Draw';
                                ?></b></div>
                                <?php if ($match['status'] === 'completed'): ?>
                                    <div class="rv-pred-points">✓ +<?php echo $matchPredictions['points'] ?? 0 ?> points</div>
                                <?php endif; ?>
                            </div>
                        <?php elseif (!$isLocked): ?>
                            <form method="POST" action="<?= BASE_URL ?>/predict" class="rv-form">
                                <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
                                <div class="rv-form-box">
                                    <div class="rv-form-box-label">🎯 Exact Score</div>
                                    <div class="rv-score-row">
                                        <input type="number" name="home_score" min="0" max="20" value="0" required>
                                        <span class="rv-score-dash">:</span>
                                        <input type="number" name="away_score" min="0" max="20" value="0" required>
                                    </div>
                                </div>
                                <div class="rv-form-box">
                                    <div class="rv-form-box-label">👑 Who Wins?</div>
                                    <div class="rv-winner-opts">
                                        <label class="rv-winner-opt">
                                            <input type="radio" name="predicted_winner" value="home" checked required>
                                            <span><?php echo htmlspecialchars(substr($match['home_team_name'], 0, 14)) ?></span>
                                        </label>
                                        <label class="rv-winner-opt">
                                            <input type="radio" name="predicted_winner" value="draw" required>
                                            <span>Draw</span>
                                        </label>
                                        <label class="rv-winner-opt">
                                            <input type="radio" name="predicted_winner" value="away" required>
                                            <span><?php echo htmlspecialchars(substr($match['away_team_name'], 0, 14)) ?></span>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="rv-submit">Submit Prediction</button>
                            </form>
                        <?php else: ?>
                            <div class="rv-locked">🔒 Predictions are closed for this match</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="rv-empty">
                    <div class="rv-empty-icon">📅</div>
                    <p>No upcoming matches to predict right now.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- MEMBERS PANEL -->
        <div id="members" class="rv-panel">
            <h2 class="rv-panel-title">👥 Room Members</h2>

            <?php if (!empty($members)): ?>
                <div class="rv-members">
                    <?php foreach ($members as $member): ?>
                        <div class="rv-member">
                            <div class="rv-member-avatar"><?php echo strtoupper(substr($member['username'], 0, 1)) ?></div>
                            <div class="rv-member-name"><?php echo htmlspecialchars($member['username']) ?></div>
                            <div class="rv-member-stats">
                                <span class="rv-chip"><?php echo $member['predictions'] ?? 0 ?> preds</span>
                                <span class="rv-chip green"><?php echo $member['correct_predictions'] ?? 0 ?> ✓</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rv-empty">
                    <div class="rv-empty-icon">👥</div>
                    <p>No members yet. Share the invite link to get started!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="rv-copy-toast" id="copyToast">✓ Link copied to clipboard!</div>

    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
    <script>
        function switchTab(tabName, btn) {
            document.querySelectorAll('.rv-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.rv-tab').forEach(t => t.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            btn.classList.add('active');
            localStorage.setItem('rvTab', tabName);
        }

        function showToast(msg) {
            const toast = document.getElementById('copyToast');
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2500);
        }

        function copyRoomLink() {
            const input = document.getElementById('roomLink');
            input.select();
            input.setSelectionRange(0, 99999);
            try {
                document.execCommand('copy');
                showToast('✓ Link copied to clipboard!');
            } catch (err) {
                console.error('Copy failed:', err);
            }
        }

        function shareRoom() {
            const url = document.getElementById('roomLink').value;
            const name = '<?php echo htmlspecialchars($room["name"], ENT_QUOTES) ?>';
            if (navigator.share) {
                navigator.share({
                    title: 'Join ' + name + ' on PredictCup',
                    text: 'Join me in this World Cup 2026 prediction room!',
                    url: url
                }).catch(err => { if (err.name !== 'AbortError') console.error(err); });
            } else {
                copyRoomLink();
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('rvTab') || 'leaderboard';
            const btn = document.querySelector(`.rv-tab[data-tab="${saved}"]`);
            if (btn) switchTab(saved, btn);
        });
    </script>
</body>
</html>
