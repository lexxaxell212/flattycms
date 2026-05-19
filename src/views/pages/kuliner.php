<?php
$page_title = "Kuliner Bandung";
//
?>
<main id="content" class="container py-5">
  <section id="Kuliner-Khas-Bandung">
    <h1 class="text-title">KULINER KHAS BANDUNG</h1>
    <p class="lead">Surga gastronomi yang memadukan kreativitas modern dengan resep tradisional turun-temurun. Keanekaragaman cita rasa dari gurihnya bumbu kacang hingga kesegaran kaldu sapi menciptakan pengalaman kuliner tak terlupakan di setiap sudut jalanan kota yang sejuk ini.</p>
  </section>
  
  <div class="row g-4 py-5">
    <?php
    //  RANDOM QUERY - ORDER BY RAND()
    $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('kuliner') ORDER BY RAND()");
    $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
          <div class="alert alert-warning" style="margin: auto;max-width:320px">
            <i class="icon d-block fa-3x fas fa-dice"></i>
            <span class="fw-semibold h5">Belum ada Card</span>
            <p class="text-muted">Tambahkan cards melalui <strong>Dashboard</strong></p>
          </div>
        </div>
    <?php } else {$visible_items = array_slice($all_items, 0, 10);
      $total_items = count($all_items);
      $hidden_count = $total_items - 10;

      foreach ($visible_items as $item) {

        $image = $item["image"] ?? "/default.jpg";
        $image_path =
          strpos($image, "http") === 0 ? $image : "uploads/" . $image;
        $category = strtolower($item["category"] ?? "general");
        $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
        $title = htmlspecialchars($item["title"] ?? "Untitled");
        $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 200));
        $link = htmlspecialchars($item["button_link"] ?? "#");
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-kuliner">
          <div class="card card-glass h-100">
            <img src="<?= $image_path ?>" 
                 class="card-img-top w-100" 
                 style="height: 220px; object-fit: cover;"
                 alt="<?= $title ?>"
                 onerror="this.src='uploads/default.jpg'">
            <div class="card-body">
              <h3><?= $title ?></h3>
              <p class="text-muted"><?= $excerpt ?>...</p>
                <a href="<?= $link ?>" target="_blank" class="btn
                btn-outline-primary">
                  Baca selengkapnya
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
    <button class="btn btn-outline-accent expand-btn" id="loadKulinerMoreBtn">
      LEBARKAN
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>

  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="row g-4 py-5 hidden-items d-none" id="hiddenKulinerItems">
    <?php
    $hidden_items = array_slice($all_items, 10);
    foreach ($hidden_items as $item) {

      $image = $item["image"] ?? "/default.jpg";
      $image_path =
        strpos($image, "http") === 0 ? $image : "/uploads/" . $image;
      $category = strtolower($item["category"] ?? "general");
      $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
      $title = htmlspecialchars($item["title"] ?? "Untitled");
      $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 150));
      $link = htmlspecialchars($item["button_link"] ?? "#");
      ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-kuliner">
        <div class="card card-glass h-100">
          <img src="<?= $image_path ?>" 
               class="card-img-top w-100" 
               style="height: 220px; object-fit: cover;"
               alt="<?= $title ?>"
               onerror="this.src='uploads/default.jpg'">
          <div class="card-body">
            <h3><?= $title ?></h3>
            <p class="text-muted"><?= $excerpt ?>...</p>
              <a href="<?= $link ?>" target="_blank" class="btn btn-outline-primary">
                  Baca selengkapnya
                  <i class="arrow-icon fas fa-angle-right"></i>
                 </button>
                </a>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
  <?php endif; ?>
  <div class="alert alert-warning" id="noMores1" style="display:none">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
  const expandBtn = document.getElementById('loadKulinerMoreBtn');
  const hiddenItems = document.getElementById('hiddenKulinerItems');
  const noMores = document.getElementById('noMores1');
  
  if (expandBtn) {
    expandBtn.addEventListener('click', function() {
      if (!isExpanded) {
        hiddenItems.classList.remove('d-none');
        isExpanded = true;
        this.innerHTML = `
          <span>KECILKAN</span>
          <i class="fas fa-chevron-up ms-2"></i>
        `;
        noMores1.style.display = 'block';
      } else {
        hiddenItems.classList.add('d-none');
        isExpanded = false;
        this.innerHTML = `
          <span class="btn-text">LEBARKAN</span>
          <i class="fas fa-chevron-down ms-2"></i>
        `;
        noMores1.style.display = 'none';
      }
    });
  }
});
</script>
</main>
<?php
// ?>