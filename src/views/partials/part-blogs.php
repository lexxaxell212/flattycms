<?php
require_once LIB_PATH . "blogs.php";

$cat_id   = (int) ($_GET["cat"]  ?? 0);
$page     = max(1, (int) ($_GET["page"] ?? 1));
$per_page = 5;
$offset   = ($page - 1) * $per_page;
$posts      = safe_get_posts($pdo, $per_page, $offset, $cat_id);
$categories = safe_get_categories($pdo);
?>
<script src="<?= JS_URL ?>card-slider.js" defer></script>
<section class="container py-6" name="artikel-terbaru">
  <div class="overflow-hidden">
    <div class="mb-5">
      <h2 class="fw-bold">ARTIKEL TERBARU</h2>
    </div>
    <div class="slider-wrapper mb-6">
      <div class="slider-track" id="sliderTrack">
        <?php if (empty($posts)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-50"></i>
            <p>Belum ada artikel</p>
        </div>
        <?php else: ?>
        <?php foreach ($posts as $p): ?>
        <div class="slide-card glass glass-hover overflow-hidden"> 
            <img class="w-100 card-img-top" src="<?= $p["image"]
            ? BASE_UPLOAD_URL . $p["image"] ?>"
             onerror="this.onerror=null; this.src='/assets/images/default.jpg'"
             alt="<?= htmlspecialchars($p["title"] ?? "", ENT_QUOTES, "UTF-8") ?>">
            <div class="card-body py-5 px-3">
                <a href="/blogs/?id=<?= (int) $p["id"] ?>"
                   class="h5 text-decoration-none card-title mb-4 mt-3 d-block">
                    <?= htmlspecialchars($p["title"] ?? "", ENT_QUOTES, "UTF-8") ?>
                </a>
                <p class="card-text text-muted mb-4 small">
                    <?= safe_excerpt($p["excerpt"] ?? ($p["content"] ?? ""), 130) ?>
                </p>
                <a href="/blogs/?id=<?= (int) $p[
                  "id"
                ] ?>" class="btn btn-primary btn-sm">
                    Baca Selengkapnya <i class="fas fa-angle-right"></i>
                </a>
            </div>
        </div> 
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="slider-controls p-5">
      <button aria-label="Card sebelumnya" style="color:var(--blue-700)" class="btn-sm btn btn-outline-primary border-0" id="btnPrev" onclick="moveSlide(-1)"><i class="fas fa-angle-left"></i></button>
      <button aria-label="Card selanjutnya"  style="color:var(--blue-700)" class="btn-sm btn btn-outline-primary border-0" id="btnNext" onclick="moveSlide(1)"><i class="fas fa-angle-right"></i></button>
    </div>
  </div>
</section>
