<section id="ai-surprise-me" class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="text-center mb-4">
        <p class="eyebrow text-muted mb-2">AI-Powered</p>
        <h2>Rencanakan Tripmu dengan AI</h2>
        <p class="text-muted">Ceritain mau ngapain di Bandung, biar AI yang susunin itinerary-nya</p>
      </div>

      <div class="d-flex flex-wrap gap-2 mb-3 justify-content-center" id="homeAiChips">
        <span class="ai-chip badge badge-accent" data-val="Kuliner">🍜 Kuliner</span>
        <span class="ai-chip badge badge-accent" data-val="Alam">🌿 Alam</span>
        <span class="ai-chip badge badge-accent" data-val="Belanja">🛍 Belanja</span>
        <span class="ai-chip badge badge-accent" data-val="Sejarah">🏛 Sejarah</span>
        <span class="ai-chip badge badge-accent" data-val="Budget">💰 Budget</span>
        <span class="ai-chip badge badge-accent" data-val="Premium">✨ Premium</span>
      </div>

      <textarea id="homeAiPrompt" class="form-control mb-2" rows="3"
        placeholder="Contoh: trip Bandung 2 hari, suka kuliner dan alam..."
        style="resize:none"></textarea>

      <a id="btnHomeGenerate" class="btn btn-primary w-100">
        <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Buat Itinerary
      </a>

    </div>
  </div>
</section>

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