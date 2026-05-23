<?php
require_once LIB_PATH . 'poi-actions.php';
$page_title = 'Galeri Foto — ' . SITE_NAME;
$pois       = get_all_poi(true);
$is_logged  = isset($_SESSION['user']['id']);
$pois_json  = json_encode($pois);
$user_id_js = $is_logged ? (int)$_SESSION['user']['id'] : 0;
?>

<script>
  const CSRF      = CONFIG.csrfToken;
  const BASE      = CONFIG.baseUrl;
  const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
  const POIS      = <?= $pois_json ?>;
  const API_GAL   = BASE + '/api/map/api-gallery.php';
  const MY_ID = <?= $user_id_js ?? 0 ?>;
</script>
<script src="<?= JS_URL ?>gallery.js" defer></script>

<main id="content" class="container-fluid">
<div class="container">

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
    <a href="/login" class="btn btn-outline-primary btn-sm">
      <i class="fa-brands fa-google me-1"></i>Login untuk Upload
    </a>
    <?php endif; ?>
  </div>

   <!-- Filter POI -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-4 d-flex gap-2 flex-wrap align-items-center">
      <div class="ms-auto" style="max-width:220px">
        <div class="input-group input-group-sm">
          <input type="text" id="searchPoiFilter" class="form-control" placeholder="Cari tempat...">
          <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
        </div>
      </div>
    </div>
  </div>
  <!-- Dropdown di luar card -->
  <div class="position-relative">
    <div id="searchPoiFilterResults" class="list-group shadow"
         style="position:absolute;top:0;right:0;width:220px;z-index:1555;max-height:200px;overflow-y:auto;display:none"></div>
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

</div>
</main>