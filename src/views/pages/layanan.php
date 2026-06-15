<?php
$page_title = "Layanan Publik Bandung";
?>
<main class="main-content">
  <div class="container">
    <div class="page-header text-center">
      <div class="page-header-svg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 280">
          <defs>
            <style>
              @keyframes draw {
                from {
                  stroke-dashoffset: 400;
                }
                to {
                  stroke-dashoffset: 0;
                }
              }
              @keyframes pulse {
                0%, 100% {
                  opacity: 0.4;
                  r: 14;
                }
                50% {
                  opacity: 0.15;
                  r: 18;
                }
              }
              @keyframes blink {
                0%, 100% {
                  opacity: 1;
                }
                50% {
                  opacity: 0.2;
                }
              }
              .line-a {
                fill: none;
                stroke: var(--icon-3);
                stroke-width: 4;
                stroke-linecap: round;
                stroke-dasharray: 400;
                animation: draw 2s ease forwards;
              }
              .line-b {
                fill: none;
                stroke: var(--icon-2);
                stroke-width: 4;
                stroke-linecap: round;
                stroke-dasharray: 400;
                stroke-dashoffset: 400;
                animation: draw 2.3s ease 0.3s forwards;
              }
              .line-c {
                fill: none;
                stroke: var(--icon-4);
                stroke-width: 4;
                stroke-linecap: round;
                stroke-dasharray: 400;
                stroke-dashoffset: 400;
                animation: draw 2.6s ease 0.6s forwards;
              }
              .line-d {
                fill: none;
                stroke: var(--icon-4);
                stroke-width: 3;
                stroke-linecap: round;
                stroke-dasharray: 120;
                stroke-dashoffset: 120;
                animation: draw 1.5s ease 0.9s forwards;
              }
              .station {
                fill: oklch(0.22 0.05 295);
                stroke: oklch(0.55 0.04 295);
                stroke-width: 2;
              }
              .station-major {
                fill: var(--bg-primary);
                stroke: var(--bg-primary-subtle);
                stroke-width: 2.5;
              }
              .station-pulse {
                fill: var(--bg-accent);
                animation: pulse 2s ease-in-out infinite;
              }
              .train-rect {
                fill: var(--bg-primary);
              }
              .train-rect-b {
                fill: var(--bg-green);
              }
              .train-window {
                fill: var(--bg-primary-subtle);
                opacity: 0.7;
              }
              .label {
                font-family: monospace;
                font-size: 0.6rem;
                fill: oklch(0.27 0.05 295);
              }
              .label-major {
                font-size: 0.65rem;
                font-weight: bold;
                fill: oklch(0.18 0.06 295);
                opacity: 0.7;
              }
              .dec-dot {
                fill: oklch(0.35 0.04 295);
                animation: blink 3s ease-in-out infinite;
              }
            </style>
          </defs>
          <!-- transit lines -->
          <path class="line-a" d="M 50,145 L 110,145 L 150,115 L 240,115 L 280,145 L 350,145" />
          <path class="line-b" d="M 70,215 L 130,170 L 200,145 L 265,105 L 320,75" />
          <path class="line-c" d="M 55,215 L 125,210 L 185,190 L 245,190 L 310,210 L 355,205" />
          <path class="line-d" d="M 200,145 L 200,190" />
          <!-- stations -->
          <!-- major hub -->
          <circle cx="200" cy="145" r="18" class="station-pulse" />
          <circle cx="200" cy="145" r="9" class="station-major" />
          <!-- line A -->
          <circle cx="50" cy="145" r="5" class="station" />
          <circle cx="110" cy="145" r="5" class="station" />
          <circle cx="150" cy="115" r="5" class="station" />
          <circle cx="240" cy="115" r="5" class="station" />
          <circle cx="280" cy="145" r="6" class="station" />
          <circle cx="350" cy="145" r="5" class="station" />
          <!-- line B -->
          <circle cx="70" cy="215" r="5" class="station" />
          <circle cx="130" cy="170" r="5" class="station" />
          <circle cx="265" cy="105" r="5" class="station" />
          <circle cx="320" cy="75" r="5" class="station" />
          <!-- line C -->
          <circle cx="55" cy="215" r="5" class="station" />
          <circle cx="125" cy="210" r="5" class="station" />
          <circle cx="185" cy="190" r="5" class="station" />
          <circle cx="200" cy="190" r="6" class="station" />
          <circle cx="245" cy="190" r="5" class="station" />
          <circle cx="310" cy="210" r="5" class="station" />
          <circle cx="355" cy="205" r="5" class="station" />
          <!-- train A: animateMotion along line A -->
          <g opacity="0">
            <rect class="train-rect" x="-9" y="-5" width="18" height="10" rx="3" />
            <rect class="train-window" x="-7" y="-3" width="4" height="4" rx="1" />
            <rect class="train-window" x="-1" y="-3" width="4" height="4" rx="1" />
            <animate attributeName="opacity" from="0" to="1" begin="0.5s" dur="0.1s" fill="freeze" />
            <animateMotion dur="5s" repeatCount="indefinite" begin="0.5s"
              path="M 50,145 L 110,145 L 150,115 L 240,115 L 280,145 L 350,145"
              rotate="auto" />
          </g>
          <!-- train B: animateMotion along line B -->
          <g opacity="0">
            <rect class="train-rect-b" x="-7" y="-4" width="14" height="8" rx="2" />
            <rect class="train-window" x="-5" y="-2" width="3" height="3" rx="1" />
            <rect class="train-window" x="0" y="-2" width="3" height="3" rx="1" />
            <animate attributeName="opacity" from="0" to="1" begin="2s" dur="0.1s" fill="freeze" />
            <animateMotion dur="6s" repeatCount="indefinite" begin="2s"
              path="M 70,215 L 130,170 L 200,145 L 265,105 L 320,75"
              rotate="auto" />
          </g>
          <!-- labels -->
          <text x="200" y="132" class="label label-major"
            text-anchor="middle">BANDUNG</text>
          <text x="44" y="138" class="label" text-anchor="middle">A1</text>
          <text x="350" y="138" class="label" text-anchor="middle">A6</text>
          <text x="320" y="67" class="label" text-anchor="middle">B4</text>
          <text x="355" y="198" class="label" text-anchor="middle">C7</text>
          <!-- decorative dots -->
          <circle cx="30" cy="55" r="3" class="dec-dot" style="animation-delay:0s" />
          <circle cx="375" cy="240" r="3" class="dec-dot" style="animation-delay:0.8s" />
          <circle cx="365" cy="45" r="2" class="dec-dot" style="animation-delay:1.4s" />
          <circle cx="22" cy="240" r="2" class="dec-dot" style="animation-delay:0.4s" />
        </svg>
      </div>
      <h1>Transportasi Bandung</h1>
      <p class="lead">
        Opsi transportasi tercepat, termurah, dan terpercaya di Kota Kembang.
      </p>
    </div>
    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="card card-flatty h-100">
          <div class="card-body">
            <img src="<?= BASE_UPLOAD_URL ?>default.jpg" class="card-img" alt="default">
            <h2 class="h4">Grab/Gojek</h2>
            <p>
              Layanan door-to-door yang fleksibel, mencakup motor (Bike) dan mobil (Car).
            </p>
            <ul class="list-unstyled">
              <li class="text-muted small mb-1"><strong>Motor :</strong> Jasa minimal sekitar Rp9.200 – Rp11.000 (untuk 4 km pertama), dengan tarif selanjutnya sekitar Rp2.300 – Rp2.750/km.</li>
              <li class="text-muted small mb-1">
                <strong>Mobil :</strong> Tarif sangat bergantung pada jam sibuk (surge pricing) dan jarak, biasanya mulai dari Rp15.000 – Rp20.000 untuk jarak pendek.
              </li>
              <li class="text-muted small mb-2"><strong>Pelayanan :</strong> Penjemputan sesuai titik GPS, pelacakan real-time, pilihan pembayaran tunai atau dompet digital (OVO/GoPay), dan fitur keamanan (tombol darurat).</li>
            </ul>
          </div>
          <div class="card-footer">
            <a href="https://google.com/search?q=grab" class="btn btn-primary">Grab
              <i class="arrow-icon fas fa-angle-right ms-2"></i>
            </a>
            <a href="https://google.com/search?q=gojek" class="btn btn-primary">Gojek<i class="arrow-icon fas
              fa-angle-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-flatty h-100">
          <div class="card-body">
            <img src="<?= BASE_UPLOAD_URL ?>default.jpg" class="card-img" alt="default">
            <h2 class="h4">Metro Jabar Trans</h2>
            <p>
              Evolusi dari Trans Metro Bandung (TMB) dan Teman Bus yang kini terintegrasi dalam sistem Metro Jabar Trans (MJT).
            </p>
            <div class="d-flex flex-wrap gap-1 mb-2">
              <ul class="list-unstyled mb-2">
                <li class="text-muted small mb-1"><strong>Umum :</strong> Flat Rp4.900 per perjalanan (berlaku mulai 1 April 2026).</li>
                <li class="text-muted small mb-1">
                  <strong>Pelajar :</strong> Rp2.000 (dengan menunjukkan kartu pelajar/seragam).
                </li>
                <li class="text-muted small mb-1"><strong>Pelayanan :</strong> Bus ber-AC, berhenti hanya di halte resmi, pembayaran menggunakan kartu uang elektronik (Tap-on-Bus) atau QRIS. Mencakup rute strategis seperti Dago, Leuwipanjang, Jatinangor, dan Alun-alun.</li>
              </ul>
            </div>
          </div>
          <div class="card-footer">
            <a
              href="https://www.google.com/maps/search/?api=1&query=halte%20metro%20jabar%20trans%20terdekat"
              class="btn btn-primary">Cek Halte terdekat<i
                class="arrow-icon fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card card-flatty h-100">
          <div class="card-body">
            <img src="<?= BASE_UPLOAD_URL ?>default.jpg" class="card-img" alt="default">
            <h2 class="h4">Bandara Kertajati/BDO</h2>
            <p>
              Layanan transportasi udara yang menghubungkan Bandung dengan kota-kota lain. Penerbangan jet komersial saat ini dipusatkan di Bandara Kertajati (KJT), sementara Husein Sastranegara (BDO) melayani pesawat baling-baling (propeller) dan jet pribadi/militer.
            </p>
            <div class="d-flex flex-wrap gap-1 mb-2">
              <ul class="list-unstyled mb-3">
                <li class="text-muted small mb-1"><strong>Tarif Damri ke Kertajati :</strong> Sekitar Rp90.000 – Rp120.000 dari pusat kota Bandung.</li>
                <li class="text-muted small mb-1">
                  <strong>Pelayanan :</strong> Layanan shuttle terjadwal, area check-in mandiri, dan fasilitas standar bandara internasional di Kertajati.
                </li>
              </ul>
              <ul class="list-unstyled list-inline small mb-3">
                <li class="d-inline me-2 text-muted"><i class="fas fa-check text-success me-1"></i>Shuttle terjadwal</li>
                <li class="d-inline text-muted"><i class="fas fa-check text-success me-1"></i>Check-in mandiri</li>
              </ul>
            </div>
            <div class="card-footer">
              <a href="https://www.google.com/maps/search/?api=1&query=bandara%20kertajati%20bdo" class="btn btn-primary">Lihat Maps<i class="arrow-icon fas fa-angle-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-flatty h-100">
          <div class="card-body">
            <img src="<?= BASE_UPLOAD_URL ?>default.jpg" class="card-img" alt="default">
            <h2 class="h4">Terminal Leuwipanjang</h2>
            <p>
              Pusat pemberangkatan bus Antar Kota Antar Provinsi (AKAP) dan Antar Kota Dalam Provinsi (AKDP).
            </p>
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
          </div>
          <div class="card-footer">
            <a
              href="https://www.google.com/maps/search/?api=1&query=Terminal%20Lewuipanjang%20bandung" class="btn btn-primary">Lihat Maps<i class="arrow-icon fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-flatty h-100">
          <div class="card-body">
            <img src="<?= BASE_UPLOAD_URL ?>default.jpg" class="card-img" alt="default">
            <h2 class="h4">Stasiun Hall/Kiaracondong</h2>
            <p>
              Gerbang utama jalur kereta api jarak jauh dan komuter (Lokal Bandung Raya).
            </p>
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
          </div>
          <div class="card-footer">
            <a
              href="https://www.google.com/maps/search/?api=1&query=statiun%20kiaracondong%20bandung"
              class="btn btn-primary">Lihat Maps<i class="arrow-icon fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-primary mx-auto" style="max-width: 440px">
      <span class="badge badge-accent mx-0 mb-4 fw-bold">
        <i class="fas fa-lightbulb me-2"></i>
        Tips
      </span>
      <p class="small mb-4">
        Informasi lebih lengkap mengenai layanan publik di Bandung bisa kamu akses disini
      </p>
      <a href="/trip#explore:3" class="btn btn-outline-white">Layanan Publik<i class="arrow-icon fas fa-angle-right ms-1"></i></a>
    </div>
  </div>
</main>