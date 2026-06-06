(function () {

  const API_GROQ = BASE + '/api/map/api-groq-trip.php';

  const waktuIcon = {
    'Pagi'  : 'fa-solid fa-sun',
    'Siang' : 'fa-solid fa-cloud-sun',
    'Sore'  : 'fa-solid fa-sunset',
  };

  function escHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;')
      .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function renderTimeline(days) {
    const wrap = document.getElementById('aiItineraryResult');
    if (!days || !days.length) {
      wrap.innerHTML = `
        <div class="tp-empty-state">
          <i class="fa-solid fa-compass"></i>
          <p>Tidak ada hasil. Coba input yang lebih spesifik.</p>
        </div>`;
      return;
    }

    wrap.innerHTML = days.map(day => `
      <div class="ai-day-block mb-4">
        <div class="ai-day-label mb-3">
          <i class="fa-solid fa-calendar-day me-2 text-purple"></i>
          <span>Hari ${day.day}</span>
        </div>
        <div class="ai-slots">
          ${day.slots.map((slot, idx) => `
            <div class="ai-slot d-flex gap-3 mb-3">
              <div class="ai-slot-line d-flex flex-column align-items-center">
                <div class="ai-slot-dot"></div>
                ${idx < day.slots.length - 1 ? '<div class="ai-slot-connector"></div>' : ''}
              </div>
              <div class="card card-flatty flex-grow-1 mb-0">
                <div class="card-body">
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="${waktuIcon[slot.waktu] || 'fa-solid fa-clock'} text-purple" style="font-size:.85rem"></i>
                    <span class="badge badge-accent">${escHtml(slot.waktu)}</span>
                    <span class="text-muted small">${escHtml(slot.kategori)}</span>
                  </div>
                  <h5 class="mb-1">${escHtml(slot.nama)}</h5>
                  <p class="text-muted small mb-2">${escHtml(slot.tips)}</p>
                  <a href="${BASE}pages/poi/${escHtml(slot.slug)}" class="small text-purple" target="_blank" rel="noopener">
                    Lihat detail <i class="fa-solid fa-angle-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `).join('');
  }

  async function generateItinerary() {
    const input = document.getElementById('aiPromptInput').value.trim();
    if (!input) {
      flattyToast('warning', 'Tulis dulu rencanamu');
      return;
    }

    const btn    = document.getElementById('btnGenerateAI');
    const result = document.getElementById('aiItineraryResult');
    const loader = document.getElementById('aiLoader');

    btn.disabled    = true;
    btn.innerHTML   = 'MEMBUAT <i class="fa-solid fa-circle-notch fa-spin ms-2"></i>';
    loader.style.display  = '';
    result.innerHTML      = '';

    try {
      const res  = await fetch(API_GROQ, {
        method  : 'POST',
        headers : { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body    : JSON.stringify({ prompt: input, pois: POIS_FULL }),
      });
      const data = await res.json();

      if (data.success) {
        renderTimeline(data.data.days);
      } else {
        flattyToast('error', data.message ?? 'Gagal generate itinerary.');
        result.innerHTML = '';
      }
    } catch (e) {
      flattyToast('error', 'Tidak bisa menghubungi AI. Coba lagi.');
    } finally {
      btn.disabled  = false;
      btn.innerHTML = 'BUAT ITINERARY<i class="fa-solid fa-wand-magic-sparkles ms-2"></i>';
      loader.style.display = 'none';
    }
  }

  window.initAiTrip = function() {
    const btn   = document.getElementById('btnGenerateAI');
    const input = document.getElementById('aiPromptInput');
    if (!btn || !input) return;
    btn.addEventListener('click', generateItinerary);
    input.addEventListener('keydown', e => {
      if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault();
        generateItinerary();
      }
    });
    document.querySelectorAll('.ai-chip').forEach(chip => {
      chip.addEventListener('click', function () {
        this.classList.toggle('active');
        const active = [...document.querySelectorAll('.ai-chip.active')]
          .map(c => c.dataset.val).join(', ');
        if (active) {
          input.value = input.value.replace(/\(.*?\)/g, '').trim();
          input.value += ` (${active})`;
        }
      });
    });
  }

  // init setelah tab AI aktif
  const aiTab = document.querySelector('[data-tab="ai"]');
  if (aiTab) {
    aiTab.addEventListener('click', function () {
      setTimeout(window.initAiTrip, 50);
    });
  }

  // fallback kalau langsung landing di tab ai
  if (document.getElementById('tab-ai') &&
      document.getElementById('tab-ai').style.display !== 'none') {
    window.initAiTrip();
  }

  // auto trigger dari home
  const urlParams  = new URLSearchParams(window.location.search);
  const autoPrompt = urlParams.get('ai_prompt');
  if (autoPrompt) {
    setTimeout(() => {
      const inputEl = document.getElementById('aiPromptInput');
      const tabAi   = document.querySelector('[data-tab="ai"]');
      if (inputEl && tabAi) {
        inputEl.value = autoPrompt;
        tabAi.click();
        window.initAiTrip();
        setTimeout(generateItinerary, 200);
      }
    }, 500);
  }

})();