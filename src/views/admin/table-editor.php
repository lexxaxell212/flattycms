<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

if (!isset($_GET['table']) || !isset($_SESSION['admin_id'])) {
    exit('Access denied');
}

$table = $_GET['table'];

// ========== PRESERVE TABLE PARAMETER ==========
$redirect_url = "?table=" . urlencode($table);

// ========== HELPER: Skip kolom tertentu ==========
function shouldSkipColumn($col) {
    $skip_types = ['timestamp', 'datetime'];
    $skip_names = ['id', 'created_at', 'updated_at'];
    
    return in_array($col['Field'], $skip_names) || 
           in_array($col['Type'], $skip_types) || 
           strpos($col['Type'], 'timestamp') !== false ||
           strpos($col['Type'], 'datetime') !== false;
}

// ========== EDIT MODE & UPDATE ==========
$edit_mode = false;
$edit_id = null;
$edit_data = [];
$success = '';

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $edit_mode = !empty($edit_data);
}

if (isset($_POST['update_record'])) {
    $set_clauses = [];
    $values = [];
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'col_') === 0) {
            $col_name = substr($key, 4);
            // Cek apakah kolom boleh diupdate
            $col_info = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
            $col_data = null;
            foreach ($col_info as $c) {
                if ($c['Field'] === $col_name) {
                    $col_data = $c;
                    break;
                }
            }
            
            if ($col_data && !shouldSkipColumn($col_data) && $value !== '') {
                $set_clauses[] = "`$col_name` = ?";
                $values[] = $value;
            }
        }
    }
    
    if (!empty($set_clauses)) {
        $values[] = $edit_id;
        $sql = "UPDATE `$table` SET " . implode(',', $set_clauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $success = "Record ID $edit_id berhasil diupdate!";
        header("Location: $redirect_url");
        exit;
    } else {
        $success = "Tidak ada perubahan yang valid!";
    }
}

// ========== ADD RECORD ==========
if (isset($_POST['add_record'])) {
    $columns = [];
    $values = [];
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'col_') === 0 && $value !== '') {
            $col_name = substr($key, 4);
            $col_info = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
            $col_data = null;
            foreach ($col_info as $c) {
                if ($c['Field'] === $col_name) {
                    $col_data = $c;
                    break;
                }
            }
            
            if ($col_data && !shouldSkipColumn($col_data)) {
                $columns[] = "`$col_name`";
                $values[] = $value;
            }
        }
    }
    
    if (!empty($columns)) {
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $sql = "INSERT INTO `$table` (" . implode(',', $columns) . ") VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $success = "Record berhasil ditambahkan!";
    } else {
        $success = "Isi minimal 1 field!";
    }
}

// ========== DELETE RECORD ==========
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: $redirect_url");
    exit;
}

// GET COLUMNS & DATA
$columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->query("SELECT * FROM `$table` ORDER BY id DESC LIMIT 50");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <style>
        .form-control:invalid {
            border-color: #dc3545;
        }
    </style>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-table text-primary"></i> <?php echo htmlspecialchars($table); ?></h3>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- ADD RECORD FORM -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6><i class="fas fa-plus-circle"></i> Tambah Record Baru</h6>
        </div>
        <form method="POST">
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($columns as $col): ?>
                    <?php if (!shouldSkipColumn($col)): ?>
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><?php echo htmlspecialchars($col['Field']); ?></label>
                        <small class="text-muted d-block mb-1"><?php echo $col['Type']; ?></small>
                        <input type="text" 
                               class="form-control" 
                               name="col_<?php echo htmlspecialchars($col['Field']); ?>"
                               placeholder="Masukkan <?php echo htmlspecialchars($col['Field']); ?>"
                               required>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" name="add_record" class="btn btn-success">
                    <i class="fas fa-plus-lg"></i> Tambah Record
                </button>
            </div>
        </form>
    </div>

    <!-- EDIT FORM -->
    <?php if ($edit_mode): ?>
    <div class="card mb-4 shadow-lg border-primary">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <div>
                <h6><i class="fas fa-pencil-square"></i> Edit Record ID: <strong><?php echo $edit_id; ?></strong></h6>
                <small class="text-muted">Edit field yang diizinkan saja</small>
            </div>
            <a href="<?php echo $redirect_url; ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-x-lg"></i> Tutup
            </a>
        </div>
        <form method="POST">
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($columns as $col): ?>
                    <?php if (!shouldSkipColumn($col)): ?>
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><?php echo htmlspecialchars($col['Field']); ?></label>
                        <small class="text-muted d-block mb-1"><?php echo $col['Type']; ?></small>
                        <input type="text" 
                               class="form-control" 
                               name="col_<?php echo htmlspecialchars($col['Field']); ?>"
                               value="<?php echo htmlspecialchars($edit_data[$col['Field']] ?? ''); ?>"
                               placeholder="Kosongkan untuk skip">
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo $redirect_url; ?>" class="btn btn-secondary">
                    <i class="fas fa-x-circle"></i> Batal
                </a>
                <button type="submit" name="update_record" class="btn btn-warning">
                    <i class="fas fa-check-lg"></i> Update Record
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- DATA TABLE -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6><i class="fas fa-list-ul"></i> Data (<?php echo count($data); ?> records)</h6>
            <?php if ($edit_mode): ?>
            <span class="badge bg-warning text-dark fs-6">MODE EDIT AKTIF</span>
            <?php else: ?>
            <a href="<?php echo $redirect_url; ?>&refresh=1" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-clockwise"></i> Refresh
            </a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <?php foreach ($columns as $col): ?>
                        <th scope="col"><?php echo htmlspecialchars($col['Field']); ?></th>
                        <?php endforeach; ?>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="<?php echo count($columns) + 1; ?>" class="text-center text-muted py-4">
                            <i class="fas fa-inbox display-4 opacity-25"></i><br>
                            Data kosong. Tambah record pertama!
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($data as $row): ?>
                    <tr <?php if ($edit_mode && $row['id'] == $edit_id): ?>class="table-warning fw-bold border-start border-primary border-4"<?php endif; ?>>
                        <?php foreach ($columns as $col): ?>
                        <td>
                            <small class="text-muted"><?php echo htmlspecialchars($row[$col['Field']] ?? '-'); ?></small>
                        </td>
                        <?php endforeach; ?>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <?php if ($edit_mode && $row['id'] == $edit_id): ?>
                                    <a href="<?php echo $redirect_url; ?>" 
                                       class="btn btn-success" title="Selesai Edit">
                                        <i class="fas fa-check-lg"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $redirect_url; ?>&edit=<?php echo $row['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo $redirect_url; ?>&delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-outline-danger" 
                                   onclick="return confirm('Hapus record ID <?php echo $row['id']; ?>?\nData tidak bisa dikembalikan!')"
                                   title="Hapus">
                                    <i class="fas fa-trash"></i>
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
</div>