<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";

$page_title = "Kenapa harus Bandung";
?>

<div class="container py-5">
    <div class="container px-4 px-lg-5">
        <div class="shadow-lg border-0 rounded-4 overflow-hidden mb-6">
          <img src="https://asset.kompas.com/crops/C9ZPsnX6Z3zXGwjO98AZlxUSsfc=/0x0:640x427/1200x800/data/photo/2024/05/14/6642ea1cd3515.jpg"
          class="card-img-top img-fluid mb-6" alt="Pemandangan Kota Bandung" style="height: 350px; object-fit: cover;">
        </div>
      <div class="text-center mb-5">
        <h1 class="fs-1 mb-5">Kenapa Harus Bandung?</h1>
        <p>
          Bandung 2026: Perpaduan sempurna inovasi digital, kesejukan alam, dan kreativitas kuliner terbaik.
        </p>
      </div>
      <div class="spacer"></div>
      
      <!-- GRID -->
      <div class="row g-5 py-3">
 <?php
 $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
    category IN ('trending') ORDER BY id DESC");
 $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
 if (empty($all_items)) { ?>
      <div class="col-12 text-center py-5">
        <div class="alert alert-warning border-0 shadow-lg" style="max-width: 600px; margin: auto;">
          <i class="fas fa-dice fa-3x text-warning mb-4 d-block animate__animated animate__tada"></i>
          <h4 class="fw-bold fs-3">Belum ada Card</h4>
          <p class="fs-5 mb-4">Tambahkan cards melalui <strong>Dashboard</strong></p>
        </div>
      </div>
    <?php } else {foreach ($all_items as $item) {

     $image = $item["image"] ?? "cards/default.jpg";
     $image_path =
       strpos($image, "http") === 0 ? $image : "../assets/images/" . $image;
     $category = strtolower($item["category"] ?? "general");
     $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
     $title = htmlspecialchars($item["title"] ?? "Untitled");
     $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 200));
     $link = htmlspecialchars($item["button_link"] ?? "#");
     ?>
        <div class="col-lg-6 col-xl-4">
          <div class="glass glass-hover bdg-reason-card h-100">
            <div class="overflow-hidden">
              <img src="<?= $image_path ?>" alt="<?= $title ?>"
              style="height:220px" class="card-img-top w-100 mb-4"
              onerror="this.src='../assets/images/cards/default.jpg'"
              >
              <div class="p-5">
                <h5 class="h5 mb-3">
                 <?= $title ?>
                </h5>
                <p class="mb-3 text-muted">
                  <?= $excerpt ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <?php
   }}
 ?>
      </div>
      
      <div class="spacer"></div>
      <div class="spacer"></div>
      <div class="text-center rounded-xl bg-light mb-5 p-6 mx-auto">
        <h5 class="mb-4">Siap Petualangan ke Bandung?</h5>
        <p class="mb-5">
          Jadwal akhir pekan sudah penuh? Booking sekarang sebelum ketinggalan!
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
          <a href="https://google.com/search?q=Tiket pesawat ke bandung" class="btn btn-primary">
            <i class="fas fa-plane me-2"></i>Pesan Tiket
          </a>
          <a href="https://google.com/search?q=Booking hotel bandung" class="btn btn-outline-primary">
            <i class="fas fa-hotel me-2"></i>Cari Hotel
          </a>
        </div>
      </div>
    </div>

</div>

<?php
require_once SRC_PATH . "footer.php"; ?>