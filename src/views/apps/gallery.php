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

<main id="content" class="container-fluid">
<div class="container">

  <div class="gal-header">
    <div>
      <h4 class="gal-title"><i class="fa-solid fa-images me-2"></i>Galeri Komunitas</h4>
      <p class="gal-sub">Foto & cerita perjalanan Bandung dari komunitas</p>
    </div>
    <div class="gal-header__actions">
      <?php if ($is_logged): ?>
      <button class="gal-btn gal-btn--outline" id="btnOpenUpload">
        <i class="fa-solid fa-camera me-1"></i>Upload Foto
      </button>
      <button class="gal-btn gal-btn--primary" id="btnOpenReview">
        <i class="fa-solid fa-pen-to-square me-1"></i>Tulis Review
      </button>
      <?php else: ?>
      <a href="/login" class="gal-btn gal-btn--outline">
        <i class="fa-brands fa-google me-1"></i>Login untuk Berkontribusi
      </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="gal-search-block">
    <div class="gal-search-wrap">
      <i class="fa-solid fa-search gal-search-icon"></i>
      <input type="text" id="searchPoiFilter" class="gal-search-input" placeholder="Cari tempat wisata...">
      <button class="gal-search-reset" id="btnResetSearch" title="Reset">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div id="searchPoiFilterResults" class="gal-search-results"></div>
  </div>

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

  <div class="gal-tabs">
    <button class="gal-tab active" data-tab="gallery">
      <i class="fa-solid fa-images me-1"></i>Galeri Foto
    </button>
    <button class="gal-tab" data-tab="review">
      <i class="fa-solid fa-star me-1"></i>Review
    </button>
  </div>

  <div id="tab-gallery">
    <div class="row g-3" id="galleryGrid">
      <div class="col-12 text-center py-5 text-muted" id="galleryLoading">
        <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat foto...
      </div>
    </div>
    <div class="d-flex justify-content-center gap-2 mt-4" id="pagination"></div>
  </div>

  <div id="tab-review" style="display:none">
    <div id="reviewGrid" class="gal-review-grid">
      <div class="text-center py-5 text-muted">
        <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat review...
      </div>
    </div>
    <div class="d-flex justify-content-center gap-2 mt-4" id="paginationReview"></div>
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
      <span><i class="fa-solid fa-camera me-2 text-primary"></i>Upload Foto</span>
      <button class="gal-modal__close" id="btnBatalUpload"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="gal-modal__body">
      <div class="gal-field">
        <label class="gal-label">Lokasi <span class="text-danger">*</span></label>
        <input type="text" id="uploadPoiSearch" class="gal-input" placeholder="Ketik nama tempat...">
        <div id="uploadPoiResults" class="gal-search-results" style="display:none"></div>
        <input type="hidden" id="uploadPoiId">
        <div id="uploadPoiSelected" class="gal-selected" style="display:none">
          <i class="fa-solid fa-check me-1"></i><span id="uploadPoiName"></span>
        </div>
      </div>
      <div class="gal-field">
        <label class="gal-label">Foto <span class="text-danger">*</span></label>
        <input type="file" id="uploadFile" class="gal-input" accept="image/jpeg,image/png,image/webp">
        <div class="gal-hint">Maks 10MB · JPG, PNG, WebP</div>
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
        <i class="fa-solid fa-upload me-1"></i>Upload
      </button>
    </div>
  </div>
</div>

<!-- MODAL REVIEW -->
<div id="reviewModal" class="gal-modal-overlay" style="display:none">
  <div class="gal-modal">
    <div class="gal-modal__header">
      <span><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Tulis Review</span>
      <button class="gal-modal__close" id="btnBatalReview"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="gal-modal__body">
      <div class="gal-field">
        <label class="gal-label">Lokasi <span class="text-danger">*</span></label>
        <input type="text" id="reviewPoiSearch" class="gal-input" placeholder="Ketik nama tempat...">
        <div id="reviewPoiResults" class="gal-search-results" style="display:none"></div>
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
        <i class="fa-solid fa-paper-plane me-1"></i>Kirim Review
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
.gal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 2.5rem 0 1.5rem;
}
.gal-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: #1e1b4b;
  margin: 0 0 .25rem;
}
.gal-sub { color: #6b7280; font-size: .9rem; margin: 0; }
.gal-header__actions { display: flex; gap: .75rem; flex-wrap: wrap; align-items: center; }

.gal-btn {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .6rem 1.25rem;
  border-radius: 100px;
  font-size: .85rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all .2s;
  white-space: nowrap;
}
.gal-btn--primary { background: #7c3aed; color: #fff; box-shadow: 0 4px 14px rgba(124,58,237,.3); }
.gal-btn--primary:hover { background: #6d28d9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(124,58,237,.4); color: #fff; }
.gal-btn--outline { background: transparent; color: #7c3aed; border: 1.5px solid #ede9fe; }
.gal-btn--outline:hover { background: #f5f3ff; border-color: #7c3aed; }
.gal-btn--ghost { background: transparent; color: #6b7280; border: 1.5px solid #e5e7eb; }
.gal-btn--ghost:hover { background: #f9fafb; }

.gal-search-block {
  position: relative;
  margin-bottom: 1.5rem;
}
.gal-search-wrap {
  display: flex;
  align-items: center;
  background: #fff;
  border: 1.5px solid #ede9fe;
  border-radius: 100px;
  padding: .6rem 1rem .6rem 1.25rem;
  gap: .75rem;
  transition: border-color .2s, box-shadow .2s;
}
.gal-search-wrap:focus-within {
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124,58,237,.1);
}
.gal-search-icon { color: #a78bfa; font-size: .9rem; flex-shrink: 0; }
.gal-search-input {
  flex: 1;
  border: none;
  outline: none;
  font-size: .9rem;
  color: #1e1b4b;
  background: transparent;
}
.gal-search-input::placeholder { color: #9ca3af; }
.gal-search-reset {
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: .2rem .4rem;
  border-radius: 50%;
  transition: color .2s, background .2s;
  flex-shrink: 0;
}
.gal-search-reset:hover { color: #7c3aed; background: #f5f3ff; }
.gal-search-results {
  position: absolute;
  top: calc(100% + .5rem);
  left: 0; right: 0;
  background: #fff;
  border: 1px solid #ede9fe;
  border-radius: 1rem;
  box-shadow: 0 8px 24px rgba(124,58,237,.1);
  z-index: 100;
  overflow: hidden;
  display: none;
}
.gal-search-results.open { display: block; }
.gal-search-results .list-group-item {
  border: none;
  border-bottom: 1px solid #f5f3ff;
  font-size: .875rem;
  padding: .65rem 1rem;
}
.gal-search-results .list-group-item:last-child { border-bottom: none; }
.gal-search-results .list-group-item:hover { background: #f5f3ff; color: #7c3aed; }

.gal-stats {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 1.75rem;
}
.gal-stat { display: flex; align-items: baseline; gap: .4rem; }
.gal-stat__val { font-size: 1.3rem; font-weight: 800; color: #1e1b4b; }
.gal-stat__label { font-size: .78rem; color: #9ca3af; font-weight: 500; }
.gal-stat__divider { width: 1px; height: 20px; background: #ede9fe; }

.gal-tabs {
  display: flex;
  gap: .25rem;
  border-bottom: 2px solid #f3f4f6;
  margin-bottom: 1.75rem;
}
.gal-tab {
  background: none;
  border: none;
  padding: .75rem 1.25rem;
  font-size: .9rem;
  font-weight: 600;
  color: #9ca3af;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  transition: all .2s;
  border-radius: .5rem .5rem 0 0;
}
.gal-tab:hover { color: #7c3aed; background: #f5f3ff; }
.gal-tab.active { color: #7c3aed; border-bottom-color: #7c3aed; }

.gal-review-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.gal-review-card {
  background: #fff;
  border: 1px solid #ede9fe;
  border-radius: 1rem;
  padding: 1.5rem;
  transition: box-shadow .2s, transform .2s;
}
.gal-review-card:hover {
  box-shadow: 0 .5rem 1.5rem rgba(124,58,237,.1);
  transform: translateY(-2px);
}
.gal-review-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: .75rem;
  flex-wrap: wrap;
  gap: .5rem;
}
.gal-review-card__user {
  display: flex;
  align-items: center;
  gap: .625rem;
}
.gal-review-card__avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ede9fe;
}
.gal-review-card__name { font-weight: 600; font-size: .9rem; color: #1e1b4b; }
.gal-review-card__poi { font-size: .75rem; color: #a78bfa; }
.gal-review-card__stars { color: #f59e0b; font-size: .9rem; letter-spacing: .1em; }
.gal-review-card__date { font-size: .72rem; color: #9ca3af; }
.gal-review-card__title { font-weight: 700; color: #1e1b4b; margin-bottom: .4rem; font-size: .95rem; }
.gal-review-card__body { color: #6b7280; font-size: .875rem; line-height: 1.65; }

.gal-modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 1200;
  background: rgba(15,23,42,.5);
  backdrop-filter: blur(4px);
  align-items: center;
  justify-content: center;
}
.gal-modal {
  background: #fff;
  border-radius: 1.25rem;
  width: 100%;
  max-width: 480px;
  margin: 1rem;
  box-shadow: 0 24px 60px rgba(0,0,0,.15);
  overflow: hidden;
}
.gal-modal__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f3f4f6;
  font-weight: 700;
  color: #1e1b4b;
  font-size: .95rem;
}
.gal-modal__close {
  background: #f3f4f6;
  border: none;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  color: #6b7280;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .2s;
}
.gal-modal__close:hover { background: #ede9fe; color: #7c3aed; }
.gal-modal__body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.125rem; }
.gal-modal__footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #f3f4f6;
  display: flex;
  justify-content: flex-end;
  gap: .75rem;
}

.gal-field { display: flex; flex-direction: column; gap: .4rem; }
.gal-label { font-size: .82rem; font-weight: 600; color: #374151; }
.gal-optional { font-weight: 400; color: #9ca3af; margin-left: .25rem; }
.gal-input {
  border: 1.5px solid #e5e7eb;
  border-radius: .625rem;
  padding: .6rem .875rem;
  font-size: .875rem;
  color: #1e1b4b;
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  width: 100%;
}
.gal-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
.gal-textarea { resize: vertical; min-height: 100px; font-family: inherit; }
.gal-hint { font-size: .75rem; color: #9ca3af; }
.gal-selected { font-size: .8rem; color: #059669; font-weight: 500; }

.gal-stars { display: flex; gap: .25rem; }
.gal-star {
  font-size: 1.4rem;
  color: #d1d5db;
  cursor: pointer;
  transition: color .15s, transform .15s;
}
.gal-star:hover, .gal-star.active { color: #f59e0b; transform: scale(1.15); }
</style>