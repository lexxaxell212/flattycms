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

<main id="content">
<div class="tp-main">
<div class="tp-main-outer">
  <div class="tp-main-outer-content text-center">
    <h1 class="text-gradient">Trip Planner</h1>
    <p class="text-white">Explore Bandung, rencanakan perjalananmu</p>
  </div>
<div class="tp-main-inner">
<div class="container">

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
        <a href="/login" class="btn btn-primary btn-sm">
          <i class="fa-brands fa-google me-1"></i>Login
        </a>
      </div>
    <?php endif; ?>
  </div>

  <div class="tp-tabs">
    <button class="tp-tab active" data-tab="explore">
      Explore POI
    </button>
    <button class="tp-tab" data-tab="tripku">
      Tripku
    </button>
    <button class="tp-tab" data-tab="map">
      Map POI
    </button>
  </div>

  <div id="tab-explore" class="tp-tab-content">
  <div class="mb-3">
    <div class="input-group mb-2">
      <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
      <input type="text" id="exploreSearch" class="form-control" placeholder="Cari tempat wisata...">
    </div>
    <div class="d-flex gap-2 flex-wrap" id="exploreCatFilter">
      <button class="btn btn-primary btn-sm explore-cat active" data-cat="">Semua</button>
      <?php foreach ($categories as $cat): ?>
      <button class="btn btn-outline-primary btn-sm explore-cat" data-cat="<?=
      $cat['id'] ?>">
        <?= safe_html($cat['name']) ?>
      </button>
      <?php endforeach; ?>
    </div>
  </div>
  <div style="position:relative">
    <div id="explorePoiList" class="tp-card-grid"></div>
    <div id="exploreOverlay" style="display:none;position:absolute;bottom:0;left:0;right:0;height:200px;background:linear-gradient(to bottom, transparent, #fff);display:flex;align-items:flex-end;justify-content:center;padding-bottom:1rem">
      <button class="btn btn-primary btn-sm" id="btnShowAllPoi">
        <i class="fa-solid fa-grid me-1"></i>Lihat Semua
      </button>
    </div>
  </div>
</div>

  <div id="tab-tripku" class="tp-tab-content" style="display:none">
    <?php if ($is_logged): ?>
      <div id="tripkuList" class="tp-card-grid">
        <div class="text-center py-4 text-muted small" style="grid-column:1/-1">
          <i class="fa-solid fa-spinner fa-spin me-1"></i>Memuat trip tersimpan...
        </div>
      </div>
    <?php else: ?>
      <div class="tp-empty-state">
        <i class="fa-solid fa-lock"></i>
        <p>Login untuk melihat trip tersimpanmu.</p>
        <a href="/login" class="btn btn-primary mt-2">
          <i class="fa-brands fa-google me-1"></i>Login dengan Google
        </a>
      </div>
    <?php endif; ?>
  </div>

  <div id="tab-map" class="tp-tab-content" style="display:none">

    <div class="cat-filters">
      <button class="btn btn-primary btn-sm cat-filter active" data-cat="">
        <i class="fa-solid fa-layer-group me-1"></i>Semua
      </button>
      <?php foreach ($categories as $cat): ?>
      <button class="btn btn-outline-primary btn-sm cat-filter" data-cat="<?= $cat['id'] ?>">
        <?= safe_html($cat['name']) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <div class="tp-map-wrap">
      <div id="mainMap"></div>
    </div>

    <div class="tp-search-wrap">
      <div class="input-group">
        <span class="input-group-text">
          <i class="fa-solid fa-search text-muted"></i>
        </span>
        <input type="text" id="searchPoi" class="form-control" placeholder="Cari
        lokasi di map...">
      </div>
      <div id="searchPoiResults" class="list-group shadow"></div>
    </div>

    <div class="tp-info-strip d-flex flex-wrap gap-3 mb-3">
      <span><i class="fa-solid fa-circle-info text-primary"></i>Klik pin untuk info lokasi</span>
      <span><i class="fa-solid fa-route text-success"></i>Tambahkan ke Trip Planner</span>
      <?php if (!$is_logged): ?>
      <span><i class="fa-solid fa-lock text-warning"></i>
        <a href="/login">Login</a> untuk simpan trip
      </span>
      <?php endif; ?>
    </div>

    <div class="tp-planner-panel mb-3">
      <div class="tp-planner-header">
        <span class="title">
          <i class="fa-solid fa-route text-primary me-2"></i>Buat Trip Baru
        </span>
      </div>
      <div class="tp-planner-body">

        <div class="tp-start-section">
          <div class="tp-section-label">
            <i class="fa-solid fa-location-crosshairs text-danger me-1"></i>Titik Awal
          </div>
          <div class="input-group">
            <input type="text" id="startInput" class="form-control" placeholder="Cari titik awal...">
          </div>
          <div id="startResults" class="list-group mt-1" style="max-height:150px;overflow-y:auto;display:none;position:relative;z-index:100"></div>
          <div id="startSelected" class="text-success mt-1" style="display:none">
            <i class="fa-solid fa-check me-1"></i><span id="startName"></span>
          </div>
        </div>

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

        <div id="distanceInfo" class="alert alert-primary py-2 px-3 small mb-0" style="display:none">
          <i class="fa-solid fa-ruler me-1"></i>Total jarak: <strong id="totalDist">0</strong> km
          <span class="ms-2 text-muted" id="totalStops"></span>
        </div>

        <div class="tp-action-row">
          <button class="btn btn-primary btn-sm" id="btnGenerateRoute" disabled>
            <i class="fa-solid fa-route me-1"></i>Generate Rute
          </button>
          <?php if ($is_logged): ?>
          <button class="btn btn-success btn-sm" id="btnSaveTrip" disabled>
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
          </button>
          <?php endif; ?>
          <button class="btn btn-outline-accent btn-sm btn-icon" id="btnResetTrip" title="Reset">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>

        <?php if ($is_logged): ?>
        <div id="saveForm" style="display:none">
          <input type="text" id="tripTitle" class="form-control form-control-sm mb-2" placeholder="Nama trip (opsional)" value="Trip Bandungku">
          <button class="btn btn-success btn-sm w-100" id="btnConfirmSave">
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Sekarang
          </button>
        </div>
        <?php endif; ?>

      </div>
    </div>

  </div>

</div>
</div>
</div>
</div>

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
          <label class="form-label small fw-semibold">Lokasi <span class="text-danger">*</span></label>
          <div class="input-group">
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
          <div class="form-text">Maks 10MB · JPG, PNG, WebP</div>
          <div id="uploadPreview" class="mt-2" style="display:none">
            <img id="previewImg" src="" class="img-fluid rounded" style="max-height:200px;object-fit:cover;width:100%">
          </div>
        </div>
        <div class="mb-1">
          <label class="form-label small fw-semibold">Kredit <span class="text-muted fw-normal">(opsional)</span></label>
          <input type="text" id="uploadCredit" class="form-control form-control-sm" placeholder="Nama, Instagram, link...">
        </div>
      </div>
      <div class="modal-footer border-top py-3 px-4">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batalkan</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnKirimUpload">
          <i class="fa-solid fa-upload me-1"></i>Upload
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</main>