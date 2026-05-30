(function () {
  const BASE    = CONFIG.baseUrl;
  const CSRF    = CONFIG.csrfToken;
  const API     = BASE + '/api/api-user-profile.php';

  let data      = null;
  let activeTab = 'photos';

  // ── LOAD DATA ────────────────────────────────────────────
  async function loadProfile() {
    try {
      const res  = await fetch(API, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (!json.success) return;

      data = json;

      const pc = json.photos.length;
      const tc = json.trips.length;
      const rc = json.reactions.length;

      document.getElementById('statPhotos').textContent    = pc;
      document.getElementById('statTrips').textContent     = tc;
      document.getElementById('statReactions').textContent = rc;
      document.getElementById('countPhotos').textContent   = pc;
      document.getElementById('countTrips').textContent    = tc;
      document.getElementById('countReactions').textContent = rc;

      document.getElementById('tabLoading').style.display = 'none';
      renderTab(activeTab);

    } catch(e) {
      document.getElementById('tabLoading').innerHTML = '<div class="text-muted small text-center py-4">Gagal memuat data</div>';
    }
  }

  // ── TABS ─────────────────────────────────────────────────
  document.querySelectorAll('#profileTabs .up-tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('#profileTabs .up-tab-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      activeTab = this.dataset.tab;
      if (data) renderTab(activeTab);
    });
  });

  function renderTab(tab) {
    document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
    document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).style.display = '';
    if (tab === 'photos')    renderPhotos();
    if (tab === 'trips')     renderTrips();
    if (tab === 'reactions') renderReactions();
  }

  // ── RENDER FOTO ──────────────────────────────────────────
  function renderPhotos() {
    const grid = document.getElementById('photoGrid');
    if (!data.photos.length) {
      grid.innerHTML = emptyState('fa-images', 'Belum ada foto yang diupload');
      return;
    }
    grid.innerHTML = data.photos.map(p => `
      <div class="col-6 col-md-4" id="photo-${p.id}">
        <div class="card card-flatty h-100 overflow-hidden">
          <div class="position-relative" style="padding-top:75%">
            <img src="${BASE}/uploads/${p.photo_path}"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit:cover" loading="lazy">
          </div>
          <div class="card-body">
            <div class="small fw-semibold text-truncate mb-1">${p.poi_name}</div>
            ${p.caption ? `<div class="text-muted text-truncate" style="font-size:.72rem">${p.caption}</div>` : ''}
            <div class="text-muted mt-1 mb-2" style="font-size:.68rem">${formatDate(p.created_at)}</div>
            <button class="btn btn-danger btn-sm w-100" onclick="deleteContrib('photo', ${p.id})">
              <i class="fa-solid fa-trash fa-xs me-1"></i>Hapus
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }

  // ── RENDER TRIPS ─────────────────────────────────────────
  function renderTrips() {
    const list = document.getElementById('tripList');
    if (!data.trips.length) {
      list.innerHTML = emptyState('fa-route', 'Belum ada trip tersimpan');
      return;
    }
    list.innerHTML = data.trips.map(t => `
      <div class="card card-flatty mb-3" id="trip-${t.id}">
        <div class="card-body">
          <div class="fw-semibold mb-1 text-truncate">${t.title}</div>
          <div class="small text-muted mb-2">
            <i class="fa-solid fa-location-dot me-1 text-purple"></i>${t.start_point_name}
            <span class="mx-1">·</span>
            <i class="fa-solid fa-map-pin me-1 text-purple"></i>${t.total_stops} stop
            ${t.total_distance ? `<span class="mx-1">·</span><i class="fa-solid fa-ruler me-1"></i>${t.total_distance} km` : ''}
            ${t.duration ? `<span class="mx-1">·</span><i class="fa-solid fa-clock me-1"></i>~${t.duration} menit` : ''}
          </div>
          <div class="text-muted mb-3" style="font-size:.7rem">${formatDate(t.created_at)}</div>
          <div class="d-flex gap-2">
            <a href="/trip" class="btn btn-primary btn-sm">
              <i class="fa-solid fa-map me-1"></i>Buka di Map
            </a>
            <button class="btn btn-danger btn-sm" onclick="deleteContrib('trip', ${t.id})">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }

  // ── RENDER REACTIONS ─────────────────────────────────────
  function renderReactions() {
    const list = document.getElementById('reactionList');
    if (!data.reactions.length) {
      list.innerHTML = emptyState('fa-heart', 'Belum ada reaksi');
      return;
    }

    const typeLabel = { blog: 'Blog', page: 'Halaman', place: 'Tempat', event: 'Event' };
    const typeIcon  = { blog: 'fa-newspaper', page: 'fa-file', place: 'fa-map-pin', event: 'fa-calendar' };

    list.innerHTML = data.reactions.map(r => `
      <div class="card card-flatty mb-3" id="reaction-${r.id}">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
               style="width:40px;height:40px;background:oklch(0.95 0.04 295)">
            <i class="fa-solid ${typeIcon[r.content_type] ?? 'fa-heart'} text-purple"></i>
          </div>
          <div class="flex-grow-1 min-w-0">
            <div class="small fw-semibold text-truncate">${r.content_title ?? `${typeLabel[r.content_type] ?? r.content_type} #${r.content_id}`}</div>
            <div class="text-muted" style="font-size:.7rem">${formatDate(r.created_at)}</div>
          </div>
          <button class="btn btn-danger btn-sm flex-shrink-0" onclick="deleteContrib('reaction', ${r.id})">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>
    `).join('');
  }

  // ── DELETE ────────────────────────────────────────────────
  window.deleteContrib = async function(type, id) {
    const labels = { photo: 'foto', trip: 'trip', reaction: 'reaksi' };
    flattyConfirm(`Hapus ${labels[type]} ini? Tindakan ini permanen.`, async () => {
      const fd = new FormData();
      fd.append('action',     `delete_${type}`);
      fd.append('csrf_token', CSRF);
      fd.append(`${type}_id`, id);

      try {
        const res  = await fetch(API, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const json = await res.json();

        if (json.success) {
          document.getElementById(`${type}-${id}`)?.remove();
          const key = type === 'photo' ? 'photos' : type === 'trip' ? 'trips' : 'reactions';
          data[key] = data[key].filter(i => i.id !== id);
          const count = data[key].length;
          const capKey = key.charAt(0).toUpperCase() + key.slice(1);
          document.getElementById('stat' + capKey).textContent  = count;
          document.getElementById('count' + capKey).textContent = count;
          flattyToast('success', `${labels[type].charAt(0).toUpperCase() + labels[type].slice(1)} dihapus!`);
        } else {
          flattyToast('error', json.message ?? 'Gagal menghapus.');
        }
      } catch(e) {
        flattyToast('error', 'Tidak bisa menghubungi server.');
      }
    });
  };

  // ── EDIT NAMA ────────────────────────────────────────────
  document.getElementById('btnEditName').addEventListener('click', () => {
    document.getElementById('editNameForm').style.display = '';
    document.getElementById('btnEditName').style.display  = 'none';
    document.getElementById('inputDisplayName').focus();
  });

  document.getElementById('btnCancelName').addEventListener('click', () => {
    document.getElementById('editNameForm').style.display = 'none';
    document.getElementById('btnEditName').style.display  = '';
  });

  document.getElementById('btnSaveName').addEventListener('click', async () => {
    const name = document.getElementById('inputDisplayName').value.trim();
    if (!name) { flattyToast('warning', 'Nama tidak boleh kosong.'); return; }

    const btn = document.getElementById('btnSaveName');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
    btn.disabled  = true;

    const fd = new FormData();
    fd.append('action',       'update_name');
    fd.append('csrf_token',   CSRF);
    fd.append('display_name', name);

    try {
      const res  = await fetch(API, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
      const json = await res.json();

      if (json.success) {
        document.getElementById('displayNameText').textContent = name;
        document.getElementById('editNameForm').style.display  = 'none';
        document.getElementById('btnEditName').style.display   = '';
        flattyToast('success', 'Nama diperbarui!');
      } else {
        flattyToast('error', json.message ?? 'Gagal memperbarui nama.');
      }
    } catch(e) {
      flattyToast('error', 'Tidak bisa menyimpan nama.');
    } finally {
      btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>Simpan';
      btn.disabled  = false;
    }
  });

  document.getElementById('inputDisplayName').addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('btnSaveName').click();
  });

  // ── HELPERS ──────────────────────────────────────────────
  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
  }

  function emptyState(icon, text) {
    return `<div class="text-center text-muted py-5 col-12">
      <i class="fa-solid ${icon} fa-2x mb-3 d-block opacity-25"></i>
      <div class="small">${text}</div>
    </div>`;
  }

  loadProfile();

})();