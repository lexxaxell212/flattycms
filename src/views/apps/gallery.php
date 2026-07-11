<?php
require_once LIB_PATH . 'poi-actions.php';

$page_title = 'Galeri Foto - ' . SITE_NAME;
$pois = get_all_poi(true);
$is_logged = isset($_SESSION['user']['id']);
$pois_json = json_encode($pois, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$user_id_js = $is_logged ? (int)$_SESSION['user']['id'] : 0;
?>
<script>
 const CSRF = CONFIG.csrfToken;
 const BASE = CONFIG.baseUrl;
 const IS_LOGGED = <?= $is_logged ? 'true' : 'false' ?>;
 const POIS = <?= $pois_json ?>;
 const API_GAL = BASE + '/api/map/api-gallery.php';
 const API_REV = BASE + '/api/map/api-review.php';
 const MY_ID = <?= $user_id_js ?>;
</script>
<script src="<?= JS_URL ?>gallery.js" defer></script>
<main class="main-content">
 <div class="container">
  <div class="page-header mx-auto" style="max-width:800px;">
   <div class="gallery-rand-wrapper">
    <div class="gallery-rand-track">
     <?php
     $gallery = get_gallery_rand(3);
     $items = array_merge($gallery, $gallery);
     foreach ($items as $photo):
     $src = htmlspecialchars(BASE_UPLOAD_URL . $photo['photo_path'], ENT_QUOTES, 'UTF-8');
     $useruploader = htmlspecialchars($photo['uploader_name'], ENT_QUOTES, 'UTF-8');
     ?>
     <div class="gallery-rand-item">
      <img
      src="<?= $src ?>"
      onerror="this.onerror=null;this.src='/assets/images/default-poi.png'">
      <div class="gallery-rand-meta w-100 text-center">
       <span class="gallery-rand-uploader">Foto dari <?= $useruploader ?></span>
      </div>
     </div>
     <?php endforeach; ?>
    </div>
   </div>
  </div>
  <div class="gal-hero">
   <div class="text-center">
    <h1 class="text-hero"><span data-bhs="gal.page.hero.title">Share Your</span> <em class="styled" data-bhs="gal.page.hero.title.em">Moments</em></h1>
    <div class="gal-stats">
     <div class="gal-stat">
      <span class="gal-stat__val" id="statTotal">-</span>
      <span class="gal-stat__label" data-bhs="gal.page.stat.foto">Foto</span>
     </div>
     <div class="gal-stat">
      <span class="gal-stat__val" id="statReview">-</span>
      <span class="gal-stat__label" data-bhs="gal.page.stat.review">Review</span>
     </div>
     <div class="gal-stat">
      <span class="gal-stat__val"><?= count($pois) ?></span>
      <span class="gal-stat__label" data-bhs="gal.page.stat.poi">POI</span>
     </div>
    </div>
   </div>
   <div class="gal-hero__actions">
    <button class="btn btn-primary" id="btnOpenUpload">
     <i class="fas fa-camera"></i> <span data-bhs="gal.page.btn.upload">Upload Foto</span>
    </button>
    <button class="btn btn-primary" id="btnOpenReview">
     <i class="fas fa-pen-to-square"></i> <span data-bhs="gal.page.btn.review">Tulis Review</span>
    </button>
    <?php if (!$is_logged): ?>
    <a href="/login" class="btn btn-outline-primary">
     <span data-bhs="gal.page.btn.login">Masuk</span>
    </a>
    <?php endif; ?>
   </div>
  </div>
  <div class="bg-surface" style="padding:var(--section-padding);">
   <div class="gal-search-block">
    <div class="gal-search-wrap">
     <i class="fa-solid fa-magnifying-glass gal-search-icon"></i>
     <input type="text" id="searchPoiFilter" class="gal-search-input" data-bhs="gal.page.search.placeholder" placeholder="Cari dari POI...">
     <button class="gal-search-reset" id="btnResetSearch" title="Reset">
      <i class="fa-solid fa-xmark"></i>
     </button>
    </div>
    <div id="searchPoiFilterResults" class="gal-search-results">
    </div>
   </div>
   <div class="gal-content-wrap">
    <div class="gal-tabs" style="position:sticky;top: calc(var(--navbar-height) + 12px);z-index:90;">
     <button class="gal-tab active" data-tab="gallery">
      <i class="fa-solid fa-image"></i> <span data-bhs="gal.page.tab.gallery">Gallery</span>
     </button>
     <button class="gal-tab" data-tab="review">
      <i class="fa-solid fa-star"></i> <span data-bhs="gal.page.tab.review">Review</span>
     </button>
    </div>
    <div class="gal-content">
     <div id="tab-gallery">
      <div class="row g-4" id="galleryGrid">
       <div class="col-12">
        <div class="skeleton-wrapper">
         <div></div>
        </div>
       </div>
      </div>
      <div class="gal-pagination" id="pagination">
      </div>
     </div>
     <div id="tab-review" style="display:none">
      <div id="reviewGrid" class="gal-review-grid">
       <div class="col-12">
        <div class="skeleton-wrapper">
         <div></div>
        </div>
       </div>
      </div>
      <div class="gal-pagination" id="paginationReview"></div>
     </div>
    </div>
   </div>
  </div>
 </div>
</main>
<div class="modal fade" id="lightboxModal" tabindex="-1">
 <div class="modal-dialog modal-xl modal-dialog-centered">
  <div class="modal-content border-0 lightbox mx-auto" style="max-width:740px">
   <div class="modal-header border-0 pb-0">
    <button type="button" class="btn-close rounded-circle" data-bs-dismiss="modal"><i class="fas fa-xmark text-heading"></i></button>
   </div>
   <div class="modal-body text-center p-3">
    <img id="lightboxImg" src="" class="img-fluid rounded mb-4" style="width:100%;max-height:75vh;object-fit:contain">
    <div id="lightboxInfo"></div>
   </div>
  </div>
 </div>
</div>
<?php if ($is_logged): ?>
<div id="uploadModal" class="gal-modal-overlay" style="display:none">
 <div class="gal-modal">
  <div class="gal-modal__header">
   <span><i class="fa-solid fa-camera me-2"></i><span data-bhs="gal.page.upload.modal.title">Upload Foto</span></span>
   <button class="gal-modal__close" id="btnBatalUpload"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <div class="gal-modal__body">
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.upload.lokasi.label">Lokasi Foto</span><span class="text-danger">*</span></label>
    <div class="gal-search-field-wrap">
     <input type="text" id="uploadPoiSearch" class="gal-input" data-bhs="gal.page.upload.poi.placeholder" placeholder="Cari nama POI...">
     <div id="uploadPoiResults" class="gal-search-results gal-search-results--modal">
     </div>
    </div>
    <input type="hidden" id="uploadPoiId">
    <div id="uploadPoiSelected" class="gal-selected" style="display:none">
     <div class="badge badge-green">
      <span id="uploadPoiName" class="text-truncate fw-bold"></span>
     </div>
    </div>
   </div>
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.upload.foto.label">Foto</span> <span class="text-danger">*</span></label>
    <label class="gal-file-label" id="uploadFileLabel">
     <input type="file" id="uploadFile" accept="image/jpeg,image/png,image/webp" class="gal-file-input">
     <i class="fa-solid fa-cloud-arrow-up gal-file-icon"></i>
     <span class="gal-file-text" data-bhs="gal.page.upload.file.text">Klik untuk pilih foto</span>
     <span class="gal-file-hint" data-bhs="gal.page.upload.file.hint">JPG, PNG, WebP</span>
    </label>
    <div id="uploadPreview" style="display:none" class="mt-2">
     <img id="previewImg" src="" class="img-fluid rounded" style="max-height:180px;object-fit:cover;width:100%">
    </div>
   </div>
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.upload.kredit.label">Kredit</span> <span class="gal-optional" data-bhs="gal.page.optional">opsional</span></label>
    <input type="text" id="uploadCredit" class="gal-input" data-bhs="gal.page.upload.kredit.placeholder" placeholder="Nama / Instagram / Link...">
   </div>
  </div>
  <div class="gal-modal__footer">
   <button class="btn btn-outline-primary" id="btnBatalUpload2"><span data-bhs="gal.page.btn.batal">Batalkan</span></button>
   <button class="btn btn-primary" id="btnKirimUpload">
    <i class="fa-solid fa-upload"></i> <span data-bhs="gal.page.btn.upload2">Upload</span>
   </button>
  </div>
 </div>
</div>
<div id="reviewModal" class="gal-modal-overlay" style="display:none">
 <div class="gal-modal">
  <div class="gal-modal__header">
   <span><i class="fa-solid fa-pen-to-square me-2"></i><span data-bhs="gal.page.review.modal.title">Tulis Review</span></span>
   <button class="gal-modal__close" id="btnBatalReview"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <div class="gal-modal__body">
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.review.lokasi.label">Lokasi</span> <span class="text-danger">*</span></label>
    <div class="gal-search-field-wrap">
     <input type="text" id="reviewPoiSearch" class="gal-input" data-bhs="gal.page.upload.poi.placeholder" placeholder="Cari nama POI...">
     <div id="reviewPoiResults" class="gal-search-results gal-search-results--modal"></div>
    </div>
    <input type="hidden" id="reviewPoiId">
    <div id="reviewPoiSelected" class="gal-selected" style="display:none">
     <div class="badge badge-green">
      <span id="reviewPoiName" class="text-truncate fw-bold"></span>
     </div>
    </div>
   </div>
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.review.rating.label">Rating</span> <span class="text-danger">*</span></label>
    <div class="gal-stars" id="starRating">
     <?php for ($s = 1; $s <= 5; $s++): ?>
     <i class="fa-regular fa-star gal-star" data-val="<?= $s ?>"></i>
     <?php endfor; ?>
    </div>
    <input type="hidden" id="reviewRating" value="0">
   </div>
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.review.judul.label">Judul</span> <span class="gal-optional" data-bhs="gal.page.optional">opsional</span></label>
    <input type="text" id="reviewJudul" class="gal-input" data-bhs="gal.page.review.judul.placeholder" placeholder="Judul review...">
   </div>
   <div class="gal-field">
    <label class="gal-label"><span data-bhs="gal.page.review.cerita.label">Review</span> <span class="text-danger">*</span></label>
    <textarea id="reviewCerita" class="gal-input gal-textarea" data-bhs="gal.page.review.cerita.placeholder" placeholder="Ceritakan pengalamanmu..." rows="4"></textarea>
   </div>
  </div>
  <div class="gal-modal__footer">
   <button class="btn btn-outline-primary" id="btnBatalReview2"><span data-bhs="gal.page.btn.batal">Batalkan</span></button>
   <button class="btn btn-primary" id="btnKirimReview">
    <i class="fa-solid fa-paper-plane"></i> <span data-bhs="gal.page.btn.kirimreview">Kirim Review</span>
   </button>
  </div>
 </div>
</div>
<?php endif; ?>