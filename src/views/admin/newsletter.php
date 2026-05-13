<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit('Redirecting...');
}

// Pastikan PDO connection ada
if (!isset($pdo)) {
    die('Database connection missing!');
}


if (isset($_POST['send_newsletter'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if ($subject && $message) {
        $stmt = $pdo->prepare("INSERT INTO newsletters (subject, message, total_recipients, status) VALUES (?, ?, 0, 'draft')");
        $stmt->execute([$subject, $message]);
        $newsletter_id = $pdo->lastInsertId();
        
        $stmt = $pdo->query('SELECT id, email FROM subscribers WHERE status = "active"');
        $sent = 0; $total = 0;
        
        while ($sub = $stmt->fetch()) {
            $unsubscribe_token = generateUnsubscribeToken($pdo, $sub['id']);
            $unsub_link = "https://ayokebandung.id/pages/unsubscribe?token=" . $unsubscribe_token;
            
            $html_message = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:sans-serif">
    <div style="max-width:600px;margin:0 auto;background:#0f172a">
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:40px 30px;text-align:center">
            <h1 style="color:white;margin:0;font-size:28px;font-weight:700;">Ayokebandung.id</h1>
            <p style="color:#e2e8f0;margin:8px 0 0;font-size:16px">Update terbaru untuk Anda</p>
        </div>
        <div style="background:white;padding:40px 30px">
            <div style="border-left:4px solid #667eea;padding-left:20px;margin-bottom:30px">
                <h2 style="color:#1e293b;margin:0;font-size:24px;">' . htmlspecialchars($subject) . '</h2>
                <p style="color:#64748b;margin:8px 0 0;font-size:14px">Dikirim pada ' . date('d M Y, H:i') . '</p>
            </div>
            <div style="line-height:1.7;color:#1e293b;font-size:16px;margin-bottom:30px">
                ' . nl2br(htmlspecialchars($message)) . '
            </div>
            <div style="text-align:center;margin:30px 0">
                <a href="https://ayokebandung.id" style="display:inline-block;padding:16px 32px;background:linear-gradient(135deg,#667eea,#764ba2);color:white;text-decoration:none;border-radius:12px;font-weight:600;">Lihat Sekarang</a>
            </div>
        </div>
        <div style="background:#1e293b;padding:30px;color:#94a3b3;font-size:13px;text-align:center">
            <p>' . date('Y') . ' Ayokebandung.id. </p>
            <p style="margin:15px 0">
                <a href="' . $unsub_link . '" style="color:#60a5fa;text-decoration:underline;">Berhenti berlangganan</a>
            </p>
        </div>
    </div>
</body>
</html>';

            if (kirimEmailAyo($sub['email'], $subject, $html_message)) {
                $sent++;
            }
            $total++;
            usleep(100000);
        }
        
        $stmt = $pdo->prepare("UPDATE newsletters SET total_recipients = ?, sent_recipients = ?, status = 'sent', sent_at = NOW() WHERE id = ?");
        $stmt->execute([$total, $sent, $newsletter_id]);
        
        $_SESSION['newsletter_success'] = "Newsletter terkirim ke <strong>$sent/$total</strong> orang!";
        header('Location: ' . $_SERVER['PHP_SELF'] . '#history');
        exit;
    }
}

$total_active = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();
$total_unsub = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='unsubscribed'")->fetchColumn();
$total_deleted = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='deleted'")->fetchColumn();
$total_all = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();

$active_filter = $_GET['status'] ?? 'active';
$status_map = [
    'active' => ['status = ?', ['active']],
    'unsubscribed' => ['status = ?', ['unsubscribed']], 
    'deleted' => ['status = ?', ['deleted']],
    'all' => ['1=1', []]
];
$filter_config = $status_map[$active_filter] ?? $status_map['active'];

$stmt = $pdo->prepare("SELECT * FROM subscribers WHERE " . $filter_config[0] . " ORDER BY unsubscribed_at DESC, subscribed_at DESC LIMIT 50");
$stmt->execute($filter_config[1]);
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$newsletters = $pdo->query('SELECT * FROM newsletters ORDER BY sent_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("UPDATE subscribers SET status = 'deleted' WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: ' . $_SERVER['PHP_SELF'] . '#subscribers');
    exit;
}
?>


<style>
        :root{--p:#667eea;--s:#64748b;--bg:#f8fafc;--card:#fff;--sh:0 4px 20px rgba(0,0,0,.08);--radius:16px}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:#1e293b;padding:20px}
        .container{max-width:1200px;margin:0 auto}
        .section{background:var(--card);border-radius:var(--radius);box-shadow:var(--sh);margin-bottom:24px;overflow:hidden}
        .section-header{padding:24px;background:linear-gradient(135deg,var(--p),#764ba2);color:#fff}
        .content{padding:32px}
        .form-group{margin-bottom:24px}
        .form-control{width:100%;padding:14px;border:2px solid #e2e8f0;border-radius:12px;font-size:16px}
        .btn-send{padding:16px 32px;background:linear-gradient(135deg,var(--p),#764ba2);color:#fff;border:0;border-radius:12px;font-weight:600;cursor:pointer}
        .table{width:100%;border-collapse:collapse}
        .table th, .table td{padding:16px;text-align:left;border-bottom:1px solid #f1f5f9}
        .badge{padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600}
        .badge-success{background:#d1fae5;color:#166534}
        .badge-primary{background:#dbeafe;color:#1e40af}
        .alert{padding:16px;border-radius:12px;margin-bottom:24px;background:#d1fae5;color:#166534}
        .stats{display:flex;gap:16px;margin-bottom:24px}
        .stat-card{background:#fff;padding:20px;flex:1;border-radius:16px;box-shadow:var(--sh);text-align:center}
        .stat-num{font-size:32px;font-weight:700;color:var(--p)}
</style>
<div class="container py-5">
        <?php if (isset($_SESSION['newsletter_success'])): ?>
            <div class="alert"><i class="fas fa-check-circle"></i> <?php echo $_SESSION['newsletter_success']; unset($_SESSION['newsletter_success']); ?></div>
        <?php endif; ?>

        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-paper-plane"></i> Kirim Newsletter</h2>
                <p>Kirim pesan ke <?php echo number_format($total_active); ?> subscribers aktif</p>
            </div>
            <div class="content">
                <form method="POST">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea class="form-control" name="message" rows="6" required></textarea>
                    </div>
                    <button type="submit" name="send_newsletter" class="btn-send"><i class="fas fa-rocket"></i> Kirim Sekarang</button>
                </form>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card"><div class="stat-num"><?php echo $total_active; ?></div><div>Aktif</div></div>
            <div class="stat-card"><div class="stat-num"><?php echo $total_all; ?></div><div>Total</div></div>
        </div>

        <div class="section" id="history">
            <div class="section-header"><h2><i class="fas fa-history"></i> Riwayat</h2></div>
            <div class="content">
                <table class="table">
                    <thead><tr><th>Subject</th><th>Status</th><th>Penerima</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        <?php foreach ($newsletters as $nl): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nl['subject']); ?></td>
                            <td><span class="badge badge-success">Sent</span></td>
                            <td><?php echo $nl['sent_recipients']; ?>/<?php echo $nl['total_recipients']; ?></td>
                            <td><?php echo date('d/m/y H:i', strtotime($nl['sent_at'] ?? 'now')); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
