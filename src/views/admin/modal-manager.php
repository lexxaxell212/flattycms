<?php
$success_msg = $success_msg ?? null;
$error_msg   = $error_msg   ?? null;
$csrf        = generate_csrf_token();
?>

<div class="container py-5">

<?php if (isset($_GET['success']) || $success_msg): ?>
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i><?= $success_msg ?: 'Sukses disimpan!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error_msg): ?>
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-circle-exclamation me-2"></i><?= safe_html($error_msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary bg-opacity-10 text-primary rounded p-2 lh-1">
            <i class="fa-solid fa-layer-group fa-sm"></i>
        </span>
        <span class="fw-semibold fs-5">CMPT Manager</span>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Buat Baru
    </button>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body px-4 py-3">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari judul atau kategori..." onkeyup="filterItems()">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-filter"></i></span>
                    <select id="categoryFilter" class="form-select" onchange="filterItems()">
                        <option value="">Semua Kategori</option>
                        <?php foreach($categories as $key => $label): ?>
                        <option value="<?= $key ?>"><?= safe_html($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                    <i class="fa-solid fa-xmark"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cards -->
<?php if (empty($items)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="fa-solid fa-box fa-2x mb-3 d-block opacity-50"></i>
        <div>Belum ada cards aktif</div>
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">
            Buat yang pertama
        </button>
    </div>
</div>
<?php else: ?>
<div class="row g-3" id="cardsContainer">
    <?php foreach ($items as $item): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 card-item"
         data-category="<?= safe_html(strtolower($item['category'])) ?>"
         data-title="<?= safe_html(strtolower($item['title'])) ?>"
         data-type="<?= safe_html(strtolower($item['type'])) ?>">
        <div class="card border-0 shadow-sm h-100">
            <!-- Category Badge -->
            <span class="position-absolute top-0 start-0 m-2 badge bg-primary bg-opacity-75 z-1" style="font-size:.7rem">
                <?= safe_html($categories[$item['category']] ?? ucwords(str_replace('_', ' ', $item['category']))) ?>
            </span>
            <!-- ID Badge -->
            <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 z-1" style="font-size:.7rem">
                #<?= (int)$item['id'] ?>
            </span>

            <?php if (!empty($item['image']) && $item['image'] !== 'default.jpg'): ?>
            <img src="<?= BASE_UPLOAD_URL . safe_html($item['image']) ?>"
                 class="card-img-top" style="object-fit:cover;"
                 alt="<?= safe_html($item['title']) ?>"
                 onerror="this.onerror=null;this.src='<?= BASE_UPLOAD_URL ?>default.jpg'">
            <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height:150px;">
                <i class="fa-solid fa-image fa-2x opacity-50"></i>
            </div>
            <?php endif; ?>

            <div class="card-body pt-4 pb-4">
                <h6 class="card-title fw-semibold"><?= safe_html(mb_substr($item['title'], 0, 40)) ?></h6>
                <p class="card-text text-muted small"><?= safe_html(mb_substr($item['excerpt'], 0, 60)) ?></p>
                <div class="d-flex gap-1 mt-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill"
                            data-bs-toggle="modal" data-bs-target="#itemModal"
                            onclick="editItem(<?= $item['id'] ?>,'<?= addslashes($item['title']) ?>','<?= addslashes($item['image']) ?>','<?= addslashes($item['excerpt']) ?>','<?= addslashes($item['button_link']) ?>','<?= addslashes($item['type']) ?>','<?= addslashes($item['status']) ?>','<?= addslashes($item['category']) ?>')">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <form method="POST" class="flex-fill" onsubmit="return confirm('Arsipkan card ini?')">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                        <button class="btn btn-outline-danger btn-sm w-100" type="submit">
                            <i class="fa-solid fa-archive"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal Form -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold" id="modalTitle">Buat Card</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="cardForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="action" id="modalAction" value="create">
                    <input type="hidden" name="id" id="modalId">

                    <div class="mb-3">
                        <label class="form-label fw-medium">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="modalTitleField" class="form-control" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar</label>
                        <div id="uploadZone" class="border border-2 border-dashed rounded p-4 text-center text-muted" style="cursor:pointer;border-style:dashed!important">
                            <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2 text-primary d-block"></i>
                            Drag gambar atau <strong>klik disini</strong>
                            <div><small>JPG, PNG, GIF, WebP (max 5MB)</small></div>
                            <input type="file" id="imageInput" accept="image/*" class="d-none">
                        </div>
                        <div id="previewContainer" class="d-none mt-2 p-2 border rounded d-flex align-items-center gap-2">
                            <img id="imagePreview" style="max-width:80px;max-height:80px;border-radius:8px;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImage()">Ganti</button>
                        </div>
                        <input type="hidden" name="image" id="modalImage" value="default.jpg">
                        <div id="uploadStatus" class="alert d-none mt-2"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Deskripsi</label>
                            <textarea name="excerpt" id="modalExcerpt" class="form-control" rows="2" maxlength="500"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Link</label>
                            <input type="url" name="button_link" id="modalButtonLink" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Tipe</label>
                            <select name="type" id="modalType" class="form-select" onchange="filterCategoryByType()">
                                <option value="card">Card</option>
                                <option value="modal">Modal</option>
                                <option value="toast">Toast</option>
                                <option value="popup">Popup</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="category" id="modalCategory" class="form-select">
                                <option value="general">General</option>
                                <?php foreach($categories as $key => $label): ?>
                                <option value="<?= $key ?>"><?= safe_html($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Status</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<script>
let uploading = false;
const categories = <?= json_encode($categories) ?>;
const BASE_UPLOAD_URL = '<?= BASE_UPLOAD_URL ?>';

const typeCategories = {
    'card': ['alam','wisata_kuliner','fashion','wisata_budaya','family','kuliner','page','trending','blog','event','layanan','maps','hotel'],
    'modal': ['pusat_kota','bandung_utara','riau','dago','pasteur','cihampelas'],
    'toast': ['consent'],
    'popup': ['notifikasi']
};

document.getElementById('uploadZone').addEventListener('click', () => document.getElementById('imageInput').click());
document.getElementById('imageInput').addEventListener('change', handleImageUpload);

['dragenter','dragover','dragleave','drop'].forEach(e => {
    document.getElementById('uploadZone').addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); }, false);
});
['dragenter','dragover'].forEach(e => document.getElementById('uploadZone').addEventListener(e, () => document.getElementById('uploadZone').classList.add('border-primary'), false));
['dragleave','drop'].forEach(e => document.getElementById('uploadZone').addEventListener(e, () => document.getElementById('uploadZone').classList.remove('border-primary'), false));
document.getElementById('uploadZone').addEventListener('drop', e => {
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) uploadFile(file);
}, false);

function handleImageUpload() {
    const file = document.getElementById('imageInput').files[0];
    if (file) uploadFile(file);
}

function uploadFile(file) {
    if (uploading) return;
    if (file.size > 5 * 1024 * 1024) { showStatus('File terlalu besar! Max 5MB', 'danger'); return; }
    const formData = new FormData();
    formData.append('image', file);
    uploading = true;
    showStatus('Mengunggah...', 'info');
    fetch(window.location.href, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalImage').value = data.path;
                document.getElementById('imagePreview').src = BASE_UPLOAD_URL + data.path + '?t=' + Date.now();
                document.getElementById('previewContainer').classList.remove('d-none');
                showStatus('Upload berhasil!', 'success');
            } else {
                showStatus(data.error || 'Upload gagal', 'danger');
            }
            uploading = false;
        })
        .catch(() => { showStatus('Error koneksi', 'danger'); uploading = false; });
}

function showStatus(message, type) {
    const status = document.getElementById('uploadStatus');
    status.className = `alert alert-${type} d-block`;
    status.innerHTML = message;
    setTimeout(() => status.classList.add('d-none'), 4000);
}

function clearImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('modalImage').value = 'default.jpg';
    document.getElementById('previewContainer').classList.add('d-none');
}

function editItem(id, title, image, excerpt, link, type, status, category) {
    document.getElementById('modalTitle').textContent = 'Edit Card #' + id;
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalId').value = id;
    document.getElementById('modalTitleField').value = title;
    document.getElementById('modalImage').value = image;
    document.getElementById('modalExcerpt').value = excerpt;
    document.getElementById('modalButtonLink').value = link;
    document.getElementById('modalType').value = type;
    document.getElementById('modalStatus').value = status;
    document.getElementById('modalCategory').value = category;
    if (image && image !== 'default.jpg') {
        document.getElementById('imagePreview').src = BASE_UPLOAD_URL + image + '?t=' + Date.now();
        document.getElementById('previewContainer').classList.remove('d-none');
    }
    filterCategoryByType();
}

function filterItems() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const cat    = document.getElementById('categoryFilter').value.toLowerCase();
    document.querySelectorAll('.card-item').forEach(card => {
        const matchSearch = (card.dataset.title || '').includes(search) || (card.dataset.category || '').includes(search);
        const matchCat    = !cat || card.dataset.category === cat;
        card.classList.toggle('d-none', !(matchSearch && matchCat));
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    filterItems();
}

function filterCategoryByType() {
    const type = document.getElementById('modalType').value;
    const sel  = document.getElementById('modalCategory');
    Array.from(sel.options).forEach(opt => {
        const show = opt.value === 'general' || !typeCategories[type] || typeCategories[type].includes(opt.value);
        opt.style.display = show ? '' : 'none';
        opt.disabled = !show;
    });
    if (sel.value !== 'general' && !typeCategories[type]?.includes(sel.value)) sel.value = 'general';
}

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Buat Card Baru';
    document.getElementById('modalAction').value = 'create';
    document.getElementById('cardForm').reset();
    document.getElementById('modalImage').value = 'default.jpg';
    document.getElementById('modalCategory').value = 'general';
    document.getElementById('modalType').value = 'card';
    document.getElementById('modalStatus').value = 'active';
    clearImage();
    document.getElementById('modalTitleField').focus();
    filterCategoryByType();
}
</script>