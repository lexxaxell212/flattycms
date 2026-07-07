<?php
require_once LIB_PATH . 'poi-actions.php';

$categories = get_poi_categories();
$pois = get_all_poi();
$total = count($pois);
?>
<main class="main-content">
  <div class="container">
    <div class="page-header text-center">
      <h1>POI <em class="styled">Manager</em></h1>
      <span class="badge badge-green fw-bold"><?= $total ?> POI</span>
    </div>
    <div class="bg-surface mb-4 mx-auto" style="max-width:900px">
      <div class="bg-card py-3 px-4 mb-4">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="bg-success bg-opacity-10 text-success rounded p-1 lh-1">
              <i class="fa-solid fa-map-pin fa-sm"></i>
            </span>
            <span class="fw-semibold">POI Manager</span>
          </div>
          <button class="btn btn-primary btn-fit" data-bs-toggle="modal" data-bs-target="#modalTambahPoi">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="bg-card mb-4">
        <div class="explore-cat-wrapper">
          <button class="explore-cat active" data-category="">Semua</button>
          <?php foreach ($categories as $cat): ?>
          <button class="explore-cat" data-category="<?= $cat['id'] ?>">
            <?= safe_html($cat['name']) ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if (empty($pois)): ?>
      <div class="bg-card mb-4">
        <div class="text-center text-muted py-5">
          <i class="fa-solid fa-map fa-2x mb-3 opacity-50 d-block mx-auto"></i>
          Belum ada lokasi. Tambah sekarang!
        </div>
      </div>
      <?php else : ?>
      <div class="row g-2 mb-4" id="poiList">
        <?php foreach ($pois as $p): ?>
        <div class="col-12 col-md-6 poi-item" data-category="<?= $p['category_id'] ?>">
          <div class="card card-flatty">
            <div class="card-body">
              <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                  <?php if (!empty($p['poi_image'])): ?>
                  <img src="<?= safe_html($p['poi_image']) ?>"
                  alt="<?= safe_html($p['name']) ?>"
                  style="width:52px;height:52px;object-fit:cover;border-radius:.5rem">
                  <?php else : ?>
                  <div class="rounded bg-card d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                    <i class="fa-solid <?= safe_html($p['category_icon']) ?> text-muted"></i>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="flex-grow-1" style="min-width:0">
                  <div class="fw-semibold mb-1">
                    <?= safe_html($p['name']) ?>
                  </div>
                  <div class="small text-muted">
                    <i class="fa-solid fa-tag me-1"></i><?= safe_html($p['category_name']) ?>
                    <span class="mx-1">·</span>
                    <i class="fa-solid fa-location-dot me-1"></i><?= $p['latitude'] ?>, <?= $p['longitude'] ?>
                  </div>
                  <?php if ($p['address']): ?>
                  <div class="small text-muted mt-1">
                    <i class="fa-solid fa-road me-1"></i><?= safe_html($p['address']) ?>
                  </div>
                  <?php endif; ?>
                  <?php if ($p['copyright']): ?>
                  <div class="small text-muted mt-1">
                    <i class="fa-solid fa-copyright me-1"></i><?= safe_html($p['copyright']) ?>
                  </div>
                  <?php endif; ?>
                  <div class="d-flex flex-wrap gap-2 mt-2">
                    <button class="btn btn-fit btn-outline-primary btn-edit-poi"
                      data-id="<?= $p['id'] ?>"
                      data-name="<?= safe_html($p['name']) ?>"
                      data-category="<?= $p['category_id'] ?>"
                      data-lat="<?= $p['latitude'] ?>"
                      data-lng="<?= $p['longitude'] ?>"
                      data-address="<?= safe_html($p['address'] ?? '') ?>"
                      data-desc="<?= safe_html($p['description'] ?? '') ?>"
                      data-copyright="<?= safe_html($p['copyright'] ?? '') ?>"
                      data-image="<?= safe_html($p['poi_image'] ?? '') ?>"
                      data-active="<?= $p['is_active'] ?>">
                      <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                    </button>
                    <button class="btn btn-fit <?= $p['is_active'] ? 'btn-success' : 'btn-outline-secondary' ?> btn-toggle-poi"
                      data-id="<?= $p['id'] ?>" data-name="<?= safe_html($p['name']) ?>">
                      <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                    </button>
                    <button class="btn btn-fit btn-outline-danger btn-hapus-poi"
                      data-id="<?= $p['id'] ?>" data-name="<?= safe_html($p['name']) ?>">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<div class="modal fade" id="modalTambahPoi" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h6 class="modal-title fw-semibold">
          <i class="fa-solid fa-map-pin me-2 text-success"></i>Tambah Lokasi Baru
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-3 p-md-4">
        <div class="mb-4">
          <label class="form-label fw-semibold small">Cari Lokasi via Nominatim</label>
          <div class="input-group">
            <input type="text" id="searchNominatim" class="form-control" placeholder="Contoh: Kawah Putih Bandung">
            <button class="btn border-0 shadow-none btn-fit" id="btnCariLokasi" type="button">
              <i class="fa-solid fa-search me-1"></i>
            </button>
          </div>
          <div id="hasilNominatim" class="list-group mt-2" style="max-height:200px;overflow-y:auto;display:none"></div>
        </div>
        <div id="mapPreviewWrap" class="mb-4" style="display:none">
          <label class="form-label fw-semibold small">Preview Lokasi</label>
          <div id="mapPreview" style="height:180px;border-radius:8px;overflow:hidden;border:1px solid #dee2e6"></div>
        </div>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label small fw-semibold">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" id="poiName" class="form-control" placeholder="Nama tampilan di peta">
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select id="poiCategory" class="form-select">
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= safe_html($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">Latitude</label>
            <input type="text" id="poiLat" class="form-control" readonly placeholder="-6.9xxx">
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">Longitude</label>
            <input type="text" id="poiLng" class="form-control" readonly placeholder="107.6xxx">
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
            <label class="form-label small fw-semibold">Copyright <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea rows="3" id="poiCopyright" class="form-control" placeholder="Nama / Sumber foto..."></textarea>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Gambar POI</label>
            <input type="file" id="poiImage" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">Maks 5MB · JPG, PNG, WebP</div>
            <div id="poiImagePreview" class="mt-2" style="display:none">
              <img id="poiPreviewImg" src="" class="img-fluid rounded" style="max-height:140px;object-fit:cover">
            </div>
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
        <button type="button" class="btn btn-secondary btn-fit" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success btn-fit" id="btnSimpanPoi">
          <i class="fa-solid fa-save me-1"></i>Simpan Lokasi
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalEditPoi" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h6 class="modal-title fw-semibold">
          <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit Lokasi
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-3 p-md-4">
        <input type="hidden" id="editPoiId">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label small fw-semibold">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" id="editPoiName" class="form-control" placeholder="Nama tampilan di peta">
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select id="editPoiCategory" class="form-select">
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= safe_html($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">Latitude <span class="text-danger">*</span></label>
            <input type="text" id="editPoiLat" class="form-control" placeholder="-6.9xxx">
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">Longitude <span class="text-danger">*</span></label>
            <input type="text" id="editPoiLng" class="form-control" placeholder="107.6xxx">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Alamat</label>
            <input type="text" id="editPoiAddress" class="form-control" placeholder="Opsional">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Deskripsi</label>
            <textarea id="editPoiDesc" class="form-control" rows="3" placeholder="Opsional"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Copyright <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea rows="3" id="editPoiCopyright" class="form-control" placeholder="Nama / Sumber foto..."></textarea>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Ganti Gambar <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="file" id="editPoiImage" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">Maks 5MB · kosongkan jika tidak ingin mengganti</div>
            <div id="editPoiCurrentImg" class="mt-2" style="display:none">
              <p class="small text-muted mb-1">Gambar saat ini:</p>
              <img id="editPoiCurrentImgEl" src="" class="img-fluid rounded" style="max-height:130px;object-fit:cover">
            </div>
            <div id="editPoiNewPreview" class="mt-2" style="display:none">
              <p class="small text-muted mb-1">Preview baru:</p>
              <img id="editPoiNewPreviewImg" src="" class="img-fluid rounded" style="max-height:130px;object-fit:cover">
            </div>
          </div>
          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="editPoiActive">
              <label class="form-check-label small" for="editPoiActive">Aktif (tampil di peta)</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top">
        <button type="button" class="btn btn-secondary btn-fit" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary btn-fit" id="btnSimpanEdit">
          <i class="fa-solid fa-save me-1"></i>Simpan Perubahan
        </button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  (function () {
    const CSRF = CONFIG.csrfToken;
    const BASE = CONFIG.baseUrl;
    const API = BASE + '/api/map/api-admin-poi.php';
    let miniMap = null, miniMark = null;

    document.querySelectorAll('.explore-cat').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.explore-cat').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.category;
        document.querySelectorAll('.poi-item').forEach(el => {
          el.style.display = (!cat || el.dataset.category === cat) ? '' : 'none';
        });
      });
    });

    document.getElementById('modalTambahPoi').addEventListener('shown.bs.modal', () => {
      if (!miniMap) {
        document.getElementById('mapPreviewWrap').style.display = '';
        miniMap = L.map('mapPreview').setView([-6.9175, 107.6191], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap'
        }).addTo(miniMap);
      }
      setTimeout(() => miniMap.invalidateSize(), 300);
    });

    async function cariLokasi() {
      const q = document.getElementById('searchNominatim').value.trim();
      if (!q) return;
      const btn = document.getElementById('btnCariLokasi');
      btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;
      try {
        await new Promise((r) => setTimeout(r, 1000));
        const res = await fetch(`${BASE}/api/map/api-nominatim.php?q=${encodeURIComponent(q)}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        const box = document.getElementById('hasilNominatim');
        box.innerHTML = ''; box.style.display = '';
        if (!json.success || !json.data.length) {
          box.innerHTML = '<div class="text-muted small">Tidak ditemukan</div>';
          return;
        }
        json.data.forEach(item => {
          const el = document.createElement('button');
          el.type = 'button'; el.className = 'btn-popup';
          el.textContent = item.display_name;
          el.addEventListener('click', () => pilihLokasi(item));
          box.appendChild(el);
        });
      } catch (e) {
        flattyToast('error', 'Tidak bisa menghubungi server');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-search me-1"></i>';
        btn.disabled = false;
      }
    }

    document.getElementById('btnCariLokasi').addEventListener('click', cariLokasi);
    document.getElementById('searchNominatim').addEventListener('keydown', e => {
      if (e.key === 'Enter') cariLokasi();
    });

    function pilihLokasi(item) {
      const lat = parseFloat(item.lat);
      const lng = parseFloat(item.lon);
      document.getElementById('poiLat').value = lat.toFixed(7);
      document.getElementById('poiLng').value = lng.toFixed(7);
      document.getElementById('hasilNominatim').style.display = 'none';
      if (!document.getElementById('poiName').value)
        document.getElementById('poiName').value = item.display_name.split(',')[0].trim();
      if (!document.getElementById('poiAddress').value)
        document.getElementById('poiAddress').value = item.display_name;
      document.getElementById('mapPreviewWrap').style.display = '';
      if (miniMap) {
        miniMap.setView([lat, lng], 15);
        if (miniMark) miniMark.remove();
        miniMark = L.marker([lat, lng]).addTo(miniMap).bindPopup(item.display_name).openPopup();
        miniMap.invalidateSize();
      }
    }

    document.getElementById('poiImage').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 5 * 1024 * 1024) {
        flattyToast('warning', 'Maksimal 5MB'); this.value = ''; return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('poiPreviewImg').src = e.target.result;
        document.getElementById('poiImagePreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });

    document.getElementById('btnSimpanPoi').addEventListener('click', async () => {
      const name = document.getElementById('poiName').value.trim();
      const cat = document.getElementById('poiCategory').value;
      const lat = document.getElementById('poiLat').value;
      const lng = document.getElementById('poiLng').value;
      if (!name || !cat || !lat || !lng) {
        flattyToast('warning', 'Nama, kategori, dan lokasi wajib diisi'); return;
      }
      const btn = document.getElementById('btnSimpanPoi');
      btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;
      const fd = new FormData();
      fd.append('action', 'add'); fd.append('csrf_token', CSRF);
      fd.append('name', name); fd.append('category_id', cat);
      fd.append('latitude', lat); fd.append('longitude', lng);
      fd.append('address', document.getElementById('poiAddress').value.trim());
      fd.append('description', document.getElementById('poiDesc').value.trim());
      fd.append('copyright', document.getElementById('poiCopyright').value.trim());
      fd.append('is_active', document.getElementById('poiActive').checked ? 1 : 0);
      const imgFile = document.getElementById('poiImage').files[0];
      if (imgFile) fd.append('poi_image', imgFile);
      try {
        await new Promise((r) => setTimeout(r, 1000));
        const res = await fetch(API, {
          method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd
        });
        const data = await res.json();
        if (data.success) {
          await flattyToast('success', data.message); location.reload();
        } else flattyToast('error', data.message);
      } catch (e) {
        flattyToast('error', 'Tidak bisa menghubungi server');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i>Simpan';
        btn.disabled = false;
      }
    });

    document.querySelectorAll('.btn-toggle-poi').forEach(btn => {
      btn.addEventListener('click', async function () {
        const id = this.dataset.id, name = this.dataset.name;
        const fd = new FormData();
        fd.append('action', 'toggle'); fd.append('csrf_token', CSRF); fd.append('poi_id', id);
        const res = await fetch(API, {
          method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd
        });
        const data = await res.json();
        if (data.success) {
          flattyToast("success", `${name} - status diubah`);
          setTimeout(() => location.reload(), 1500);
        } else flattyToast('error', data.message);
      });
    });

    document.querySelectorAll('.btn-hapus-poi').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.dataset.id, name = this.dataset.name;
        flattyConfirm(`Hapus Lokasi? "${name}" akan dihapus permanen`, async () => {
          const fd = new FormData();
          fd.append('action', 'delete'); fd.append('csrf_token', CSRF); fd.append('poi_id', id);
          const res = await fetch(API, {
            method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd
          });
          const data = await res.json();
          if (data.success) {
            flattyToast('success', data.message); location.reload();
          } else flattyToast('error', data.message);
        });
      });
    });

    document.querySelectorAll('.btn-edit-poi').forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('editPoiId').value = this.dataset.id;
        document.getElementById('editPoiName').value = this.dataset.name;
        document.getElementById('editPoiCategory').value = this.dataset.category;
        document.getElementById('editPoiLat').value = this.dataset.lat;
        document.getElementById('editPoiLng').value = this.dataset.lng;
        document.getElementById('editPoiAddress').value = this.dataset.address;
        document.getElementById('editPoiDesc').value = this.dataset.desc;
        document.getElementById('editPoiCopyright').value = this.dataset.copyright || '';
        document.getElementById('editPoiActive').checked = this.dataset.active === '1';
        document.getElementById('editPoiImage').value = '';
        document.getElementById('editPoiNewPreview').style.display = 'none';
        const curImg = document.getElementById('editPoiCurrentImg');
        if (this.dataset.image) {
          document.getElementById('editPoiCurrentImgEl').src = this.dataset.image;
          curImg.style.display = '';
        } else {
          curImg.style.display = 'none';
        }
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditPoi')).show();
      });
    });

    document.getElementById('editPoiImage').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 5 * 1024 * 1024) {
        flattyToast('warning', 'File terlalu besar, Maksimal 5MB'); this.value = ''; return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('editPoiNewPreviewImg').src = e.target.result;
        document.getElementById('editPoiNewPreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });

    document.getElementById('btnSimpanEdit').addEventListener('click', async () => {
      const name = document.getElementById('editPoiName').value.trim();
      const cat = document.getElementById('editPoiCategory').value;
      const lat = document.getElementById('editPoiLat').value.trim();
      const lng = document.getElementById('editPoiLng').value.trim();
      if (!name || !cat || !lat || !lng) {
        flattyToast('warning', 'Nama, kategori, dan koordinat wajib diisi'); return;
      }
      const btn = document.getElementById('btnSimpanEdit');
      btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;
      const fd = new FormData();
      fd.append('action', 'update'); fd.append('csrf_token', CSRF);
      fd.append('poi_id', document.getElementById('editPoiId').value);
      fd.append('name', name); fd.append('category_id', cat);
      fd.append('latitude', lat); fd.append('longitude', lng);
      fd.append('address', document.getElementById('editPoiAddress').value.trim());
      fd.append('description', document.getElementById('editPoiDesc').value.trim());
      fd.append('copyright', document.getElementById('editPoiCopyright').value.trim());
      fd.append('is_active', document.getElementById('editPoiActive').checked ? 1 : 0);
      const imgFile = document.getElementById('editPoiImage').files[0];
      if (imgFile) fd.append('poi_image', imgFile);
      try {
        await new Promise((r) => setTimeout(r, 1000));
        const res = await fetch(API, {
          method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd
        });
        const data = await res.json();
        if (data.success) {
          bootstrap.Modal.getInstance(document.getElementById('modalEditPoi'))?.hide();
          await flattyToast('success', data.message);
          location.reload();
        } else flattyToast('error', data.message);
      } catch (e) {
        flattyToast('error', 'Tidak bisa menghubungi server');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i>Simpan';
        btn.disabled = false;
      }
    });
  })();
</script>