<?php
$page_title = "Kritik dan Saran";
?>
<script src="<?= JS_URL ?>feedback.js" defer></script>
<main id="content">
<div class="container">
    <section id="Kritik-dan-saran" class="text-center">
        <h1>Kritik & Saran</h1>
        <p class="lead">Bantu kami menjadi lebih baik dengan feedback Anda.</p>
    </section>
    <div id="feedback-successMsg" class="mx-auto d-none" style="max-width:320px">
        <div class="bg-gray py-5 px-5">
            <div class="text-center">
                <i class="fa-solid fa-circle-check fa-2x text-success"></i>
                <span class="h5">Terima Kasih!</span>
                <p class="text-muted">Kritik & saran Anda telah berhasil terkirim.</p>
                <div class="alert alert-light border mb-4 text-start">
                    <strong>Detail:</strong><br>
                    <small id="feedback-summaryDetail" class="text-dark"></small>
                </div>
                <button class="btn btn-outline-primary" onclick="location.reload()">
                    <i class="fa-solid fa-rotate-right me-2"></i>Kirim Feedback Lagi
                </button>
            </div>
        </div>
    </div>
    <div id="feedback-feedbackForm" class="mx-auto" style="max-width:440px">
        <div class="bg-light">
            <div class="card-body">
                <form id="feedback-feedbackFormMain">
                    <div class="row g-3">
                        <div class="col-12 mb-3" id="feedback-col-rating">
                            <label class="form-label mb-2"><i class="fa-solid fa-star me-2"></i>Skor Website (1-10)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range flex-grow-1" id="feedback-rating" name="rating" min="1" max="10" value="8">
                                <span id="feedback-ratingValue" class="badge bg-success fs-6 px-3 py-2 fw-bold">8</span>
                            </div>
                            <small class="text-muted">Rating Anda membantu kami prioritas perbaikan</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-2"><i class="fa-solid fa-tags me-2"></i>Kategori</label>
                            <select class="form-select" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="desain">Desain & UI/UX</option>
                                <option value="konten">Konten & Informasi</option>
                                <option value="fungsional">Fungsionalitas</option>
                                <option value="performance">Performance & Speed</option>
                                <option value="seo">SEO & Sharing</option>
                                <option value="mobile">Mobile Responsif</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Kritik & Kekurangan</label>
                            <textarea class="form-control" name="kritik" rows="4" placeholder="Apa yang perlu diperbaiki?" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-2"><i class="fa-solid fa-lightbulb me-2"></i>Saran Perbaikan</label>
                            <textarea class="form-control mb-5" name="saran" rows="4" placeholder="Fitur apa yang ingin ditambahkan?" required></textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="feedback-anonymous" name="anonymous">
                                <label class="text-muted" for="feedback-anonymous">
                                    Kirim sebagai anonim - centang untuk melanjutkan
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" id="feedback-submitBtn" disabled>
                            <span id="feedback-btnText">Kirim Feedback</span>
                            <i class="fa-solid fa-paper-plane ms-2"></i>
                            <i id="feedback-loadingSpinner" class="d-none fa-solid fa-circle-notch fa-spin ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</main>