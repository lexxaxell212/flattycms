<?php
$pdo = $GLOBALS['pdo'];

$categories = $pdo->query("SELECT * FROM poi_categories ORDER BY id")->fetchAll();
$total      = (int)$pdo->query("SELECT COUNT(*) FROM poi")->fetchColumn();
$pois       = $pdo->query("
    SELECT p.*, c.name AS category_name, c.icon AS category_icon
    FROM poi p
    JOIN poi_categories c ON c.id = p.category_id
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<div class="container py-5">

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-success bg-opacity-10 text-success rounded p-1 lh-1">
                        <i class="fa-solid fa-map-pin fa-sm"></i>
                    </span>
                    <span class="fw-semibold">POI Manager</span>
                    <span class="badge bg-success ms-1"><?= $total ?> lokasi</span>
                </div>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPoi">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Lokasi
                </button>
            </div>
        </div>
    </div>

    <!-- Filter kategori -->
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <button class="btn btn-sm btn-primary filter-btn active" data-category="">Semua</button>
        <?php foreach ($categories as $cat): ?>
        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="<?= $cat['id'] ?>">
            <i class="fa-solid <?= safe_html($cat['icon']) ?> me-1"></i><?= safe_html($cat['name']) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- List POI -->
    <?php if (empty($pois)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="fa-solid fa-map fa-2x mb-3 opacity-50 d-block"></i>
            Belum ada lokasi. Tambah sekarang!
        </div>
    </div>
    <?php else: ?>
    <div class="row g-3" id="poiList">
        <?php foreach ($pois as $p): ?>
        <div class="col-12 poi-item" data-category="<?= $p['category_id'] ?>">
            <div class="card border-0 shadow-sm">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-start gap-3">
                            <?php if ($p['cover_image']): ?>
                            <img src="<?= safe_html($p['cover_image']) ?>" class="rounded" width="56" height="56" style="object-fit:cover">
                            <?php else: ?>
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:56px;height:56px">
                                <i class="fa-solid <?= safe_html($p['category_icon']) ?> text-muted"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-semibold"><?= safe_html($p['name']) ?></div>
                                <div class="small text-muted mb-1">
                                    <i class="fa-solid fa-tag me-1"></i><?= safe_html($p['category_name']) ?>
                                    <span class="mx-2">·</span>
                                    <i class="fa-solid fa-location-dot me-1"></i><?= $p['latitude'] ?>, <?= $p['longitude'] ?>
                                </div>
                                <?php if ($p['address']): ?>
                                <div class="small text-muted"><i class="fa-solid fa-road me-1"></i><?= safe_html($p['address']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge <?= $p['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                            <button class="btn btn-sm btn-outline-danger btn-hapus-poi"
                                    data-id="<?= $p['id'] ?>"
                                    data-name="<?= safe_html($p['name']) ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- ── Modal Tambah POI ───────────────────────────────────── -->
<div class="modal fade" id="modalTambahPoi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold">
                    <i class="fa-solid fa-map-pin me-2 text-success"></i>Tambah Lokasi Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                <!-- Search Nominatim -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Cari Lokasi</label>
                    <div class="input-group">
                        <input type="text" id="searchNominatim" class="form-control"
                               placeholder="Contoh: Kawah Putih Bandung...">
                        <button class="btn btn-outline-secondary" id="btnCariLokasi" type="button">
                            <i class="fa-solid fa-search me-1"></i> Cari
                        </button>
                    </div>
                    <div id="hasilNominatim" class="list-group mt-2" style="max-height:200px;overflow-y:auto;display:none"></div>
                </div>

                <!-- Mini map preview -->
                <div id="mapPreviewWrap" class="mb-4" style="display:none">
                    <label class="form-label fw-semibold small">Preview Lokasi</label>
                    <div id="mapPreview" style="height:200px;border-radius:8px;overflow:hidden;border:1px solid #dee2e6"></div>
                </div>

                <!-- Form -->
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" id="poiName" class="form-control" placeholder="Nama tampilan di peta">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select id="poiCategory" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= safe_html($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Latitude</label>
                        <input type="text" id="poiLat" class="form-control" placeholder="-6.9xxx" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Longitude</label>
                        <input type="text" id="poiLng" class="form-control" placeholder="107.6xxx" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat</label>
                        <input type="text" id="poiAddress" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea id="poiDesc" class="form-control" rows="3" placeholder="Opsional"></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="poiActive" checked>
                            <label class="form-check-label small" for="poiActive">Aktif (tampil di peta)</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm" id="btnSimpanPoi">
                    <i class="fa-solid fa-save me-1"></i> Simpan Lokasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const CSRF   = CONFIG.csrfToken;
    const BASE   = CONFIG.baseUrl;
    let miniMap  = null;
    let miniMark = null;

    // ── Filter kategori ─────────────────────────────────────
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.add('btn-outline-secondary'));
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-secondary');

            const cat = this.dataset.category;
            document.querySelectorAll('.poi-item').forEach(el => {
                el.style.display = (!cat || el.dataset.category === cat) ? '' : 'none';
            });
        });
    });

    // ── Init mini map saat modal dibuka ─────────────────────
    document.getElementById('modalTambahPoi').addEventListener('shown.bs.modal', () => {
        if (!miniMap) {
            document.getElementById('mapPreviewWrap').style.display = '';
            miniMap = L.map('mapPreview').setView([-6.9175, 107.6191], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(miniMap);
            miniMap.invalidateSize();
        }
    });

    // ── Search Nominatim ────────────────────────────────────
    document.getElementById('btnCariLokasi').addEventListener('click', async () => {
        const q = document.getElementById('searchNominatim').value.trim();
        if (!q) return;

        const btn = document.getElementById('btnCariLokasi');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled  = true;

        try {
            const res  = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=5&countrycodes=id`, {
                headers: { 'Accept-Language': 'id', 'User-Agent': 'ayokebandung.id/1.0' }
            });
            const data = await res.json();

            const box = document.getElementById('hasilNominatim');
            box.innerHTML = '';
            box.style.display = '';

            if (!data.length) {
                box.innerHTML = '<div class="list-group-item text-muted small">Tidak ditemukan</div>';
                return;
            }

            data.forEach(item => {
                const el = document.createElement('button');
                el.type      = 'button';
                el.className = 'list-group-item list-group-item-action small';
                el.textContent = item.display_name;
                el.addEventListener('click', () => pilihLokasi(item));
                box.appendChild(el);
            });

        } catch (e) {
            Swal.fire('Gagal', 'Tidak bisa menghubungi Nominatim', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-search me-1"></i> Cari';
            btn.disabled  = false;
        }
    });

    // Enter key buat search
    document.getElementById('searchNominatim').addEventListener('keydown', e => {
        if (e.key === 'Enter') document.getElementById('btnCariLokasi').click();
    });

    // ── Pilih hasil Nominatim ────────────────────────────────
    function pilihLokasi(item) {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lon);

        document.getElementById('poiLat').value  = lat.toFixed(7);
        document.getElementById('poiLng').value  = lng.toFixed(7);
        document.getElementById('hasilNominatim').style.display = 'none';

        // Isi nama & alamat kalau kosong
        if (!document.getElementById('poiName').value) {
            const shortName = item.display_name.split(',')[0].trim();
            document.getElementById('poiName').value = shortName;
        }
        if (!document.getElementById('poiAddress').value) {
            document.getElementById('poiAddress').value = item.display_name;
        }

        // Update mini map
        document.getElementById('mapPreviewWrap').style.display = '';
        if (miniMap) {
            miniMap.setView([lat, lng], 15);
            if (miniMark) miniMark.remove();
            miniMark = L.marker([lat, lng]).addTo(miniMap).bindPopup(item.display_name).openPopup();
            miniMap.invalidateSize();
        }
    }

    // ── Simpan POI ───────────────────────────────────────────
    document.getElementById('btnSimpanPoi').addEventListener('click', async () => {
        const name     = document.getElementById('poiName').value.trim();
        const category = document.getElementById('poiCategory').value;
        const lat      = document.getElementById('poiLat').value;
        const lng      = document.getElementById('poiLng').value;
        const address  = document.getElementById('poiAddress').value.trim();
        const desc     = document.getElementById('poiDesc').value.trim();
        const active   = document.getElementById('poiActive').checked ? 1 : 0;

        if (!name || !category || !lat || !lng) {
            Swal.fire('Oops!', 'Nama, kategori, dan lokasi wajib diisi', 'warning');
            return;
        }

        const btn = document.getElementById('btnSimpanPoi');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';
        btn.disabled  = true;

        try {
            const res  = await fetch(`${BASE}/api/map/api-admin-poi.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ csrf_token: CSRF, name, category_id: category, latitude: lat, longitude: lng, address, description: desc, is_active: active })
            });
            const data = await res.json();

            if (data.success) {
                await Swal.fire('Berhasil!', 'Lokasi berhasil ditambahkan', 'success');
                location.reload();
            } else {
                Swal.fire('Gagal', data.error ?? 'Terjadi kesalahan', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Tidak bisa menghubungi server', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan Lokasi';
            btn.disabled  = false;
        }
    });

    // ── Hapus POI ────────────────────────────────────────────
    document.querySelectorAll('.btn-hapus-poi').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;

            const conf = await Swal.fire({
                title: 'Hapus Lokasi?',
                text: `"${name}" akan dihapus permanen`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (!conf.isConfirmed) return;

            const res  = await fetch(`${BASE}/api/map/api-admin-poi.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ csrf_token: CSRF, poi_id: id })
            });
            const data = await res.json();

            if (data.success) {
                await Swal.fire('Dihapus!', 'Lokasi berhasil dihapus', 'success');
                location.reload();
            } else {
                Swal.fire('Gagal', data.error ?? 'Terjadi kesalahan', 'error');
            }
        });
    });

})();
</script>