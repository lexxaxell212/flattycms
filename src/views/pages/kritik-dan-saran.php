<?php
$page_title = "Kritik dan Saran";
?>
<script src="<?= JS_URL ?>feedback.js" defer></script>
<main class="main-content">
  <div class="container">
    <div class="row g-4 page-header">
      <div class="col-12 col-lg-4">
        <div class="sticky-wrapper text-center">
          <div class="page-header-svg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">
              <defs>
                <style>
                  .envelope-body {
                    fill: var(--icon-3);
                  }
                  .envelope-flap {
                    fill: var(--icon-1);
                  }
                  .envelope-fold {
                    fill: var(--icon-2);
                  }
                  .paper {
                    fill: var(--icon-2);
                  }
                  .mail-lines {
                    stroke: var(--icon-5);
                  }
                  .dot-primary {
                    fill: var(--bg-primary);
                  }
                  .dot-accent {
                    fill: var(--bg-primary-subtle);
                  }
                  @keyframes float {
                    0%, 100% {
                      transform: translateY(0px);
                    }
                    50% {
                      transform: translateY(-10px);
                    }
                  }
                  @keyframes paper-out {
                    0%, 30% {
                      transform: translateY(0);
                      opacity: 0;
                    }
                    40% {
                      opacity: 1;
                    }
                    60%, 80% {
                      transform: translateY(-38px);
                      opacity: 1;
                    }
                    90%,100% {
                      transform: translateY(-38px);
                      opacity: 0;
                    }
                  }
                  @keyframes fly {
                    0%, 70% {
                      transform: translate(0, 0) rotate(0deg);
                      opacity: 1;
                    }
                    85% {
                      transform: translate(60px, -30px) rotate(8deg);
                      opacity: 1;
                    }
                    95%,100% {
                      transform: translate(120px, -55px) rotate(12deg);
                      opacity: 0;
                    }
                  }
                  @keyframes trail-dot {
                    0%, 70% {
                      opacity: 0;
                      transform: translateX(0);
                    }
                    75% {
                      opacity: 1;
                    }
                    90%,100% {
                      opacity: 0;
                      transform: translateX(30px);
                    }
                  }
                  @keyframes sparkle {
                    0%, 60% {
                      opacity: 0;
                      transform: scale(0);
                    }
                    70% {
                      opacity: 1;
                      transform: scale(1);
                    }
                    80% {
                      opacity: 0;
                      transform: scale(1.3);
                    }
                    100% {
                      opacity: 0;
                    }
                  }
                  @keyframes line-draw {
                    0% {
                      stroke-dashoffset: 60;
                    }
                    40% {
                      stroke-dashoffset: 0;
                    }
                    100% {
                      stroke-dashoffset: 0;
                    }
                  }
                  .g-envelope {
                    animation: float 3s ease-in-out infinite, fly 4s ease-in-out 1s infinite;
                    transform-origin: 200px 160px;
                  }
                  .paper-sheet {
                    animation: paper-out 4s ease-in-out 1s infinite;
                    transform-origin: 200px 150px;
                  }
                  .line1 {
                    stroke-dasharray: 60;
                    animation: line-draw 4s ease 1s infinite;
                  }
                  .line2 {
                    stroke-dasharray: 45;
                    animation: line-draw 4s ease 1.2s infinite;
                  }
                  .line3 {
                    stroke-dasharray: 30;
                    animation: line-draw 4s ease 1.4s infinite;
                  }
                  .trail1 {
                    animation: trail-dot 4s ease 1s infinite;
                  }
                  .trail2 {
                    animation: trail-dot 4s ease 1.15s infinite;
                  }
                  .trail3 {
                    animation: trail-dot 4s ease 1.3s infinite;
                  }
                  .sp1 {
                    animation: sparkle 4s ease 1.8s infinite;
                    transform-origin: 290px 90px;
                  }
                  .sp2 {
                    animation: sparkle 4s ease 2s infinite;
                    transform-origin: 310px 110px;
                  }
                  .sp3 {
                    animation: sparkle 4s ease 1.9s infinite;
                    transform-origin: 275px 75px;
                  }
                </style>
              </defs>
              <g class="paper-sheet">
                <rect x="170" y="118" width="60" height="72" rx="4" class="paper" />
                <line x1="180" y1="136" x2="220" y2="136" class="mail-lines line1" />
                <line x1="180" y1="148" x2="215" y2="148" class="mail-lines line2" />
                <line x1="180" y1="160" x2="208" y2="160" class="mail-lines line3" />
              </g>
              <g class="g-envelope">
                <!-- envelope body -->
                <rect x="148" y="155" width="104" height="72" rx="6" class="envelope-body" />
                <!-- envelope bottom fold lines -->
                <polygon points="148,227 200,193 252,227" class="envelope-fold" />
                <!-- envelope flap (open) -->
                <polygon points="148,155 200,188 252,155" class="envelope-flap" />
                <!-- left fold -->
                <polygon points="148,155 148,227 183,193" fill="var(--icon-2)" opacity="0.5" />
                <!-- right fold -->
                <polygon points="252,155 252,227 217,193" fill="var(--icon-2)" opacity="0.5" />
              </g>
              <circle cx="258" cy="152" r="4" class="dot-primary trail1" />
              <circle cx="270" cy="145" r="3" class="dot-accent trail2" />
              <circle cx="280" cy="138" r="2.5" class="dot-primary trail3" />
              <g class="sp1">
                <line x1="287" y1="86" x2="287" y2="94" stroke="var(--dotid,#7c3aed)" stroke-width="2" stroke-linecap="round" />
                <line x1="283" y1="90" x2="291" y2="90" stroke="var(--dotid,#7c3aed)" stroke-width="2" stroke-linecap="round" />
              </g>
              <g class="sp2">
                <line x1="308" y1="107" x2="308" y2="113" stroke="#d4aaff" stroke-width="2" stroke-linecap="round" />
                <line x1="305" y1="110" x2="311" y2="110" stroke="#d4aaff" stroke-width="2" stroke-linecap="round" />
              </g>
              <g class="sp3">
                <line x1="273" y1="72" x2="273" y2="78" stroke="#d4aaff" stroke-width="1.5" stroke-linecap="round" />
                <line x1="270" y1="75" x2="276" y2="75" stroke="#d4aaff" stroke-width="1.5" stroke-linecap="round" />
              </g>
              <circle cx="120" cy="120" r="5" class="dot-accent" opacity="0.4" />
              <circle cx="310" cy="200" r="4" class="dot-primary" opacity="0.3" />
              <circle cx="340" cy="155" r="3" class="dot-accent" opacity="0.3" />
              <circle cx="95" cy="185" r="3" class="dot-primary" opacity="0.25" />
            </svg>
          </div>
          <h1><em class="styled" data-bhs="fb.title">Kritik & Saran</em></h1>
          <p class="lead" data-bhs="fb.excerpt">
            Bantu kami menjadi lebih baik dengan feedback Anda.
          </p>
        </div>
      </div>
      <div class="col-12 col-lg-8">
        <div id="feedback-successMsg" class="mx-auto mb-4 d-none" style="max-width:440px">
          <div class="d-flex justify-content-center flex-column">
            <span class="h4 mb-2">
              <i class="fa-solid fa-circle-check me-1"></i><span data-bhs="fb.ty">Terima Kasih!</span></span>
            <span class="small mb-4" data-bhs="fb.ty.message">Kritik & saran Anda telah berhasil terkirim.</span>
            <div class="mb-4 text-start">
              <strong data-bhs="fb.label.detail">Detail:</strong><br>
              <small id="feedback-summaryDetail" class="text-dark"></small>
            </div>
            <button class="btn btn-primary mx-auto" onclick="location.reload()">
              <span data-bhs="btn.send.again">Kirim Lagi</span>
              <i class="fa-solid fa-rotate-right me-2"></i>
            </button>
          </div>
        </div>
        <div id="feedback-feedbackForm" class="mx-auto">
          <form id="feedback-feedbackFormMain">
            <div class="row g-3">
              <div class="col-12" id="feedback-col-rating">
                <label class="form-label">
                  <i class="fa-solid fa-star me-2"></i><span data-bhs="form.score.label">Skor Website (1-10)</span>
                </label>
                <div class="d-flex align-items-center gap-3">
                  <input type="range" class="form-range flex-grow-1"
                  id="feedback-rating" name="rating" min="1" max="10" value="8">
                  <span id="feedback-ratingValue" class="badge bg-success fs-6 px-3 py-2 fw-bold">8</span>
                </div>
                <small class="text-muted" data-bhs="form.score.desc">Rating Anda membantu kami prioritas perbaikan</small>
              </div>
              <div class="col-12">
                <label class="form-label">
                  <i class="fa-solid fa-tags me-2"></i><span data-bhs="form.cat.label">Kategori</span>
                </label>
                <select class="form-select" name="kategori" required>
                  <option value="" data-bhs="form.cat.select">Pilih kategori</option>
                  <option value="desain" data-bhs="form.cat.1">Desain & UI/UX</option>
                  <option value="konten" data-bhs="form.cat.2">Konten & Informasi</option>
                  <option value="fungsional" data-bhs="form.cat.3">Fungsionalitas</option>
                  <option value="performance" data-bhs="form.cat.4">Performance & Speed</option>
                  <option value="seo" data-bhs="form.cat.5">SEO & Sharing</option>
                  <option value="mobile" data-bhs="form.cat.6">Mobile Responsif</option>
                  <option value="lainnya" data-bhs="form.cat.7">Lainnya</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i><span data-bhs="form.kritik.label">Kritik & Kekurangan</span>
                </label>
                <textarea class="form-control bg-white" name="kritik" rows="4" placeholder="Tulis kritik dan kekurangan.." required></textarea>
              </div>
              <div class="col-12 mb-4">
                <label class="form-label">
                  <i class="fa-solid fa-lightbulb me-2"></i><span data-bhs="form.saran.label">Saran Perbaikan</span>
                </label>
                <textarea class="form-control bg-white" name="saran" rows="4" placeholder="Tulis saran perbaikanmu.." required></textarea>
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"
                  id="feedback-anonymous" name="anonymous">
                  <label class="text-muted small" for="feedback-anonymous" data-bhs="form.anonymous">
                    Kirim sebagai anonim - centang untuk melanjutkan
                  </label>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary" id="feedback-submitBtn" disabled>
                  Kirim <i class="fas fa-paper-plane ms-1"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>