<?php
$lib_path = dirname(__DIR__) . '/lib/functions.php';
if (!file_exists($lib_path)) {
    die('Missing: ' . $lib_path);
}
require_once $lib_path;
autoload_core(); 

if (empty($_SESSION['admin_id'])) {
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header('Location: login');
        exit;
    }
}

// CSRF token 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = $GLOBALS['pdo'] ?? null;

try {
    if ($pdo === null) {
        throw new RuntimeException('Koneksi database tidak tersedia.');
    }

    $total_subs = (int) $pdo->query(
        "SELECT COUNT(*) FROM subscribers"
    )->fetchColumn();

    $today_subs = (int) $pdo->query(
        "SELECT COUNT(*) FROM subscribers WHERE DATE(subscribed_at) = CURDATE()"
    )->fetchColumn();

} catch (RuntimeException | PDOException $e) {
    $total_subs = 0;
    $today_subs = 0;
    $db_error   = 'Database tidak dapat dijangkau. Silakan coba lagi.';
    error_log('[dashboard] Error: ' . $e->getMessage());
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Admin</title>
<style>
        /* ── Stat cards ── */
        .stat-card {
            background: var(--blue-200);
            border: 2px solid var(--blue-300);
            border-radius: 16px;
            padding: 24px;
            display: flex; align-items: center; gap: 16px;
            transition: transform .25s, box-shadow .25s;
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ""; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--blue-400);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }

        .stat-icon {
            width: 52px; height: 52px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-icon.users  { background: var(--blue-100); color: var(--blue-800); }
        .stat-icon.visits { background: var(--blue-100); color: var(--blue-700); }

        .stat-body { flex: 1; }
        .stat-number { font-size: 2rem; font-weight: 700; color: var(--blue-900); line-height: 1; }
        .stat-label  { font-size: .8rem; color: var(--blue-950); margin-top: 4px; }
        .stat-badge  { font-size: .75rem; margin-top: 6px; color: var(--blue-800); }

        /* ── Responsive ── */
        @media (min-width: 1025px) {
            .mobile-header { display: none; }
            .sidebar { transform: translateX(0); top: 0; }
            .main-content { margin-left: 260px; margin-top: 0; }
            #mobile-toggle:checked ~ .admin-container::before { display: none; }
        }
        @media (max-width: 480px) {
            .sidebar { width: 88vw; }
            .main-content { padding: 12px; }
        }

        /* Stats grid — selalu 2 kolom sejajar */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<?php include 'includes/header.php'; ?>

<div>

    <?php if (!empty($db_error)): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fa fa-triangle-exclamation"></i>
            <?= htmlspecialchars($db_error) ?>
        </div>
    <?php endif; ?>

    <!-- Stats — 1 baris, 2 kolom -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon users"><i class="fas fa-users"></i></div>
            <div class="stat-body">
                <div class="stat-number"><?= number_format($total_subs) ?></div>
                <div class="stat-label">Total Subscriber</div>
                <div class="stat-badge">
                    <i class="fas fa-arrow-up"></i> +<?= $today_subs ?> hari ini
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon visits"><i class="fas fa-chart-line"></i></div>
            <div class="stat-body">
                <div class="stat-number">—</div>
                <div class="stat-label">Total Pengunjung</div>
                <div class="stat-badge">
                    <i class="fas fa-clock"></i> Segera tersedia
                </div>
            </div>
        </div>

    </div>



</div>

<?php include 'includes/footer.php'; ?>
