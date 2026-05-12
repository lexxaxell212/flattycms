<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";

$page_title = "Kuliner Bandung";
?>

<div class="container py-5">
  <!-- Hero Section -->
  <div class="text-center mb-5">
    <h1 class="fs-1 mb-6">KULINER KHAS BANDUNG</h1>
    <p class="fs-6 mb-5">Surga gastronomi yang memadukan kreativitas modern dengan resep tradisional turun-temurun. Keanekaragaman cita rasa dari gurihnya bumbu kacang hingga kesegaran kaldu sapi menciptakan pengalaman kuliner tak terlupakan di setiap sudut jalanan kota yang sejuk ini.</p>
  </div>
  
  <!-- Cards Grid -->
  <div class="row g-4 mt-6">
    <?php
    //  RANDOM QUERY - ORDER BY RAND()
    $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('kuliner') ORDER BY RAND()");
    $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
        <div class="alert alert-warning border-0 shadow-lg" style="max-width: 600px; margin: auto;">
          <i class="fas fa-dice fa-3x text-warning mb-4 d-block animate__animated animate__tada"></i>
          <h4 class="fw-bold fs-3">Belum Ada Kuliner</h4>
          <p class="fs-5 mb-4">Tambahkan cards melalui <strong>Dashboard</strong></p>
        </div>
      </div>
    <?php } else {$visible_items = array_slice($all_items, 0, 10);
      $total_items = count($all_items);
      $hidden_count = $total_items - 10;

      foreach ($visible_items as $item) {

        $image = $item["image"] ?? "cards/default.jpg";
        $image_path =
          strpos($image, "http") === 0 ? $image : "../assets/images/" . $image;
        $category = strtolower($item["category"] ?? "general");
        $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
        $title = htmlspecialchars($item["title"] ?? "Untitled");
        $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 200));
        $link = htmlspecialchars($item["button_link"] ?? "#");
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-kuliner">
          <div class="mb-4 card card-glass h-100 overflow-hidden shadow-lg position-relative">
            
            <!-- Image -->
            <img src="<?= $image_path ?>" 
                 class="card-img-top w-100" 
                 style="height: 220px; object-fit: cover;"
                 alt="<?= $title ?>"
                 onerror="this.src='../assets/images/cards/default.jpg'">
            
            <!-- Content -->
            <div class="card-body p-5 pb-3">
              <h5 class="mb-4"><?= $title ?></h5>
              <p class="mb-5"><?= $excerpt ?>...</p>
              <!-- Action Button -->
              <div class="mt-1">
                <a href="<?= $link ?>" target="_blank">
                 <button class="w-100 btn btn-primary px-4 py-2 fw-semibold fs-6">
                  Buka
                  <i class="fas fa-angle-right me-1"></i>
                 </button>
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
    <button class="btn btn-outline-primary btn-md px-6 py-3 fw-bold fs-5
    rounded-pill expand-btn" id="loadKulinerMoreBtn">
      <span class="btn-text">LEBARKAN</span>
      <i class="fas fa-chevron-down ms-2"></i>
    </button>
  </div>
  <?php endif; ?>

  <!-- Hidden Items -->
  <?php if (!empty($all_items) && $total_items > 10): ?>
  <div class="row g-4 mt-4 hidden-items d-none" id="hiddenKulinerItems">
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
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wisata-kuliner">
        <div class="mb-3 card card-glass h-100 overflow-hidden shadow-lg position-relative">
          <img src="<?= $image_path ?>" 
               class="card-img-top w-100" 
               style="height: 220px; object-fit: cover;"
               alt="<?= $title ?>"
               onerror="this.src='../assets/images/cards/default.jpg'">
          <div class="card-body p-4 pb-3">
            <h5 class="mb-4"><?= $title ?></h5>
            <p class="mb-5"><?= $excerpt ?>...</p>
            <div class="mt-1">
              <a href="<?= $link ?>" target="_blank">
                 <button class="w-100 btn btn-primary px-4 py-2 fw-semibold fs-6">
                  Buka
                  <i class="fas fa-angle-right me-1"></i>
                 </button>
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
  <div class="text-center mt-6 p-6" id="noMores1" style="display:none">
 <div class="spacer">
  <p class="text-muted">
   Tidak ada lagi ...
  </p>
 </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let isExpanded = false;
  
  // EXPAND TOGGLE
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

<?php
require_once SRC_PATH "footer.php"; ?>