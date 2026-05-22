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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const CSRF      = CONFIG.csrfToken;
  const BASE      = CONFIG.baseUrl;
  const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
  const POIS      = <?= $pois_json ?>;
  const API_TRIP  = BASE + '/api/map/api-trips.php';
  const API_GAL   = BASE + '/api/map/api-gallery.php';
</script>
<script src="<?= JS_URL ?>trip.js" defer></script>

<style>
/* ── Trip Planner Page ── */
.tp-hero {
  position: relative;
  height: 200px;
  background: url('<?= BASE_URL ?>assets/img/bandung-hero.jpg') center/cover no-repeat;
  border-radius: 0 0 1.25rem 1.25rem;
  overflow: hidden;
  margin-bottom: 1rem;
}
.tp-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,.25) 0%, rgba(0,0,0,.65) 100%);
}
.tp-hero-content {
  position: absolute;
  bottom: 1.25rem;
  left: 1.25rem;
  z-index: 1;
  color: #fff;
}
.tp-hero-content h4 {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 1px 4px rgba(0,0,0,.4);
}
.tp-hero-content p {
  font-size: .82rem;
  margin: .15rem 0 0;
  opacity: .85;
}

/* Profile Card */
.tp-profile-card {
  display: flex;
  align-items: center;
  gap: .9rem;
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: .875rem;
  padding: .85rem 1rem;
  margin-bottom: 1rem;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.tp-profile-avatar {
  width: 46px;
  height: 46px;
  border-radius: 50%;
  background: var(--bs-primary);
  color: #fff;
  font-size: 1.1rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.tp-profile-avatar img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}
.tp-profile-info .name {
  font-weight: 600;
  font-size: .92rem;
  line-height: 1.2;
  color: #212529;
}
.tp-profile-info .email {
  font-size: .78rem;
  color: #6c757d;
  line-height: 1.3;
}
.tp-profile-info .trip-count {
  font-size: .78rem;
  font-weight: 600;
  color: var(--bs-primary);
  margin-top: .15rem;
}
.tp-profile-login {
  margin-left: auto;
}

/* Tabs */
.tp-tabs {
  display: flex;
  border-bottom: 2px solid #e9ecef;
  margin-bottom: 1rem;
  gap: 0;
}
.tp-tabs .tp-tab {
  flex: 1;
  text-align: center;
  padding: .6rem .5rem;
  font-size: .82rem;
  font-weight: 600;
  color: #6c757d;
  cursor: pointer;
  border: none;
  background: none;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  transition: color .2s, border-color .2s;
  white-space: nowrap;
}
.tp-tabs .tp-tab.active {
  color: var(--bs-primary);
  border-bottom-color: var(--bs-primary);
}
.tp-tabs .tp-tab:hover:not(.active) {
  color: #343a40;
}

/* POI Card */
.poi-card {
  display: flex;
  gap: .85rem;
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: .875rem;
  padding: .85rem;
  margin-bottom: .75rem;
  box-shadow: 0 1px 3px rgba(0,0,0,.05);
  transition: box-shadow .2s;
}
.poi-card:hover {
  box-shadow: 0 3px 10px rgba(0,0,0,.1);
}
.poi-card-img {
  width: 80px;
  height: 80px;
  border-radius: .625rem;
  object-fit: cover;
  flex-shrink: 0;
  background: #f0f0f0;
}
.poi-card-body { flex: 1; min-width: 0; }
.poi-card-title {
  font-size: .9rem;
  font-weight: 700;
  color: var(--bs-primary);
  margin-bottom: .2rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.poi-card-desc {
  font-size: .78rem;
  color: #6c757d;
  line-height: 1.4;
  margin-bottom: .5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.poi-card-actions { display: flex; gap: .4rem; flex-wrap: wrap; }
.poi-card-actions .btn { font-size: .72rem; padding: .2rem .55rem; border-radius: .4rem; }

/* Trip Saved Card */
.trip-saved-card {
  display: flex;
  gap: .85rem;
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: .875rem;
  padding: .85rem;
  margin-bottom: .75rem;
  box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.trip-saved-thumb {
  width: 80px;
  height: 80px;
  border-radius: .625rem;
  object-fit: cover;
  flex-shrink: 0;
  background: #f0f0f0;
}
.trip-saved-body { flex: 1; min-width: 0; }
.trip-saved-title {
  font-size: .9rem;
  font-weight: 700;
  color: var(--bs-primary);
  margin-bottom: .15rem;
}
.trip-saved-note {
  font-size: .78rem;
  color: #6c757d;
  margin-bottom: .5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Map Tab */
.tp-map-wrap {
  border-radius: .875rem;
  overflow: hidden;
  border: 1px solid #e9ecef;
  margin-bottom: .875rem;
  box-shadow: 0 1px 4px rgba(0,0,0,.07);
}
#mainMap { height: 340px; }

.tp-search-wrap {
  position: relative;
  margin-bottom: .875rem;
}
.tp-search-wrap .input-group-text {
  background: #fff;
  border-right: none;
}
.tp-search-wrap .form-control {
  border-left: none;
  font-size: .85rem;
}
.tp-search-wrap .form-control:focus {
  box-shadow: none;
  border-color: #dee2e6;
}
#searchPoiResults {
  position: absolute;
  top: 100%;
  left: 0; right: 0;
  z-index: 999;
  max-height: 200px;
  overflow-y: auto;
  display: none;
}

.cat-filters {
  display: flex;
  gap: .45rem;
  overflow-x: auto;
  padding-bottom: .5rem;
  margin-bottom: .875rem;
  scrollbar-width: none;
}
.cat-filters::-webkit-scrollbar { display: none; }
.cat-filters .btn { white-space: nowrap; font-size: .75rem; }

/* Trip Planner Panel */
.tp-planner-panel {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: .875rem;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.tp-planner-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: .875rem 1rem;
  border-bottom: 1px solid #e9ecef;
  background: #fafafa;
}
.tp-planner-header .title {
  font-weight: 600;
  font-size: .9rem;
  color: #212529;
}
.tp-planner-body { padding: 1rem; }

.tp-start-section { margin-bottom: .875rem; }
.tp-section-label {
  font-size: .76rem;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: .04em;
  margin-bottom: .4rem;
}

.route-empty {
  text-align: center;
  padding: 2rem 1rem;
  color: #adb5bd;
}
.route-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; }
.route-empty span { font-size: .82rem; }

#distanceInfo { font-size: .82rem; border-radius: .5rem; }

.tp-action-row {
  display: flex;
  gap: .5rem;
  flex-wrap: wrap;
  margin-top: .875rem;
}
.tp-action-row .btn { font-size: .8rem; flex: 1; }
.tp-action-row .btn-icon { flex: 0 0 auto; }

#saveForm { margin-top: .75rem; }
#saveForm .form-control { font-size: .82rem; border-radius: .5rem; }

.tp-info-strip {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: .875rem;
  padding: .65rem 1rem;
  margin-top: .875rem;
}
.tp-info-strip span {
  font-size: .76rem;
  color: #6c757d;
  display: inline-flex;
  align-items: center;
  gap: .3rem;
}

/* Empty/placeholder states */
.tp-empty-state {
  text-align: center;
  padding: 2.5rem 1rem;
  color: #adb5bd;
}
.tp-empty-state i { font-size: 2.5rem; display: block; margin-bottom: .75rem; opacity: .4; }
.tp-empty-state p { font-size: .85rem; margin: 0; }

/* Trending badge */
.trending-badge {
  font-size: .68rem;
  font-weight: 700;
  background: #fff3cd;
  color: #856404;
  border-radius: .3rem;
  padding: .1rem .4rem;
  margin-left: .35rem;
  vertical-align: middle;
}

/* Desktop: 2-col card grid for Trending & Tripku */
@media (min-width: 768px) {
  .tp-card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .75rem;
  }
  .tp-card-grid .poi-card,
  .tp-card-grid .trip-saved-card { margin-bottom: 0; }

  #mainMap { height: 460px; }
  .tp-hero { height: 240px; }
}
</style>

<main id="content">
<div class="container-fluid">
<div class="container px-3 px-md-4" style="max-width:960px">

  <!-- Hero -->
  <div class="tp-hero">
    <div class="tp-hero-content">
      <h4><i class="fa-solid fa-map-location-dot me-2"></i>Trip Planner</h4>
      <p>Explore Bandung, rencanakan perjalananmu</p>
    </div>
  </div>

  <!-- Profile Card -->
  <div class="tp-profile-card">
    <?php if ($is_logged):
      $u = $_SESSION['user'];
      $initials = strtoupper(substr($u['name'] ?? 'U', 0, 1));
    ?>
      <div class="tp-profile-avatar">
        <?php if (!empty($u['avatar'])): ?>
          <img src="<?= safe_html($u['avatar']) ?>" alt="Avatar">
        <?php else: ?>
          <?= $initials ?>
        <?php endif; ?>
      </div>
      <div class="tp-profile-info">
        <div class="name"><?= safe_html($u['name'] ?? 'Pengguna') ?></div>
        <div class="email"><?= safe_html($u['email'] ?? '') ?></div>
        <div class="trip-count">Trip tersimpan : <span id="profileTripCount">—</span></div>
      </div>
    <?php else: ?>
      <div class="tp-profile-avatar" style="background:#dee2e6;color:#6c757d">
        <i class="fa-solid fa-user"></i>
      </div>
      <div class="tp-profile-info">
        <div class="name">Tamu</div>
        <div class="email">Belum login</div>
      </div>
      <div class="tp-profile-login ms-auto">
        <a href="/auth/google" class="btn btn-primary btn-sm">
          <i class="fa-brands fa-google me-1"></i>Login
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tabs -->
  <div class="tp-tabs">
    <button class="tp-tab active" data-tab="trending">
      <i class="fa-solid fa-fire-flame-curved me-1"></i>Trending POI
    </button>
    <button class="tp-tab" data-tab="tripku">
      <i class="fa-solid fa-suitcase me-1"></i>Tripku
    </button>
    <button class="tp-tab" data-tab="map">
      <i class="fa-solid fa-map me-1"></i>Map POI
    </button>
  </div>

  <!-- ── TAB: TRENDING POI ──────────────────────────── -->
  <div id="tab-trending" class="tp-tab-content">
    <div id="trendingPoiList" class="tp-card-grid">
      <!-- Skeleton / Loading state -->
      <div class="text-center py-4 text-muted small" id="trendingLoading">
        <i class="fa-solid fa-spinner fa-spin me-1"></i>Memuat trending POI...
      </div>
    </div>
  </div>

  <!-- ── TAB: TRIPKU ────────────────────────────────── -->
  <div id="tab-tripku" class="tp-tab-content" style="display:none">
    <?php if ($is_logged): ?>
      <div id="tripkuList" class="tp-card-grid">
        <div class="text-center py-4 text-muted small" id="tripkuLoading">
          <i class="fa-solid fa-spinner fa-spin me-1"></i>Memuat trip tersimpan...
        </div>
      </div>
    <?php else: ?>
      <div class="tp-empty-state">
        <i class="fa-solid fa-lock"></i>
        <p>Login untuk melihat trip tersimpanmu.</p>
        <a href="/auth/google" class="btn btn-primary btn-sm mt-2">
          <i class="fa-brands fa-google me-1"></i>Login dengan Google
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- ── TAB: MAP POI ───────────────────────────────── -->
  <div id="tab-map" class="tp-tab-content" style="display:none">

    <!-- Search -->
    <div class="tp-search-wrap">
      <div class="input-group input-group-sm">
        <span class="input-group-text border-end-0">
          <i class="fa-solid fa-search text-muted"></i>
        </span>
        <input type="text" id="searchPoi" class="form-control border-start-0"
               placeholder="Cari lokasi di peta...">
      </div>
      <div id="searchPoiResults" class="list-group shadow"></div>
    </div>

    <!-- Filter Kategori -->
    <div class="cat-filters">
      <button class="btn btn-primary btn-sm cat-filter active" data-cat="">
        <i class="fa-solid fa-layer-group me-1"></i>Semua
      </button>
      <?php foreach ($categories as $cat): ?>
      <button class="btn btn-outline-secondary btn-sm cat-filter"
              data-cat="<?= $cat['id'] ?>">
        <i class="fa-solid <?= safe_html($cat['icon']) ?> me-1"></i><?= safe_html($cat['name']) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Map -->
    <div class="tp-map-wrap">
      <div id="mainMap"></div>
    </div>

    <!-- Info strip -->
    <div class="tp-info-strip d-flex flex-wrap gap-3 mb-3">
      <span><i class="fa-solid fa-circle-info text-primary"></i>Klik pin untuk info lokasi</span>
      <span><i class="fa-solid fa-route text-success"></i>Tambahkan ke Trip Planner</span>
      <?php if (!$is_logged): ?>
      <span><i class="fa-solid fa-lock text-warning"></i>
        <a href="/auth/google" class="text-warning text-decoration-none">Login</a> untuk simpan trip
      </span>
      <?php endif; ?>
    </div>

    <!-- Trip Planner Panel -->
    <div class="tp-planner-panel mb-3">
      <div class="tp-planner-header">
        <span class="title">
          <i class="fa-solid fa-route text-primary me-2"></i>Buat Trip Baru
        </span>
        <?php if ($is_logged): ?>
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                  data-bs-toggle="dropdown">
            <i class="fa-solid fa-folder-open me-1"></i>Tersimpan
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow" id="savedTripsList"
              style="min-width:250px">
            <li><span class="dropdown-item text-muted small">Memuat...</span></li>
          </ul>
        </div>
        <?php endif; ?>
      </div>

      <div class="tp-planner-body">
        <!-- Titik Awal -->
        <div class="tp-start-section">
          <div class="tp-section-label">
            <i class="fa-solid fa-location-crosshairs text-danger me-1"></i>Titik Awal
          </div>
          <div class="input-group input-group-sm">
            <input type="text" id="startInput" class="form-control"
                   placeholder="Cari titik awal...">
          </div>
          <div id="startResults" class="list-group mt-1"
               style="max-height:150px;overflow-y:auto;display:none;position:relative;z-index:100"></div>
          <div id="startSelected" class="small text-success mt-1" style="display:none">
            <i class="fa-solid fa-check me-1"></i><span id="startName"></span>
          </div>
        </div>

        <!-- Rute -->
        <div class="mb-3">
          <div class="tp-section-label">
            <i class="fa-solid fa-list-ol text-primary me-1"></i>Rute Perjalanan
          </div>
          <div id="routeList">
            <div class="route-empty" id="routeEmpty">
              <i class="fa-solid fa-map-pin"></i>
              <span>Klik pin di peta untuk tambah lokasi</span>
            </div>
          </div>
        </div>

        <!-- Jarak -->
        <div id="distanceInfo" class="alert alert-primary py-2 px-3 small mb-0"
             style="display:none">
          <i class="fa-solid fa-ruler me-1"></i>Total jarak: <strong id="totalDist">0</strong> km
          <span class="ms-2 text-muted" id="totalStops"></span>
        </div>

        <!-- Actions -->
        <div class="tp-action-row">
          <button class="btn btn-primary btn-sm" id="btnGenerateRoute" disabled>
            <i class="fa-solid fa-route me-1"></i>Generate Rute
          </button>
          <?php if ($is_logged): ?>
          <button class="btn btn-success btn-sm" id="btnSaveTrip" disabled>
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
          </button>
          <?php endif; ?>
          <button class="btn btn-outline-danger btn-sm btn-icon" id="btnResetTrip" title="Reset">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>

        <!-- Save Form -->
        <?php if ($is_logged): ?>
        <div id="saveForm" style="display:none">
          <input type="text" id="tripTitle" class="form-control form-control-sm mb-2"
                 placeholder="Nama trip (opsional)" value="Trip Bandungku">
          <button class="btn btn-success btn-sm w-100" id="btnConfirmSave">
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Sekarang
          </button>
        </div>
        <?php endif; ?>

      </div>
    </div>

  </div><!-- /tab-map -->

</div><!-- /container -->
</div><!-- /container-fluid -->
</main>


<!-- ── MODAL UPLOAD FOTO ──────────────────────────────── -->
<?php if ($is_logged): ?>
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;overflow:hidden">
      <div class="modal-header border-bottom py-3 px-4">
        <h6 class="modal-title fw-semibold mb-0">
          <i class="fa-solid fa-camera me-2 text-primary"></i>Upload Foto
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label small fw-semibold">
            Lokasi <span class="text-danger">*</span>
          </label>
          <div class="input-group input-group-sm">
            <input type="text" id="uploadPoiSearch" class="form-control"
                   placeholder="Cari nama tempat...">
          </div>
          <div id="uploadPoiResults" class="list-group mt-1"
               style="max-height:140px;overflow-y:auto;display:none"></div>
          <input type="hidden" id="uploadPoiId">
          <div id="uploadPoiSelected" class="small text-success mt-1" style="display:none">
            <i class="fa-solid fa-check me-1"></i><span id="uploadPoiName"></span>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold">
            Foto <span class="text-danger">*</span>
          </label>
          <input type="file" id="uploadFile" class="form-control form-control-sm"
                 accept="image/jpeg,image/png,image/webp">
          <div class="form-text">Maks 10MB · JPG, PNG, WebP</div>
          <div id="uploadPreview" class="mt-2" style="display:none">
            <img id="previewImg" src="" class="img-fluid rounded"
                 style="max-height:200px;object-fit:cover;width:100%">
          </div>
        </div>
        <div class="mb-1">
          <label class="form-label small fw-semibold">
            Kredit <span class="text-muted fw-normal">(opsional)</span>
          </label>
          <input type="text" id="uploadCredit" class="form-control form-control-sm"
                 placeholder="Nama, Instagram, link...">
        </div>
      </div>
      <div class="modal-footer border-top py-3 px-4">
        <button type="button" class="btn btn-outline-secondary btn-sm"
                data-bs-dismiss="modal" id="btnBatalUpload">Batalkan</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnKirimUpload">
          <i class="fa-solid fa-upload me-1"></i>Upload
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
/* ── Tab switching ────────────────────────────── */
(function () {
  const tabs     = document.querySelectorAll('.tp-tab');
  const contents = document.querySelectorAll('.tp-tab-content');
  let tripkuLoaded    = false;
  let trendingLoaded  = false;
  let mapInitialized  = false;

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const target = this.dataset.tab;
      contents.forEach(c => c.style.display = 'none');
      document.getElementById('tab-' + target).style.display = '';

      if (target === 'trending' && !trendingLoaded) {
        loadTrendingPoi();
        trendingLoaded = true;
      }
      if (target === 'tripku' && !tripkuLoaded && IS_LOGGED) {
        loadTripku();
        tripkuLoaded = true;
      }
      if (target === 'map' && !mapInitialized) {
        /* Trigger resize supaya Leaflet render benar setelah tab muncul */
        setTimeout(() => {
          if (window.mainMap) window.mainMap.invalidateSize();
        }, 50);
        mapInitialized = true;
      }
    });
  });

  /* Load trending saat pertama buka (tab default) */
  loadTrendingPoi();
  trendingLoaded = true;

  /* Update trip count di profile card */
  if (IS_LOGGED) {
    fetch(API_TRIP + '?action=count')
      .then(r => r.json())
      .then(d => {
        const el = document.getElementById('profileTripCount');
        if (el && d.count !== undefined) el.textContent = d.count;
      }).catch(() => {});
  }
})();

/* ── Load Trending POI ────────────────────────── */
function loadTrendingPoi() {
  fetch(API_TRIP + '?action=trending')
    .then(r => r.json())
    .then(data => {
      const wrap = document.getElementById('trendingPoiList');
      if (!data.length) {
        wrap.innerHTML = `
          <div class="tp-empty-state" style="grid-column:1/-1">
            <i class="fa-solid fa-fire-flame-curved"></i>
            <p>Belum ada data trending POI.</p>
          </div>`;
        return;
      }
      wrap.innerHTML = data.map((poi, i) => `
        <div class="poi-card">
          <img class="poi-card-img"
               src="${poi.thumbnail || BASE + '/assets/img/poi-placeholder.jpg'}"
               alt="${escHtml(poi.name)}"
               onerror="this.src='${BASE}/assets/img/poi-placeholder.jpg'">
          <div class="poi-card-body">
            <div class="poi-card-title">
              ${escHtml(poi.name)}
              ${i < 3 ? '<span class="trending-badge">🔥 Hot</span>' : ''}
            </div>
            <div class="poi-card-desc">${escHtml(poi.description || 'Deskripsi belum tersedia.')}</div>
            <div class="poi-card-actions">
              <a href="${BASE}/poi/${poi.slug || poi.id}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-location-dot me-1"></i>Lihat
              </a>
              <button class="btn btn-outline-secondary btn-sm"
                      onclick="openPoiGallery(${poi.id})">
                <i class="fa-solid fa-images me-1"></i>Galeri
              </button>
            </div>
          </div>
        </div>`).join('');
    })
    .catch(() => {
      document.getElementById('trendingPoiList').innerHTML = `
        <div class="tp-empty-state" style="grid-column:1/-1">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <p>Gagal memuat data. Coba refresh halaman.</p>
        </div>`;
    });
}

/* ── Load Tripku ──────────────────────────────── */
function loadTripku() {
  const wrap = document.getElementById('tripkuList');
  wrap.innerHTML = `<div class="text-center py-4 text-muted small" style="grid-column:1/-1">
    <i class="fa-solid fa-spinner fa-spin me-1"></i>Memuat trip tersimpan...</div>`;

  fetch(API_TRIP + '?action=list')
    .then(r => r.json())
    .then(data => {
      /* Normalkan: support {data:[...]} atau langsung [...] */
      const list = Array.isArray(data) ? data : (data.data || []);

      /* Update profile count */
      const countEl = document.getElementById('profileTripCount');
      if (countEl) countEl.textContent = list.length;

      if (!list.length) {
        wrap.innerHTML = `
          <div class="tp-empty-state" style="grid-column:1/-1">
            <i class="fa-solid fa-suitcase"></i>
            <p>Belum ada trip tersimpan.<br>Buat trip pertamamu di tab Map POI!</p>
          </div>`;
        return;
      }

      wrap.innerHTML = list.map(trip => `
        <div class="trip-saved-card">
          <img class="trip-saved-thumb"
               src="${trip.thumbnail || BASE + '/assets/img/poi-placeholder.jpg'}"
               alt="${escHtml(trip.title)}"
               onerror="this.src='${BASE}/assets/img/poi-placeholder.jpg'">
          <div class="trip-saved-body">
            <div class="trip-saved-title">${escHtml(trip.title || 'Trip tanpa nama')}</div>
            <div class="trip-saved-note">
              ${trip.total_stops ? `<span class="me-2"><i class="fa-solid fa-map-pin me-1 text-primary"></i>${trip.total_stops} stop</span>` : ''}
              ${trip.total_distance ? `<span><i class="fa-solid fa-ruler me-1 text-muted"></i>${trip.total_distance} km</span>` : ''}
              ${(!trip.total_stops && !trip.total_distance) ? escHtml(trip.notes || 'Catatan kosong.') : ''}
            </div>
            <div class="d-flex gap-2 mt-2 flex-wrap">
              <button class="btn btn-primary btn-sm"
                      onclick="loadSavedTrip(${trip.id})">
                <i class="fa-solid fa-route me-1"></i>Buka di Peta
              </button>
              <button class="btn btn-outline-danger btn-sm"
                      onclick="deleteSavedTrip(${trip.id}, '${escHtml(trip.title || 'Trip ini')}')">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>
        </div>`).join('');
    })
    .catch(() => {
      wrap.innerHTML = `
        <div class="tp-empty-state" style="grid-column:1/-1">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <p>Gagal memuat trip. Coba refresh halaman.</p>
        </div>`;
    });
}

/* Expose refreshTripku supaya map.js bisa trigger reload setelah save/delete */
window.refreshTripku = function () {
  loadTripku();
};

/* ── Helpers ──────────────────────────────────── */
function escHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

/* Buka trip tersimpan → pindah ke tab Map */
function loadSavedTrip(tripId) {
  document.querySelector('[data-tab="map"]').click();
  /* Map.js harus expose fungsi ini */
  if (typeof window.loadTripById === 'function') {
    window.loadTripById(tripId);
  }
}

/* Open gallery (modal dipanggil dari map.js) */
function openPoiGallery(poiId) {
  if (typeof window.showPoiGallery === 'function') {
    window.showPoiGallery(poiId);
  }
}

/* Upload modal: konversi dari custom ke Bootstrap Modal */
<?php if ($is_logged): ?>
const _uploadModalEl = document.getElementById('uploadModal');
const _uploadModal   = _uploadModalEl ? new bootstrap.Modal(_uploadModalEl) : null;

/* Supaya map.js tetap bisa panggil openUploadModal() */
window.openUploadModal = function (poiId, poiName) {
  if (!_uploadModal) return;
  document.getElementById('uploadPoiId').value    = poiId  || '';
  document.getElementById('uploadPoiName').textContent = poiName || '';
  const sel = document.getElementById('uploadPoiSelected');
  if (poiId && poiName) sel.style.display = '';
  else sel.style.display = 'none';
  _uploadModal.show();
};
document.getElementById('btnBatalUpload')
  ?.addEventListener('click', () => _uploadModal?.hide());
<?php endif; ?>
</script>