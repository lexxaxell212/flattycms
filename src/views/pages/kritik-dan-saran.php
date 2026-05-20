<?php
$page_title = "Kritik dan Saran";
//
?>
<script src="<?= JS_URL ?>feedback.js" defer></script>
<main id="content" class="container-fluid">
<div class="container">
    <section id="Kritik-dan-saran">
        <h1 class="text-title">Kritik & Saran</h1>
        <p class="lead">Bantu kami menjadi lebih baik dengan feedback Anda.</p>
    </section>
    <div id="feedback-successMsg" class="mx-auto mb-5 d-none" style="max-width:320px">
        <div class="bg-gray py-5 px-5">
            <div class="text-center">
                <i class="fas fa-check-circle fa-2x text-success"></i>
                <span class="h5">Terima Kasih!</span>
                <p class="text-muted">Kritik & saran Anda telah berhasil terkirim.</p>
                <div class="alert alert-light border mb-4 text-start">
                    <strong>Detail:</strong><br>
                    <small id="feedback-summaryDetail" class="text-dark"></small>
                </div>
                <button class="btn btn-outline-primary" onclick="location.reload()">
                    <i class="fas fa-redo me-2"></i>Kirim Feedback Lagi
                </button>
            </div>
        </div>
    </div>
    <div id="feedback-feedbackForm" class="mx-auto py-5" style="max-width:600px">
        <div class="bg-gray">
            <div class="card-body">
                <form id="feedback-feedbackFormMain">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="feedback-nama" name="nama" placeholder="Nama" required>
                                <label for="feedback-nama"><i class="fas fa-user me-2"></i>Nama</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="feedback-email" name="email" placeholder="Email" required>
                                <label for="feedback-email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <label class="form-label fw-medium"><i class="fas fa-star me-2"></i>Skor Website (1-10)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range flex-grow-1" id="feedback-rating" name="rating" min="1" max="10" value="8">
                                <span id="feedback-ratingValue" class="badge bg-success fs-6 px-3 py-2 fw-bold">8</span>
                            </div>
                            <small class="text-muted">Rating Anda membantu kami prioritas perbaikan</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-medium"><i class="fas fa-tags me-2"></i>Kategori</label>
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
                            <label class="form-label fw-medium"><i class="fas fa-exclamation-triangle me-2"></i>Kritik & Kekurangan</label>
                            <textarea class="form-control" name="kritik" rows="4" placeholder="Apa yang perlu diperbaiki?" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><i class="fas fa-lightbulb me-2"></i>Saran Perbaikan</label>
                            <textarea class="form-control" name="saran" rows="4" placeholder="Fitur apa yang ingin ditambahkan?" required></textarea>
                        </div>
                        <div class="p-5">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span id="feedback-btnText">Kirim Feedback</span>
                                <span id="feedback-loadingSpinner" class="d-none ms-2">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</main>
<?php
// ?>