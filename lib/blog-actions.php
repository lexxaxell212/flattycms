<?php
require_once LIB_PATH . 'blogs.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$_POST['excerpt'] = $_POST['excerpt'] ?? '';
$_POST['title'] = $_POST['title']   ?? '';
$_POST['content'] = $_POST['content'] ?? '';

$form_error = '';

define('MAX_TITLE_LEN', 255);
define('MAX_EXCERPT_LEN', 500);
define('MAX_URL_LEN', 2048);
define('MAX_CONTENT_LEN', 500000);

$allowed_statuses = ['active', 'inactive', 'pending'];

function limit_str(string $val, int $max): string {
  return mb_substr(trim($val), 0, $max);
}

function handle_image_upload($file_key = 'image') {
  if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
    return null;
  }
  $file = $_FILES[$file_key];
  if ($file['size'] > 5 * 1024 * 1024) return ['error' => 'File terlalu besar! Maksimal 5MB.'];

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);

  $allowed_mime = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
  ];
  if (!array_key_exists($mime, $allowed_mime)) return ['error' => 'Tipe file tidak diizinkan.'];

  $upload_dir = BASE_UPLOAD_PATH;
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

  $ext = $allowed_mime[$mime];
  $filename = uniqid('post_', true) . '.' . $ext;
  $dest = $upload_dir . $filename;

  if (!move_uploaded_file($file['tmp_name'], $dest)) return ['error' => 'Gagal menyimpan file.'];
  return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validate_csrf();

  // DELETE
  if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    if ($id <= 0) {
      $form_error = 'ID tidak valid.';
    } else {
      $chk = $pdo->prepare('SELECT id FROM allcontent_posts WHERE id = ?');
      $chk->execute([$id]);
      if ($chk->fetch()) {
        $pdo->prepare('DELETE FROM allcontent_posts WHERE id = ?')->execute([$id]);
        regenerate_csrf_token();
        header('Location: ?msg=' . urlencode('Post dihapus'));
        exit;
      } else {
        $form_error = 'Post tidak ditemukan atau sudah dihapus.';
      }
    }
  }

  // UPDATE
  if (isset($_POST['save'])) {
    $status = in_array($_POST['status'] ?? '', $allowed_statuses) ? $_POST['status'] : 'pending';
    $title = limit_str($_POST['title'] ?? '', MAX_TITLE_LEN);
    $excerpt = limit_str($_POST['excerpt'] ?? '', MAX_EXCERPT_LEN);
    $image_url = $_POST['image_url'] ?? '';

    if (empty($title)) $form_error = 'Judul tidak boleh kosong.';

    $upload_result = handle_image_upload('image');
    if ($upload_result !== null) {
      if (is_array($upload_result) && isset($upload_result['error'])) {
        $form_error = $upload_result['error'];
      } else {
        $image_url = $upload_result;
      }
    }

    if (!$form_error) {
      $content = limit_str($_POST['content'] ?? '', MAX_CONTENT_LEN);
      $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/', '-', $title)), '-');
      $pdo->prepare(
        'UPDATE allcontent_posts SET category_id=?, title=?, slug=?, excerpt=?, content=?, image_url=?, status=? WHERE id=?'
      )->execute([
          (int)$_POST['category_id'],
          $title, $slug, $excerpt, $content, $image_url, $status,
          (int)$_POST['id'],
        ]);
      regenerate_csrf_token();
      header('Location: ?msg=' . urlencode('Post diupdate'));
      exit;
    }
  }

  // ADD
  if (isset($_POST['add'])) {
    $status = in_array($_POST['status'] ?? '', $allowed_statuses) ? $_POST['status'] : 'pending';
    $title = limit_str($_POST['title'] ?? '', MAX_TITLE_LEN);
    $excerpt = limit_str($_POST['excerpt'] ?? '', MAX_EXCERPT_LEN);
    $image_url = '';

    if (empty($title)) $form_error = 'Judul tidak boleh kosong.';

    if (!$form_error) {
      $upload_result = handle_image_upload('image');
      if ($upload_result === null) {
        $form_error = 'Gambar utama wajib diupload.';
      } elseif (is_array($upload_result) && isset($upload_result['error'])) {
        $form_error = $upload_result['error'];
      } else {
        $image_url = $upload_result;
      }
    }

    if (!$form_error) {
      $content = limit_str($_POST['content'] ?? '', MAX_CONTENT_LEN);
      $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/', '-', $title)), '-');
      $pdo->prepare(
        'INSERT INTO allcontent_posts(category_id, title, slug, excerpt, content, image_url, status) VALUES(?,?,?,?,?,?,?)'
      )->execute([(int)$_POST['category_id'], $title, $slug, $excerpt, $content, $image_url, $status]);
      regenerate_csrf_token();
      header('Location: ?msg=' . urlencode('Post ditambah'));
      exit;
    }
  }
}

// TOGGLE (GET)
if (($_GET['action'] ?? '') === 'toggle' && isset($_GET['id'], $_GET['tok'])) {
  $id = (int)$_GET['id'];
  $tok = $_GET['tok'];
  $expected = hash_hmac('sha256', 'toggle_' . $id, $_SESSION['csrf_token']);
  if (hash_equals($expected, $tok)) {
    $stmt = $pdo->prepare('SELECT status FROM allcontent_posts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
      $new_status = $row['status'] === 'active' ? 'inactive' : 'active';
      $pdo->prepare('UPDATE allcontent_posts SET status = ? WHERE id = ?')->execute([$new_status, $id]);
      regenerate_csrf_token();
      header('Location: ?msg=' . urlencode('Status diupdate'));
      exit;
    }
  }
}