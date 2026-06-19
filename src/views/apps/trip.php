<?php
require_once LIB_PATH . 'poi-actions.php';

$page_title = 'Trip Planner - ' . SITE_NAME;

$pois = get_all_poi(true);
$pois = array_map(function($p) {
  $p['description'] = mb_substr($p['description'] ?? '', 0, 150) .
  (mb_strlen($p['description'] ?? '') > 150 ? '...' : '');
  $p['name'] = mb_substr($p['name'] ?? '', 0, 30) . (mb_strlen($p['name'] ??
    '') > 30 ? '...' : '');
  return $p;
}, $pois);

$pois_full = array_map(function($p) {
  return [
    'id' => $p['id'],
    'name' => $p['name'],
    'description' => $p['description'],
    'slug' => $p['slug'],
    'category_name' => $p['category_name'],
    'address' => $p['address'],
  ];
}, get_all_poi(true));

$categories = get_poi_categories();
$is_logged = isset($_SESSION['user']);
$pois_json = json_encode($pois);
$pois_full_json = json_encode($pois_full);
$cats_json = json_encode($categories);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const CSRF = CONFIG.csrfToken;
  const BASE = CONFIG.baseUrl;
  const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
  const POIS = <?= $pois_json ?>;
  const POIS_FULL = <?= $pois_full_json ?>;
  const API_TRIP = BASE + '/api/map/api-trips.php';
  const API_GAL = BASE + '/api/map/api-gallery.php';
</script>
<script src="<?= JS_URL ?>trip.js" defer></script>
<script src="<?= JS_URL ?>ai-trip.js" defer></script>
<main class="main-content">
  <div class="tp-main-hero"></div>
  <div class="tp-main-outer">
    <div class="tp-main-outer-content text-center">
      <h1 class="text-white text-hero">Trip Planner</h1>
      <p class="text-white" data-bhs="tp.page.hero.subtitle">
        Explore Bandung, rencanakan perjalananmu
      </p>
    </div>
    <div class="tp-main-inner">
      <div class="container container-tp">
        <div class="tp-sidebar">
          <div class="tp-profile-card">
            <?php if ($is_logged):
            $u = $_SESSION['user'];
            $initials = strtoupper(substr($u['name'] ?? 'U', 0, 1));
            ?>
            <div class="tp-profile-avatar">
              <?php if (!empty($u['avatar'])): ?>
              <img src="<?= safe_html($u['avatar']) ?>" alt="Avatar">
              <?php else : ?>
              <?= $initials ?>
              <?php endif; ?>
            </div>
            <div class="tp-profile-info">
              <div class="name">
                <?= safe_html($u['name'] ?? 'Pengguna') ?>
              </div>
              <div class="email">
                <?= safe_html($u['email'] ?? '') ?>
              </div>
              <div class="trip-count">
                <span data-bhs="tp.page.profile.saved_count">Trip tersimpan :</span> <span id="profileTripCount">-</span>
              </div>
            </div>
            <?php else : ?>
            <div class="tp-profile-avatar" style="background:var(--bg-primary-subtle);color:var(--text-primary)">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="tp-profile-info">
              <div class="name" data-bhs="tp.page.profile.guest_name">
                Pengguna
              </div>
              <div class="email" data-bhs="tp.page.profile.guest_email">
                Belum masuk
              </div>
            </div>
            <div class="tp-profile-login ms-auto">
              <a href="/login" class="btn btn-primary btn-sm" data-bhs="tp.page.profile.login_btn">
                Masuk
              </a>
            </div>
            <?php endif; ?>
          </div>
          <div class="tp-tabs">
            <button class="tp-tab active" data-tab="explore" data-bhs="tp.page.tab.poi">
              <i class="fas fa-location-dot me-1"></i>POI
            </button>
            <button class="tp-tab" data-tab="tripku" data-bhs="tp.page.tab.tripku">
              <i class="fas fa-bookmark me-1"></i>Tripku
            </button>
            <button class="tp-tab" data-tab="map" data-bhs="tp.page.tab.buat">
              <i class="fas fa-route me-1"></i>Buat
            </button>
            <button class="tp-tab" data-tab="ai" data-bhs="tp.page.tab.ai">
              <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Itenerary
            </button>
          </div>
        </div>
        <div class="tp-content">
          <div id="tab-explore" class="tp-tab-content">
            <div class="input-group mb-4">
              <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
              <input type="text" id="exploreSearch" class="form-control" data-bhs="tp.page.explore.search_placeholder">
            </div>
            <div class="d-flex gap-2 flex-wrap mb-4" id="exploreCatFilter">
              <button class="btn btn-primary btn-sm explore-cat active" data-cat="" data-bhs="tp.page.explore.cat_all">Semua</button>
              <?php foreach ($categories as $cat): ?>
              <button class="btn btn-outline-primary btn-sm explore-cat" data-cat="<?= $cat['id'] ?>">
                <?= safe_html($cat['name']) ?>
              </button>
              <?php endforeach; ?>
            </div>
            <div style="position:relative">
              <div id="explorePoiList" class="tp-card-grid"></div>
              <div id="exploreOverlay" style="display:none;position:absolute;bottom:0;left:0;right:0;height:200px;background:linear-gradient(to bottom, transparent, var(--bg-surface));display:flex;align-items:flex-end;justify-content:center;padding-bottom:1rem">
                <button class="btn btn-outline-primary btn-sm" id="btnShowAllPoi" data-bhs="tp.page.explore.show_all">
                  Lihat Semua
                </button>
              </div>
            </div>
          </div>
          <div id="tab-tripku" class="tp-tab-content" style="display:none">
            <?php if ($is_logged): ?>
            <div id="tripkuList" class="tp-card-grid">
              <div class="text-center py-4 text-muted" style="grid-column:1/-1">
                <i class="fas fa-circle-notch fa-spin me-2"></i><span data-bhs="tp.page.tripku.loading">Memuat...</span>
              </div>
            </div>
            <?php else : ?>
            <div class="tp-empty-state">
              <i class="fa-solid fa-lock"></i>
              <p data-bhs="tp.page.tripku.login_msg">
                Masuk untuk melihat trip tersimpanmu.
              </p>
              <a href="/login" class="btn btn-primary btn-sm mt-3" data-bhs="tp.page.tripku.login_btn">Masuk</a>
            </div>
            <?php endif; ?>
          </div>
          <div id="tab-map" class="tp-tab-content" style="display:none">
            <div class="rounded-lg p-3" style="background:var(--bg-surface);border:var(--border-surface);">
              <div class="tp-map-wrap">
                <div id="mainMap"></div>
              </div>
              <div class="mx-auto" style="max-width:740px">
                <div class="cat-filters mb-2">
                  <button class="btn btn-primary cat-filter active btn-sm" data-cat="" data-bhs="tp.page.map.cat_all">Semua</button>
                  <?php foreach ($categories as $cat): ?>
                  <button class="btn btn-outline-primary cat-filter btn-sm" data-cat="<?= $cat['id'] ?>">
                    <?= safe_html($cat['name']) ?>
                  </button>
                  <?php endforeach; ?>
                </div>
                <div class="tp-search-wrap">
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fa-solid fa-search text-muted ms-1"></i>
                    </span>
                    <input type="text" id="searchPoi" class="form-control" data-bhs="tp.page.map.search_poi_placeholder">
                  </div>
                  <div id="searchPoiResults" class="list-group p-2 mt-2" style="display:none"></div>
                </div>
              </div>
            </div>
            <div class="tp-map-planner-grid">
              <div class="tp-planner-panel">
                <div class="tp-planner-header mx-auto">
                  <h2 class="text-center" data-bhs="tp.page.map.create_title">
                    Buat <em>Trip</em>
                  </h2>
                </div>
                <div class="tp-planner-body mx-auto">
                  <div class="tp-start-section mb-3">
                    <div class="tp-section-label">
                      <div class="d-flex flex-row mb-2">
                        <span class="badge badge-primary m-0">
                          <i class="fas fa-location-dot"></i>
                        </span>
                        <span class="h5 my-auto ms-2" data-bhs="tp.page.map.start_label">Titik Awal</span>
                      </div>
                    </div>
                    <div class="input-group mb-4">
                      <span class="input-group-text">
                        <i class="fa-solid fa-search text-muted ms-1"></i>
                      </span>
                      <input type="text" id="startInput" class="form-control" data-bhs="tp.page.map.start_placeholder">
                    </div>
                    <div id="startResults" class="list-group mt-2 p-2" style="max-height:150px;overflow-y:auto;display:none;position:relative;z-index:100"></div>
                    <div id="startSelected" class="row" style="display:none">
                      <div class="mb-2">
                        <div class="badge badge-green">
                          <span data-bhs="tp.page.map.start_badge">Kamu mulai dari</span>
                          <span id="startName2" class="text-truncate fw-bold">Kawah Putih</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-8">
                        <div class="card bg-card">
                          <div class="card-body">
                            <div id="startImg"></div>
                            <h5 id="startName" class="fw-semibold"></h5>
                            <p class="text-muted small" id="startDesc"></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tp-section-label mt-4">
                    <div class="d-flex flex-row mb-2">
                      <span class="badge badge-primary m-0">
                        <i class="fas fa-list-ol"></i>
                      </span>
                      <span class="h5 my-auto ms-2" data-bhs="tp.page.map.route_label">Rute POI</span>
                    </div>
                  </div>
                  <div id="routeList" class="mb-4">
                    <div class="route-empty mt-2" id="routeEmpty" style="display:flex">
                      <i class="fa-solid fa-map-pin text-accent"></i>
                      <span data-bhs="tp.page.map.route_empty">Klik pin di map untuk tambah lokasi</span>
                    </div>
                  </div>
                  <div id="distanceInfo" class="alert alert-primary py-2 px-3 small mb-4" style="display:none;width:fit-content">
                    <i class="fa-solid fa-ruler me-2"></i><span data-bhs="tp.page.map.distance_info">Total jarak:</span> <strong id="totalDist">0</strong> km
                    <span class="ms-2 text-muted" id="totalStops"></span>
                  </div>
                  <div class="tp-action-row">
                    <button class="btn btn-primary btn-sm" id="btnGenerateRoute" disabled data-bhs="tp.page.map.btn_create">
                      <i class="fa-solid fa-route me-1"></i>Buat Trip
                    </button>
                    <?php if ($is_logged): ?>
                    <button class="btn btn-outline-success btn-sm" id="btnSaveTrip" disabled data-bhs="tp.page.map.btn_save">
                      <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-outline-danger btn-sm" id="btnResetTrip" title="Reset" data-bhs="tp.page.map.btn_reset">
                      <i class="fa-solid fa-trash"></i>Reset
                    </button>
                  </div>
                  <?php if ($is_logged): ?>
                  <div id="saveForm" style="display:none">
                    <div class="d-flex flex-row mb-3">
                      <span class="badge badge-primary m-0">
                        <i class="fas fa-floppy-disk"></i>
                      </span>
                      <span class="h5 my-auto ms-2" data-bhs="tp.page.map.save_label">Simpan Rute</span>
                    </div>
                    <input type="text" id="tripTitle" class="form-control mb-3" data-bhs="tp.page.map.trip_title_placeholder" value="Trip Bandungku">
                    <button class="btn btn-success btn-sm" id="btnConfirmSave" data-bhs="tp.page.map.btn_confirm_save">
                      <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                    </button>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-ai" class="tp-tab-content" style="display:none">
            <div class="rounded-lg py-4 bg-surface mx-auto mb-5" style="max-width:740px">
              <div class="mb-4">
                <h2 class="text-center">Surprise Me<em><i>!</i></em></h2>
              </div>
              <p class="fw-semibold small text-muted mb-1 text-center" data-bhs="tp.page.ai.quick_picks">
                Quick Picks
              </p>
              <div class="d-flex flex-wrap gap-0 mb-3 justify-content-center" id="homeAiChips">
                <span class="ai-chip badge badge-red sparkle-origin" data-val="Kuliner" data-bhs="tp.page.ai.chip.kuliner"><i class="fas fa-utensils me-1"></i> Kuliner</span>
                <span class="ai-chip badge badge-green sparkle-origin" data-val="Alam" data-bhs="tp.page.ai.chip.alam"><i class="fas fa-leaf me-1"></i> Alam</span>
                <span class="ai-chip badge badge-accent sparkle-origin" data-val="Belanja" data-bhs="tp.page.ai.chip.belanja"><i class="fas fa-bag-shopping me-1"></i> Belanja</span>
                <span class="ai-chip badge badge-white sparkle-origin" data-val="Sejarah" data-bhs="tp.page.ai.chip.sejarah"><i class="fas fa-landmark me-1"></i> Sejarah</span>
                <span class="ai-chip badge badge-blue sparkle-origin" data-val="Budget" data-bhs="tp.page.ai.chip.budget"><i class="fas fa-wallet me-1"></i> Budget</span>
                <span class="ai-chip badge badge-primary sparkle-origin" data-val="Premium" data-bhs="tp.page.ai.chip.premium"><i class="fas fa-star me-1"></i> Premium</span>
              </div>
              <div class="d-flex justify-content-center flex-column">
                <div class="textarea-wrap">
                  <textarea id="aiPromptInput" class="form-control mb-4 mx-auto" rows="3" style="resize:none;" data-bhs="tp.page.ai.textarea_placeholder" data-ticker="Buat itinerary kuliner Bandung 2 hari..|Rute wisata alam Lembang seharian..|Itinerary belanja factory outlet Bandung..">
                  </textarea>
                  <div class="ticker-text">
                    <span data-ticker="Buat itinerary kuliner Bandung 2 hari..|Rute wisata alam Lembang seharian..|Itinerary belanja factory outlet Bandung..">Buat itinerary kuliner Bandung 2 hari</span>
                  </div>
                </div>
                <a id="btnGenerateAI" class="btn btn-primary mx-auto" data-bhs="tp.page.ai.btn_generate">Buat Itinerary<i class="fas fa-wand-magic-sparkles ms-1"></i></a>
              </div>
              <div id="aiLoader" class="text-center py-4 text-muted small" style="display:none">
                <i class="fa-solid fa-circle-notch fa-spin me-1"></i><span data-bhs="tp.page.ai.loader">Tunggu sebentar...</span>
              </div>
            </div>
            <div id="aiItineraryResult"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($is_logged): ?>
  <div class="modal fade card bg-card" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;overflow:hidden">
        <div class="modal-header border-bottom py-3 px-4">
          <h5 class="modal-title fw-semibold mb-0">
            <i class="fas fa-camera me-2 text-primary"></i><span data-bhs="tp.page.upload.title">Upload Foto</span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold" data-bhs="tp.page.upload.loc_label">Lokasi <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" id="uploadPoiSearch" class="form-control" data-bhs="tp.page.upload.loc_placeholder">
            </div>
            <div id="uploadPoiResults" class="list-group mt-2 p-2" style="max-height:140px;overflow-y:auto;display:none"></div>
            <input type="hidden" id="uploadPoiId">
            <div id="uploadPoiSelected" class="mt-2" style="display:block">
              <div class="mb-2">
                <div class="badge badge-green">
                  <span id="uploadPoiName" class="text-truncate fw-bold ms-2">
                    <i class="fas fa-location-dot me-1"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold" data-bhs="tp.page.upload.photo_label">Foto <span class="text-danger">*</span></label>
            <input type="file" id="uploadFile" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
            <div class="form-text" data-bhs="tp.page.upload.photo_hint">
              Maks 10MB · JPG, PNG, WebP
            </div>
            <div id="uploadPreview" class="mt-2" style="display:none">
              <img id="previewImg" src="" class="img-fluid rounded" style="max-height:200px;object-fit:cover;width:100%">
            </div>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold" data-bhs="tp.page.upload.credit_label">Kredit <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" id="uploadCredit" class="form-control" data-bhs="tp.page.upload.credit_placeholder">
          </div>
        </div>
        <div class="modal-footer border-top py-3 px-4">
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal" data-bhs="tp.page.upload.btn_cancel">Batalkan</button>
          <button type="button" class="btn btn-primary btn-sm" id="btnKirimUpload" data-bhs="tp.page.upload.btn_upload">
            <i class="fa-solid fa-upload me-1"></i>Upload
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</main>