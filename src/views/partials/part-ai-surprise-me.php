<div class="container">
  <section id="surprise-me" class="surprise-me-section mx-auto">
    <div class="text-center mb-4">
      <span class="text-eyebrow" data-bhs="it.eyebrow">Itinerary</span>
      <h2 class="text-hero"><span data-bhs="it.title">Buatin Dong</span><em><i>!</i></em></h2>
      <p class="p-desc" data-bhs="it.excerpt">
        Ceritain mau ngapain di Bandung, biar AI yang susunin itinerary-nya
      </p>
    </div>
    <div class="surprise-me-panel">
      <p class="fw-semibold small text-muted mb-1 text-center" data-bhs="it.qp">
        Pilih Cepat
      </p>
      <div class="d-flex flex-wrap gap-0 mb-3 justify-content-center" id="homeAiChips">
        <span class="ai-chip badge badge-red sparkle-origin" data-bhs-val="it.qp.1.val" data-val="Kuliner"><i class="fas fa-utensils me-1"></i><span data-bhs="it.qp.1">Kuliner</span></span>
        <span class="ai-chip badge badge-green sparkle-origin" data-bhs-val="it.qp.2.val" data-val="Alam"><i class="fas fa-leaf me-1"></i><span data-bhs="it.qp.2">Alam</span></span>
        <span class="ai-chip badge badge-accent sparkle-origin" data-bhs-val="it.qp.3.val" data-val="Belanja"><i class="fas fa-bag-shopping me-1"></i><span data-bhs="it.qp.3">Belanja</span></span>
        <span class="ai-chip badge badge-white sparkle-origin" data-bhs-val="it.qp.4.val" data-val="Sejarah"><i class="fas fa-landmark me-1"></i><span data-bhs="it.qp.4">Sejarah</span></span>
        <span class="ai-chip badge badge-blue sparkle-origin" data-bhs-val="it.qp.5.val" data-val="Budget"><i class="fas fa-wallet me-1"></i><span data-bhs="it.qp.5">Budget</span></span>
        <span class="ai-chip badge badge-primary sparkle-origin" data-bhs-val="it.qp.6.val" data-val="Premium"><i class="fas fa-star me-1"></i><span data-bhs="it.qp.6">Premium</span></span>
      </div>
      <div class="d-flex justify-content-center flex-column">
        <div class="textarea-wrap">
          <textarea id="homeAiPrompt" class="form-control mb-4 mx-auto" rows="3" placeholder=" " style="resize:none;" aria-label="Susun itinerary pakai AI"></textarea>
          <div class="ticker-text">
            <span data-bhs="it.ticker" data-ticker="Buat itinerary kuliner Bandung 2 hari..|Rute wisata alam Lembang seharian..|Itinerary belanja factory outlet Bandung..">Buat itinerary kuliner Bandung 2 hari</span>
          </div>
        </div>
        <a id="btnHomeGenerate" class="btn btn-primary mx-auto"><span data-bhs="btn.it">Buat Itinerary</span><i class="fas fa-wand-magic-sparkles ms-1"></i></a>
      </div>
    </div>
  </section>
</div>
<script>
  (function () {
    document.querySelectorAll('#homeAiChips .ai-chip').forEach(chip => {
      chip.addEventListener('click', function () {
        this.classList.toggle('active');
        const active = [...document.querySelectorAll('#homeAiChips .ai-chip.active')]
        .map(c => c.dataset.val).join(', ');
        const ta = document.getElementById('homeAiPrompt');
        ta.value = ta.value.replace(/\s*\(.*?\)/g, '').trim();
        if (active) ta.value += (ta.value ? ' ': '') + `(${active})`;
      });
    });
    document.getElementById('btnHomeGenerate').addEventListener('click', () => {
      const prompt = document.getElementById('homeAiPrompt').value.trim();
      if (!prompt) {
        if (typeof flattyToast === 'function') {
          flattyToast('warning', 'toast.fill.first');
        } else {
          alert('Tulis dulu rencanamu');
        }
        return;
      }
      window.location.href = '/trips?ai_prompt=' + encodeURIComponent(prompt);
    });
  })();
</script>