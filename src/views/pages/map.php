<?php
require_once LIB_PATH . 'poi-actions.php';
$page_title = 'Peta & Trip Planner — ' . SITE_NAME;
$pois       = get_all_poi(true);
$categories = get_poi_categories();
$is_logged  = isset($_SESSION['user']);
$pois_json  = json_encode($pois);
$cats_json  = json_encode($categories);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<div class="container-fluid py-4 px-3 px-md-4">

  <!-- Title -->
  <div class="mb-3">
    <h4 class="fw-bold mb-1"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i>Peta & Trip Planner</h4>
    <p class="text-muted small mb-0">Explore Bandung, rencanakan perjalananmu</p>
  </div>

  <div class="row g-4">

    <!-- ── KIRI: MAP ───────────────────────────────────── -->
    <div class="col-lg-8">

      <!-- Filter Kategori -->
      <div class="d-flex gap-2 flex-wrap mb-3">
        <button class="btn btn-primary btn-sm cat-filter active" data-cat="">
          <i class="fa-solid fa-layer-group me-1"></i>Semua
        </button>
        <?php foreach ($categories as $cat): ?>
        <button class="btn btn-outline-secondary btn-sm cat-filter" data-cat="<?= $cat['id'] ?>">
          <i class="fa-solid <?= safe_html($cat['icon']) ?> me-1"></i><?= safe_html($cat['name']) ?>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- Map -->
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0" style="border-radius:.75rem;overflow:hidden">
          <div id="mainMap" style="height:500px"></div>
        </div>
      </div>

      <!-- Info strip -->
      <div class="card border-0 shadow-sm">
        <div class="card-body py-2 px-3 d-flex gap-3 flex-wrap small text-muted">
          <span><i class="fa-solid fa-circle-info me-1 text-primary"></i>Klik pin untuk info lokasi</span>
          <span><i class="fa-solid fa-route me-1 text-success"></i>Tambahkan ke Trip Planner</span>
          <?php if (!$is_logged): ?>
          <span><i class="fa-solid fa-lock me-1 text-warning"></i><a href="/auth/google" class="text-warning">Login</a> untuk simpan trip & upload foto</span>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- ── KANAN: TRIP PLANNER ─────────────────────────── -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-route text-primary"></i>
            <span class="fw-semibold">Trip Planner</span>
          </div>
          <?php if ($is_logged): ?>
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
              <i class="fa-solid fa-folder-open me-1"></i>Tersimpan
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" id="savedTripsList" style="min-width:250px">
              <li><span class="dropdown-item text-muted small">Memuat...</span></li>
            </ul>
          </div>
          <?php endif; ?>
        </div>

        <div class="card-body px-4 py-3 d-flex flex-column" style="min-height:420px">

          <!-- Starting Point -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">
              <i class="fa-solid fa-location-crosshairs me-1 text-danger"></i>Titik Awal
            </label>
            <div class="input-group input-group-sm">
              <input type="text" id="startInput" class="form-control" placeholder="Cari titik awal...">
            </div>
            <div id="startResults" class="list-group mt-1" style="max-height:150px;overflow-y:auto;display:none;position:relative;z-index:100"></div>
            <div id="startSelected" class="small text-success mt-1" style="display:none">
              <i class="fa-solid fa-check me-1"></i><span id="startName"></span>
            </div>
          </div>

          <!-- Rute List -->
          <div class="mb-3 flex-grow-1">
            <label class="form-label small fw-semibold text-muted">
              <i class="fa-solid fa-list-ol me-1 text-primary"></i>Rute Perjalanan
            </label>
            <div id="routeList">
              <div class="text-center text-muted small py-4" id="routeEmpty">
                <i class="fa-solid fa-map-pin fa-2x mb-2 d-block opacity-25"></i>
                Klik pin di peta untuk tambah lokasi
              </div>
            </div>
          </div>

          <!-- Info Jarak -->
          <div id="distanceInfo" class="alert alert-primary py-2 px-3 small mb-3" style="display:none">
            <i class="fa-solid fa-ruler me-1"></i>Total jarak: <strong id="totalDist">0</strong> km
            <span class="ms-2 text-muted" id="totalStops"></span>
          </div>

          <!-- Actions -->
          <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary btn-sm flex-grow-1" id="btnGenerateRoute" disabled>
              <i class="fa-solid fa-route me-1"></i>Generate Rute
            </button>
            <?php if ($is_logged): ?>
            <button class="btn btn-success btn-sm flex-grow-1" id="btnSaveTrip" disabled>
              <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
            </button>
            <?php endif; ?>
            <button class="btn btn-outline-danger btn-sm" id="btnResetTrip" title="Reset">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>

          <!-- Save form (login only) -->
          <?php if ($is_logged): ?>
          <div id="saveForm" class="mt-3" style="display:none">
            <input type="text" id="tripTitle" class="form-control form-control-sm" placeholder="Nama trip (opsional)" value="Trip Bandungku">
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>

  </div>
</div>

<!-- ── MODAL UPLOAD FOTO ──────────────────────────────────── -->
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
          <input type="text" id="uploadPoiSearch" class="form-control" placeholder="Cari nama tempat...">
        </div>
        <div id="uploadPoiResults" class="list-group mt-1" style="max-height:140px;overflow-y:auto;display:none"></div>
        <input type="hidden" id="uploadPoiId">
        <div id="uploadPoiSelected" class="small text-success mt-1" style="display:none">
          <i class="fa-solid fa-check me-1"></i><span id="uploadPoiName"></span>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Foto <span class="text-danger">*</span></label>
        <input type="file" id="uploadFile" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
        <div class="form-text">Maks 10MB · JPG, PNG, WebP · resolusi HD diperbolehkan</div>
        <div id="uploadPreview" class="mt-2" style="display:none">
          <img id="previewImg" src="" class="img-fluid rounded" style="max-height:200px;object-fit:cover">
        </div>
      </div>
      <div class="mb-3">
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
  const CSRF      = CONFIG.csrfToken;
  const BASE      = CONFIG.baseUrl;
  const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
  const POIS      = <?= $pois_json ?>;
  const API_TRIP  = BASE + '/api/map/api-trips.php';
  const API_GAL   = BASE + '/api/map/api-gallery.php';

  // ── STATE ────────────────────────────────────────────────
  let startPoint = null; // {name, lat, lng}
  let routes     = [];   // [{poi_id, name, lat, lng, distance_from_prev, note}]
  let routeLine  = null;
  let markers    = {};
  let activeCat  = '';

  // ── MAP INIT ─────────────────────────────────────────────
  const map = L.map('mainMap').setView([-6.9175, 107.6191], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // ── POI ICONS ────────────────────────────────────────────
  const iconColors = { 1: '#6366f1', 2: '#f59e0b', 3: '#10b981' };
  function makeIcon(cat_id) {
    const c = iconColors[cat_id] || '#6366f1';
    return L.divIcon({
      className: '',
      html: `<div style="width:32px;height:32px;border-radius:50% 50% 50% 0;background:${c};transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.35)"></div>`,
      iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -34]
    });
  }

  // ── RENDER MARKERS ───────────────────────────────────────
  function renderMarkers() {
    Object.values(markers).forEach(m => map.removeLayer(m));
    markers = {};
    POIS.forEach(poi => {
      if (activeCat && poi.category_id != activeCat) return;
      const m = L.marker([poi.latitude, poi.longitude], { icon: makeIcon(poi.category_id) })
        .addTo(map)
        .bindPopup(buildPopup(poi), { maxWidth: 240 });
      markers[poi.id] = m;
    });
  }

  function buildPopup(poi) {
    const inRoute = routes.some(r => r.poi_id == poi.id);
    return `
      <div style="min-width:200px">
        <div style="font-weight:700;font-size:.9rem;margin-bottom:.2rem">${poi.name}</div>
        <div style="font-size:.75rem;color:#818cf8;margin-bottom:.3rem">${poi.category_name}</div>
        ${poi.address ? `<div style="font-size:.72rem;color:#94a3b8;margin-bottom:.6rem"><i class="fa-solid fa-road" style="margin-right:.25rem"></i>${poi.address}</div>` : ''}
        <div style="display:flex;gap:.4rem;flex-wrap:wrap">
          <button onclick="addToRoute(${poi.id})" style="flex:1;background:${inRoute?'#4ade80':'#6366f1'};color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;font-weight:600;cursor:pointer">
            ${inRoute ? '<i class="fa-solid fa-check"></i> Ditambahkan' : '<i class="fa-solid fa-plus"></i> Tambah Rute'}
          </button>
          ${IS_LOGGED ? `<button onclick="openUpload(${poi.id},'${poi.name.replace(/'/g,"\\'")}\")" style="background:#0ea5e9;color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;cursor:pointer"><i class="fa-solid fa-camera"></i></button>` : ''}
        </div>
      </div>`;
  }

  renderMarkers();

  // ── FILTER KATEGORI ──────────────────────────────────────
  document.querySelectorAll('.cat-filter').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.cat-filter').forEach(b => {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline-secondary');
      });
      this.classList.add('active', 'btn-primary');
      this.classList.remove('btn-outline-secondary');
      activeCat = this.dataset.cat;
      renderMarkers();
    });
  });

  // ── SEARCH STARTING POINT (dari DB, live search) ─────────
  function searchStartPoint(q) {
    const resultsEl = document.getElementById('startResults');
    if (!q) { resultsEl.style.display = 'none'; return; }
  
    const matches = POIS.filter(p => p.name.toLowerCase().includes(q.toLowerCase())).slice(0, 6);
    resultsEl.innerHTML = '';
    resultsEl.style.display = '';
  
    if (!matches.length) {
      resultsEl.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
      return;
    }

      matches.forEach(p => {
        const el = document.createElement('button');
        el.type      = 'button';
        el.className = 'list-group-item list-group-item-action small';
        el.textContent = p.name;
        el.addEventListener('click', () => {
          startPoint = { name: p.name, lat: parseFloat(p.latitude), lng: parseFloat(p.longitude) };
          document.getElementById('startName').textContent = startPoint.name;
          document.getElementById('startSelected').style.display = '';
          document.getElementById('startInput').value = '';
          resultsEl.style.display = 'none';
          updatePlannerUI();
        });
        resultsEl.appendChild(el);
      });
    }

    document.getElementById('startInput').addEventListener('input', function() {
      searchStartPoint(this.value.trim());
    });
    document.getElementById('startInput').addEventListener('keydown', e => {
      if (e.key === 'Escape') document.getElementById('startResults').style.display = 'none';
    });

  // ── ADD TO ROUTE ─────────────────────────────────────────
  window.addToRoute = function(poi_id) {
    if (!startPoint) {
      Swal.fire({ toast:true, position:'top-end', icon:'warning', title:'Pilih titik awal dulu!', showConfirmButton:false, timer:2000 });
      return;
    }
    if (routes.some(r => r.poi_id == poi_id)) {
      Swal.fire({ toast:true, position:'top-end', icon:'info', title:'Sudah ada di rute', showConfirmButton:false, timer:1500 });
      return;
    }
    const poi = POIS.find(p => p.id == poi_id);
    if (!poi) return;
    routes.push({ poi_id: poi.id, name: poi.name, lat: parseFloat(poi.latitude), lng: parseFloat(poi.longitude), note: '' });
    map.closePopup();
    updatePlannerUI();
    updateRouteOnMap();
  };

  // ── UPDATE PLANNER UI ────────────────────────────────────
  function updatePlannerUI() {
    const list  = document.getElementById('routeList');
    const empty = document.getElementById('routeEmpty');
    const btnG  = document.getElementById('btnGenerateRoute');
    const btnS  = document.getElementById('btnSaveTrip');
    const distI = document.getElementById('distanceInfo');

    if (routes.length === 0) {
      empty && (empty.style.display = '');
      list.innerHTML = '';
      list.appendChild(empty || document.createElement('div'));
      btnG.disabled = true;
      if (btnS) btnS.disabled = true;
      distI.style.display = 'none';
      return;
    }

    if (empty) empty.style.display = 'none';
    list.innerHTML = routes.map((r, i) => `
      <div class="d-flex align-items-start gap-2 mb-2 p-2 rounded border" data-idx="${i}">
        <span class="badge bg-primary rounded-pill mt-1">${i+1}</span>
        <div class="flex-grow-1 min-w-0">
          <div class="small fw-semibold text-truncate">${r.name}</div>
          ${r.distance_from_prev ? `<div class="text-muted" style="font-size:.7rem"><i class="fa-solid fa-ruler me-1"></i>${r.distance_from_prev} km dari titik sebelumnya</div>` : ''}
          ${IS_LOGGED ? `<div class="mt-1">
            <input type="text" class="form-control form-control-sm note-input" data-idx="${i}" placeholder="Tambah catatan..." value="${r.note}" style="font-size:.75rem">
          </div>` : ''}
        </div>
        <button class="btn btn-sm btn-outline-danger btn-remove-route" data-idx="${i}">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    `).join('');

    // Note input listeners
    list.querySelectorAll('.note-input').forEach(inp => {
      inp.addEventListener('change', function() {
        routes[this.dataset.idx].note = this.value;
      });
    });

    // Remove listeners
    list.querySelectorAll('.btn-remove-route').forEach(btn => {
      btn.addEventListener('click', function() {
        routes.splice(parseInt(this.dataset.idx), 1);
        updatePlannerUI();
        updateRouteOnMap();
      });
    });

    btnG.disabled = !(startPoint && routes.length > 0);
    if (btnS) btnS.disabled = !(startPoint && routes.length > 0);

    // Hitung total jarak
    const total = routes.reduce((s, r) => s + (parseFloat(r.distance_from_prev) || 0), 0);
    if (total > 0) {
      document.getElementById('totalDist').textContent = total.toFixed(1);
      document.getElementById('totalStops').textContent = `· ${routes.length} lokasi`;
      distI.style.display = '';
    }
  }

  // ── GENERATE ROUTE ───────────────────────────────────────
  document.getElementById('btnGenerateRoute').addEventListener('click', () => {
    if (!startPoint || routes.length === 0) return;

    const points = [
      [startPoint.lat, startPoint.lng],
      ...routes.map(r => [r.lat, r.lng])
    ];

    // Hitung jarak antar titik (Haversine)
    function haversine(a, b) {
      const R = 6371;
      const dLat = (b[0]-a[0]) * Math.PI/180;
      const dLng = (b[1]-a[1]) * Math.PI/180;
      const x = Math.sin(dLat/2)**2 + Math.cos(a[0]*Math.PI/180)*Math.cos(b[0]*Math.PI/180)*Math.sin(dLng/2)**2;
      return R * 2 * Math.atan2(Math.sqrt(x), Math.sqrt(1-x));
    }

    routes = routes.map((r, i) => ({
      ...r,
      distance_from_prev: haversine(points[i], points[i+1]).toFixed(2)
    }));

    updatePlannerUI();
    updateRouteOnMap(points);

    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Rute berhasil digenerate!', showConfirmButton:false, timer:2000 });
  });

  function updateRouteOnMap(points) {
    if (routeLine) map.removeLayer(routeLine);
    if (!points || points.length < 2) return;
    routeLine = L.polyline(points, { color: '#6366f1', weight: 3, opacity: .8, dashArray: '8 4' }).addTo(map);
    map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });
  }

  // ── RESET ────────────────────────────────────────────────
  document.getElementById('btnResetTrip').addEventListener('click', async () => {
    const conf = await Swal.fire({ title: 'Reset trip?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, reset', cancelButtonText: 'Batal', confirmButtonColor: '#dc3545' });
    if (!conf.isConfirmed) return;
    startPoint = null;
    routes     = [];
    document.getElementById('startSelected').style.display = 'none';
    document.getElementById('startInput').value = '';
    if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
    updatePlannerUI();
    renderMarkers();
  });

  // ── SAVE TRIP ────────────────────────────────────────────
  if (IS_LOGGED) {
    document.getElementById('btnSaveTrip').addEventListener('click', () => {
      const sf = document.getElementById('saveForm');
      sf.style.display = sf.style.display === 'none' ? '' : 'none';
    });

    // Load saved trips dropdown
    document.querySelector('[data-bs-toggle="dropdown"]')?.addEventListener('show.bs.dropdown', loadSavedTrips);

    async function loadSavedTrips() {
      const ul = document.getElementById('savedTripsList');
      try {
        const res  = await fetch(`${API_TRIP}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (!json.data.length) {
          ul.innerHTML = '<li><span class="dropdown-item text-muted small">Belum ada trip tersimpan</span></li>';
          return;
        }
        ul.innerHTML = json.data.map(t => `
          <li><button class="dropdown-item small d-flex justify-content-between align-items-center" onclick="loadTrip(${t.id})">
            <span><i class="fa-solid fa-route me-2 text-primary"></i>${t.title}</span>
            <span class="badge bg-light text-muted">${t.total_stops} stop</span>
          </button></li>
        `).join('');
      } catch(e) {}
    }

    window.loadTrip = async function(id) {
      // TODO: load trip detail & render ke planner
      Swal.fire({ toast:true, position:'top-end', icon:'info', title:'Memuat trip...', showConfirmButton:false, timer:1500 });
    };

    // Konfirmasi save
    document.getElementById('tripTitle').addEventListener('keydown', async e => {
      if (e.key !== 'Enter') return;
      await doSaveTrip();
    });

    async function doSaveTrip() {
      const title = document.getElementById('tripTitle').value.trim() || 'Trip Bandungku';
      const fd    = new FormData();
      fd.append('action',           'save');
      fd.append('csrf_token',       CSRF);
      fd.append('title',            title);
      fd.append('start_point_name', startPoint.name);
      fd.append('start_lat',        startPoint.lat);
      fd.append('start_lng',        startPoint.lng);
      fd.append('items',            JSON.stringify(routes.map((r,i) => ({
        poi_id: r.poi_id, order_index: i+1, distance_from_prev: r.distance_from_prev || 0, note: r.note
      }))));

      try {
        const res  = await fetch(API_TRIP, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const data = await res.json();
        if (data.success) {
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Trip disimpan!', showConfirmButton:false, timer:2000 });
          document.getElementById('saveForm').style.display = 'none';
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch(e) {
        Swal.fire('Error', 'Tidak bisa menyimpan trip', 'error');
      }
    }
  }

  // ── UPLOAD FOTO (LOGIN ONLY) ─────────────────────────────
  if (IS_LOGGED) {
    const modal = document.getElementById('uploadModal');

    window.openUpload = function(poi_id, poi_name) {
      document.getElementById('uploadPoiId').value    = poi_id;
      document.getElementById('uploadPoiName').textContent = poi_name;
      document.getElementById('uploadPoiSelected').style.display = '';
      document.getElementById('uploadPoiSearch').value = poi_name;
      document.getElementById('uploadPreview').style.display = 'none';
      document.getElementById('uploadFile').value     = '';
      document.getElementById('uploadCredit').value   = '';
      modal.style.display = 'flex';
    };

    document.getElementById('btnBatalUpload').addEventListener('click', () => {
      modal.style.display = 'none';
    });

    // Search POI di modal upload
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

    // Preview foto
    document.getElementById('uploadFile').addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 10 * 1024 * 1024) {
        Swal.fire('File terlalu besar', 'Maksimal 10MB', 'warning');
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });

    // Submit upload
    document.getElementById('btnKirimUpload').addEventListener('click', async () => {
      const poi_id = document.getElementById('uploadPoiId').value;
      const file   = document.getElementById('uploadFile').files[0];
      const credit = document.getElementById('uploadCredit').value.trim();

      if (!poi_id || !file) {
        Swal.fire('Oops!', 'Pilih lokasi dan foto dulu', 'warning');
        return;
      }

      const btn = document.getElementById('btnKirimUpload');
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengupload...';
      btn.disabled  = true;

      const fd = new FormData();
      fd.append('csrf_token', CSRF);
      fd.append('poi_id',     poi_id);
      fd.append('photo',      file);
      fd.append('caption',    credit);

      try {
        const res  = await fetch(API_GAL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const data = await res.json();
        if (data.success) {
          modal.style.display = 'none';
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Foto berhasil diupload!', showConfirmButton:false, timer:2500 });
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

})();
</script>