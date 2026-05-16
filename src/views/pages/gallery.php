<?php
require_once LIB_PATH . 'poi-actions.php';
$page_title = 'Galeri Foto — ' . SITE_NAME;
$pois       = get_all_poi(true);
$is_logged  = isset($_SESSION['user']);
$pois_json  = json_encode($pois);
?>

<div class="container py-4">

  <!-- Header -->
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-bold mb-1"><i class="fa-solid fa-images me-2 text-primary"></i>Galeri Foto</h4>
      <p class="text-muted small mb-0">Foto-foto indah Bandung dari komunitas</p>
    </div>
    <?php if ($is_logged): ?>
    <button class="btn btn-primary btn-sm" id="btnOpenUpload">
      <i class="fa-solid fa-camera me-1"></i>Upload Foto
    </button>
    <?php else: ?>
    <a href="/api/auth/google.php" class="btn btn-outline-primary btn-sm">
      <i class="fa-brands fa-google me-1"></i>Login untuk Upload
    </a>
    <?php endif; ?>
  </div>

  <!-- Filter POI -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-4 d-flex gap-2 flex-wrap align-items-center">
      <span class="small text-muted me-1"><i class="fa-solid fa-filter me-1"></i>Filter:</span>
      <button class="btn btn-primary btn-sm poi-filter active" data-poi="">Semua Tempat</button>
      <div class="input-group input-group-sm ms-auto" style="max-width:220px">
        <input type="text" id="searchPoiFilter" class="form-control" placeholder="Cari tempat...">
        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
      </div>
    </div>
  </div>

  <!-- POI Pills (dinamis via JS) -->
  <div id="poiPills" class="d-flex gap-2 flex-wrap mb-4" style="display:none!important"></div>

  <!-- Stats -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-5 text-primary" id="statTotal">—</div>
        <div class="small text-muted">Total Foto</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-5 text-success" id="statPoi">—</div>
        <div class="small text-muted">Lokasi</div>
      </div>
    </div>
  </div>

  <!-- Gallery Grid -->
  <div class="row g-3" id="galleryGrid">
    <div class="col-12 text-center py-5 text-muted" id="galleryLoading">
      <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat foto...
    </div>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-center gap-2 mt-4" id="pagination"></div>

</div>

<!-- ── MODAL LIGHTBOX ──────────────────────────────────────── -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 bg-dark">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-3">
        <img id="lightboxImg" src="" class="img-fluid rounded" style="max-height:75vh;object-fit:contain">
        <div class="mt-3 text-white" id="lightboxInfo"></div>
      </div>
    </div>
  </div>
</div>

<!-- ── MODAL UPLOAD ────────────────────────────────────────── -->
<?php if ($is_logged): ?>
<div id="uploadModal" style="display:none;position:fixed;inset:0;z-index:1111;background:rgba(0,0,0,.6);align-items:center;justify-content:center">
  <div class="card border-0 shadow-lg" style="width:100%;max-width:480px;margin:1rem">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between py-3 px-4">
      <span class="fw-semibold"><i class="fa-solid fa-camera me-2 text-primary"></i>Upload Foto</span>
    </div>
    <div class="card-body p-4">
      <div class="mb-3">
        <label class="form-label small fw-semibold">Lokasi <span class="text-danger">*</span></label>
        <div class="input-group input-group-sm">
          <input type="text" id="uploadPoiSearch" class="form-control" placeholder="Ketik nama tempat...">
        </div>
        <div id="uploadPoiResults" class="list-group mt-1" style="max-height:150px;overflow-y:auto;display:none"></div>
        <input type="hidden" id="uploadPoiId">
        <div id="uploadPoiSelected" class="small text-success mt-1" style="display:none">
          <i class="fa-solid fa-check me-1"></i><span id="uploadPoiName"></span>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Foto <span class="text-danger">*</span></label>
        <input type="file" id="uploadFile" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
        <div class="form-text">Maks 10MB · JPG, PNG, WebP · HD diperbolehkan</div>
        <div id="uploadPreview" class="mt-2" style="display:none">
          <img id="previewImg" src="" class="img-fluid rounded" style="max-height:180px;object-fit:cover;width:100%">
        </div>
      </div>
      <div class="mb-1">
        <label class="form-label small fw-semibold">Kredit <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="text" id="uploadCredit" class="form-control form-control-sm" placeholder="Nama, email, link Instagram/Unsplash...">
      </div>
    </div>
    <div class="card-footer border-top d-flex gap-2 justify-content-end py-3 px-4">
      <button class="btn btn-outline-secondary btn-sm" id="btnBatalUpload">Batalkan</button>
      <button class="btn btn-primary btn-sm" id="btnKirimUpload">
        <i class="fa-solid fa-upload me-1"></i>Upload
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
(function () {
  const CSRF      = CONFIG.csrfToken;
  const BASE      = CONFIG.baseUrl;
  const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
  const POIS      = <?= $pois_json ?>;
  const API_GAL   = BASE + '/api/map/api-gallery.php';

  let currentPage   = 1;
  let currentPoi    = '';
  let totalPhotos   = 0;

  // ── LOAD GALLERY ─────────────────────────────────────────
  async function loadGallery(page = 1, poi_id = '') {
    currentPage = page;
    currentPoi  = poi_id;
    const grid  = document.getElementById('galleryGrid');
    grid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat foto...</div>';

    let url = `${API_GAL}?page=${page}`;
    if (poi_id) url += `&poi_id=${poi_id}`;

    try {
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();

      totalPhotos = json.total;
      document.getElementById('statTotal').textContent = json.total;
      document.getElementById('statPoi').textContent   = POIS.length;

      if (!json.data.length) {
        grid.innerHTML = `
          <div class="col-12 text-center py-5 text-muted">
            <i class="fa-solid fa-image fa-2x mb-3 d-block opacity-25"></i>
            Belum ada foto${poi_id ? ' untuk lokasi ini' : ''}
            ${IS_LOGGED ? '<br><button class="btn btn-primary btn-sm mt-3" id="btnEmptyUpload"><i class="fa-solid fa-camera me-1"></i>Upload pertama!</button>' : ''}
          </div>`;
        if (IS_LOGGED) document.getElementById('btnEmptyUpload')?.addEventListener('click', openUploadModal);
        renderPagination(0, 0);
        return;
      }

      grid.innerHTML = json.data.map(p => `
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="position-relative" style="padding-top:75%;cursor:pointer" onclick="openLightbox('${BASE}/uploads/${p.photo_path}','${p.poi_name}','${p.uploader_name}','${p.caption || ''}','${p.created_at}')">
              <img src="${BASE}/uploads/${p.photo_path}" 
                   class="position-absolute top-0 start-0 w-100 h-100"
                   style="object-fit:cover;transition:.2s"
                   loading="lazy"
                   onerror="this.src='${BASE}/assets/img/placeholder.jpg'">
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-dark bg-opacity-75 small">${p.poi_name}</span>
              </div>
            </div>
            <div class="card-body p-2">
              <div class="small fw-semibold text-truncate">${p.poi_name}</div>
              <div class="d-flex align-items-center gap-1 mt-1">
                <img src="${p.uploader_avatar || BASE+'/assets/img/avatar.png'}" 
                     class="rounded-circle" width="18" height="18" style="object-fit:cover">
                <span class="text-muted" style="font-size:.7rem">${p.uploader_name}</span>
              </div>
              ${p.caption ? `<div class="text-muted mt-1" style="font-size:.7rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><i class="fa-solid fa-link fa-xs me-1"></i>${p.caption}</div>` : ''}
              <div class="text-muted mt-1" style="font-size:.68rem">${formatDate(p.created_at)}</div>
            </div>
          </div>
        </div>
      `).join('');

      renderPagination(json.page, json.pages);

    } catch(e) {
      grid.innerHTML = '<div class="col-12 text-center py-4 text-muted">Gagal memuat foto</div>';
    }
  }

  function formatDate(str) {
    const d = new Date(str);
    return d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
  }

  // ── PAGINATION ───────────────────────────────────────────
  function renderPagination(page, pages) {
    const el = document.getElementById('pagination');
    if (pages <= 1) { el.innerHTML = ''; return; }
    el.innerHTML = Array.from({length: pages}, (_,i) => i+1).map(p => `
      <button class="btn btn-sm ${p === page ? 'btn-primary' : 'btn-outline-secondary'}" onclick="loadGallery(${p},'${currentPoi}')">${p}</button>
    `).join('');
  }

  window.loadGallery = loadGallery;

  // ── FILTER POI ───────────────────────────────────────────
  // Render poi pills dari POIS
  const poiPills = document.getElementById('poiPills');
  POIS.slice(0,15).forEach(p => {
    const btn = document.createElement('button');
    btn.className    = 'btn btn-outline-secondary btn-sm poi-filter';
    btn.dataset.poi  = p.id;
    btn.textContent  = p.name;
    poiPills.appendChild(btn);
  });

  document.addEventListener('click', e => {
    if (!e.target.classList.contains('poi-filter')) return;
    document.querySelectorAll('.poi-filter').forEach(b => {
      b.classList.remove('active','btn-primary');
      b.classList.add('btn-outline-secondary');
    });
    e.target.classList.add('active','btn-primary');
    e.target.classList.remove('btn-outline-secondary');
    loadGallery(1, e.target.dataset.poi || '');
  });

  // Search filter poi
  document.getElementById('searchPoiFilter').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.poi-filter[data-poi]').forEach(btn => {
      if (!btn.dataset.poi) return;
      btn.style.display = btn.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });

  // ── LIGHTBOX ─────────────────────────────────────────────
  window.openLightbox = function(src, poi, uploader, credit, date) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxInfo').innerHTML = `
      <div class="fw-semibold">${poi}</div>
      <div class="small text-muted mt-1">
        <i class="fa-solid fa-user me-1"></i>${uploader}
        ${credit ? `· <i class="fa-solid fa-link me-1"></i>${credit}` : ''}
        · ${formatDate(date)}
      </div>
      <a href="${src}" target="_blank" class="btn btn-outline-light btn-sm mt-2">
        <i class="fa-solid fa-expand me-1"></i>Lihat HD
      </a>`;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
  };

  // ── UPLOAD ───────────────────────────────────────────────
  if (IS_LOGGED) {
    const modal = document.getElementById('uploadModal');

    function openUploadModal(poi_id = '', poi_name = '') {
      document.getElementById('uploadPoiId').value        = poi_id;
      document.getElementById('uploadPoiSearch').value    = poi_name;
      document.getElementById('uploadPoiName').textContent = poi_name;
      document.getElementById('uploadPoiSelected').style.display = poi_name ? '' : 'none';
      document.getElementById('uploadPoiResults').style.display  = 'none';
      document.getElementById('uploadPreview').style.display     = 'none';
      document.getElementById('uploadFile').value   = '';
      document.getElementById('uploadCredit').value = '';
      modal.style.display = 'flex';
    }

    document.getElementById('btnOpenUpload').addEventListener('click', () => openUploadModal());
    document.getElementById('btnBatalUpload').addEventListener('click', () => { modal.style.display = 'none'; });
    
    // poi search
    document.getElementById('uploadPoiSearch').addEventListener('input', function() {
      const q   = this.value.toLowerCase();
      const box = document.getElementById('uploadPoiResults');
      box.innerHTML = '';
    
      if (!q) { box.style.display = 'none'; return; }
    
      box.style.display = '';
      const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
    
      if (!matches.length) {
        box.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
        return;
      }
    
      matches.forEach(p => {
        const el = document.createElement('button');
        el.type      = 'button';
        el.className = 'list-group-item list-group-item-action small';
        el.textContent = p.name;
        el.addEventListener('click', () => {
          document.getElementById('uploadPoiId').value = p.id;
          document.getElementById('uploadPoiName').textContent = p.name;
          document.getElementById('uploadPoiSelected').style.display = '';
          box.style.display = 'none';
        });
        box.appendChild(el);
      });
    });

    // Preview
    document.getElementById('uploadFile').addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 10 * 1024 * 1024) {
        Swal.fire('File terlalu besar', 'Maksimal 10MB', 'warning');
        this.value = ''; return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });

    // Submit
    document.getElementById('btnKirimUpload').addEventListener('click', async () => {
      const poi_id = document.getElementById('uploadPoiId').value;
      const file   = document.getElementById('uploadFile').files[0];
      if (!poi_id || !file) { Swal.fire('Oops!', 'Pilih lokasi dan foto dulu', 'warning'); return; }

      const btn = document.getElementById('btnKirimUpload');
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengupload...';
      btn.disabled  = true;

      const fd = new FormData();
      fd.append('csrf_token', CSRF);
      fd.append('poi_id',     poi_id);
      fd.append('photo',      file);
      fd.append('caption',    document.getElementById('uploadCredit').value.trim());

      try {
        const res  = await fetch(API_GAL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const data = await res.json();
        if (data.success) {
          modal.style.display = 'none';
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Foto berhasil diupload!', showConfirmButton:false, timer:2500 });
          loadGallery(1, currentPoi);
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch(e) {
        Swal.fire('Error', 'Gagal upload foto', 'error');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-upload me-1"></i>Upload';
        btn.disabled  = false;
      }
    });
  }

  // ── INIT ─────────────────────────────────────────────────
  loadGallery();

})();
</script>