<?php
$page_title = "Wisata Bandung";
//
?>
<main id="content" class="container py-5">
  <style>
  .btn-outline-primary.active {
    background: var(--bg-hover) !important;
    color: #fff !important;
  }
  </style>
  <section id="Wisata-Bandung" class="py-5 mb-5">
    <h1>WISATA BANDUNG</h1>
    <p class="lead">Temukan destinasi wisata alam, kuliner, dan budaya <strong>terbaik</strong> di Kota Kembang</p>
  </section>
  
 <div class="mb-3">
    <button class="btn btn-outline-primary category-btn active" data-category="all">
      <i class="fas fa-th-large me-2"></i>Semua
    </button>
 </div>
 <div class="d-flex flex-wrap gap-2">
    <button class="btn btn-outline-primary category-btn" data-category="alam">
       Alam
    </button>
    <button class="btn btn-outline-primary category-btn" data-category="wisata_kuliner">
       Kuliner
    </button>
    <button class="btn btn-outline-primary category-btn" data-category="fashion">
       Fashion
    </button>
    <button class="btn btn-outline-primary category-btn" data-category="wisata_budaya">
       Budaya
    </button>
    <button class="btn btn-outline-primary category-btn" data-category="family">
       Family
    </button>
  </div>
  <div class="row g-4" id="wisata-grid">
    <?php
    //  RANDOM QUERY - ORDER BY RAND()
    $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('alam', 'wisata_kuliner', 'fashion', 'wisata_budaya', 'family') ORDER BY RAND()");
    $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
          <div class="alert alert-warning" style="margin: auto;max-width:320px">
            <i class="icon d-block fa-3x fas fa-dice"></i>
            <span class="fw-semibold h5">Belum ada Card</span>
            <p class="text-muted">Tambahkan cards melalui <strong>Dashboard</strong></p>
          </div>
        </div>
    <?php } else {// RANDOM 10 PERTAMA
      $visible_items = array_slice($all_items, 0, 10);
      $total_items = count($all_items);
      $hidden_count = $total_items - 10;

      foreach ($visible_items as $item) {

        $image = $item["image"] ?? "uploads/default.jpg";
        $image_path =
          strpos($image, "http") === 0 ? $image : "uploads/" . $image;
        $category = strtolower($item["category"] ?? "general");
        $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
        $title = htmlspecialchars($item["title"] ?? "Untitled");
        $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
        $link = htmlspecialchars($item["button_link"] ?? "#");
        ?>
        <div class="col-lg-4 col-md-6 wisata-item" data-category="<?= $category ?>">
          <div class="card card-glass h-100">
            <span class="position-absolute top-3 start-3 badge bg-accent">
              <?= $cat_label ?> 
            </span>
            <img src="<?= $image_path ?>" 
                 class="card-img-top w-100" 
                 style="height: 220px; object-fit: cover;"
                 alt="<?= $title ?>"
                 onerror="this.src='uploads/default.jpg'">
            <div class="card-body">
              <h3><?= $title ?></h3>
              <p class="text-muted"><?= $excerpt ?>...</p>
              <a href="<?= $link ?>" 
                   class="btn btn-primary" 
                   target="_blank">
                  Lihat Lokasi
                  <i class="arrow-icon fas fa-angle-right"></i>
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
    <button class="btn btn-outline-primary expand-btn" id="loadMoreBtn">
      <span class="btn-text">LEBARKAN</span>
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>
  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="py-5 row g-4 mt-4 hidden-items d-none" id="hiddenItems">
    <?php
    $hidden_items = array_slice($all_items, 10);
    foreach ($hidden_items as $item) {

      $image = $item["image"] ?? "default.jpg";
      $image_path =
        strpos($image, "http") === 0 ? $image : "uploads/" . $image;
      $category = strtolower($item["category"] ?? "general");
      $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
      $title = htmlspecialchars($item["title"] ?? "Untitled");
      $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
      $link = htmlspecialchars($item["button_link"] ?? "#");
      ?>
      <div class="col-lg-4 col-md-6 col-sm-12 wisata-item" data-category="<?= $category ?>">
        <div class="card card-glass h-100">
          <span class="position-absolute top-3 start-3 badge bg-accent">
            <?= $cat_label ?> 
          </span>
          <img src="<?= $image_path ?>" 
               class="card-img-top w-100" 
               style="height: 220px; object-fit: cover;"
               alt="<?= $title ?>"
               onerror="this.src='uploads/default.jpg'">
          <div class="card-body">
            <h3><?= $title ?></h3>
            <p class="text-muted"><?= $excerpt ?>...</p>
              <a href="<?= $link ?>" 
                 class="btn btn-primary" 
                 target="_blank">
                Lihat Lokasi<i class="arrow-icon fas fa-angle-right"></i>
              </a>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
  <?php endif; ?>
</main>
  <div class="alert alert-warning" id="noMores" style="display:none">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
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
// ?>