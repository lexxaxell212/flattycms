<?php
$form_error = $form_error ?? '';
$msg        = $_GET['msg'] ?? '';
$action     = $_GET['action'] ?? 'list';
$edit_id    = (int)($_GET['edit'] ?? 0);
$edit_post  = null;

if ($edit_id) {
    $stmt = $pdo->prepare(
        'SELECT p.*, c.name cat_name FROM allcontent_posts p
         LEFT JOIN allcontent_categories c ON p.category_id = c.id
         WHERE p.id = ?'
    );
    $stmt->execute([$edit_id]);
    $edit_post = $stmt->fetch() ?: null;
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

$categories     = $pdo->query('SELECT * FROM allcontent_categories ORDER BY name')->fetchAll();
$count_active   = (int)$pdo->query("SELECT COUNT(*) FROM allcontent_posts WHERE status='active'")->fetchColumn();
$count_scrapped = (int)$pdo->query("SELECT COUNT(*) FROM allcontent_posts WHERE source_domain IS NOT NULL")->fetchColumn();
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-container { min-height:200px; border-radius:0 0 .375rem .375rem; }
.ql-toolbar  { border-radius:.375rem .375rem 0 0; border-color:#dee2e6; }
.ql-editor   { font-size:1rem; line-height:1.6; }
</style>

<div class="container py-5">

<?php if ($msg): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i><?= safe_html($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (!empty($form_error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i><?= safe_html($form_error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-primary"><?= is_array($posts) ? count($posts) : 0 ?></div>
            <small class="text-muted">Posts</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-success"><?= $count_active ?></div>
            <small class="text-muted">Active</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-info"><?= count($categories) ?></div>
            <small class="text-muted">Kategori</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-warning"><?= $count_scrapped ?></div>
            <small class="text-muted">Scrapped</small>
        </div>
    </div>
</div>

<?php if ($edit_post): ?>
<!-- EDIT MODE -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                    <i class="fa-solid fa-pencil fa-sm"></i>
                </span>
                <span class="fw-semibold">Edit Post #<?= $edit_post['id'] ?></span>
            </div>
            <a href="?" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-list-ul me-1"></i> Daftar
            </a>
        </div>
    </div>
    <div class="card-body px-4 py-3">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="id" value="<?= (int)$edit_post['id'] ?>">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= (int)($edit_post['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>>
                            <?= safe_html($c['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"   <?= ($edit_post['status'] ?? '') === 'active'   ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($edit_post['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="pending"  <?= ($edit_post['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Judulnya diisi yang<span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" maxlength="255" value="<?= safe_html($edit_post['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Excerpt</label>
                <textarea name="excerpt" class="form-control" rows="2" maxlength="500"><?= safe_html($edit_post['excerpt']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Uda makan belom? <span class="text-danger">*</span></label>
                <div id="quill-editor"></div>
                <input type="hidden" name="content" id="quill-content">
                <div id="edit-content-data" data-content="<?= safe_html($edit_post['content']) ?>" style="display:none"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Gambar Utama</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <input type="hidden" name="image_url" id="edit-image-url" value="<?= safe_html($edit_post['image_url'] ?? '') ?>">
                <?php if ($edit_post['image_url']): ?>
                <div class="mt-2">
                    <img src="<?= safe_html($edit_post['image_url']) ?>" style="max-height:120px;border-radius:6px" class="img-thumbnail">
                    <small class="text-muted d-block mt-1">Gambar saat ini</small>
                </div>
                <?php endif; ?>
                <div id="image-preview-edit" class="mt-2"></div>
            </div>

            <div class="d-flex gap-2">
                <button name="save" class="btn btn-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Update Post
                </button>
                <a href="?" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- LIST MODE -->

<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body px-4 py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-9">
                <input type="search" name="q" class="form-control" placeholder="Cari judul atau konten..." value="<?= safe_html($q) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fa-solid fa-search me-1"></i> Cari</button>
            </div>
            <?php if ($q): ?>
            <div class="col-md-1">
                <a href="?" class="btn btn-outline-secondary w-100">✕</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Add Form -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4" style="cursor:pointer" onclick="this.nextElementSibling.classList.toggle('d-none')">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-success bg-opacity-10 text-success rounded p-1 lh-1">
                <i class="fa-solid fa-plus fa-sm"></i>
            </span>
            <span class="fw-semibold">Tambah Post Baru</span>
            <i class="fa-solid fa-chevron-down ms-auto text-muted"></i>
        </div>
    </div>
    <div class="card-body px-4 py-3 d-none">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih...</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= safe_html($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" maxlength="255" placeholder="Judul post" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2" maxlength="500"></textarea>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Konten <span class="text-danger">*</span></label>
                <div id="quill-add-editor"></div>
                <input type="hidden" name="content" id="quill-add-content">
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Gambar Thumbnail <span class="text-danger">*</span></label>
                <input type="file" name="image" id="add-image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" required>
                <input type="hidden" name="image_url" id="add-image-url">
                <div id="image-preview-add" class="mt-2"></div>
            </div>
            <button name="add" class="btn btn-primary px-4">
                <i class="fa-solid fa-plus me-1"></i> Tambah Post
            </button>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-table fa-sm"></i>
            </span>
            <span class="fw-semibold">Daftar Posts</span>
            <?php if ($q): ?>
            <span class="badge bg-primary"><?= safe_html($q) ?></span>
            <?php endif; ?>
            <small class="text-muted ms-auto"><?= is_array($posts) ? count($posts) : 0 ?> posts</small>
        </div>
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
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fa-2x d-block mb-3 opacity-50"></i>
                        Belum ada post
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $p):
                    $toggle_tok = hash_hmac('sha256', 'toggle_' . (int)$p['id'], $_SESSION['csrf_token']);
                    $is_active  = ($p['status'] ?? '') === 'active';
                ?>
                <tr>
                    <td><strong>#<?= (int)$p['id'] ?></strong></td>
                    <td>
                        <div class="fw-semibold" style="max-width:250px" title="<?= safe_html($p['title']) ?>">
                            <?= safe_html(mb_substr($p['title'] ?? '', 0, 45)) ?><?= mb_strlen($p['title'] ?? '') > 45 ? '...' : '' ?>
                        </div>
                        <small class="text-muted"><?= safe_html($p['cat_name'] ?? '') ?></small>
                    </td>
                    <td>
                        <span class="badge <?= $is_active ? 'bg-success' : (($p['status'] ?? '') === 'pending' ? 'bg-warning' : 'bg-secondary') ?>">
                            <?= $is_active ? 'Active' : (($p['status'] ?? '') === 'pending' ? 'Pending' : 'Inactive') ?>
                        </span>
                    </td>
                    <td><?= number_format((int)($p['views'] ?? 0)) ?></td>
                    <td><small class="text-muted"><?= fmt_date($p['created_at'] ?? '') ?></small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="?edit=<?= (int)$p['id'] ?>" class="btn btn-outline-primary" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="?action=toggle&id=<?= (int)$p['id'] ?>&tok=<?= $toggle_tok ?>"
                               class="btn <?= $is_active ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                               onclick="return confirm('<?= $is_active ? 'Nonaktifkan' : 'Aktifkan' ?> post ini?')"
                               title="Toggle">
                                <i class="fa-solid fa-power-off"></i>
                            </a>
                            <form method="POST" style="display:inline" onsubmit="return confirm('Hapus permanen?')">
                                <input type="hidden" name="csrf_token" value="<?= safe_html($_SESSION['csrf_token']) ?>">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button name="delete" class="btn btn-outline-danger" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            <a href="/blogs/?id=<?= (int)$p['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="Lihat">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const TOOLBAR = [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link', 'image', 'blockquote'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        ['clean']
    ];

    // Custom image handler - upload ke server, bukan base64
    function imageHandler(quillInstance) {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/webp');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;

            const csrf = document.querySelector('input[name="csrf_token"]')?.value ?? '';
            const formData = new FormData();
            formData.append('image', file);
            formData.append('csrf_token', csrf);

            try {
                const res  = await fetch('/api/api-upload-image.php', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const data = await res.json();
                if (data.url) {
                    const range = quillInstance.getSelection(true);
                    quillInstance.insertEmbed(range.index, 'image', data.url);
                    quillInstance.setSelection(range.index + 1);
                } else {
                    alert('Upload gagal: ' + (data.error ?? 'Unknown error'));
                }
            } catch (e) {
                alert('Upload gagal, coba lagi.');
            }
        };
    }

    function initQuill(editorId, contentId, existingContent = null) {
        const editorEl = document.getElementById(editorId);
        if (!editorEl) return null;

        const quill = new Quill('#' + editorId, {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: TOOLBAR,
                    handlers: { image: () => imageHandler(quill) }
                }
            },
            placeholder: 'Mulai menulis...'
        });

        if (existingContent) {
            const decoded = document.createElement('textarea');
            decoded.innerHTML = existingContent;
            quill.root.innerHTML = decoded.value;
        }

        quill.on('text-change', () => {
            document.getElementById(contentId).value = quill.root.innerHTML;
        });

        return quill;
    }

    // Init Quill edit
    const dataEl     = document.getElementById('edit-content-data');
    const existingContent = dataEl ? dataEl.getAttribute('data-content') : null;
    const quillEdit  = initQuill('quill-editor', 'quill-content', existingContent);

    // Init Quill add
    const quillAdd   = initQuill('quill-add-editor', 'quill-add-content');

    // Sync sebelum submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            if (quillEdit) document.getElementById('quill-content').value     = quillEdit.root.innerHTML;
            if (quillAdd)  document.getElementById('quill-add-content').value = quillAdd.root.innerHTML;
        });
    });

    // Preview gambar thumbnail
    function previewImage(input, previewId, hiddenId) {
        const preview = document.getElementById(previewId);
        const hidden  = document.getElementById(hiddenId);
        if (!preview) return;
        preview.innerHTML = '';
        if (hidden) hidden.value = '';
        if (!input.files || !input.files[0]) return;

        const file    = input.files[0];
        const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!allowed.includes(file.type)) {
            preview.innerHTML = '<small class="text-danger">Tipe tidak diizinkan.</small>';
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            preview.innerHTML = '<small class="text-danger">Maks 5MB.</small>';
            input.value = '';
            return;
        }

        const reader  = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" style="max-height:120px;border-radius:6px" class="img-thumbnail mt-1">`;
            if (hidden) hidden.value = 'uploaded';
        };
        reader.readAsDataURL(file);
    }

    const imgEdit = document.getElementById('image');
    const imgAdd  = document.getElementById('add-image');
    if (imgEdit) imgEdit.addEventListener('change', () => previewImage(imgEdit, 'image-preview-edit', 'edit-image-url'));
    if (imgAdd)  imgAdd.addEventListener('change',  () => previewImage(imgAdd,  'image-preview-add',  'add-image-url'));

});
</script>