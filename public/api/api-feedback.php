<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');
require_once LIB_PATH . 'mailer.php';

header('Content-Type: application/json; charset=utf-8');

$nama     = trim($_POST['nama'] ?? '');
$email    = trim($_POST['email'] ?? '');
$kritik   = trim($_POST['kritik'] ?? '');
$saran    = trim($_POST['saran'] ?? '');
$pesan    = "Kritik:\n$kritik\n\nSaran:\n$saran";
$rating   = (int)($_POST['rating'] ?? 0);
$kategori = trim($_POST['kategori'] ?? '-');

if (empty($nama) || empty($kritik)) {
    echo json_encode(['success' => false, 'error' => 'Nama dan kritik wajib diisi!']);
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
<p><strong>Rating:</strong> $rating/10</p>
<p><strong>Pesan:</strong><br>$pesan</p>
";

$sent = kirimEmailAyo(SMTP_USER, $subject, $html);

echo json_encode([
    'success' => true,
    'data'    => [
        'nama'     => $nama,
        'rating'   => $rating,
        'kategori' => $_POST['kategori'] ?? '-',
    ]
]);