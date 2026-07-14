<?php
require_once LIB_PATH . 'poi-actions.php';
$total_poi = count(get_all_poi());
$page_title = "Tentang Website";
?>
<main class="main-content">
 <div class="container">
  <div class="page-header">
   <div class="text-center">
    <h1 class="h2 mb-4">Ayokebandung<em>.ID</em></h1>
    <p class="lead" data-bhs="about.hero.lead">
     Platform digital yang membantumu menjelajahi Bandung dengan mudah, dari destinasi wisata hingga rencana perjalanan.
    </p>
   </div>
  </div>
  <div class="mb-5">
   <div class="row g-4 justify-content-center text-center">
    <div class="col-6 col-md-3">
     <div class="fw-bold fs-2 text-primary"><?= $total_poi ?></div>
     <div class="small text-muted" data-bhs="about.stat.destinasi">Destinasi Terdaftar</div>
    </div>
    <div class="col-6 col-md-3">
     <div class="fw-bold fs-2 text-primary">24/7</div>
     <div class="small text-muted" data-bhs="about.stat.ai">Asisten AI Siap Bantu</div>
    </div>
   </div>
  </div>
  <section class="bg-surface mb-5" style="padding:var(--section-padding)">
   <div class="row align-items-center g-4">
    <div class="col-lg-6">
     <h2 class="h3 mb-3" data-bhs="about.misi.title">Misi Kami</h2>
     <p class="text-muted" data-bhs="about.misi.body1">
      Bandung punya ratusan tempat menarik, tapi sering kali wisatawan bingung
      mulai dari mana. Ayokebandung.id hadir untuk menjembatani itu - menyatukan
      informasi destinasi, kuliner, budaya, dan akomodasi dalam satu platform,
      lengkap dengan alat bantu perencanaan perjalanan berbasis teknologi terkini.
     </p>
     <p class="text-muted mb-0" data-bhs="about.misi.body2">
      Kami percaya menjelajahi kota gak harus ribet. Cukup ceritakan maumu,
      biar sisanya kami bantu susun.
     </p>
    </div>
    <div class="col-lg-6">
     <img class="mx-auto d-block my-3" src="/assets/images/undraw-traveler.svg" style="width:100%;max-width:400px;" />
    </div>
   </div>
  </section>
  <section class="bg-surface mb-5" style="padding:var(--section-padding)">
   <h2 class="h3 text-center mb-5" data-bhs="about.offer.title">
    Apa yang Kami Tawarkan
   </h2>
   <div class="row g-4 justify-content-center">
    <div class="col-lg-4 col-md-6">
     <div class="h-100 text-center p-3">
      <i class="fa-solid fa-location-dot fs-1 text-primary mb-3"></i>
      <h3 class="h5" data-bhs="about.offer.poi.title">
       POI
      </h3>
      <p class="text-muted small mb-0" data-bhs="about.offer.poi.desc">
       Temukan POI (Point of Interest) di Bandung dan sekitarnya.
      </p>
     </div>
    </div>
    <div class="col-lg-4 col-md-6">
     <div class="h-100 text-center p-3">
      <i class="fa-solid fa-route fs-1 text-primary mb-3"></i>
      <h3 class="h5" data-bhs="about.offer.trip.title">
       Trip Planner
      </h3>
      <p class="text-muted small mb-0" data-bhs="about.offer.trip.desc">
       Susun rencana perjalananmu sendiri dengan mudah dan fleksibel lewat peta interaktif.
      </p>
     </div>
    </div>
    <div class="col-lg-4 col-md-6">
     <div class="h-100 text-center p-3">
      <i class="fa-solid fa-wand-magic-sparkles fs-1 text-primary mb-3"></i>
      <h3 class="h5" data-bhs="about.offer.ai.title">
       Itinerary AI
      </h3>
      <p class="text-muted small mb-0" data-bhs="about.offer.ai.desc">
       Dapatkan rekomendasi itinerary otomatis berbasis AI sesuai preferensimu.
      </p>
     </div>
    </div>
    <div class="col-lg-4 col-md-6">
     <div class="h-100 text-center p-3">
      <i class="fa-solid fa-images fs-1 text-primary mb-3"></i>
      <h3 class="h5" data-bhs="about.offer.gallery.title">
       Galeri & Ulasan
      </h3>
      <p class="text-muted small mb-0" data-bhs="about.offer.gallery.desc">
       Lihat momen dan pengalaman traveler lain, atau bagikan foto dan ceritamu sendiri.
      </p>
     </div>
    </div>
    <div class="col-lg-4 col-md-6">
     <div class="h-100 text-center p-3">
      <i class="fa-solid fa-bullhorn fs-1 text-primary mb-3"></i>
      <h3 class="h5" data-bhs="about.offer.event.title">
       Event Mendatang
      </h3>
      <p class="text-muted small mb-0" data-bhs="about.offer.event.desc">
       Info event di Bandung, Jangan sampai ketinggalan momen seru.
      </p>
     </div>
    </div>
   </div>
  </section>
  <section class="bg-surface mb-5 text-center" style="padding:var(--section-padding)">
   <h2 class="h3 mb-3" data-bhs="about.feedback.title">Punya Masukan?</h2>
   <p class="text-muted mb-4" data-bhs="about.feedback.body">
    Ayokebandung.id terus berkembang. Kritik dan saranmu bantu kami jadi lebih baik.
   </p>
   <a href="/pages/kritik-dan-saran" class="btn btn-outline-primary btn-fit">
    <i class="fa-solid fa-comment-dots me-1"></i><span data-bhs="about.feedback.btn">Kirim Feedback</span>
   </a>
  </section>
  <section class="bg-surface text-center mb-4" style="padding:var(--section-padding)">
   <h2 class="h3 mb-3" data-bhs="about.cta.title">Siap Jelajahi Bandung?</h2>
   <p class="text-muted mb-4" data-bhs="about.cta.body">
    Mulai rencanakan perjalananmu sekarang, gratis dan gak pakai ribet.
   </p>
   <div class="d-flex flex-wrap justify-content-center">
    <a href="/trip" class="btn btn-primary">
     <i class="fa-solid fa-route me-1"></i><span data-bhs="about.cta.btn">Mulai Trip Planner</span>
    </a>
   </div>
  </section>
 </div>
</main>
