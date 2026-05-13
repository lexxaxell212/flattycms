<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login');
    exit;
}

// ========== CREATE TABLE ==========
if (isset($_POST['create_table'])) {
    $table_name = trim($_POST['table_name']);
    $columns = trim($_POST['columns']);
    
    if (empty($table_name) || empty($columns)) {
        $error = "Nama tabel dan kolom tidak boleh kosong!";
    } else {
        $sql = "CREATE TABLE `$table_name` (
            id INT PRIMARY KEY AUTO_INCREMENT,
            " . implode(",\n        ", explode("\n", $columns)) . ",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            $pdo->exec($sql);
            $success = "Table `$table_name` berhasil dibuat!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// ========== DROP TABLE ==========
if (isset($_GET['drop_table'])) {
    $table_name = $_GET['drop_table'];
    $pdo->exec("DROP TABLE `$table_name`");
    $success = "Table `$table_name` dihapus!";
}

// ========== GET TABLES ==========
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .card-hover {
            transition: all 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        @media (max-width: 768px) {
            .btn-group-sm .btn { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        }
    </style>
</head>

<?php
require_once ADMIN_URL . 'includes/header.php';
?>

<div class="container mt-4 mb-5">
    <!-- ALERTS -->
    <?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle-fill me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="fw-bold mb-0">
                <i class="fas fa-database-fill text-primary"></i> 
                Database Manager
            </h1>
            <p class="text-muted mb-0">Kelola tabel database dengan mudah</p>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-table"></i> <?php echo count($tables); ?> tabel
            </span>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
        <button class="btn btn-success btn-lg flex-fill flex-sm-fill" data-bs-toggle="modal" data-bs-target="#createTableModal">
            <i class="fas fa-plus-circle-fill me-2"></i>Buat Tabel Baru
        </button>
        <button class="btn btn-outline-secondary flex-fill flex-sm-fill" onclick="location.reload()">
            <i class="fas fa-arrow-clockwise me-2"></i>Refresh
        </button>
    </div>

    <!-- TABLES GRID -->
    <div class="row g-3 g-lg-4">
        <?php if (empty($tables)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-table display-1 text-muted opacity-25 mb-4"></i>
                <h4 class="text-muted">Belum ada tabel</h4>
                <p class="text-muted">Klik "Buat Tabel Baru" untuk memulai</p>
                <button class="btn btn-success btn-lg px-5" data-bs-toggle="modal" data-bs-target="#createTableModal">
                    <i class="fas fa-plus-circle"></i> Buat Tabel Pertama
                </button>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($tables as $index => $table): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-hover h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="card-title fw-bold mb-0 text-truncate" style="max-width: 80%;" title="<?php echo htmlspecialchars($table); ?>">
                            <?php echo htmlspecialchars($table); ?>
                        </h6>
                        <span class="badge bg-light text-dark small">ID: <?php echo $index + 1; ?></span>
                    </div>
                    
                    <p class="text-muted small flex-grow-1 mb-3">
                        <i class="fas fa-info-circle-fill me-1"></i>
                        Kelola data tabel
                    </p>
                    
                    <div class="btn-group w-100" role="group">
                        <a href="table_editor.php?table=<?php echo urlencode($table); ?>" 
                           class="btn btn-outline-primary flex-fill btn-sm">
                            <i class="fas fa-eye d-md-none"></i>
                            <span class="d-none d-md-inline">Lihat Data</span>
                        </a>
                        <a href="table_editor.php?table=<?php echo urlencode($table); ?>&drop_table=<?php echo urlencode($table); ?>" 
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('HAPUS TABEL <?php echo strtoupper($table); ?>?\n\nSemua data akan hilang PERMANEN!\n\nLanjutkan?')"
                           title="Hapus Tabel">
                            <i class="fas fa-trash d-md-none"></i>
                            <span class="d-none d-md-inline">Hapus</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- CREATE TABLE MODAL -->
<div class="modal fade" id="createTableModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle-fill text-success me-2"></i>
                        Buat Tabel Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Tabel <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   name="table_name" 
                                   placeholder="contoh: users, products"
                                   pattern="[a-zA-Z0-9_]+"
                                   title="Hanya huruf, angka, dan underscore"
                                   required>
                            <div class="form-text">Gunakan huruf kecil, angka, underscore</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Kolom</label>
                            <select class="form-select" id="columnCount">
                                <option>3</option><option>4</option><option>5</option>
                                <option>6</option><option>7</option><option>8</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kolom <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  name="columns" 
                                  rows="6" 
                                  placeholder="name VARCHAR(100),
email VARCHAR(150),
phone VARCHAR(20),
status ENUM('active','inactive'),
price DECIMAL(10,2)"
                                  required
                                  style="font-family: monospace;"></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Satu kolom per baris. Contoh: <code>name VARCHAR(100)</code>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightning-charge me-2"></i>
                        <strong>Otomatis ditambahkan:</strong> id (auto), created_at, updated_at
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-x-circle me-2"></i>Batal
                    </button>
                    <button type="submit" name="create_table" class="btn btn-success px-4">
                        <i class="fas fa-check-lg-circle me-2"></i>Buat Tabel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= CSS_URL ?>bs533.bundle.min.js"></script>
<script>
document.getElementById('columnCount').addEventListener('change', function() {
    // Auto-generate column examples
    const rows = this.value;
    let example = '';
    const fields = ['name VARCHAR(100)', 'email VARCHAR(150)', 'phone VARCHAR(20)', 
                   'status ENUM("active","inactive")', 'price DECIMAL(10,2)', 
                   'description TEXT', 'image VARCHAR(255)', 'created DATE'];
    
    for(let i = 0; i < rows; i++) {
        example += fields[i % fields.length] + '\n';
    }
    
    const textarea = document.querySelector('textarea[name="columns"]');
    textarea.value = example.trim();
});
</script>

<?php
require_once ADMIN_URL . 'includes/footer.php';
?>
