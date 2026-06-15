<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');

header('Content-Type: application/json');

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
  http_response_code(403);
  echo json_encode(['message' => 'Invalid CSRF token']);
  exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo json_encode(['message' => 'File tidak valid']);
  exit;
}

$file = $_FILES['image'];

if ($file['size'] > 5 * 1024 * 1024) {
  echo json_encode(['message' => 'File terlalu besar! Maksimal 5MB.']);
  exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowed_mime = [
  'image/jpeg' => 'jpg',
  'image/png' => 'png',
  'image/gif' => 'gif',
  'image/webp' => 'webp',
];

if (!array_key_exists($mime, $allowed_mime)) {
  echo json_encode(['message' => 'Tipe file tidak diizinkan.']);
  exit;
}

if (getimagesize($file['tmp_name']) === false) {
  echo json_encode(['message' => 'File bukan gambar valid.']);
  exit;
}

// Simpan file
$ext = $allowed_mime[$mime];
$filename = uniqid('content_', true) . '.' . $ext;
$dest = BASE_UPLOAD_PATH . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
  echo json_encode(['message' => 'Gagal menyimpan file.']);
  exit;
}

echo json_encode(['url' => BASE_UPLOAD_URL . $filename]);