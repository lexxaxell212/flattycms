<?php
$lib_path = dirname(__DIR__) . '/lib/functions.php';
if (!file_exists($lib_path)) die('lib/functions.php missing: ' . $lib_path);
require_once $lib_path;
autoload_core();

if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include '../config/blog-config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// NULL SAFETY
$_POST['excerpt'] = $_POST['excerpt'] ?? '';
$_POST['title']   = $_POST['title']   ?? '';
$_POST['content'] = $_POST['content'] ?? '';

$action  = $_GET['action'] ?? 'list';
$msg     = $_GET['msg']    ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
$edit_post = null;

function handle_image_upload($file_key = 'image') {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return null; 
    }

    $file = $_FILES[$file_key];

    // Validasi ukuran (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'File terlalu besar! Maksimal 5MB.'];
    }

    // Validasi MIME type
    $finfo     = finfo_open(FILEINFO_MIME_TYPE);
    $mime      = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mime = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    if (!array_key_exists($mime, $allowed_mime)) {
        return ['error' => 'Tipe file tidak diizinkan.'];
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext      = $allowed_mime[$mime];
    $filename = uniqid('post_', true) . '.' . $ext; // Prefix 'post_' untuk bedakan
    $dest     = $upload_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['error' => 'Gagal menyimpan file.'];
    }
    
    return 'uploads/' . $filename;
}

define('MAX_TITLE_LEN',   255);
define('MAX_EXCERPT_LEN', 500);
define('MAX_URL_LEN',     2048);
define('MAX_CONTENT_LEN', 500000); // 500KB teks HTML

function limit_str(string $val, int $max): string {
    return mb_substr(trim($val), 0, $max);
}

$allowed_statuses = ['active', 'inactive', 'pending'];

function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();

    // --- DELETE ---
    if (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            $form_error = 'ID tidak valid.';
        } else {
            // Ownership check - verifikasi post ada sebelum hapus
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
    
    // --- UPDATE ---
if (isset($_POST['save'])) {
    $status = in_array($_POST['status'] ?? '', $allowed_statuses) ? $_POST['status'] : 'pending';
    $title   = limit_str($_POST['title'] ?? '', MAX_TITLE_LEN);
    $excerpt = limit_str($_POST['excerpt'] ?? '', MAX_EXCERPT_LEN);
    $image_url = $_POST['image_url'] ?? ''; // 

    if (empty($title)) {
        $form_error = 'Judul tidak boleh kosong.';
    }

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
        
        $pdo->prepare(
            'UPDATE allcontent_posts SET category_id=?, title=?, excerpt=?, content=?, image_url=?, status=? WHERE id=?'
        )->execute([
            (int)$_POST['category_id'],
            $title,
            $excerpt,
            $content,
            $image_url,
            $status,
            (int)$_POST['id'],
        ]);
        regenerate_csrf_token();
        header('Location: ?msg=' . urlencode('Post diupdate'));
        exit;
    }
}

    // --- ADD ---
if (isset($_POST['add'])) {
    $status = in_array($_POST['status'] ?? '', $allowed_statuses) ? $_POST['status'] : 'pending';
    $title   = limit_str($_POST['title'] ?? '', MAX_TITLE_LEN);
    $excerpt = limit_str($_POST['excerpt'] ?? '', MAX_EXCERPT_LEN);
    $image_url = '';

    if (empty($title)) {
        $form_error = 'Judul tidak boleh kosong.';
    }

    $url_main = limit_str($_POST['url_main'] ?? '', MAX_URL_LEN);
    if (!$form_error && !is_valid_url($url_main)) {
        $form_error = 'URL sumber tidak valid.';
    }

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
        
        $pdo->prepare(
            'INSERT INTO allcontent_posts(category_id, title, excerpt, content, url_main, image_url, status) VALUES(?,?,?,?,?,?,?)'
        )->execute([
            (int)$_POST['category_id'],
            $title,
            $excerpt,
            $content,
            $url_main,
            $image_url,
            $status,
        ]);
        regenerate_csrf_token();
        header('Location: ?msg=' . urlencode('Post ditambahkan'));
        exit;
    }
}
}

if ($action === 'toggle' && isset($_GET['id']) && isset($_GET['tok'])) {
    // Token berbasis session untuk validasi GET toggle
    $expected_tok = hash_hmac('sha256', 'toggle_' . (int)$_GET['id'], $_SESSION['csrf_token']);
    if (!hash_equals($expected_tok, $_GET['tok'])) {
        die('Invalid token.');
    }
    $id   = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT status FROM allcontent_posts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $new_status = ($row['status'] === 'active') ? 'inactive' : 'active';
        $pdo->prepare('UPDATE allcontent_posts SET status = ? WHERE id = ?')->execute([$new_status, $id]);
    }
    regenerate_csrf_token(); 
    header('Location: ?msg=' . urlencode('Status diupdate'));
    exit;
}

if ($edit_id) {
    $stmt = $pdo->prepare(
        'SELECT p.*, c.name cat_name FROM allcontent_posts p
         LEFT JOIN allcontent_categories c ON p.category_id = c.id
         WHERE p.id = ?'
    );
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch();
    if ($row !== false) {
        $edit_post = $row;
        $edit_post['excerpt'] = $edit_post['excerpt'] ?? '';
        $edit_post['title']   = $edit_post['title']   ?? '';
        $edit_post['content'] = $edit_post['content'] ?? '';
    } else {
        // ID tidak ditemukan, redirect ke list
        header('Location: ?msg=' . urlencode('Post tidak ditemukan.'));
        exit;
    }
}

$q = trim($_POST['q'] ?? $_GET['q'] ?? '');
if ($q) {
    $stmt = $pdo->prepare(
        'SELECT p.*, c.name cat_name FROM allcontent_posts p
         LEFT JOIN allcontent_categories c ON p.category_id = c.id
         WHERE p.title LIKE ? OR p.content LIKE ?
         ORDER BY p.created_at DESC LIMIT 50'
    );
    $stmt->execute(['%' . $q . '%', '%' . $q . '%']);
    $posts = $stmt->fetchAll();
} else {
    $posts = $pdo->query(
        'SELECT p.*, c.name cat_name FROM allcontent_posts p
         LEFT JOIN allcontent_categories c ON p.category_id = c.id
         ORDER BY p.created_at DESC LIMIT 50'
    )->fetchAll();
}

$categories = $pdo->query('SELECT * FROM allcontent_categories ORDER BY name')->fetchAll();

$count_active   = (int)$pdo->query("SELECT COUNT(*) FROM allcontent_posts WHERE status='active'")->fetchColumn();
$count_scrapped = (int)$pdo->query("SELECT COUNT(*) FROM allcontent_posts WHERE source_domain IS NOT NULL")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Blog Manager - <?= safe_html($_SESSION['admin_name'] ?? 'Admin') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
:root {
    --primary: #1A56DB;
    --primary-dark: #233876;
    --primary-rgb: 26, 86, 219;
}
.card {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    border: none;
    border-radius: .5rem;
}
.table-hover tbody tr:hover {
    background: rgba(26, 86, 219, .05);
}
.btn-primary:focus {
    box-shadow: 0 0 0 .25rem rgba(26, 86, 219, .25);
}
.ql-container {
    min-height: 200px;
    border-radius: 0 0 .375rem .375rem;
}
.ql-toolbar {
    border-radius: .375rem .375rem 0 0;
    border-color: #dee2e6;
}
.ql-editor {
    font-size: 1rem;
    line-height: 1.6;
}
.image-upload {
    position: relative;
    display: inline-block;
    width: 100%;
}
.image-upload input[type=file] {
    position: absolute;
    left: -9999px;
}
.image-upload-label {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border: 2px dashed #0d6efd;
    border-radius: .375rem;
    background: #f8f9ff;
    cursor: pointer;
    transition: all .2s;
}
.image-upload-label:hover {
    background: #e3f2fd;
}
.image-upload-label i {
    font-size: 1.2rem;
    margin-right: 8px;
}
summary {
    cursor: pointer;
    font-weight: 500;
}
.fs-2rem { font-size: 1.1rem; }

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">

    <?php if ($msg): ?>
    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle-fill me-2"></i><?= safe_html($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($form_error)): ?>
    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle-fill me-2"></i><?= safe_html($form_error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-file-earmark-text fs-1" style="color:var(--primary)"></i>
                    <div class="h4 mb-0"><?= is_array($posts) ? count($posts) : 0 ?></div>
                    <div class="text-muted small">Posts</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100" style="background:rgba(25,135,84,.1)">
                <div class="card-body">
                    <i class="fas fa-eye-fill fs-1" style="color:#198754"></i>
                    <div class="h4 mb-0" style="color:#198754"><?= $count_active ?></div>
                    <div class="text-muted small">Active</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-tags-fill fs-1" style="color:#0dcaf0"></i>
                    <div class="h4 mb-0" style="color:#0dcaf0"><?= count($categories) ?></div>
                    <div class="text-muted small">Kategori</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100" style="background:rgba(255,193,7,.1)">
                <div class="card-body">
                    <i class="fas fa-clipboard-data fs-1" style="color:#ffc107"></i>
                    <div class="h4 mb-0" style="color:#ffc107"><?= $count_scrapped ?></div>
                    <div class="text-muted small">Scrapped</div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($edit_post): // EDIT MODE ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-pencil-square me-2"></i>Edit Post #<?= $edit_post['id'] ?>
            </h5>
            <a href="?" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list-ul"></i> Daftar
            </a>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" value="<?= (int)$edit_post['id'] ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= (int)($edit_post['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>>
                                <?= safe_html($c['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active"   <?= ($edit_post['status'] ?? '') === 'active'   ? 'selected' : '' ?>>✅ Active</option>
                            <option value="inactive" <?= ($edit_post['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>⏸️ Inactive</option>
                            <option value="pending"  <?= ($edit_post['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>⏳ Pending</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" maxlength="255" value="<?= safe_html($edit_post['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2" maxlength="500"><?= safe_html($edit_post['excerpt']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <div id="quill-editor"></div>
                    <input type="hidden" name="content" id="quill-content">
                    <div id="edit-content-data" data-content="<?= safe_html($edit_post['content']) ?>" style="display:none"></div>
                </div>

                <!-- EDIT FORM - Ganti bagian image upload -->
<div class="image-upload mb-4">
    <label for="image" class="image-upload-label">
        <i class="fas fa-image"></i>
        <span>Gambar Utama Post (JPG/PNG/GIF/WEBP, maks 5MB)</span>
    </label>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
    <input type="hidden" name="image_url" id="edit-image-url" value="<?= safe_html($edit_post['image_url'] ?? '') ?>">
    <?php if ($edit_post['image_url']): ?>
    <div class="mt-2">
        <img src="<?= safe_html($edit_post['image_url']) ?>" style="max-height:120px;border-radius:6px;border:1px solid #dee2e6" class="img-thumbnail">
        <small class="text-muted d-block">Gambar saat ini</small>
    </div>
    <?php endif; ?>
    <div id="image-preview-edit" class="mt-2"></div>
</div>

                <div class="d-flex gap-2">
                    <button name="save" class="btn btn-primary px-4">
                        <i class="fas fa-check2-square me-2"></i>Update Post
                    </button>
                    <a href="?" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <?php else: // LIST MODE ?>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <input type="search" name="q" class="form-control"
                           placeholder="Cari judul atau konten..."
                           value="<?= safe_html($q) ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <?php if ($q): ?>
                <div class="col-md-2">
                    <a href="?" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- ADD FORM -->
    <details class="mb-4">
        <summary class="btn btn-primary w-100 text-start ps-4 py-3 fw-semibold">
            <i class="fas fa-plus-circle-fill me-2"></i>Tambah Post Baru
        </summary>
        <div class="card mt-3">
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Kategori *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih...</option>
                                <?php foreach ($categories as $c): ?>
                                <option value="<?= (int)$c['id'] ?>"><?= safe_html($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Judul *</label>
                            <input type="text" name="title" class="form-control" maxlength="255" placeholder="Judul post" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">URL Sumber *</label>
                            <input type="url" name="url_main" class="form-control" maxlength="2048" placeholder="https://example.com/post" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Excerpt</label>
                            <textarea name="excerpt" class="form-control" rows="2" maxlength="500" placeholder="Ringkasan..."></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Konten *</label>
                        <div id="quill-add-editor"></div>
                        <input type="hidden" name="content" id="quill-add-content">
                    </div>

                   <!-- ADD FORM -->
                    <div class="image-upload mt-3">
                        <label for="add-image" class="image-upload-label">
                            <i class="fas fa-image"></i>
                            <span>Gambar Utama Post (JPG/PNG/GIF/WEBP, maks 5MB) <strong>*</strong></span>
                        </label>
                        <input type="file" id="add-image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <input type="hidden" name="image_url" id="add-image-url">
                        <div id="image-preview-add" class="mt-2"></div>
                    </div>

                    <button name="add" class="btn btn-primary mt-3 px-4">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Post
                    </button>
                </form>
            </div>
        </div>
    </details>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header d-flex justify-content-between bg-primary bg-opacity-10">
            <h6 class="mb-0 text-primary">
                <i class="fas fa-table me-2"></i>Daftar Posts
                <?php if ($q): ?>
                <span class="badge bg-primary"><?= safe_html($q) ?></span>
                <?php endif; ?>
            </h6>
            <small class="text-muted"><?= is_array($posts) ? count($posts) : 0 ?> posts</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Judul</th>
                        <th width="90">Status</th>
                        <th width="70">Views</th>
                        <th width="90">Tanggal</th>
                        <th width="230">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                            <div>Belum ada post</div>
                            <small>Gunakan form tambah diatas</small>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $p):
                        // Generate toggle token per post
                        $toggle_tok = hash_hmac('sha256', 'toggle_' . (int)$p['id'], $_SESSION['csrf_token']);
                        $is_active  = ($p['status'] ?? '') === 'active';
                    ?>
                    <tr>
                        <td><strong>#<?= (int)$p['id'] ?></strong></td>
                        <td>
                            <div class="fw-semibold" style="max-width:250px;" title="<?= safe_html($p['title']) ?>">
                                <?= safe_html(mb_substr($p['title'] ?? '', 0, 45)) ?><?= mb_strlen($p['title'] ?? '') > 45 ? '...' : '' ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge fs-2rem <?= $is_active ? 'bg-success' : (($p['status'] ?? '') === 'pending' ? 'bg-warning' : 'bg-secondary') ?>">
                                <?= $is_active ? 'Active' : (($p['status'] ?? '') === 'pending' ? 'Pending' : 'Inactive') ?>
                            </span>
                        </td>
                        <td><?= number_format((int)($p['views'] ?? 0)) ?></td>
                        <td>
                            <small class="text-muted">
                                <?php
                                    // FIX: strtotime bisa return false jika nilai tidak valid
                                    $ts = strtotime($p['created_at'] ?? '');
                                    echo $ts !== false ? date('d M Y', $ts) : '-';
                                ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Edit -->
                                <a href="?edit=<?= (int)$p['id'] ?>" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <!-- Toggle dengan token -->
                                <a href="?action=toggle&id=<?= (int)$p['id'] ?>&tok=<?= $toggle_tok ?>"
                                   class="btn <?= $is_active ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                   onclick="return confirm(this.dataset.confirm)"
                                   data-confirm="<?= $is_active ? 'Nonaktifkan' : 'Aktifkan' ?> post ini?"
                                   title="Toggle Status">
                                    <i class="fas fa-power"></i>
                                </a>
                                <!-- Delete -->
                                <form method="POST" style="display:inline;"
                                      onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')">
                                    <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                    <button name="delete" class="btn btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <!-- View -->
                                <a href="../blogs/?id=<?= (int)$p['id'] ?>" target="_blank" class="btn btn-primary" title="Lihat Post">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- Source -->
                                <?php if (!empty($p['url_main'])): ?>
                                <a href="<?= safe_html($p['url_main']) ?>" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-outline-secondary" title="Source">
                                    <i class="fas fa-box-arrow-up-right"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mt-4 text-muted small">
        <a href="<?= defined('ADMIN_URL') ? safe_html(ADMIN_URL) . 'dashboard' : '?' ?>" class="text-decoration-none">
            <i class="fas fa-house-door"></i> Dashboard
        </a>
    </div>

    <?php endif; ?>
</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function () {
    'use strict';

    const QUILL_TOOLBAR = [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link', 'image', 'blockquote'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        ['clean']
    ];

    let quillEdit = null;
    let quillAdd  = null;

    document.addEventListener('DOMContentLoaded', function () {

        // EDIT MODE Quill
        const editContainer = document.getElementById('quill-editor');
        if (editContainer) {
            quillEdit = new Quill('#quill-editor', {
                theme: 'snow',
                modules: { toolbar: QUILL_TOOLBAR },
                placeholder: 'Mulai menulis konten...'
            });

            // Ambil konten dari data-attribute
            const dataEl = document.getElementById('edit-content-data');
            if (dataEl) {
                const rawContent = dataEl.getAttribute('data-content') || '';
                quillEdit.root.innerHTML = rawContent;
                document.getElementById('quill-content').value = rawContent;
            }

            quillEdit.on('text-change', function () {
                document.getElementById('quill-content').value = quillEdit.root.innerHTML;
            });
        }

        // ADD MODE Quill
        const addContainer = document.getElementById('quill-add-editor');
        if (addContainer) {
            quillAdd = new Quill('#quill-add-editor', {
                theme: 'snow',
                modules: { toolbar: QUILL_TOOLBAR },
                placeholder: 'Mulai menulis konten...'
            });
            quillAdd.on('text-change', function () {
                document.getElementById('quill-add-content').value = quillAdd.root.innerHTML;
            });
        }

        // Sync semua hidden input sebelum form submit
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                if (quillEdit) {
                    document.getElementById('quill-content').value = quillEdit.root.innerHTML;
                }
                if (quillAdd && document.getElementById('quill-add-content')) {
                    document.getElementById('quill-add-content').value = quillAdd.root.innerHTML;
                }
            });
        });

        // Preview gambar sebelum upload (edit form)
        const imgEdit = document.getElementById('image');
        if (imgEdit) {
            imgEdit.addEventListener('change', function () {
                previewImage(this, 'image-preview-edit');
            });
        }

        // Preview gambar sebelum upload (add form)
        // Update event listeners
        imgEdit.addEventListener('change', function () {
            previewImage(this, 'image-preview-edit', 'edit-image-url');
        });
        imgAdd.addEventListener('change', function () {
            previewImage(this, 'image-preview-add', 'add-image-url');
        });

        // Animasi details
        document.querySelectorAll('details').forEach(function (detail) {
            detail.addEventListener('toggle', function () {
                const card = this.querySelector('.card');
                if (this.open && card) {
                    card.style.animation = 'slideDown 0.3s ease-out';
                }
            });
        });
    });

    // Helper: preview gambar
    function previewImage(input, previewId, hiddenUrlId) {
    const preview = document.getElementById(previewId);
    const hiddenUrl = document.getElementById(hiddenUrlId);
    if (!preview || !hiddenUrl) return;
    
    preview.innerHTML = '';
    hiddenUrl.value = ''; // Reset
        if (input.files && input.files[0]) {
            const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const file = input.files[0];
            if (!allowed.includes(file.type)) {
                preview.innerHTML = '<span class="text-danger small">Tipe file tidak diizinkan.</span>';
                input.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                preview.innerHTML = '<span class="text-danger small">File terlalu besar (maks 5MB).</span>';
                input.value = '';
                return;
            }
            const reader = new FileReader();
        reader.onload = function (e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:120px;border-radius:6px;border:1px solid #dee2e6" class="img-thumbnail">';
            hiddenUrl.value = 'uploaded'; // Marker untuk PHP
        };
        reader.readAsDataURL(file);
        }
    }

})();
</script>
<?php include 'includes/footer.php'; ?>
