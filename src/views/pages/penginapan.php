<?php
$page_title = "Hotel dan Penginapan";
//
?>
<style>
  .btn-outline-primary.active {
  background: var(--pu-700) !important;
  border-color: var(--pu-700) !important;
  }
</style>
<main id="content" class="container-fluid">
<div class="container">
<section id="Hotel-dan-penginapan">
  <h1 class="text-title">Hotel dan Penginapan Rekomendasi</h1>
  <p class="lead">Hotel dan penginapan terbaik untukmu di Bandung</p>
</section>
  <div class="mb-3">
    <button class="btn btn-outline-primary lokasi-btn active" data-category="all">
      <i class="fas fa-th-large me-2"></i>Semua
    </button>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <button class="btn btn-outline-primary lokasi-btn" data-category="pusat_kota">
      Pusat Bandung
    </button>
    <button class="btn btn-outline-primary lokasi-btn" data-category="bandung_utara">
      Bandung utara
    </button>
    <button class="btn btn-outline-primary lokasi-btn" data-category="riau">
      Riau
    </button>
    <button class="btn btn-outline-primary lokasi-btn" data-category="dago">
      Dago
    </button>
    <button class="btn btn-outline-primary lokasi-btn" data-category="pasteur">
      Pasteur
    </button>
    <button class="btn btn-outline-primary lokasi-btn" data-category="cihampelas">
      Cihampelas
    </button>
  </div>
<div class="row g-4 mt-6" id="hotel-grid">
 
 <?php
 $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('pusat_kota', 'bandung_utara', 'dago', 'riau', 'pasteur',
    'cihampelas') ORDER BY RAND()");
 $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

 if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
          <div class="alert alert-warning" style="margin: auto;max-width:320px">
            <i class="icon d-block fa-3x fas fa-dice"></i>
            <h3>Belum ada Card</h3>
            <p class="text-muted">Tambahkan cards melalui <strong>Dashboard</strong></p>
          </div>
        </div>
    <?php } else {
   $visible_items = array_slice($all_items, 0, 10);
   $total_items = count($all_items);
   $hidden_count = $total_items - 10;

   foreach ($visible_items as $item) {

     $image = $item["image"] ?? "/uploads/default.jpg";
     $image_path =
       strpos($image, "http") === 0 ? $image : BASE_UPLOAD_URL . $image;
     $category = strtolower($item["category"] ?? "general");
     $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
     $title = htmlspecialchars($item["title"] ?? "Untitled");
     $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
     $link = htmlspecialchars($item["button_link"] ?? "#");
     ?>
<div class="col-lg-4 col-md-6 hotel-item" data-category="<?= $category ?>">
<div class="card card-glass h-100"> 
<span class="position-absolute top-3 start-3 badge bg-primary">
<?= $cat_label ?> 
</span>
<img src="<?= $image_path ?>" class="card-img-top" alt="<?= $title ?>"
onerror="this.src='/uploads/default.jpg'">
<div class="card-body">
<h3><?= $title ?></h3>
<p class="text-muted"><?= $excerpt ?>...</p>
<a href="<?= $link ?>" target="_blank" class="btn btn-primary">Pesan Sekarang<i
class="arrow-icon fas fa-angle-right"></i>
</a>
</div>
</div>
</div>
<?php
   }}
 ?>

</div>
 <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="text-center">
    <button class="btn btn-outline-primary expand-btn" id="loadhotelMoreBtn">
      <span class="btn-text">LEBARKAN</span>
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>
  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="py-5 row g-4 mt-4 hidden-items d-none" id="hiddenhotelItems">
    <?php
    $hidden_items = array_slice($all_items, 10);
    foreach ($hidden_items as $item) {

      $image = $item["image"] ?? "/uploads/default.jpg";
      $image_path =
        strpos($image, "http") === 0 ? $image : BASE_UPLOAD_URL . $image;
      $category = strtolower($item["category"] ?? "general");
      $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
      $title = htmlspecialchars($item["title"] ?? "Untitled");
      $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
      $link = htmlspecialchars($item["button_link"] ?? "#");
      ?>
      <div class="col-lg-4 col-md-6 hotel-item" data-category="<?= $category ?>">
<div class="card card-glass h-100"> 
<span class="position-absolute top-3 start-3 badge bg-primary">
<?= $cat_label ?> 
</span>
<img src="<?= $image_path ?>" class="card-img-top" alt="<?= $title ?>"
onerror="this.src='/uploads/default.jpg'">
<div class="card-body">
<h3><?= $title ?></h3>
<p class="text-muted"><?= $excerpt ?>...</p>
<a href="<?= $link ?>" target="_blank" class="btn btn-primary">Pesan Sekarang<i
class="arrow-icon fas fa-angle-right"></i>
</a>
</div>
</div>
</div>
    <?php
    }
    ?>
  </div>
  <?php endif; ?>
<div class="alert alert-warning" id="noMore" style="display:none">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
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
</div>
</main>
<?php
// ?>