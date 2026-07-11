<?php
$categories = [
 // card type
 'ads' => 'Iklan',
 'cta' => 'CTA',
 'promo' => 'Promo',
 'form' => 'Form',
 'media' => 'Media',
 //activity type
 'bandung_pusat' => 'Bandung Pusat',
 'bandung_utara' => 'Bandung Utara',
 'bandung_selatan' => 'Bandung Selatan',
 'bandung_timur' => 'Bandung Timur',
 'bandung_barat' => 'Bandung Barat',
 'lain_nya' => 'Lain - Lain',
 // pop up type
 'consent' => 'Consent',
 // toast type
 'notifikasi' => 'Notifikasi'
];

$upload_dir = BASE_UPLOAD_PATH;
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$success_msg = null;
$error_msg = null;

// AJAX image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && empty($_POST['action'])) {
 $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
 $allowed = ['jpg',
  'jpeg',
  'png',
  'gif',
  'webp'];

 if (!in_array($ext, $allowed) || $_FILES['image']['size'] > 5000000) {
  echo json_encode(['success' => false, 'error' => 'Format/size salah! Max 5MB']);
  exit;
 }

 $filename = time() . '_' . basename($_FILES['image']['name']);
 $target_file = $upload_dir . $filename;

 if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
  echo json_encode(['success' => true, 'path' => BASE_UPLOAD_URL . $filename]);
 } else {
  echo json_encode(['success' => false, 'error' => 'Upload gagal']);
 }
 exit;
}

// CREATE / UPDATE / DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
 validate_csrf();
 $action = $_POST['action'];

 try {
  if ($action === 'create') {
   $title = trim($_POST['title'] ?? '');
   if (empty($title)) throw new Exception('Judul wajib diisi!');

   $pdo->prepare("INSERT INTO cmpt (title, image, `desc`, button_link, type, category, status) VALUES (?,?,?,?,?,?,'active')")
   ->execute([
    $title,
    $_POST['image']       ?? IMG_URL . 'default.png',
    trim($_POST['desc']      ?? ''),
    trim($_POST['button_link']  ?? '#'),
    $_POST['type']        ?? 'card',
    $_POST['category']    ?? 'general',
   ]);
   $success_msg = 'Berhasil dibuat!';

  } elseif ($action === 'update') {
   $id = (int)$_POST['id'];
   if ($id <= 0) throw new Exception('ID tidak valid!');

   $pdo->prepare("UPDATE cmpt SET title=?, image=?, `desc`=?, button_link=?, type=?, category=?, status=? WHERE id=?")
   ->execute([
    trim($_POST['title']       ?? ''),
    $_POST['image']            ?? IMG_URL . 'default.png',
    trim($_POST['desc']     ?? ''),
    trim($_POST['button_link'] ?? '#'),
    $_POST['type']             ?? 'card',
    $_POST['category']         ?? 'general',
    $_POST['status']           ?? 'active',
    $id,
   ]);
   $success_msg = 'Berhasil diupdate!';

  } elseif ($action === 'delete') {
   $id = (int)$_POST['id'];
   if ($id <= 0) throw new Exception('ID tidak valid!');

   $pdo->prepare("UPDATE cmpt SET status='inactive' WHERE id=?")->execute([$id]);
   $success_msg = 'Berhasil diarsipkan!';
  }

  regenerate_csrf_token();
  header('Location: /admin/cmpt-manager?success=1');
  exit;

 } catch (Exception $e) {
  $error_msg = $e->getMessage();
 }
}

// LOAD DATA
$items = $pdo->query("SELECT * FROM cmpt WHERE status = 'active' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$items = array_map(fn($i) => array_merge([
 'title' => 'Tanpa Judul',
 'image' => IMG_URL . 'default.png',
 'desc' => '',
 'button_link' => '#',
 'type' => 'card',
 'category' => 'general',
], $i), $items);