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
  const API_REV   = BASE + '/api/map/api-review.php';
  const MY_ID     = <?= $user_id_js ?>;
</script>

<script src="<?= JS_URL ?>gallery.js" defer></script>

<main class="main-content">
  <div class="gal-page">
    <div class="container">
    
      <section class="gal-hero">
        <div>
          <span class="gal-eyebrow">Komunitas Bandung</span>
          <h2 class="gal-title">Galeri & Review</h2>
          <p class="gal-sub">Foto & cerita perjalanan Bandung dari komunitas</p>
        </div>
        <div class="gal-hero__actions">
          <?php if ($is_logged): ?>
          <button class="gal-btn gal-btn--outline" id="btnOpenUpload">
            <i class="fa-solid fa-camera"></i> Upload Foto
          </button>
          <button class="gal-btn gal-btn--primary" id="btnOpenReview">
            <i class="fa-solid fa-pen-to-square"></i> Tulis Review
          </button>
          <?php else: ?>
          <a href="/login" class="gal-btn gal-btn--outline">
            <i class="fa-brands fa-google"></i> Login untuk Berkontribusi
          </a>
          <?php endif; ?>
        </div>
      </section>
    
      <!-- Search -->
      <div class="gal-search-block">
        <div class="gal-search-wrap">
          <i class="fa-solid fa-magnifying-glass gal-search-icon"></i>
          <input type="text" id="searchPoiFilter" class="gal-search-input" placeholder="Cari tempat wisata...">
          <button class="gal-search-reset" id="btnResetSearch" title="Reset">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div id="searchPoiFilterResults" class="gal-search-results"></div>
      </div>
    
      <!-- Stats -->
      <div class="gal-stats">
        <div class="gal-stat">
          <span class="gal-stat__val" id="statTotal">—</span>
          <span class="gal-stat__label">Foto</span>
        </div>
        <div class="gal-stat__divider"></div>
        <div class="gal-stat">
          <span class="gal-stat__val" id="statReview">—</span>
          <span class="gal-stat__label">Review</span>
        </div>
        <div class="gal-stat__divider"></div>
        <div class="gal-stat">
          <span class="gal-stat__val"><?= count($pois) ?></span>
          <span class="gal-stat__label">Lokasi</span>
        </div>
      </div>
    
      <!-- Tabs -->
      <div class="gal-tabs">
        <button class="gal-tab active" data-tab="gallery">
          <i class="fa-solid fa-images"></i> Galeri Foto
        </button>
        <button class="gal-tab" data-tab="review">
          <i class="fa-solid fa-star"></i> Review
        </button>
      </div>
    
      <!-- Tab Gallery -->
      <div id="tab-gallery">
        <div class="row g-3" id="galleryGrid">
          <div class="col-12 text-center py-5 text-muted">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat foto...
          </div>
        </div>
        <div class="gal-pagination" id="pagination"></div>
      </div>
    
      <!-- Tab Review -->
      <div id="tab-review" style="display:none">
        <div id="reviewGrid" class="gal-review-grid">
          <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat review...
          </div>
        </div>
        <div class="gal-pagination" id="paginationReview"></div>
      </div>
    
    </div>
  </div>
</main>

<!-- LIGHTBOX -->
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

<?php if ($is_logged): ?>
<!-- MODAL UPLOAD -->
<div id="uploadModal" class="gal-modal-overlay" style="display:none">
  <div class="gal-modal">
    <div class="gal-modal__header">
      <span><i class="fa-solid fa-camera me-2"></i>Upload Foto</span>
      <button class="gal-modal__close" id="btnBatalUpload"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="gal-modal__body">
      <div class="gal-field">
        <label class="gal-label">Lokasi <span class="text-danger">*</span></label>
        <div class="gal-search-field-wrap">
          <input type="text" id="uploadPoiSearch" class="gal-input" placeholder="Ketik nama tempat...">
          <!-- FIXED: hapus style display:none, biarkan CSS .gal-search-results handle -->
          <div id="uploadPoiResults" class="gal-search-results gal-search-results--modal"></div>
        </div>
        <input type="hidden" id="uploadPoiId">
        <div id="uploadPoiSelected" class="gal-selected" style="display:none">
          <i class="fa-solid fa-check me-1"></i><span id="uploadPoiName"></span>
        </div>
      </div>
      <div class="gal-field">
        <label class="gal-label">Foto <span class="text-danger">*</span></label>
        <label class="gal-file-label" id="uploadFileLabel">
          <input type="file" id="uploadFile" accept="image/jpeg,image/png,image/webp" class="gal-file-input">
          <i class="fa-solid fa-cloud-arrow-up gal-file-icon"></i>
          <span class="gal-file-text">Klik untuk pilih foto</span>
          <span class="gal-file-hint">Maks 10MB · JPG, PNG, WebP</span>
        </label>
        <div id="uploadPreview" style="display:none" class="mt-2">
          <img id="previewImg" src="" class="img-fluid rounded" style="max-height:180px;object-fit:cover;width:100%">
        </div>
      </div>
      <div class="gal-field">
        <label class="gal-label">Kredit <span class="gal-optional">opsional</span></label>
        <input type="text" id="uploadCredit" class="gal-input" placeholder="Nama, Instagram, Unsplash...">
      </div>
    </div>
    <div class="gal-modal__footer">
      <button class="gal-btn gal-btn--ghost" id="btnBatalUpload2">Batalkan</button>
      <button class="gal-btn gal-btn--primary" id="btnKirimUpload">
        <i class="fa-solid fa-upload"></i> Upload
      </button>
    </div>
  </div>
</div>

<!-- MODAL REVIEW -->
<div id="reviewModal" class="gal-modal-overlay" style="display:none">
  <div class="gal-modal">
    <div class="gal-modal__header">
      <span><i class="fa-solid fa-pen-to-square me-2"></i>Tulis Review</span>
      <button class="gal-modal__close" id="btnBatalReview"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="gal-modal__body">
      <div class="gal-field">
        <label class="gal-label">Lokasi <span class="text-danger">*</span></label>
        <div class="gal-search-field-wrap">
          <input type="text" id="reviewPoiSearch" class="gal-input" placeholder="Ketik nama tempat...">
          <div id="reviewPoiResults" class="gal-search-results gal-search-results--modal"></div>
        </div>
        <input type="hidden" id="reviewPoiId">
        <div id="reviewPoiSelected" class="gal-selected" style="display:none">
          <i class="fa-solid fa-check me-1"></i><span id="reviewPoiName"></span>
        </div>
      </div>
      <div class="gal-field">
        <label class="gal-label">Rating <span class="text-danger">*</span></label>
        <div class="gal-stars" id="starRating">
          <?php for ($s = 1; $s <= 5; $s++): ?>
          <i class="fa-regular fa-star gal-star" data-val="<?= $s ?>"></i>
          <?php endfor; ?>
        </div>
        <input type="hidden" id="reviewRating" value="0">
      </div>
      <div class="gal-field">
        <label class="gal-label">Judul <span class="gal-optional">opsional</span></label>
        <input type="text" id="reviewJudul" class="gal-input" placeholder="Ringkasan pengalamanmu...">
      </div>
      <div class="gal-field">
        <label class="gal-label">Cerita <span class="text-danger">*</span></label>
        <textarea id="reviewCerita" class="gal-input gal-textarea" placeholder="Ceritakan pengalamanmu di tempat ini..." rows="4"></textarea>
      </div>
    </div>
    <div class="gal-modal__footer">
      <button class="gal-btn gal-btn--ghost" id="btnBatalReview2">Batalkan</button>
      <button class="gal-btn gal-btn--primary" id="btnKirimReview">
        <i class="fa-solid fa-paper-plane"></i> Kirim Review
      </button>
    </div>
  </div>
</div>
<?php endif; ?>
