<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');
require_once LIB_PATH . 'mailer.php';
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['last_feedback']) && (time() - $_SESSION['last_feedback']) < 30) {
 echo json_encode(['success' => false, 'message' => 'Tunggu sebentar sebelum kirim feedback lagi.']);
 exit;
}

$nama = 'Anonim';
$email = 'anonymous@ayokebandung.id';
$kritik = trim($_POST['kritik'] ?? '');
$saran = trim($_POST['saran'] ?? '');
$pesan = "Kritik:\n$kritik\n\nSaran:\n$saran";

$rating = (int)($_POST['rating'] ?? 0);
$rating = max(0, min(10, $rating));

$kategori = trim($_POST['kategori'] ?? '-');

if (empty($kritik)) {
 echo json_encode(['success' => false, 'message' => 'Kritik wajib diisi!']);
 exit;
}

try {
 $pdo->prepare("INSERT INTO feedback (nama, email, pesan, rating) VALUES (?,?,?,?)")
 ->execute([$nama, $email, $pesan, $rating]);
} catch (PDOException $e) {
 error_log('Feedback insert failed: ' . $e->getMessage());
}

$_SESSION['last_feedback'] = time();

$subject = "Feedback Baru (Anonim)";
$html = "
<h2>Feedback Baru</h2>
<p><strong>Nama:</strong> " . htmlspecialchars($nama) . "</p>
<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
<p><strong>Rating:</strong> $rating/10</p>
<p><strong>Kategori:</strong> " . htmlspecialchars($kategori) . "</p>
<p><strong>Pesan:</strong><br>" . nl2br(htmlspecialchars($pesan)) . "</p>
";
$sent = kirimEmailAyo(SMTP_USER, $subject, $html);

echo json_encode([
 'success' => true,
 'data' => [
  'rating' => $rating,
  'kategori' => $kategori,
 ]
]);