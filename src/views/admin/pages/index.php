<?php
$csrf_token = generate_csrf_token();
$msg_type = '';
$msg_text = '';

if (isset($_GET['saved'])) {
  $msg_type = 'success'; $msg_text = 'Page berhasil disimpan!';
}
if (isset($_GET['deleted'])) {
  $msg_type = 'success'; $msg_text = 'Page berhasil dihapus!';
}
if (isset($_GET['error'])) {
  $msg_type = 'danger'; $msg_text = 'Gagal menyimpan page!';
}

$list_pages = $pdo->query("SELECT * FROM pages ORDER BY updated_at DESC LIMIT 10")->fetchAll();

$page = null;
if (isset($_GET['edit'])) {
  $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
  $stmt->execute([$_GET['edit']]);
  $page = $stmt->fetch();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<style>
  .CodeMirror {
    height: auto!important;
    min-height: 450px;
    border-radius: .5rem;
    font-size: 14px;
  }
  .CodeMirror-scroll {
    min-height: 450px;
  }
  .preview-frame {
    width: 100%;
    height: 320px;
    border: none;
    border-radius: .5rem;
    background: #fff;
  }
  .page-list-item {
    transition: all .2s;
    border-radius: .5rem;
  }
  .page-list-item:hover {
    background: rgba(13,110,253,.08)!important;
    transform: translateX(4px);
  }
  .page-list-item.active {
    background: rgba(13,110,253,.12)!important;
    border-left: 3px solid #0d6efd;
  }
</style>

<div class="container py-5">

  <?php if ($msg_text): ?>
  <div class="alert alert-<?= $msg_type ?> alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-<?= $msg_type === 'success' ? 'circle-check' : 'triangle-exclamation' ?> me-2"></i>
    <?= safe_html($msg_text) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-xl-3 col-lg-4">
      <div class="card border-0 shadow-sm" style="position:sticky;top:20px">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
              <i class="fa-solid fa-file-code fa-sm"></i>
            </span>
            <span class="fw-semibold">Recent Pages</span>
            <span class="badge bg-primary ms-auto"><?= count($list_pages) ?></span>
          </div>
        </div>
        <div class="card-body p-2" style="max-height:500px;overflow-y:auto">
          <?php if (empty($list_pages)): ?>
          <div class="text-center text-muted py-5">
            <i class="fa-solid fa-file fa-2x mb-2 d-block opacity-50"></i>
            Belum ada pages
          </div>
          <?php else : ?>
          <?php foreach ($list_pages as $p):
          $is_current = isset($_GET['edit']) && $_GET['edit'] === $p['slug'];
          ?>
          <div class="page-list-item p-3 mb-1 <?= $is_current ? 'active' : '' ?>">
            <div class="d-flex align-items-start justify-content-between gap-2">
              <div class="flex-grow-1 overflow-hidden">
                <div class="fw-medium text-truncate small">
                  <?= safe_html($p['title']) ?>
                </div>
                <small class="text-primary text-truncate d-block">/pages/<?= safe_html($p['slug']) ?>/</small>
              </div>
              <div class="btn-group btn-group-sm flex-shrink-0">
                <a href="?edit=<?= urlencode($p['slug']) ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                  <i class="fa-solid fa-pencil"></i>
                </a>
                <a href="/pages/<?= safe_html($p['slug']) ?>/" target="_blank" class="btn btn-outline-success btn-sm" title="View">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="?delete=<?= urlencode($p['slug']) ?>&csrf_delete=<?= $csrf_token ?>"
                  class="btn btn-outline-danger btn-sm"
                  onclick="return confirm('Hapus page ini?')"
                  title="Hapus">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-white border-top py-2 px-3">
          <a href="?" class="btn btn-outline-primary btn-sm w-100">
            <i class="fa-solid fa-plus me-1"></i> New Page
          </a>
        </div>
      </div>
    </div>

    <div class="col-xl-9 col-lg-8">
      <form method="POST" id="pageForm">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="edit_slug" value="<?= safe_html($page['slug'] ?? '') ?>">

        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body px-4 py-3">
            <div class="row g-3 align-items-end">
              <div class="col-md-6">
                <label class="form-label fw-medium">Page Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title"
                placeholder="Judul halaman"
                value="<?= safe_html($page['title'] ?? '') ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-medium">Slug</label>
                <input type="text" class="form-control bg-light" id="slug-preview"
                value="<?= safe_html($page['slug'] ?? '') ?>"
                placeholder="auto-generate..." readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-medium">Tanggal Event <span class="text-muted fw-normal small">opsional</span></label>
                <input type="date" class="form-control" name="event_date"
                value="<?= safe_html($page['event_date'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="card-footer bg-white border-top py-2 px-4">
            <div class="d-flex flex-wrap gap-2">
              <button type="button" id="previewBtn" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-eye me-1"></i> Preview
              </button>
              <button type="button" id="clearBtn" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-trash me-1"></i> Clear
              </button>
              <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save
              </button>
              <?php if ($page): ?>
              <a href="/pages/<?= safe_html($page['slug']) ?>/" target="_blank" class="btn btn-outline-info btn-sm">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                  <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                    <i class="fa-solid fa-code fa-sm"></i>
                  </span>
                  <span class="fw-semibold">Code Editor</span>
                  <small class="text-muted ms-auto">HTML / CSS</small>
                </div>
              </div>
              <div class="card-body p-2">
                <textarea id="htmlEditor" name="html_content"><?= htmlspecialchars($page['html_content'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
          <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                  <span class="bg-success bg-opacity-10 text-success rounded p-1 lh-1">
                    <i class="fa-solid fa-desktop fa-sm"></i>
                  </span>
                  <span class="fw-semibold">Live Preview</span>
                </div>
              </div>
              <div class="card-body p-2">
                <iframe id="previewFrame" class="preview-frame" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
                <small class="text-muted d-block mt-2 px-1">
                  <i class="fa-solid fa-circle-info me-1"></i>PHP tidak dieksekusi di preview.
                </small>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

<script>
  const titleInput = document.querySelector('input[name="title"]');
  const slugPreview = document.getElementById('slug-preview');
  const editSlug = document.querySelector('input[name="edit_slug"]').value;

  const editor = CodeMirror.fromTextArea(document.getElementById('htmlEditor'), {
    mode: 'htmlmixed',
    theme: 'dracula',
    lineNumbers: true,
    lineWrapping: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    tabSize: 2,
    indentUnit: 2,
    viewportMargin: Infinity,
    extraKeys: {
      'Ctrl-S': () => document.getElementById('pageForm').requestSubmit(),
      'Cmd-S': () => document.getElementById('pageForm').requestSubmit()
    }
  });

  editor.on('change', () => {
    clearTimeout(window._previewTimeout);
    window._previewTimeout = setTimeout(updatePreview, 500);
  });

  if (editor.getValue().trim()) setTimeout(updatePreview, 300);

  titleInput?.addEventListener('input', function() {
    if (editSlug) return;
    slugPreview.value = this.value.trim()
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
  });

  function updatePreview() {
    const iframe = document.getElementById('previewFrame');
    iframe.srcdoc = `<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>${editor.getValue()}</body></html>`;
  }

  document.getElementById('previewBtn').onclick = updatePreview;

  document.getElementById('clearBtn').onclick = function() {
    if (!confirm('Hapus semua kode?')) return;
    editor.setValue(`<div class="p-5 text-center">\n  <h1>${titleInput?.value || 'New Page'}</h1>\n  <p>Siap diedit!</p>\n</div>`);
    updatePreview();
  };
</script>