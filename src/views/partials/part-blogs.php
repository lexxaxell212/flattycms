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
<main id="content">
<section id="artikel-terbaru">
  <div class="overflow-hidden">
      <h2 class="fw-bold">ARTIKEL TERBARU</h2>
    <div class="slider-wrapper">
      <div class="slider-track" id="sliderTrack">
        <?php if (empty($posts)): ?>
        <div class="text-center text-muted">
            <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-50"></i>
            <p>Belum ada artikel</p>
        </div>
        <?php else: ?>
        <?php foreach ($posts as $p): ?>
        <div class="slide-card card card-glass overflow-hidden"> 
            <img class="w-100 card-img-top" src="<?= BASE_UPLOAD_URL . $p["image"] ?>"
             onerror="this.onerror=null; this.src='/uploads/default.jpg'"
             alt="<?= htmlspecialchars($p["title"] ?? "", ENT_QUOTES, "UTF-8")
             ?>" loading="lazy">
            <div class="card-body">
                <a href="/blogs/?id=<?= (int) $p["id"] ?>"
                   class="h3 text-decoration-none d-block">
                    <?= htmlspecialchars($p["title"] ?? "", ENT_QUOTES, "UTF-8") ?>
                </a>
                <p class="text-muted">
                    <?= safe_excerpt($p["excerpt"] ?? ($p["content"] ?? ""), 130) ?>
                </p>
                <a href="/blogs/?id=<?= (int) $p[
                  "id"
                ] ?>" class="btn btn-primary">
                    Baca Selengkapnya <i class="arrow-icon fas fa-angle-right"></i>
                </a>
            </div>
        </div> 
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="slider-controls py-3">
      <button aria-label="Card sebelumnya" class="btn btn-outline-primary" id="btnPrev" onclick="moveSlide(-1)"><i class="fas fa-angle-left"></i></button>
      <button aria-label="Card selanjutnya" class="btn btn-outline-primary" id="btnNext" onclick="moveSlide(1)"><i class="fas fa-angle-right"></i></button>
    </div>
  </div>
</section>
</main>