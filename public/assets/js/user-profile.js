(function () {
  const BASE = CONFIG.baseUrl;
  const CSRF = CONFIG.csrfToken;
  const API = BASE + '/api/api-user-profile.php';

  let data = null;
  let activeTab = 'photos';

  // ── LOAD DATA ────────────────────────────────────────────
  async function loadProfile() {
    try {
      await new Promise(r => setTimeout(r, 1000));
      const res = await fetch(API, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const json = await res.json();
      if (!json.success) return;

      data = json;

      const pc = json.photos.length;
      const tc = json.trips.length;
      const rc = json.reactions.length;

      document.getElementById('statPhotos').textContent = pc;
      document.getElementById('statTrips').textContent = tc;
      document.getElementById('statReactions').textContent = rc;
      document.getElementById('countPhotos').textContent = pc;
      document.getElementById('countTrips').textContent = tc;
      document.getElementById('countReactions').textContent = rc;

      document.getElementById('tabLoading').style.display = 'none';
      renderTab(activeTab);

    } catch(e) {
      document.getElementById('tabLoading').innerHTML = '<span class="badge badge-red"><i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data</span>';
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
    if (tab === 'photos') renderPhotos();
    if (tab === 'trips') renderTrips();
    if (tab === 'reactions') renderReactions();
  }

  // ── RENDER FOTO ──────────────────────────────────────────
  function renderPhotos() {
    const grid = document.getElementById('photoGrid');
    if (!data.photos.length) {
      grid.innerHTML = emptyState('fa-image', 'Belum ada foto yang diupload');
      return;
    }
    grid.innerHTML = data.photos.map(p => `
      <div class="col-12 col-md-6" id="photo-${p.id}">
      <div class="card card-flatty h-100">
      <div class="card-body">
      <img src="${BASE}/uploads/${p.photo_path}" class="card-img" loading="lazy">
      <div class="text-truncate">
      <h2 class="h4">${p.poi_name}</h2>
      </div>
      ${p.caption ? `<div class="mb-2"><i class="fas fa-link me-1"></i>${p.caption}</div>`: ''}
      <div class="text-muted small">
      <span>Diupload pada : <strong>${formatDate(p.created_at)}</strong>
      </span>
      </div>
      </div>
      <div class="card-footer">
      <button class="btn btn-danger" onclick="deleteContrib('photo', ${p.id})">Hapus<i class="fa-solid fa-trash ms-2"></i>
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
    list.innerHTML = data.trips.map(t => {
      const isAi = t.source === 'itinerary';
      const badge = isAi
        ? `<span class="badge badge-accent me-2" style="font-size:.7rem"><i class="fas fa-wand-magic-sparkles me-1"></i>Itinerary</span>`
        : `<span class="badge badge-blue me-2" style="font-size:.7rem"><i class="fas fa-route me-1"></i>Classic</span>`;
      const openBtn = isAi
        ? `<a href="/trip?open_ai=${t.id}" class="btn btn-primary btn-fit">Buka<i class="fa-solid fa-wand-magic-sparkles ms-2"></i></a>`
        : `<a href="/trip?open_trip=${t.id}" class="btn btn-primary btn-fit">Buka<i class="fa-solid fa-route ms-2"></i></a>`;
      return `
      <div class="col-12 col-md-6" id="trip-${t.id}">
      <div class="card card-flatty h-100">
      <div class="card-body">
      <div class="d-flex align-items-center">
      ${badge}
      <span class="h4" style="margin:0.5rem;padding:0.5rem 0;">${t.title}</span>
      </div>
      <div class="row g-1 mb-2">
      ${!isAi ? `<span class="small p-1"><i class="fa-solid fa-location-dot me-2"></i>Mulai dari : <strong>${t.start_point_name}</strong></span>` : ''}
      <span class="small p-1"><i class="fa-solid fa-map-pin me-2"></i><strong>${t.total_stops}</strong> lokasi</span>
      ${!isAi && t.total_distance ? `<span class="small p-1"><i class="fa-solid fa-ruler me-2"></i>Total jarak : <strong>${t.total_distance}</strong> km</span>` : ''}
      ${!isAi && t.duration ? `<span class="small p-1"><i class="fa-solid fa-clock me-2"></i><strong>${t.duration}</strong> menit</span>` : ''}
      </div>
      <div class="text-muted small mb-2">
      <span>Dibuat pada : <strong>${formatDate(t.created_at)}</strong>
      </span>
      </div>
      </div>
      <div class="card-footer d-flex gap-2">
      ${openBtn}
      <button class="btn btn-danger btn-fit" onclick="deleteContrib('trip', ${t.id})">Hapus<i class="fa-solid fa-trash ms-2"></i></button>
      </div>
      </div>
      </div>`;
    }).join('');
  }

  // ── RENDER REACTIONS ─────────────────────────────────────
  function renderReactions() {
    const list = document.getElementById('reactionList');
    if (!data.reactions.length) {
      list.innerHTML = emptyState('fa-heart', 'Belum ada reaksi');
      return;
    }

    const typeLabel = {
      blog: 'Blog',
      page: 'Halaman',
      place: 'POI',
      event: 'Event'
    };
    const typeIcon = {
      blog: 'fa-newspaper',
      page: 'fa-bullhorn',
      place: 'fa-map-location-dot',
      event: 'fa-bullhorn'
    };

    list.innerHTML = data.reactions.map(r => `
      <div class="col-12 col-md-6" id="reaction-${r.id}">
      <div class="card card-flatty h-100">
      <div class="card-body">
      <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:40px;height:40px;background: var(--bg-primary-subtle)">
      <i class="fa-solid ${typeIcon[r.content_type] ?? 'fa-heart'} text-purple mx-auto"></i>
      </div>
      <div class="mb-2">
        <h2 class="h4">
          ${r.content_title ?? `${typeLabel[r.content_type] ?? r.content_type} #${r.content_id}`}
        </h2>
      </div>
      <div class="text-muted mb-2 small">
        <span>Kamu memberi <i class="fas fa-heart text-danger"></i> pada :  <strong>${formatDate(r.created_at)}</strong></span>
      </div>
      </div>
      <div class="card-footer">
      <button class="btn btn-danger" onclick="deleteContrib('reaction', ${r.id})"> Hapus
      <i class="fa-solid fa-trash ms-2"></i>
      </button>
      </div>
      </div>
      </div>
      `).join('');
  }

  // ── DELETE ────────────────────────────────────────────────
  window.deleteContrib = async function(type, id) {
    const labels = {
      photo: 'foto',
      trip: 'trip',
      reaction: 'reaksi'
    };
    flattyConfirm(`Hapus ${labels[type]} ini? Tindakan ini permanen.`, async () => {
      const fd = new FormData();
      fd.append('action', `delete_${type}`);
      fd.append('csrf_token', CSRF);
      fd.append(`${type}_id`, id);

      try {
        const res = await fetch(API, {
          method: 'POST', headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }, body: fd
        });
        const json = await res.json();

        if (json.success) {
          document.getElementById(`${type}-${id}`)?.remove();
          const key = type === 'photo' ? 'photos': type === 'trip' ? 'trips': 'reactions';
          data[key] = data[key].filter(i => i.id !== id);
          const count = data[key].length;
          const capKey = key.charAt(0).toUpperCase() + key.slice(1);
          document.getElementById('stat' + capKey).textContent = count;
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
  document.getElementById('btnEditName').addEventListener('click',
    () => {
      document.getElementById('editNameForm').style.display = '';
      document.getElementById('btnEditName').style.display = 'none';
      document.getElementById('inputDisplayName').focus();
    });

  document.getElementById('btnCancelName').addEventListener('click',
    () => {
      document.getElementById('editNameForm').style.display = 'none';
      document.getElementById('btnEditName').style.display = '';
    });

  document.getElementById('btnSaveName').addEventListener('click',
    async () => {
      const name = document.getElementById('inputDisplayName').value.trim();
      if (!name) {
        flattyToast('warning', 'Nama tidak boleh kosong.'); return;
      }

      const btn = document.getElementById('btnSaveName');
      btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;

      const fd = new FormData();
      fd.append('action', 'update_name');
      fd.append('csrf_token', CSRF);
      fd.append('display_name', name);

      try {
        await new Promise(r => setTimeout(r, 1000));
        const res = await fetch(API, {
          method: 'POST', headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }, body: fd
        });
        const json = await res.json();

        if (json.success) {
          document.getElementById('displayNameText').textContent = name;
          document.getElementById('editNameForm').style.display = 'none';
          document.getElementById('btnEditName').style.display = '';
          flattyToast('success', 'Nama diperbarui!');
        } else {
          flattyToast('error', json.message ?? 'Gagal memperbarui nama.');
        }
      } catch(e) {
        flattyToast('error', 'Tidak bisa menyimpan nama.');
      } finally {
        btn.innerHTML = 'Simpan<i class="fa-solid fa-check ms-2"></i>';
        btn.disabled = false;
      }
    });

  document.getElementById('inputDisplayName').addEventListener('keydown',
    e => {
      if (e.key === 'Enter') document.getElementById('btnSaveName').click();
    });

  // ── HELPERS ──────────────────────────────────────────────
  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID',
      {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
  }

  function emptyState(icon,
    text) {
    return `<div class="text-center text-muted py-5 col-12">
    <i class="fa-solid ${icon} fa-2x mb-2 d-block opacity-25 mx-auto"></i>
    <div class="small">${text}</div>
    </div>`;
  }

  loadProfile();

})();