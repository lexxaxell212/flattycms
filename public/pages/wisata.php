<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";
require_once LIB_PATH . "blogs.php";

$page_title = "Wisata Bandung";
?>

<div class="container py-5">
  <!-- Hero Section -->
  <div class="text-center mb-5">
    <h1 class="fs-1 mb-6">WISATA BANDUNG</h1>
    <p class="fs-6 mb-5">Temukan destinasi wisata alam, kuliner, dan budaya <strong>terbaik</strong> di Kota Kembang</p>
  </div>

  <!-- Filter Buttons -->
  <div class="d-flex flex-wrap gap-3 justify-content-center mt-6 mb-5 px-3">
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold
    category-btn active shadow-sm fs-6" data-category="all">
      <i class="fas fa-th-large me-2"></i>Semua
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold category-btn shadow-sm fs-6" data-category="alam">
      <i class="fas fa-mountain me-2"></i>Alam
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold
    category-btn shadow-sm fs-6" data-category="wisata_kuliner">
      <i class="fas fa-utensils me-2"></i>Kuliner
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold category-btn shadow-sm fs-6" data-category="fashion">
      <i class="fas fa-tshirt me-2"></i>Fashion
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold
    category-btn shadow-sm fs-6" data-category="wisata_budaya">
      <i class="fas fa-landmark me-2"></i>Budaya
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold category-btn shadow-sm fs-6" data-category="family">
      <i class="fas fa-users me-2"></i>Family
    </button>
  </div>

  <!-- Cards Grid -->
  <div class="row g-4 mt-6" id="wisata-grid">
    <?php
    //  RANDOM QUERY - ORDER BY RAND()
    $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('alam', 'wisata_kuliner', 'fashion', 'wisata_budaya', 'family') ORDER BY RAND()");
    $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
        <div class="alert alert-warning border-0 shadow-lg" style="max-width: 600px; margin: auto;">
          <i class="fas fa-dice fa-3x text-warning mb-4 d-block animate__animated animate__tada"></i>
          <h4 class="fw-bold fs-3">Belum Ada Destinasi Wisata</h4>
          <p class="fs-5 mb-4">Tambahkan cards melalui <strong>Dashboard</strong></p>
        </div>
      </div>
    <?php } else {// RANDOM 10 PERTAMA
      $visible_items = array_slice($all_items, 0, 10);
      $total_items = count($all_items);
      $hidden_count = $total_items - 10;

      foreach ($visible_items as $item) {

        $image = $item["image"] ?? "cards/default.jpg";
        $image_path =
          strpos($image, "http") === 0 ? $image : "../assets/images/" . $image;
        $category = strtolower($item["category"] ?? "general");
        $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
        $title = htmlspecialchars($item["title"] ?? "Untitled");
        $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
        $link = htmlspecialchars($item["button_link"] ?? "#");
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-item" data-category="<?= $category ?>">
          <div class="mb-3 glass glass-hover h-100 overflow-hidden shadow position-relative">
            <!-- Random Badge -->
            <span class="position-absolute top-3 start-3 badge bg-accent px-3 py-2 rounded-pill fw-semibold fs-6">
              <?= $cat_label ?> 
            </span>
            
            <!-- Image -->
            <img src="<?= $image_path ?>" 
                 class="card-img-top w-100" 
                 style="height: 220px; object-fit: cover;"
                 alt="<?= $title ?>"
                 onerror="this.src='../assets/images/cards/default.jpg'">
            
            <!-- Content -->
            <div class="card-body p-5 pb-3">
              <h5 class="fs-5 fw-bold mb-3"><?= $title ?></h5>
              <p class="mb-4"><?= $excerpt ?>...</p>
              
              <!-- Action Button -->
              <div class="d-flex justify-content-between align-items-center">
                <a href="<?= $link ?>" 
                   class="btn btn-primary px-4 py-2 fw-semibold fs-6" 
                   target="_blank">
                  Lihat Lokasi
                  <i class="fas fa-angle-right me-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
    <?php
      }}
    ?>
  </div>

  <?php if (!empty($all_items) && $total_items > 10): ?>
  <!-- EXPAND BUTTON -->
  <div class="text-center mt-5 pt-5">
    <button class="btn btn-outline-primary btn-md px-6 py-3 fw-bold fs-5 rounded-pill expand-btn" id="loadMoreBtn">
      <span class="btn-text">LEBARKAN</span>
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>

  <!-- Hidden Items -->
  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="row g-4 mt-4 hidden-items d-none" id="hiddenItems">
    <?php
    $hidden_items = array_slice($all_items, 10);
    foreach ($hidden_items as $item) {

      $image = $item["image"] ?? "cards/default.jpg";
      $image_path =
        strpos($image, "http") === 0 ? $image : "../assets/images/" . $image;
      $category = strtolower($item["category"] ?? "general");
      $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
      $title = htmlspecialchars($item["title"] ?? "Untitled");
      $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
      $link = htmlspecialchars($item["button_link"] ?? "#");
      ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-item" data-category="<?= $category ?>">
        <div class="mb-3 glass glass-hover h-100 overflow-hidden shadow position-relative">
          <span class="position-absolute top-3 start-3 badge bg-accent px-3 py-2 rounded-pill fw-semibold fs-6">
            <?= $cat_label ?> 
          </span>
          <img src="<?= $image_path ?>" 
               class="card-img-top w-100" 
               style="height: 220px; object-fit: cover;"
               alt="<?= $title ?>"
               onerror="this.src='../assets/images/cards/default.jpg'">
          <div class="card-body p-4 pb-3">
            <h5 class="fs-5 fw-bold mb-3"><?= $title ?></h5>
            <p class="mb-4"><?= $excerpt ?>...</p>
            <div class="d-flex justify-content-between align-items-center">
              <a href="<?= $link ?>" 
                 class="btn btn-primary px-4 py-2 fw-semibold fs-6" 
                 target="_blank">
                Lihat Lokasi<i class="fas fa-angle-right me-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
  <?php endif; ?>
</div>
<div class="text-center mt-6 p-6" id="noMores" style="display:none">
 <div class="spacer">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
 </div>
</div>

<style>
.hidden-items {
  animation: slideDown 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes slideDown {
  from { opacity: 0; transform: translateY(40px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
  // Filter functionality
  document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('category-active', 'active');
      });
      this.classList.add('category-active', 'active');
      
      const category = this.dataset.category;
      document.querySelectorAll('.wisata-item').forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
  
  // EXPAND TOGGLE
  const expandBtn = document.getElementById('loadMoreBtn');
  const hiddenItems = document.getElementById('hiddenItems');
  const noMores = document.getElementById('noMores');
  
  if (expandBtn) {
    expandBtn.addEventListener('click', function() {
      if (!isExpanded) {
        hiddenItems.classList.remove('d-none');
        isExpanded = true;
        this.innerHTML = `
          <span>KECILKAN</span>
          <i class="fas fa-chevron-up ms-2"></i>
        `;
        noMores.style.display = 'block';
      } else {
        hiddenItems.classList.add('d-none');
        isExpanded = false;
        this.innerHTML = `
          <span class="btn-text">LEBARKAN</span>
          <i class="fas fa-chevron-down ms-2"></i>
        `;
        noMores.style.display = 'none';
      }
    });
  }
});
</script>

<?php
require_once SRC_PATH "footer.php"; ?>
