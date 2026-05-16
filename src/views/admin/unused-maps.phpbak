<?php
$msg  = $msg ?? null;
$csrf = $csrf ?? generate_csrf_token();
?>

<div class="container py-5">

<?php if ($msg): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="mapsToast" class="toast align-items-center text-bg-<?= $msg === 'success' ? 'success' : 'danger' ?> border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= $msg === 'success' ? 'Berhasil disimpan!' : 'Lokasi dihapus!' ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
    const t = document.getElementById('mapsToast');
    bootstrap.Toast.getOrCreateInstance(t, { delay: 3000 }).show();
    t.addEventListener('hidden.bs.toast', () => {
        const url = new URL(window.location);
        url.searchParams.delete('saved');
        url.searchParams.delete('deleted');
        history.replaceState({}, '', url);
    });
</script>
<?php endif; ?>

<!-- Form Tambah -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-location-dot fa-sm"></i>
            </span>
            <span class="fw-semibold">Tambah Lokasi</span>
        </div>
    </div>
    <div class="card-body px-4 py-3">
        <form method="POST" action="/admin/maps">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="add">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Lokasi</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="wisata">Wisata</option>
                        <option value="kuliner">Kuliner</option>
                        <option value="penginapan">Penginapan</option>
                        <option value="belanja">Belanja</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Latitude</label>
                    <input type="number" name="lat" step="any" class="form-control" placeholder="-6.9175" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Longitude</label>
                    <input type="number" name="lng" step="any" class="form-control" placeholder="107.6191" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Icon FA</label>
                    <input type="text" name="icon" class="form-control" placeholder="fas fa-location-dot" value="fas fa-location-dot">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Jarak dari Pusat</label>
                    <input type="text" name="distance" class="form-control" placeholder="5 km">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Lokasi -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-map fa-sm"></i>
            </span>
            <span class="fw-semibold">Daftar Lokasi</span>
            <span class="ms-auto badge bg-primary"><?= count($locations) ?> lokasi</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th width="100">Kategori</th>
                    <th width="120">Koordinat</th>
                    <th width="80">Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $loc): ?>
                <tr>
                    <td><strong>#<?= (int)$loc['id'] ?></strong></td>
                    <td>
                        <div class="fw-medium">
                            <i class="<?= safe_html($loc['icon']) ?> me-1 text-primary"></i>
                            <?= safe_html($loc['name']) ?>
                        </div>
                        <small class="text-muted"><?= safe_html(mb_substr($loc['description'] ?? '', 0, 50)) ?>...</small>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= safe_html($loc['category']) ?></span></td>
                    <td><small class="text-muted"><?= $loc['lat'] ?>, <?= $loc['lng'] ?></small></td>
                    <td>
                        <span class="badge <?= $loc['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $loc['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <form method="POST" action="/admin/maps" style="display:inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= (int)$loc['id'] ?>">
                                <button type="submit" class="btn btn-outline-warning" title="Toggle">
                                    <i class="fa-solid fa-power-off"></i>
                                </button>
                            </form>
                            <form method="POST" action="/admin/maps" style="display:inline"
                                  onsubmit="return confirm('Hapus lokasi ini?')">
                                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$loc['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($locations)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada lokasi</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>