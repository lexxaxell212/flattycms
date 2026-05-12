<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";
require_once LIB_PATH . "blogs.php";

$page_title = "Penginapan";
?>

<div class="container py-5">
 
<!-- Hero Section -->
<section id="hoteldanpenginapan">
        <div class="row align-items-center">
            <div class="mx-auto col-lg-6 text-center mb-5">
                <h1 class="fs-1 mb-6">Hotel dan Penginapan Rekomendasi</h1>
                <p class="fs-6 mb-5">Hotel dan penginapan terbaik untukmu di Bandung</p>
            </div>
        </div>
</section>

<!-- Filter -->
<div class="d-flex flex-wrap gap-3 justify-content-center mt-6 mb-5 px-3">
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn active shadow-sm fs-6" data-category="all">
      <i class="fas fa-th-large me-2"></i>Semua
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="pusat_kota">
      Pusat Bandung
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="bandung_utara">
      Bandung utara
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="riau">
      Riau
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="dago">
      Dago
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="pasteur">
      Pasteur
    </button>
    <button class="btn btn-outline-primary rounded-pill px-5 py-3 fw-semibold lokasi-btn shadow-sm fs-6" data-category="cihampelas">
      Cihampelas
    </button>
  </div>

<!-- Rooms Section -->
<div class="row g-4 mt-6" id="hotel-grid">
 
 <?php
 //  RANDOM QUERY - ORDER BY RAND()
 $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('pusat_kota', 'bandung_utara', 'dago', 'riau', 'pasteur',
    'cihampelas') ORDER BY RAND()");
 $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

 if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
        <div class="alert alert-warning border-0 shadow-lg" style="max-width: 600px; margin: auto;">
          <i class="fas fa-dice fa-3x text-warning mb-4 d-block animate__animated animate__tada"></i>
          <h4 class="fw-bold fs-3">Belum Ada Cards Hotel</h4>
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
<div class="col-lg-4 col-md-6 hotel-item" data-category="<?= $category ?>">
<div class="glass glass-hover h-100"> 
<!-- Random Badge -->
<span class="position-absolute top-3 start-3 badge bg-accent px-3 py-2 rounded-pill fw-semibold fs-6">
<?= $cat_label ?> 
</span>
<img src="<?= $image_path ?>" class="card-img-top" alt="<?= $title ?>"
onerror="this.src='../assets/images/cards/default.jpg'">
<div class="card-body d-flex flex-column p-5">
<h5 class="mt-6 mb-3"><?= $title ?></h5>
<p class="mb-3"><?= $excerpt ?>...</p>
<a href="<?= $link ?>" target="_blank"><button class="btn btn-primary mt-6 w-100">Pesan Sekarang<i class="fas fa-angle-right me-1"></i></button>
</a>
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
    <button class="btn btn-outline-primary btn-md px-6 py-3 fw-bold fs-5
    rounded-pill expand-btn" id="loadhotelMoreBtn">
      <span class="btn-text">LEBARKAN</span>
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>

  <!-- Hidden Items -->
  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="row g-4 mt-4 hidden-items d-none" id="hiddenhotelItems">
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
      <div class="col-lg-4 col-md-6 hotel-item" data-category="<?= $category ?>">
<div class="glass glass-hover h-100"> 
<!-- Random Badge -->
<span class="position-absolute top-3 start-3 badge bg-accent px-3 py-2 rounded-pill fw-semibold fs-6">
<?= $cat_label ?> 
</span>
<img src="<?= $image_path ?>" class="card-img-top" alt="<?= $title ?>"
onerror="this.src='../assets/images/cards/default.jpg'">
<div class="card-body d-flex flex-column p-5">
<h5 class="mt-6 mb-3"><?= $title ?></h5>
<p class="mb-3"><?= $excerpt ?>...</p>
<a href="<?= $link ?>" target="_blank"><button class="btn btn-primary mt-6 w-100">Pesan Sekarang<i class="fas fa-angle-right me-1"></i></button>
</a>
</div>
</div>
</div>
    <?php
    }
    ?>
  </div>
  <?php endif; ?>
</div>
<div class="text-center mt-6 p-6" id="noMore" style="display:none">
 <div class="spacer">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
  // Filter functionality
  document.querySelectorAll('.lokasi-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.lokasi-btn').forEach(b => {
        b.classList.remove('lokasi-active', 'active');
      });
      this.classList.add('lokasi-active', 'active');
      
      const category = this.dataset.category;
      document.querySelectorAll('.hotel-item').forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
  
  // EXPAND TOGGLE
  const expandBtn = document.getElementById('loadhotelMoreBtn');
  const hiddenItems = document.getElementById('hiddenhotelItems');
  const noMore = document.getElementById('noMore');
  
  if (expandBtn) {
    expandBtn.addEventListener('click', function() {
      if (!isExpanded) {
        hiddenItems.classList.remove('d-none');
        isExpanded = true;
        this.innerHTML = `
          <span class="btn-text">KECILKAN</span>
          <i class="fas fa-chevron-up ms-2"></i>
        `;
        noMore.style.display = 'block';
      } else {
        hiddenItems.classList.add('d-none');
        isExpanded = false;
        this.innerHTML = `
          <span class="btn-text">LEBARKAN</span>
          <i class="fas fa-chevron-down ms-2"></i>
        `;
        noMore.style.display = 'none';
      }
    });
  }
});
</script>

<?php
require_once SRC_PATH "footer.php"; ?>