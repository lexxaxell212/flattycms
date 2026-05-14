<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');
require_once LIB_PATH . 'mailer.php';

header('Content-Type: application/json; charset=utf-8');

$nama    = trim($_POST['nama'] ?? '');
$email   = trim($_POST['email'] ?? '');
$pesan   = trim($_POST['pesan'] ?? '');
$rating  = (int)($_POST['rating'] ?? 0);

if (empty($nama) || empty($pesan)) {
    echo json_encode(['success' => false, 'error' => 'Nama dan pesan wajib diisi!']);
    exit;
}

// Simpan ke DB (opsional)
try {
    $pdo->prepare("INSERT INTO feedback (nama, email, pesan, rating) VALUES (?,?,?,?)")
        ->execute([$nama, $email, $pesan, $rating]);
} catch (Exception $e) {
    // tabel belum ada, skip
}

// Kirim email notif ke admin
$subject = "Feedback Baru dari $nama";
$html = "
<h2>Feedback Baru</h2>
<p><strong>Nama:</strong> $nama</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Rating:</strong> $rating/5</p>
<p><strong>Pesan:</strong><br>$pesan</p>
";

$sent = kirimEmailAyo(SMTP_USER, $subject, $html);

echo json_encode(['success' => true]);