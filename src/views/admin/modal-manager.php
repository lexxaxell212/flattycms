<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) die('Database gagal!');

$categories = [
    // Kategori Card
    'alam' => 'Wisata Alam',
    'wisata_kuliner' => 'Wisata Kuliner',
    'fashion' => 'Wisata Fashion',
    'wisata_budaya' => 'Wisata Budaya',
    'family' => 'Wisata Family',
    'kuliner' => 'Kuliner',
    'page' => 'Artikel',
    'trending' => 'Informasi',
    'blog' => 'Blog',
    'event' => 'Event',
    'layanan' => 'Layanan',
    'maps' => 'Maps',
    'hotel' => 'Penginapan/hotel',
    
    // Kategori Modal
    'pusat_kota' => 'Pusat Kota',
    'bandung_utara' => 'Bandung Utara',
    'riau' => 'Riau',
    'dago' => 'Dago',
    'pasteur' => 'Pasteur',
    'cihampelas' => 'Cihampelas',
    
    // Kategori Toast
    'consent' => 'Consent',
    
    // Kategori Popup
    'notifikasi' => 'Notifikasi'
];

$upload_dir = BASE_UPLOAD_PATH;
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$success_msg = 'Success';
$error_msg = 'Error';

// Ajax handle imahe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && empty($_POST['action'])) {
    $target_file = $upload_dir . time() . '_' . basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed_ext) || $_FILES['image']['size'] > 5000000) {
        echo json_encode(['success' => false, 'error' => 'Format/size salah! Max 5MB, JPG/PNG/GIF/WebP']);
        exit;
    }
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo json_encode(['success' => true, 'path' => '/' . basename($target_file)]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Upload gagal']);
    }
    exit;
}

// HANDLE FORM DATA (CREATE/UPDATE/DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    try {
        if ($action === 'create') {
            $title = trim($_POST['title'] ?? '');
            if (empty($title)) throw new Exception('Judul wajib diisi!');
            
            $stmt = $pdo->prepare("INSERT INTO admin_items (title, image, excerpt, button_link, type, category, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
            $result = $stmt->execute([ 
                $title, 
                $_POST['image'] ?? 'default.jpg', 
                trim($_POST['excerpt'] ?? ''), 
                trim($_POST['button_link'] ?? '#'), 
                $_POST['type'] ?? 'card',
                $_POST['category'] ?? 'general'
            ]);
            
            if ($result) {
                $success_msg = 'Berhasil dibuat!';
            } else {
                throw new Exception('Gagal menyimpan data');
            }
            
        } elseif ($action === 'update') {
            $id = (int)$_POST['id'];
            if ($id <= 0) throw new Exception('ID tidak valid!');
            
            $stmt = $pdo->prepare("UPDATE admin_items SET title=?, image=?, excerpt=?, button_link=?, type=?, category=?, status=? WHERE id=?");
            $result = $stmt->execute([ 
                trim($_POST['title'] ?? ''), 
                $_POST['image'] ?? 'default.jpg', 
                trim($_POST['excerpt'] ?? ''), 
                trim($_POST['button_link'] ?? '#'), 
                $_POST['type'] ?? 'card',
                $_POST['category'] ?? 'general',
                $_POST['status'] ?? 'active', 
                $id 
            ]);
            
            if ($result) {
                $success_msg = 'Berhasil diupdate!';
            } else {
                throw new Exception('Gagal update data');
            }
            
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($id <= 0) throw new Exception('ID tidak valid!');
            
            $stmt = $pdo->prepare("UPDATE admin_items SET status='inactive' WHERE id=?");
            if ($stmt->execute([$id])) {
                $success_msg = 'Berhasil diarsipkan!';
            } else {
                throw new Exception('Gagal arsipkan data');
            }
        }
        
        // Redirect untuk clear POST data
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
        
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
}

// LOAD DATA
$stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' ORDER BY id DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($items as &$item) {
    $item['title'] = $item['title'] ?? 'Tanpa Judul';
    $item['image'] = $item['image'] ?? 'default.jpg';
    $item['excerpt'] = $item['excerpt'] ?? '';
    $item['button_link'] = $item['button_link'] ?? '#';
    $item['type'] = $item['type'] ?? 'card';
    $item['category'] = $item['category'] ?? 'general';
}
unset($item);
?>

<style>
        .upload-zone {border:3px dashed #0d6efd;border-radius:15px;padding:30px;text-align:center;cursor:pointer;transition:all 0.3s;background:#f8f9ff;}
        .upload-zone:hover,.upload-zone.dragover {border-color:#0a58ca;background:#e3f2fd;transform:scale(1.02);}
        .upload-zone i {font-size:2.5rem;color:#0d6efd;margin-bottom:10px;}
        .image-preview {max-width:80px;max-height:80px;border-radius:8px;object-fit:cover;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        .card {border:none;border-radius:15px;box-shadow:0 5px 20px rgba(13,110,253,0.1);transition:all 0.3s;position:relative;overflow:hidden;}
        .card:hover {transform:translateY(-5px);box-shadow:0 15px 40px rgba(13,110,253,0.2);}
        .modal-header {background:linear-gradient(45deg,#0d6efd,#0a58ca);color:white;border-radius:15px 15px 0 0 !important;}
        .category-badge {font-size:0.75rem;padding:0.25rem 0.5rem;border-radius:20px;}
        .card-id {position: absolute;bottom: 15px;right: 15px;background: rgba(0,0,0,0.85);color: white;padding: 6px 12px;border-radius: 20px;font-size: 0.75rem;font-weight: 600;backdrop-filter: blur(15px);border: 1px solid rgba(255,255,255,0.2);z-index: 3;transition: all 0.3s ease;}
        .card:hover .card-id {background: rgba(0,0,0,0.95);transform: translateY(-2px);box-shadow: 0 4px 15px rgba(0,0,0,0.3);}

        /* WARNA KATEGORI */
        .cat-general {background-color: #6c757d !important;}
        .cat-alam {background-color: #28a745 !important;}
        .cat-kuliner {background-color: #fd7e14 !important;}
        .cat-fashion {background-color: #e83e8c !important;}
        .cat-budaya {background-color: #6f42c1 !important;}
        .cat-family {background-color: #20c997 !important;}
        .cat-page {background-color: #17a2b8 !important;}
        .cat-trending {background-color: #ffc107 !important; color: #000 !important;}
        .cat-blog {background-color: #dc3545 !important;}
        .cat-event {background-color: #0dcaf0 !important;}
        .cat-layanan {background-color: #6f42c1 !important;}
        .cat-maps {background-color: #17a2b8 !important;}
        .cat-hotel {background-color: #fd7e14 !important;}
        
        /* Kategori Modal */
        .cat-pusat_kota {background-color: #dc3545 !important;}
        .cat-bandung_utara {background-color: #28a745 !important;}
        .cat-riau {background-color: #17a2b8 !important;}
        .cat-dago {background-color: #fd7e14 !important;}
        .cat-pasteur {background-color: #6f42c1 !important;}
        .cat-cihampelas {background-color: #e83e8c !important;}
        
        /* Kategori Toast & Popup */
        .cat-consent {background-color: #ffc107 !important; color: #000 !important;}
        .cat-notifikasi {background-color: #0dcaf0 !important;}
    </style>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3"><i class="fas
        fa-layer-group me-2"></i>CMPT Manager</h1>
        <button class="btn btn-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">
            <i class="fas fa-plus me-2"></i>Buat Baru
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari judul atau kategori..." onkeyup="filterItems()">
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                <select id="categoryFilter" class="form-select" onchange="filterItems()">
                    <option value="">Semua Kategori</option>
                    <?php foreach($categories as $key => $label): ?>
                        <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['success']) || $success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
            <i class="fas fa-check me-2"></i><?= $success_msg ?: 'Sukses disimpan!' ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm">
            <i class="fas fa-exclamation me-2"></i><?= htmlspecialchars($error_msg) ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Cards List -->
    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="fas fa-box fa-4x text-muted mb-3"></i>
            <h4>Belum ada cards aktif</h4>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">Buat yang pertama</button>
        </div>
    <?php else: ?>
        <div class="row g-4" id="cardsContainer">
            <?php foreach ($items as $item): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 card-item" 
                     data-category="<?= htmlspecialchars(strtolower($item['category'])) ?>"
                     data-title="<?= htmlspecialchars(strtolower($item['title'])) ?>"
                     data-type="<?= htmlspecialchars(strtolower($item['type'])) ?>">
                    <div class="card h-100">
                        <span class="position-absolute top-0 start-0 m-2 badge category-badge text-white cat-<?= htmlspecialchars(strtolower($item['category'])) ?>">
                            <?= htmlspecialchars($categories[$item['category']] ?? ucwords(str_replace('_', ' ', $item['category']))) ?>
                        </span>
                        
                        <?php if ($item['image'] !== 'default.jpg' && !empty($item['image'])): ?>
                            <img src="../assets/images/cards/<?= htmlspecialchars($item['image']) ?>" 
                                 class="card-img-top" 
                                 style="height:150px; object-fit:cover;"
                                 alt="<?= htmlspecialchars($item['title']) ?>"
                                 onerror="this.src='../assets/images/cards/default.jpg'">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height:150px;">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body pt-4 pb-5">
                            <h6 class="card-title fw-bold"><?= htmlspecialchars($item['title']) ?></h6>
                            <p class="card-text text-muted small"><?= htmlspecialchars($item['excerpt']) ?></p>
                            <div class="btn-group w-100 mt-2">
                                <button class="btn btn-warning btn-sm flex-fill me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#itemModal" 
                                        onclick="editItem(<?= $item['id'] ?>,'<?= addslashes($item['title']) ?>','<?= addslashes($item['image']) ?>','<?= addslashes($item['excerpt']) ?>','<?= addslashes($item['button_link']) ?>','<?= addslashes($item['type']) ?>','<?= addslashes($item['status']) ?>','<?= addslashes($item['category']) ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" class="flex-fill ms-1 d-inline" style="margin:0;" onsubmit="return confirm('Arsipkan card ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-archive"></i></button>
                                </form>
                            </div>
                            <div class="card-id">#<?= $item['id'] ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Buat Card</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="cardForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" id="modalAction" value="create">
                    <input type="hidden" name="id" id="modalId">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="modalTitleField" class="form-control" required maxlength="255">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Gambar</label>
                        <div class="upload-zone mb-2" id="uploadZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div>Drag gambar atau <strong>klik disini</strong></div>
                            <small class="text-muted">JPG, PNG, GIF, WebP (max 5MB)</small>
                            <input type="file" id="imageInput" accept="image/*" class="d-none">
                        </div>
                        <div id="previewContainer" class="d-none mb-2 p-2 border rounded">
                            <img id="imagePreview" class="image-preview me-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImage()">Ganti</button>
                        </div>
                        <input type="hidden" name="image" id="modalImage" value="default.jpg">
                        <div id="uploadStatus" class="alert d-none"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="excerpt" id="modalExcerpt" class="form-control" rows="2" maxlength="500"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Link</label>
                            <input type="url" name="button_link" id="modalButtonLink" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Tipe</label>
                            <select name="type" id="modalType" class="form-select" onchange="filterCategoryByType()">
                                <option value="card">Card</option>
                                <option value="modal">Modal</option>
                                <option value="toast">Toast</option>
                                <option value="popup">Popup</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" id="modalCategory" class="form-select">
                                <option value="general">General</option>
                                <?php foreach($categories as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
let uploading = false;
const categories = <?= json_encode($categories) ?>;

// Category mapping by type
const typeCategories = {
    'card':
    ['alam','wisata_kuliner','fashion','wisata_budaya','family','kuliner','page','trending','blog','event','layanan','maps','hotel'],
    'modal':
    ['pusat_kota','bandung_utara','riau','dago','pasteur','cihampelas'],
    'toast': ['consent'],
    'popup': ['notifikasi']
};

// Upload handlers (sama seperti sebelumnya)
document.getElementById('uploadZone').addEventListener('click', () => document.getElementById('imageInput').click());
document.getElementById('imageInput').addEventListener('change', handleImageUpload);

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    document.getElementById('uploadZone').addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    document.getElementById('uploadZone').addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    document.getElementById('uploadZone').addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    document.getElementById('uploadZone').classList.add('dragover');
}

function unhighlight(e) {
    document.getElementById('uploadZone').classList.remove('dragover');
}

document.getElementById('uploadZone').addEventListener('drop', handleDrop, false);

function handleImageUpload() {
    const file = document.getElementById('imageInput').files[0];
    if (file) uploadFile(file);
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const file = dt.files[0];
    if (file && file.type.startsWith('image/')) {
        document.getElementById('imageInput').files = dt.files;
        uploadFile(file);
    }
}

function uploadFile(file) {
    if (uploading) return;
    
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        showStatus('File terlalu besar! Max 5MB', 'danger');
        return;
    }

    const formData = new FormData();
    formData.append('image', file);

    uploading = true;
    showStatus('Mengunggah...', 'info');

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalImage').value = data.path;
            document.getElementById('imagePreview').src = BASE_UPLOAD_PATH + data.path + '?t=' + Date.now();
            document.getElementById('previewContainer').classList.remove('d-none');
            showStatus('Upload berhasil!', 'success');
        } else {
            showStatus('' + (data.error || 'Upload gagal'), 'danger');
        }
        uploading = false;
    })
    .catch(() => {
        showStatus('Error koneksi', 'danger');
        uploading = false;
    });
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
    document.getElementById('uploadZone').classList.remove('dragover');
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
        document.getElementById('imagePreview').src = BASE_UPLOAD_PATH + image + '?t=' + Date.now();
        document.getElementById('previewContainer').classList.remove('d-none');
    }
    
    filterCategoryByType();
}

// FILTER DISEMUA KATEGORI BARU + SEARCH
function filterItems() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const cards = document.querySelectorAll('.card-item');
    
    cards.forEach(card => {
        const title = card.dataset.title || '';
        const category = card.dataset.category || '';
        const type = card.dataset.type || '';
        
        const matchesSearch = title.includes(searchTerm) || category.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        if (matchesSearch && matchesCategory) {
            card.style.display = '';
            card.classList.remove('d-none');
        } else {
            card.classList.add('d-none');
        }
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    filterItems();
}

// Filter kategori berdasarkan tipe
function filterCategoryByType() {
    const type = document.getElementById('modalType').value;
    const categorySelect = document.getElementById('modalCategory');
    const allOptions = Array.from(categorySelect.options);
    
    // Show all options first
    allOptions.forEach(option => {
        option.style.display = '';
        option.disabled = false;
    });
    
    // Filter by type
    if (typeCategories[type]) {
        allOptions.forEach(option => {
            if (option.value !== 'general' && !typeCategories[type].includes(option.value)) {
                option.style.display = 'none';
                option.disabled = true;
            }
        });
    }
    
    // Reset to general if current category not available
    if (categorySelect.value !== 'general' && !typeCategories[type]?.includes(categorySelect.value)) {
        categorySelect.value = 'general';
    }
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