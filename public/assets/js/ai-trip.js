(function () {
  const API_GROQ = BASE + "/api/map/api-groq-trip.php";
  const API_TRIP = BASE + "/api/map/api-trips.php";

  const waktuIcon = {
    Pagi: "fas fa-cloud",
    Siang: "fas fa-cloud-sun",
    Sore: "fas fa-cloud-moon",
  };

  let lastItinerary = null;

  function escHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function getPoiSlugs(days) {
    const slugs = [];
    days.forEach(day => {
      day.slots.forEach(slot => {
        if (slot.slug && !slugs.includes(slot.slug)) slugs.push(slot.slug);
      });
    });
    return slugs;
  }

  function renderTimeline(days, savedTripId = null) {
    const wrap = document.getElementById("aiItineraryResult");
    if (!days || !days.length) {
      wrap.innerHTML = `
      <div class="tp-empty-state">
      <i class="fas fa-wand-magic-sparkles"></i>
      <p>Tidak ada hasil. Coba input yang lebih spesifik.</p>
      </div>`;
      return;
    }

    const saveBtn = IS_LOGGED && !savedTripId ? `
    <div class="d-flex justify-content-center mb-4">
      <button class="btn btn-success" id="btnSaveItinerary">
        <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Itinerary
      </button>
    </div>` : !IS_LOGGED ? `
    <div class="d-flex justify-content-center mb-4">
      <button class="btn btn-outline-success" onclick="flattyToast('info', 'Login untuk menyimpan itinerary ini')">
        <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Itinerary
      </button>
    </div>` : '';

    wrap.innerHTML = saveBtn + days.map(day => `
      <div class="rounded-lg py-4 bg-surface mx-auto" style="max-width:740px">
      <div class="ai-day-block mb-4">
      <div class="ai-day-label mb-2">
      <span class="me-2 py-1 px-2 rounded-sm text-primary" style="background:var(--bg-primary-subtle)">
      <i class="fas fa-calendar-day"></i>
      </span>
      <span class="fw-bold">Hari ${day.day}</span>
      </div>
      <div class="ai-slots">
      ${day.slots.map((slot, idx) => `
        <div class="ai-slot d-flex gap-3 mb-3">
        <div class="ai-slot-line d-flex flex-column align-items-center">
        <div class="ai-slot-dot"></div>
        ${idx < day.slots.length - 1 ? '<div class="ai-slot-connector"></div>' : ""}
        </div>
        <div class="card bg-card shadow-lg flex-grow-1 mb-0" style="max-width: 440px">
        <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-2">
        <i class="${waktuIcon[slot.waktu] || "fas fa-clock"}" style="font-size:.85rem"></i>
        <span class="badge badge-accent">${escHtml(slot.waktu)}</span>
        <span class="text-muted small">${escHtml(slot.kategori)}</span>
        </div>
        <h5 class="mb-2">${escHtml(slot.nama)}</h5>
        <p class="text-muted small mb-3">${escHtml(slot.tips)}</p>
        <a href="${BASE}poi/${escHtml(slot.slug)}" class="btn btn-outline-primary" target="_blank" rel="noopener">
        Selengkapnya <i class="fa-solid fa-angle-right ms-1"></i>
        </a>
        </div>
        </div>
        </div>
        `).join("")}
      </div>
      </div>
      </div>
      `).join("");

    if (IS_LOGGED && !savedTripId) {
      document.getElementById("btnSaveItinerary")?.addEventListener("click", openSaveModal);
    }
  }

  function openSaveModal() {
    const existing = document.getElementById("aiSaveForm");
    if (existing) existing.remove();

    const form = document.createElement("div");
    form.id = "aiSaveForm";
    form.style.cssText = "position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem";
    form.innerHTML = `
    <div class="card bg-card shadow-lg" style="width:100%;max-width:400px;border-radius:1rem;overflow:hidden">
      <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
          <span class="badge badge-primary me-2"><i class="fas fa-floppy-disk"></i></span>
          <h5 class="mb-0 fw-semibold">Simpan Itinerary</h5>
        </div>
        <input type="text" id="aiTripTitle" class="form-control mb-3" placeholder="Nama itinerary (opsional)" value="Itinerary Bandungku">
        <div class="d-flex gap-2 justify-content-end">
          <button class="btn btn-outline-primary" id="btnCancelAiSave">Batalkan</button>
          <button class="btn btn-success" id="btnConfirmAiSave">
            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
          </button>
        </div>
      </div>
    </div>`;
    document.body.appendChild(form);
    document.getElementById("btnCancelAiSave").addEventListener("click", () => form.remove());
    document.getElementById("btnConfirmAiSave").addEventListener("click", saveItinerary);
  }

  async function saveItinerary() {
    if (!lastItinerary) return;
    const title = document.getElementById("aiTripTitle")?.value.trim() || "Itinerary Bandungku";
    const btn = document.getElementById("btnConfirmAiSave");
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
    try {
      await new Promise((r) => setTimeout(r, 1000));
      const res = await fetch(API_GROQ, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          action: "save",
          csrf_token: CSRF,
          title,
          ai_json: lastItinerary,
          poi_slugs: getPoiSlugs(lastItinerary.days),
        }),
      });
      const data = await res.json();
      if (data.success) {
        document.getElementById("aiSaveForm")?.remove();
        flattyToast("success", "Itinerary berhasil disimpan!");
        renderTimeline(lastItinerary.days, data.trip_id);
      } else {
        flattyToast("error", data.message ?? "Gagal menyimpan.");
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i>Simpan';
      }
    } catch (e) {
      flattyToast("error", "Gagal menyimpan itinerary.");
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i>Simpan';
    }
  }

  async function generateItinerary() {
    const input = document.getElementById("aiPromptInput").value.trim();
    if (!input) {
      flattyToast("warning", "toast.fill.first");
      return;
    }
    const btn = document.getElementById("btnGenerateAI");
    const result = document.getElementById("aiItineraryResult");
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
    result.innerHTML = "";
    lastItinerary = null;
    try {
      await new Promise((r) => setTimeout(r, 1000));
      const res = await fetch(API_GROQ, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          action: "generate",
          prompt: input,
          pois: POIS_FULL,
        }),
      });
      const data = await res.json();
      if (data.success) {
        lastItinerary = data.data;
        renderTimeline(data.data.days);
      } else {
        flattyToast("error", data.message ?? "Gagal membuat itinerary.");
        result.innerHTML = "";
      }
    } catch (e) {
      flattyToast("error", "Tidak bisa menghubungi AI. Coba lagi.");
    } finally {
      btn.disabled = false;
      btn.innerHTML = 'BUAT ITINERARY<i class="fas fa-wand-magic-sparkles ms-1"></i>';
    }
  }

  window.renderAiItinerary = async function(tripId) {
    const res = await fetch(`${API_GROQ}?action=get_ai&trip_id=${tripId}`, {
      headers: { "X-Requested-With": "XMLHttpRequest" }
    });
    const data = await res.json();
    if (data.success) {
      lastItinerary = data.data;
      const tabAi = document.querySelector('[data-tab="ai"]');
      tabAi?.click();
      setTimeout(() => renderTimeline(data.data.days, tripId), 100);
    } else {
      flattyToast("error", "Gagal memuat itinerary.");
    }
  };

  window.initAiTrip = function () {
    const btn = document.getElementById("btnGenerateAI");
    const input = document.getElementById("aiPromptInput");
    if (!btn || !input) return;
    btn.addEventListener("click", generateItinerary);
    input.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && e.shiftKey) {
        e.preventDefault();
        generateItinerary();
      }
    });
  };

  const aiTab = document.querySelector('[data-tab="ai"]');
  if (aiTab) {
    aiTab.addEventListener("click", function () {
      setTimeout(window.initAiTrip, 50);
    });
  }

  if (
    document.getElementById("tab-ai") &&
    document.getElementById("tab-ai").style.display !== "none"
  ) {
    window.initAiTrip();
  }

  const urlParams = new URLSearchParams(window.location.search);
  const autoPrompt = urlParams.get("ai_prompt");
  if (autoPrompt) {
    setTimeout(() => {
      const inputEl = document.getElementById("aiPromptInput");
      const tabAi = document.querySelector('[data-tab="ai"]');
      if (inputEl && tabAi) {
        inputEl.value = autoPrompt;
        tabAi.click();
        window.initAiTrip();
        setTimeout(generateItinerary, 200);
      }
    }, 500);
  }
})();