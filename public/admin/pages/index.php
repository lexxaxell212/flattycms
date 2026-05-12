<?php
$lib_path = dirname(__DIR__) . '/../lib/functions.php';
if (!file_exists($lib_path)) die('../lib/functions.php missing: ' . $lib_path);
require_once $lib_path;
autoload_core();

require_once 'functions.php';

if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$message  = '';
$msg_type = 'success';
$msg_text = '';
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $title        = trim($_POST['title']);
    $html_content = $_POST['html_content'];
    $edit_slug    = trim($_POST['edit_slug'] ?? '');

    if ($edit_slug) {
        $slug = $edit_slug;
        $stmt = $pdo->prepare("UPDATE pages SET title=?, html_content=?, updated_at=NOW() WHERE slug=?");
        $saved = $stmt->execute([$title, $html_content, $slug]);
    } else {
        $base_slug = strtolower(preg_replace('/[^a-z0-9-]+/', '-', $title));
        $slug      = generateUniqueSlug($pdo, $base_slug);
        $stmt      = $pdo->prepare("INSERT INTO pages (title, slug, html_content) VALUES (?, ?, ?)");
        $saved     = $stmt->execute([$title, $slug, $html_content]);
    }

    if ($saved && generateStaticPage($slug, $html_content)) {
        header("Location: ?edit=" . urlencode($slug) . "&saved=1");
        exit;
    } else {
        $msg_type = 'error';
        $msg_text = 'Gagal menyimpan page!';
    }
}

if (isset($_GET['delete']) && verify_csrf_token($_GET['csrf_delete'] ?? '')) {
    $stmt = $pdo->prepare("SELECT slug FROM pages WHERE slug = ?");
    $stmt->execute([$_GET['delete']]);
    $page_to_delete = $stmt->fetch();
    if ($page_to_delete) {
        deletePageFiles($page_to_delete['slug']);
        $pdo->prepare("DELETE FROM pages WHERE slug = ?")->execute([$_GET['delete']]);
        header('Location: ?deleted=1');
        exit;
    }
}

$list_pages = $pdo->query("SELECT * FROM pages ORDER BY updated_at DESC LIMIT 10")->fetchAll();

$page = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->execute([$_GET['edit']]);
    $page = $stmt->fetch();
}

if (isset($_GET['saved']))   { $msg_type = 'success'; $msg_text = 'Page berhasil disimpan!'; }
if (isset($_GET['deleted'])) { $msg_type = 'success'; $msg_text = 'Page berhasil dihapus!'; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages Builder - <?= htmlspecialchars($page['title'] ?? 'New Page') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(30, 41, 59, 0.95);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #3b82f6;
        }
        * { box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: 100vh;
            font-family: system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            color: var(--text-primary);
        }
        .header {
            background: rgba(30, 41, 59, 0.98) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar {
            background: var(--bg-card) !important;
            backdrop-filter: blur(20px);
            border-radius: 20px !important;
            height: fit-content;
            position: sticky;
            top: 20px;
            z-index: 10;
        }
        .main-card {
            background: var(--bg-card) !important;
            backdrop-filter: blur(20px);
            border-radius: 20px !important;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .CodeMirror {
            height: auto !important;
            min-height: 400px;
            border-radius: 15px !important;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .CodeMirror-scroll { min-height: 400px; }
        .page-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            margin-bottom: 8px;
            color: var(--text-primary) !important;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.03) !important;
        }
        .page-item:hover {
            background: rgba(59, 130, 246, 0.25) !important;
            transform: translateX(6px) scale(1.02);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
            border-color: rgba(59, 130, 246, 0.6);
        }
        .page-item.current {
            background: rgba(59, 130, 246, 0.35) !important;
            border-color: var(--accent) !important;
        }
        .page-slug { color: rgba(59, 130, 246, 0.9) !important; }
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            font-size: 0.875rem;
        }
        .btn-modern:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4) !important;
        }
        .preview-container {
            width: 100%;
            height: 300px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
        }
        #previewFrame { width: 100%; height: 100%; border: none; background: #fff; }
        .form-control {
            background: rgba(255,255,255,0.08) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: var(--text-primary) !important;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.12) !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25) !important;
            color: var(--text-primary) !important;
        }
        .form-label { color: var(--text-primary) !important; }
        input::placeholder { color: #94a3b8 !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 3px; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 4px; }
        @media (max-width: 991.98px) {
            .sidebar { position: relative !important; top: 0 !important; margin-bottom: 2rem; }
            .CodeMirror, .CodeMirror-scroll { min-height: 300px !important; }
            .preview-container { height: 250px !important; }
        }
        @media (max-width: 767.98px) {
            .btn-modern { padding: 8px 16px; font-size: 0.8rem; }
            .CodeMirror { font-size: 12px !important; }
            .CodeMirror, .CodeMirror-scroll { min-height: 250px !important; }
            .preview-container { height: 200px !important; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark header py-4 mb-4">
        <div class="container-fluid px-3 px-lg-0">
            <a class="navbar-brand fw-bold fs-2" href="#">
                <i class="fas fa-file-code me-3"></i>Pages Builder
            </a>
            <a href="../dashboard.php" class="btn btn-primary btn-modern ms-2">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="container-fluid px-lg-5 px-3 px-md-4">
        <div class="row g-4 g-lg-5">
            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-4 col-md-12">
                <div class="sidebar card p-4">
                    <h5 class="fw-bold text-white mb-4 pb-2 border-bottom border-secondary border-opacity-50">
                        <i class="fas fa-list me-2"></i>Recent Pages (<?= count($list_pages) ?>)
                    </h5>
                    <div style="max-height: 450px; overflow-y: auto;" class="custom-scrollbar">
                        <?php if (empty($list_pages)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-file fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">Belum ada pages</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($list_pages as $p): ?>
                            <?php $is_current = isset($_GET['edit']) && $_GET['edit'] === $p['slug']; ?>
                            <div class="page-item p-3 <?= $is_current ? 'current' : '' ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1 me-2">
                                        <div class="fw-semibold text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($p['title']) ?>
                                        </div>
                                        <small class="page-slug d-block text-truncate" style="max-width: 200px;">
                                            /pages/<?= htmlspecialchars($p['slug']) ?>/
                                            <?php if($is_current): ?><i class="fas fa-edit ms-1"></i><?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="btn-group btn-group-sm flex-shrink-0">
                                        <a href="?edit=<?= urlencode($p['slug']) ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="../../pages/<?= htmlspecialchars($p['slug']) ?>/" target="_blank" class="btn btn-outline-success btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="?delete=<?= urlencode($p['slug']) ?>&csrf_delete=<?= $csrf_token ?>"
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Hapus page «<?= htmlspecialchars($p['title']) ?>»?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main -->
            <div class="col-xl-9 col-lg-8 col-md-12">
                <?php if($msg_text): ?>
                <div class="alert alert-<?= $msg_type === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-4 shadow-lg">
                    <?= htmlspecialchars($msg_text) ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" id="pageForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="edit_slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">

                    <div class="main-card p-4 mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8 col-md-7">
                                <label class="form-label fw-bold fs-5">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" name="title"
                                       placeholder="Masukkan judul page"
                                       value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
                            </div>
                            <div class="col-lg-4 col-md-5">
                                <label class="form-label fw-bold fs-5">Slug</label>
                                <input type="text" class="form-control form-control-lg"
                                       id="slug-preview"
                                       value="<?= htmlspecialchars($page['slug'] ?? '') ?>"
                                       placeholder="auto-generate..." readonly>
                            </div>
                        </div>
                        <div class="row g-2 mt-4 pt-3 border-top border-secondary border-opacity-50">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                                    <button type="button" id="previewBtn" class="btn btn-primary btn-modern">
                                        <i class="fas fa-eye me-1"></i>Live Preview
                                    </button>
                                    <button type="button" id="clearBtn" class="btn btn-danger btn-modern">
                                        <i class="fas fa-trash me-1"></i>Clear Code
                                    </button>
                                    <button type="submit" class="btn btn-success btn-modern">
                                        <i class="fas fa-save me-1"></i>Save Page
                                    </button>
                                    <?php if($page): ?>
                                    <a href="../../pages/<?= htmlspecialchars($page['slug']) ?>/" target="_blank" class="btn btn-info btn-modern">
                                        <i class="fas fa-external-link-alt me-1"></i>View Live
                                    </a>
                                    <?php endif; ?>
                                    <a href="?" class="btn btn-outline-light btn-modern">
                                        <i class="fas fa-plus me-1"></i>New Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-xl-8 col-lg-7 col-md-12">
                            <div class="main-card p-4">
                                <h6 class="fw-bold text-white mb-4 pb-2 border-bottom border-secondary border-opacity-50">
                                    <i class="fas fa-code me-2 text-primary"></i>Code Editor (HTML/CSS/JS/PHP)
                                </h6>
                                <textarea id="htmlEditor" name="html_content"><?= htmlspecialchars($page['html_content'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-md-12">
                            <div class="main-card p-4">
                                <h6 class="fw-bold text-white mb-4 pb-2 border-bottom border-secondary border-opacity-50">
                                    <i class="fas fa-desktop me-2 text-success"></i>Live Preview
                                </h6>
                                <div class="preview-container">
                                    <iframe id="previewFrame" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>PHP tidak dieksekusi di preview.
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

    <script>
    const editor = CodeMirror.fromTextArea(document.getElementById('htmlEditor'), {
        mode        : 'htmlmixed',
        theme       : 'dracula',
        lineNumbers : true,
        lineWrapping: true,
        autoCloseTags    : true,
        autoCloseBrackets: true,
        tabSize    : 2,
        indentUnit : 2,
        viewportMargin: Infinity,
        extraKeys: {
            'Ctrl-S': () => document.getElementById('pageForm').requestSubmit(),
            'Cmd-S' : () => document.getElementById('pageForm').requestSubmit()
        }
    });

    editor.on('change', function () {
        clearTimeout(window.previewTimeout);
        window.previewTimeout = setTimeout(updatePreview, 500);
    });

    if (editor.getValue().trim()) {
        setTimeout(updatePreview, 300);
    }

    const titleInput  = document.querySelector('input[name="title"]');
    const slugPreview = document.getElementById('slug-preview');
    const editSlug    = document.querySelector('input[name="edit_slug"]').value;

    if (titleInput) {
        titleInput.addEventListener('input', function () {
            if (editSlug) return; // jangan ubah slug saat edit
            const title = this.value.trim();
            slugPreview.value = title
                ? title.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '')
                : '';
        });
    }

    function updatePreview() {
        try {
            const content = editor.getValue();
            const iframe  = document.getElementById('previewFrame');
            const title   = titleInput?.value || 'Preview';
            iframe.srcdoc = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>* { margin:0; padding:0; box-sizing:border-box; } body { padding:16px; font-family:system-ui,sans-serif; }</style>
</head>
<body>
${content}
<scr` + `ipt src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></scr` + `ipt>
</body>
</html>`;
        } catch(e) { console.error('Preview error:', e); }
    }

    document.getElementById('previewBtn').onclick = updatePreview;

    document.getElementById('clearBtn').onclick = function () {
        if (!confirm('Hapus semua kode?')) return;
        const defaultContent = `<div class="min-vh-100 d-flex align-items-center justify-content-center text-white" style="background:linear-gradient(135deg,#667eea,#764ba2)">
  <div class="text-center p-5">
    <h1 class="display-4 fw-bold mb-3">${titleInput?.value || 'New Page'}</h1>
    <p class="lead">Siap diedit!</p>
  </div>
</div>`;
        editor.setValue(defaultContent);
        updatePreview();
    };
    </script>
</body>
</html>