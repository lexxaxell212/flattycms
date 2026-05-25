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

      // Stats
      document.getElementById('statPhotos').textContent   = json.photos.length;
      document.getElementById('statTrips').textContent    = json.trips.length;
      document.getElementById('statReactions').textContent = json.reactions.length;

      document.getElementById('tabLoading').style.display = 'none';
      renderTab(activeTab);

    } catch(e) {
      document.getElementById('tabLoading').innerHTML = '<div class="text-muted">Gagal memuat data</div>';
    }
  }

  // ── TABS ─────────────────────────────────────────────────
  document.querySelectorAll('#profileTabs .nav-link').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('#profileTabs .nav-link').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      activeTab = this.dataset.tab;
      document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
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
        <div class="bg-light card h-100 overflow-hidden">
          <div class="position-relative" style="padding-top:75%">
            <img src="${BASE}/uploads/${p.photo_path}"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit:cover" loading="lazy">
          </div>
          <div class="card-body">
            <div class="small fw-semibold text-truncate">${p.poi_name}</div>
            ${p.caption ? `<div class="text-muted" style="font-size:.7rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${p.caption}</div>` : ''}
            <div class="text-muted mt-1" style="font-size:.68rem">${formatDate(p.created_at)}</div>
            <button class="btn bg-accent btn-sm w-100 mt-2" onclick="deleteContrib('photo', ${p.id})">
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
      <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded border mb-2" id="trip-${t.id}">
        <div class="flex-grow-1 min-w-0">
          <div class="fw-semibold text-truncate">${t.title}</div>
          <div class="small text-muted mt-1">
            <i class="fa-solid fa-location-dot me-1"></i>${t.start_point_name}
            <span class="mx-2">·</span>
            <i class="fa-solid fa-map-pin me-1"></i>${t.total_stops} stop
            ${t.total_distance ? `<span class="mx-2">·</span><i class="fa-solid fa-ruler me-1"></i>${t.total_distance} km` : ''}
            ${t.duration ? `<span class="mx-2">·</span><i class="fa-solid fa-clock me-1"></i>~${t.duration} menit` : ''}
          </div>
          <div class="text-muted mt-1" style="font-size:.7rem">${formatDate(t.created_at)}</div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
          <a href="/trip" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-map me-1"></i>Buka
          </a>
          <button class="btn bg-accent btn-sm" onclick="deleteContrib('trip', ${t.id})">
            <i class="fa-solid fa-trash"></i>
          </button>
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
      <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded border mb-2" id="reaction-${r.id}">
        <div class="d-flex align-items-center gap-3">
          <span class="badge bg-primary bg-opacity-10 text-white p-2">
            <i class="fa-solid ${typeIcon[r.content_type] ?? 'fa-heart'}"></i>
          </span>
          <div>
            <div class="small fw-semibold">${r.content_title ?? `${typeLabel[r.content_type] ?? r.content_type} #${r.content_id}`}</div>
            <div class="text-muted" style="font-size:.7rem">${formatDate(r.created_at)}</div>
          </div>
        </div>
        <button class="btn bg-accent btn-sm" onclick="deleteContrib('reaction', ${r.id})">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
    `).join('');
  }

  // ── DELETE KONTRIBUSI ────────────────────────────────────
  window.deleteContrib = async function(type, id) {
    const labels = { photo: 'foto', trip: 'trip', reaction: 'reaksi' };
    const conf   = await Swal.fire({
      title: `Hapus ${labels[type]}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    });
    if (!conf.isConfirmed) return;

    const fd = new FormData();
    fd.append('action',     `delete_${type}`);
    fd.append('csrf_token', CSRF);
    fd.append(`${type}_id`, id);

    try {
      const res  = await fetch(API, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
      const json = await res.json();

      if (json.success) {
        document.getElementById(`${type}-${id}`)?.remove();
        // Update stats
        const key = type === 'photo' ? 'photos' : type === 'trip' ? 'trips' : 'reactions';
        data[key] = data[key].filter(i => i.id !== id);
        document.getElementById(`stat${key.charAt(0).toUpperCase() + key.slice(1)}`).textContent = data[key].length;
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:`${labels[type].charAt(0).toUpperCase() + labels[type].slice(1)} dihapus!`, showConfirmButton:false, timer:2000 });
      } else {
        Swal.fire('Gagal', json.message, 'error');
      }
    } catch(e) {
      Swal.fire('Error', 'Tidak bisa menghubungi server', 'error');
    }
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
    if (!name) { Swal.fire('Oops!', 'Nama tidak boleh kosong', 'warning'); return; }

    const btn = document.getElementById('btnSaveName');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
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
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Nama diperbarui!', showConfirmButton:false, timer:2000 });
      } else {
        Swal.fire('Gagal', json.message, 'error');
      }
    } catch(e) {
      Swal.fire('Error', 'Tidak bisa menyimpan nama', 'error');
    } finally {
      btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>Simpan';
      btn.disabled  = false;
    }
  });

  // Enter key buat save nama
  document.getElementById('inputDisplayName').addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('btnSaveName').click();
  });

  // ── HELPERS ──────────────────────────────────────────────
  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
  }

  function emptyState(icon, text) {
    return `<div class="text-center text-muted py-5 col-12">
      <i class="fa-solid ${icon} fa-2x mb-3 d-block opacity-25"></i>${text}
    </div>`;
  }

  // ── INIT ─────────────────────────────────────────────────
  loadProfile();

})();