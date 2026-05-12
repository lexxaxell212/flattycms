<?php
$page_title = "Budaya";
require_once SRC_PATH . "header.php";
?>

<div class="container py-5">
<style>
.btn:hover .arrow-icon {
transform: translateX(3px);
}
</style>
<section id="kebudayaan-bandung">
  <div class="row text-center">
    <h1 class="fs-1 mb-6">Warisan Kebudayaan Bandung</h1>
    <p>
      Kekayaan seni, tradisi, dan kearifan lokal Sunda yang telah diwariskan turun-temurun dan diakui dunia internasional.
    </p>
  </div>
</section>
<div class="spacer"></div>
  <!-- Musik Tradisional -->
  <div class="text-start mb-6 mt-6">
    <h4 class="mb-3">Musik Tradisional</h4>
    <p class="mb-3">
      Alat musik bambu dan perkusi khas Sunda yang mencerminkan harmoni alam dan kehidupan masyarakat.
    </p>
  </div>
  <div class="row g-4 mb-6">
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass h-100 overflow-hidden">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Angklung Bandung">
        <div class="card-body p-6">
          <h5 class="mb-3">Angklung</h5>
          <p class="mb-6 text-muted">
            Alat musik bambu yang dimainkan dengan cara digoyang. Suaranya unik dan harmonis, diakui UNESCO sebagai Warisan Budaya Takbenda Dunia pada 2010.
          </p>
          <a href="#" target="_blank">
            <button class="btn btn-primary btn-sm">
              Buka 
              <i class="fas fa-angle-right me-1"></i>
            </button>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass h-100 overflow-hidden">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Calung">
        <div class="p-6">
          <h5 class="mb-3">Calung</h5>
          <p class="mb-6 text-muted">
            Alat musik idiofon dari bambu yang dimainkan dengan dipukul. Biasa digunakan dalam kesenian Calung dan pantun Sunda.
          </p>
          <a href="#">
            <button class="btn btn-primary btn-sm">
              Buka
              <i class="fas fa-angle-right me-1"></i>
            </button>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Kendang">
        <div class="p-6">
          <h5 class="mb-3">Kendang</h5>
          <p class="mb-6 text-muted">
            Gendang kulit kayu yang menjadi pengatur irama dalam berbagai kesenian Sunda seperti Jaipong dan Wayang Golek.
          </p>
          <a href="#">
            <button class="btn btn-primary btn-sm">
              Buka
              <i class="fas fa-angle-right me-1"></i>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="spacer"></div>
  <!-- Tari Tradisional -->
  <div class="text-start mb-6 mt-6">
    <h4 class="mb-3">Tari Tradisional</h4>
    <p class="mb-3">
      Menghidupkan identitas Sunda lewat gerak gemulai dan hentakan kendang. Tari tradisional Bandung bukan sekadar tontonan, melainkan harmoni antara ekspresi jiwa dan kekayaan tradisi yang abadi.
    </p>
  </div>
  <div class="row g-4 mb-6">
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Jaipong">
        <div class="card-body p-6">
          <h5 class="mb-3">Jaipong</h5>
          <p class="mb-6 text-muted">
            Tarian dinamis ciptaan Haji Suanda yang menggabungkan gerakan pencak silat, ketuk tilu, dan gaya ronggeng. Simbol kegembiraan rakyat Sunda.
          </p>
          <a href="#" class="btn btn-primary btn-sm">
            Buka
            <i class="fas fa-angle-right me-1"></i>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Kecapi Suling">
        <div class="card-body p-6">
          <h5 class="mb-3">Tari Kecapi Suling</h5>
          <p class="mb-6 text-muted">
            Tarian romantis yang mengiringi musik gamelan degung. Menggambarkan kisah cinta dan keindahan alam Priangan.
          </p>
          <a href="#" class="btn btn-primary btn-sm">
            Buka
            <i class="fas fa-angle-right me-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="spacer"></div>
  <!-- Wayang Golek -->
  <div class="text-center mb-6 mt-6">
    <h4 class="fs-2 mb-6">Wayang Golek</h4>
    <div class="mb-6">
      <img class="rounded-lg w-100" src="<?php echo IMG_URL; ?>cards/default.jpg" alt="Kecapi Suling">
    </div>
    <p class="mb-6">
      Keluar sosok Astrajingga, atau yang lebih dikenal sebagai Cepot. Wajahnya
      merah membara, dengan satu gigi putih yang mencuat jenaka. Meskipun rupa
      fisiknya tampak seperti banyolan, di balik gerak patah-patah leher
      kayunya, tersimpan kearifan lokal yang tajam. Ia bukan sekadar boneka, ia
      adalah suara rakyat, pengkritik yang jenaka, sekaligus pengingat bahwa di
      atas langit masih ada langit.
    </p>
    <a href="#" class="btn btn-primary btn-sm">
      Buka
      <i class="fas fa-angle-right me-1"></i>
    </a>
  </div>
  <div class="spacer"></div>
  <!-- Arsitektur & Kerajinan -->
  <div class="text-start mb-6 mt-6">
    <h4 class="mb-3">Arsitektur & Kerajinan</h4>
    <p class="mb-3">
      Bangunan bersejarah dan kerajinan tangan yang menjadi identitas visual Kota Kembang.
    </p>
  </div>
  <div class="row g-4 mb-6">
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Rumah Adat Sunda">
        <div class="p-6">
          <h5 class="mb-3">Rumah Adat Sunda</h5>
          <p class="mb-6 text-muted">
            Rumah panggung dengan atap julang ngapak (bentuk burung phoenix) dan struktur kayu jati. Simbol filosofi hidup Sunda.
          </p>
          <a href="#" class="btn btn-primary btn-sm">
            Buka
            <i class="fas fa-angle-right me-1"></i>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Batik Bandung">
        <div class="p-6">
          <h5 class="mb-3">Batik & Kain</h5>
          <p class="mb-6 text-muted">
            Batik modern dengan motif parahyangan seperti kawung, mega mendung, dan pula kembang. Pusatnya di Kampung Batik Cipadu.
          </p>
          <a href="#" class="btn btn-primary btn-sm">
            Buka
            <i class="fas fa-angle-right me-1"></i>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
      <div class="card card-glass overflow-hidden h-100">
        <img src="<?php echo IMG_URL; ?>cards/default.jpg" class="card-img-top w-100" alt="Keramik">
        <div class="p-6">
          <h5 class="mb-3">Gerabah & Keramik</h5>
          <p class="mb-6 text-muted">
            Kerajinan tanah liat khas Plered dan Cigadung dengan motif tradisional Sunda yang indah dan fungsional.
          </p>
          <a href="#" class="btn btn-primary btn-sm">
            Buka
            <i class="fas fa-angle-right me-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
    
<?php
require_once SRC_PATH . "footer.php"; ?>