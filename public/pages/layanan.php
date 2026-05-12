<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";

$page_title = "Layanan";
?>

<div class="container py-5">
  <!-- Header -->
  <div class="text-center mb-6">
    <h1 class="fs-1 mb-6">Transportasi Bandung 2026</h1>
    <p class="">Opsi transportasi tercepat, termurah, dan terpercaya di Kota Kembang.</p>
  </div>
  <!-- Cards Grid -->
  <div class="row g-4 mt-6">
    <!-- 1. Transportasi Aplikasi -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
      <div class="glass glass-hover h-100 shadow-lg">
        <div class="card-body p-4">
          <div class="d-flex align-items-start mb-3">
            <div class="me-3">
              <i class="fas fa-motorcycle fa-2x text-muted py-5"></i>
              <h6 class="mb-2">Grab/Gojek</h6>
              <p class="mb-2">Layanan door-to-door yang fleksibel, mencakup motor (Bike) dan mobil (Car).</p>

              <ul class="list-unstyled mb-3">
                <li class="text-muted small mb-1"><strong>Motor :</strong> Jasa minimal sekitar Rp9.200 – Rp11.000 (untuk 4 km pertama), dengan tarif selanjutnya sekitar Rp2.300 – Rp2.750/km.</li>
                <li class="text-muted small mb-1">
                  <strong>Mobil :</strong> Tarif sangat bergantung pada jam sibuk (surge pricing) dan jarak, biasanya mulai dari Rp15.000 – Rp20.000 untuk jarak pendek.
                </li>
                <li class="text-muted small mb-5"><strong>Pelayanan :</strong> Penjemputan sesuai titik GPS, pelacakan real-time, pilihan pembayaran tunai atau dompet digital (OVO/GoPay), dan fitur keamanan (tombol darurat).</li>
              </ul>
              <a href="https://google.com/search?q=gojek"><button class="btn btn-primary btn-sm">Gojek
                  <i class="fas fa-angle-right me-1"></i>
                </button>
              </a>
              <a href="https://google.com/search?q=grab"><button class="btn btn-primary btn-sm">Grab<i class="fas fa-angle-right me-1"></i></button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 2. Bus Umum -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
      <div class="glass glass-hover h-100 shadow-lg">
        <div class="card-body p-4">
          <div class="d-flex align-items-start mb-3">
            <div class="me-3">
              <i class="fas fa-bus fa-2x text-muted py-5"></i>
              <h6 class="mb-2">Metro Jabar Trans</h6>
              <p class="mb-2">Evolusi dari Trans Metro Bandung (TMB) dan Teman Bus yang kini terintegrasi dalam sistem Metro Jabar Trans (MJT).</p>
              <div class="d-flex flex-wrap gap-1 mb-2">
                <ul class="list-unstyled mb-2">
                  <li class="text-muted small mb-1"><strong>Umum :</strong> Flat Rp4.900 per perjalanan (berlaku mulai 1 April 2026).</li>
                  <li class="text-muted small mb-1">
                    <strong>Pelajar :</strong> Rp2.000 (dengan menunjukkan kartu pelajar/seragam).
                  </li>
                  <li class="text-muted small mb-1"><strong>Pelayanan :</strong> Bus ber-AC, berhenti hanya di halte resmi, pembayaran menggunakan kartu uang elektronik (Tap-on-Bus) atau QRIS. Mencakup rute strategis seperti Dago, Leuwipanjang, Jatinangor, dan Alun-alun.</li>
                </ul>
              </div>
              <ul class="list-unstyled list-inline small mb-3">
                <li class="d-inline me-2 text-muted"><i class="fas fa-check text-success me-1"></i>Halte resmi</li>
                <li class="d-inline text-muted"><i class="fas fa-check text-success me-1"></i>QRIS</li>
              </ul>
              <a href="https://www.google.com/maps/search/?api=1&query=halte%20metro%20jabar%20trans%20terdekat"><button class="mt-2 btn btn-primary btn-sm">Cek Halte terdekat<i class="fas fa-angle-right me-1"></i></button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 3. Terminal Bus -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
      <div class="glass glass-hover h-100 shadow-lg">
        <div class="card-body p-4">
          <div class="d-flex align-items-start mb-3">
            <div class="me-3">
              <i class="fas fa-building fa-2x text-muted py-5"></i>
              <h6 class="mb-2">Terminal Leuwipanjang</h6>
              <p class="mb-2">Pusat pemberangkatan bus Antar Kota Antar Provinsi (AKAP) dan Antar Kota Dalam Provinsi (AKDP).</p>
              <div class="d-flex flex-wrap gap-1 mb-2">
                <ul class="list-unstyled mb-3">
                  <li class="text-muted small mb-1"><strong>Status Terbaru (April 2026) :</strong> Layanan bus AKAP/AKDP mulai dipusatkan di Terminal Leuwipanjang. Terminal Cicaheum dialihfungsikan menjadi depo dan pusat layanan BRT Metro Jabar Trans.</li>
                  <li class="text-muted small mb-1">
                    <strong>Tarif :</strong> Bergantung pada tujuan (Contoh: Bandung – Subang mulai Rp60.000, Bandung – Jakarta via Damri sekitar Rp175.000).
                  </li>
                  <li class="text-muted small mb-1"><strong>Pelayanan :</strong> Ruang tunggu penumpang, loket tiket terpadu, area UMKM, dan integrasi dengan angkutan pengumpan (feeder).</li>
                </ul>
              </div>
              <ul class="list-unstyled list-inline small mb-3">
                <li class="d-inline me-2 text-muted"><i class="fas fa-check text-success me-1"></i>Loket terpadu</li>
                <li class="d-inline text-muted"><i class="fas fa-check text-success me-1"></i>UMKM</li>
              </ul>
              <a href="https://www.google.com/maps/search/?api=1&query=Terminal%20Lewuipanjang%20bandung"><button class="mt-2 btn btn-primary btn-sm">Lihat Maps<i class="fas fa-angle-right me-1"></i></button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 4. Kereta Api -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
      <div class="glass glass-hover h-100 shadow-lg">
        <div class="card-body p-4">
          <div class="d-flex align-items-start mb-3">
            <div class="me-3">
              <i class="fas fa-train fa-2x text-muted py-5"></i>
              <h6 class="mb-2">Stasiun Hall/Kiaracondong</h6>
              <p class="mb-2">Gerbang utama jalur kereta api jarak jauh dan komuter (Lokal Bandung Raya).</p>
              <div class="d-flex flex-wrap gap-1 mb-2">
                <ul class="list-unstyled mb-3">
                  <li class="text-muted small mb-1"><strong>Lokal / Commuter Line :</strong> Tarif
                    sekitar Rp5000.</li>
                  <li class="text-muted small mb-1">
                    <strong>Jarak jauh :</strong> Variatif mulai dari Rp80.000 (Ekonomi) hingga Rp500.000+ (Eksekutif/Luxury).
                  </li>
                  <li class="text-muted small mb-1"><strong>Whoosh (Kereta Cepat) :</strong> Tarif normal sekitar Rp300.000 (Stasiun Tegalluar/Padalarang).</li>
                  <li class="text-muted small mb-1"><strong>Stasiun Bandung (Hall) :</strong> Fasilitas kelas satu, akses langsung ke pusat kota, integrasi dengan KA Feeder Whoosh.</li>
                  <li class="text-muted small mb-1"><strong>Stasiun Kiaracondong :</strong> Fokus pada kereta kelas ekonomi, fasilitas sudah direnovasi lebih modern dengan area tunggu yang lebih luas.</li>
                </ul>
              </div>
              <ul class="list-unstyled list-inline small mb-3">
                <li class="d-inline me-2 text-muted"><i class="fas fa-check text-success me-1"></i>Renovasi modern</li>
                <li class="d-inline text-muted"><i class="fas fa-check text-success me-1"></i>Feeder Whoosh</li>
              </ul>
              <a href="https://www.google.com/maps/search/?api=1&query=statiun%20kiaracondong%20bandung"><button class="mt-2 btn btn-primary btn-sm">Lihat Maps<i class="fas fa-angle-right me-1"></i></button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 5. Bandara -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
      <div class="glass glass-hover h-100 shadow-lg">
        <div class="card-body p-4">
          <div class="d-flex align-items-start mb-3">
            <div class="me-3">
              <i class="fas fa-plane fa-2x text-muted py-5"></i>
              <h6 class="mb-2">Bandara Kertajati/BDO</h6>
              <p class="mb-2">Layanan transportasi udara yang menghubungkan Bandung dengan kota-kota lain. Penerbangan jet komersial saat ini dipusatkan di Bandara Kertajati (KJT), sementara Husein Sastranegara (BDO) melayani pesawat baling-baling (propeller) dan jet pribadi/militer.</p>
              <div class="d-flex flex-wrap gap-1 mb-2">
                <ul class="list-unstyled mb-3">
                  <li class="text-muted small mb-1"><strong>Tarif Damri ke Kertajati :</strong> Sekitar Rp90.000 – Rp120.000 dari pusat kota Bandung.</li>
                  <li class="text-muted small mb-1">
                    <strong>Pelayanan :</strong> Layanan shuttle terjadwal, area check-in mandiri, dan fasilitas standar bandara internasional di Kertajati.
                  </li>
              </div>
              <ul class="list-unstyled list-inline small mb-3">
                <li class="d-inline me-2 text-muted"><i class="fas fa-check text-success me-1"></i>Shuttle terjadwal</li>
                <li class="d-inline text-muted"><i class="fas fa-check text-success me-1"></i>Check-in mandiri</li>
              </ul>
              <a href="https://www.google.com/maps/search/?api=1&query=bandara%20kertajati%20bdo"><button class="mt-2 btn btn-primary btn-sm">Lihat Maps<i class="fas fa-angle-right me-1"></i></button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Tips -->
    <div class="col-12 mt-6">
      <div class="alert alert-warning shadow-lg mt-6">
        <h6 class="mb-2 fw-bold">
          <i class="fas fa-lightbulb me-2"></i>
          Tips Perjalanan Bandung
        </h6>
        <p class="mb-0">Gunakan <strong>Metro Jabar Trans</strong> untuk rute utama atau <strong>Ojek Online</strong> untuk kecepatan menembus kemacetan Bandung.</p>
      </div>
    </div>
  </div>
</div>

<?php
require_once SRC_PATH . "footer.php"; ?>