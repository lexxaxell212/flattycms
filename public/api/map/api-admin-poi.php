<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
require_once LIB_PATH . 'poi-actions.php';
header('Content-Type: application/json');
if (!isset($_SESSION['admin_id'])) {
 http_response_code(401);
 echo json_encode(['success' => false, 'message' => 'Unauthorized']);
 exit;
}
verify_ajax_request('POST');
validate_csrf();
$action = $_POST['action'] ?? '';
switch ($action) {
 case 'add':
  $result = add_poi($_POST);
  if ($result) {
   $poi_id = $result;
   if (!empty($_FILES['poi_image']['tmp_name'])) {
    $upload = upload_poi_image($poi_id, $_FILES['poi_image']);
    if ($upload['success']) update_poi_image($poi_id, $upload['path']);
   }
   echo json_encode(['success' => true, 'poi_id' => $poi_id, 'message' => 'Lokasi berhasil ditambahkan!']);
  } else {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Nama, kategori, dan koordinat wajib diisi']);
  }
  break;

 case 'update':
  $id = (int)($_POST['poi_id'] ?? 0);
  if (!$id) {
   http_response_code(400); echo json_encode(['success' => false, 'message' => 'POI ID tidak valid']); break;
  }
  $result = update_poi($id, $_POST);
  if ($result === false) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Nama, kategori, dan koordinat wajib diisi']);
   break;
  }
  if (!empty($_FILES['poi_image']['tmp_name'])) {
   $upload = upload_poi_image($id, $_FILES['poi_image']);
   if ($upload['success']) update_poi_image($id, $upload['path']);
   else {
    http_response_code(400); echo json_encode(['success' => false, 'message' => $upload['message']]); break;
   }
  }
  echo json_encode(['success' => true, 'message' => 'Lokasi berhasil diperbarui!']);
  break;

 case 'delete':
  $id = (int)($_POST['poi_id'] ?? 0);
  $result = delete_poi($id);
  if ($result === false) {
   http_response_code(409);
   echo json_encode(['success' => false, 'message' => 'Lokasi ini dipakai di trip planner, tidak bisa dihapus']);
  } elseif ($result) {
   echo json_encode(['success' => true, 'message' => 'Lokasi berhasil dihapus']);
  } else {
   http_response_code(404);
   echo json_encode(['success' => false, 'message' => 'Lokasi tidak ditemukan']);
  }
  break;

 case 'toggle':
  $id = (int)($_POST['poi_id'] ?? 0);
  $result = toggle_poi_status($id);
  if ($result) {
   echo json_encode(['success' => true, 'message' => 'Status berhasil diubah']);
  } else {
   http_response_code(404);
   echo json_encode(['success' => false, 'message' => 'Lokasi tidak ditemukan']);
  }
  break;

 default:
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Action tidak valid']);
 }

 function upload_poi_image($poi_id, $file) {
  $allowed = ['image/jpeg',
   'image/png',
   'image/webp'];
  $maxSize = 5 * 1024 * 1024;
  if (!in_array($file['type'], $allowed))
   return ['success' => false,
   'message' => 'Format gambar tidak didukung (JPG, PNG, WebP)'];
  if ($file['size'] > $maxSize)
   return ['success' => false,
   'message' => 'Ukuran gambar maksimal 5MB'];
  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $fname = 'poi_' . $poi_id . '_' . time() . '.' . strtolower($ext);
  $dir = BASE_UPLOAD_PATH . 'poi/';
  if (!is_dir($dir)) mkdir($dir, 0755, true);
  $dest = $dir . $fname;
  if (!move_uploaded_file($file['tmp_name'], $dest))
   return ['success' => false,
   'message' => 'Gagal menyimpan gambar'];
  return ['success' => true,
   'path' => BASE_UPLOAD_URL . 'poi/' . $fname];
 }