<div class="container">
  <section id="surprise-me" class="surprise-me-section mx-auto"  style="max-width:740px">
    <div class="text-center mb-4">
      <span class="text-eyebrow">Itinerary</span>
      <h2 class="text-hero">Surprise Me<em><i>!</i></em></h2>
      <p class="p-desc">Ceritain mau ngapain di Bandung, biar AI yang susunin itinerary-nya</p>
    </div>
    <div class="surprise-me-panel">
      <p class="fw-semibold small text-muted mb-1 text-center">Quick Picks</p>
      <div class="d-flex flex-wrap gap-0 mb-3 justify-content-center" id="homeAiChips">
        <span class="ai-chip badge badge-red sparkle-origin" data-val="Kuliner"><i class="fas fa-utensils me-1"></i> Kuliner</span>
        <span class="ai-chip badge badge-green sparkle-origin" data-val="Alam"><i class="fas fa-leaf me-1"></i> Alam</span>
        <span class="ai-chip badge badge-accent sparkle-origin" data-val="Belanja"><i class="fas fa-bag-shopping me-1"></i> Belanja</span>
        <span class="ai-chip badge badge-white sparkle-origin" data-val="Sejarah"><i class="fas fa-landmark me-1"></i> Sejarah</span>
        <span class="ai-chip badge badge-blue sparkle-origin" data-val="Budget"><i class="fas fa-wallet me-1"></i> Budget</span>
        <span class="ai-chip badge badge-primary sparkle-origin" data-val="Premium"><i class="fas fa-star me-1"></i> Premium</span>
      </div>
      <div class="d-flex justify-content-center flex-column">
        <div class="textarea-wrap">
          <textarea id="homeAiPrompt" class="form-control mb-4 mx-auto" rows="3" placeholder=" " style="resize:none;"></textarea>
          <div class="ticker-text">
            <span data-ticker="Buat itinerary kuliner Bandung 2hari..|Rute
            wisata alam Lembang seharian..|Itinerary belanja factory outlet
            Bandung..">Buat itinerary kuliner Bandung 2hari</span>
          </div>
        </div>
        <a id="btnHomeGenerate" class="btn btn-primary mx-auto">Buat Itinerary<i class="fa-solid fa-wand-magic-sparkles ms-1"></i></a>
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
      if (active) ta.value += (ta.value ? ' ' : '') + `(${active})`;
    });
  });
  document.getElementById('btnHomeGenerate').addEventListener('click', () => {
    const prompt = document.getElementById('homeAiPrompt').value.trim();
    if (!prompt) {
      if (typeof flattyToast === 'function') {
        flattyToast('warning', 'Tulis dulu rencanamu~');
      } else {
        alert('Tulis dulu rencanamu~');
      }
      return;
    }
    window.location.href = '/trips?ai_prompt=' + encodeURIComponent(prompt);
  });
})();
</script>