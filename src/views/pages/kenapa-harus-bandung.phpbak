<?php
$page_title = "Kenapa harus Bandung";
//
?>
<main id="content" class="container-fluid">
<div class="container">
  <section id="Kenapa-Harus-Bandung">
    <div class="overflow-hidden mb-5 h-100">
          <img src="https://ayokebandung.id/uploads/default.jpg"
          class="card-img-top w-100 mb-5 rounded-lg" alt="Pemandangan Kota Bandung">
    </div>
    <div>
        <h1 class="text-title">Kenapa Harus Bandung?</h1>
        <p class="lead">
          Bandung 2026: Perpaduan sempurna inovasi digital, kesejukan alam, dan kreativitas kuliner terbaik.
        </p>
    </div>
  </section>
  <section class="row g-4 py-5 mb-5">
   <?php
   $stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
      category IN ('trending') ORDER BY id DESC");
   $all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
   if (empty($all_items)) { ?>
        <div class="col-12 text-center py-5">
          <div class="alert alert-warning" style="margin: auto;max-width:320px">
            <i class="icon d-block fa-3x fas fa-dice"></i>
            <h3>Belum ada Card</h3>
            <p class="text-muted">Tambahkan cards melalui <strong>Dashboard</strong></p>
          </div>
        </div>
      <?php } else {foreach ($all_items as $item) {
  
       $image = $item["image"] ?? "/uploads/default.jpg";
       $image_path =
         strpos($image, "http") === 0 ? $image : BASE_UPLOAD_URL . $image;
       $category = strtolower($item["category"] ?? "general");
       $cat_label = ucwords(str_replace(["-", "_"], " ", $category));
       $title = htmlspecialchars($item["title"] ?? "Untitled");
       $excerpt = htmlspecialchars(substr($item["excerpt"] ?? "", 0, 200));
       $link = htmlspecialchars($item["button_link"] ?? "#");
       ?>
          <div class="col-lg-6 col-xl-4">
            <div class="card card-glass h-100 overflow-hidden">
                <img src="<?= $image_path ?>" alt="<?= $title ?>" class="card-img-top"
                onerror="this.src='/uploads/default.jpg'"
                >
                <div class="card-body">
                  <h3>
                   <?= $title ?>
                  </h3>
                  <p class="text-muted">
                    <?= $excerpt ?>
                  </p>
                </div>
            </div>
          </div>
          <?php
     }}
   ?>
    
  </section>
  <section class="text-center bg-gray py-5 px-5">
        <h2>Siap Petualangan ke Bandung ?</h2>
        <p>
          Jadwal akhir pekan sudah penuh? Booking sekarang sebelum ketinggalan!
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
          <a href="https://google.com/search?q=Tiket pesawat ke bandung"
          class="btn btn-outline-primary">
            <i class="fas fa-plane me-2"></i>Pesan Tiket
          </a>
          <a href="https://google.com/search?q=Booking hotel bandung" class="btn
          btn-outline-accent">
            <i class="fas fa-hotel me-2"></i>Cari Hotel
          </a>
        </div>
      </section>

</div>
</main>
<?php
// ?>