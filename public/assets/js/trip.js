document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('page-trip');
  window.addEventListener('scroll', () => {
    const offset = window.scrollY * 0.4;
    document.body.style.backgroundPositionY = `calc(50% + ${offset}px)`;
  });
});

window.addEventListener('beforeunload', () => {
  document.body.classList.remove('page-trip');
});

(function () {
  let startPoint    = null;
  let routes        = [];
  let routeLine     = null;
  let markers       = {};
  let activeCat     = '';
  let routePolyline = null;
  let routeDuration = 0;

  const map = L.map('mainMap').setView([-6.9175, 107.6191], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);
  window.mainMap = map;

  const iconColors = { 1: 'oklch(0.487 0.167 295)', 2: 'oklch(0.769 0.166 70)', 3: 'oklch(0.558 0.174 295)' };
  function makeIcon(cat_id) {
    const c = iconColors[cat_id] || 'oklch(0.487 0.167 295)';
    return L.divIcon({
      className: '',
      html: `<div style="width:32px;height:32px;border-radius:50% 50% 50% 0;background:${c};transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.35)"></div>`,
      iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -34]
    });
  }

  function renderMarkers() {
    Object.values(markers).forEach(m => map.removeLayer(m));
    markers = {};
    POIS.forEach(poi => {
      if (activeCat && poi.category_id != activeCat) return;
      const m = L.marker([poi.latitude, poi.longitude], { icon: makeIcon(poi.category_id) })
        .addTo(map)
        .bindPopup(buildPopup(poi), { maxWidth: 240 });
      markers[poi.id] = m;
    });
  }

  function buildPopup(poi) {
  const inRoute = routes.some(r => r.poi_id == poi.id);
  return `
    <div style="min-width:200px">
      <div style="font-weight:700;font-size:.9rem;margin-bottom:.2rem">${poi.name}</div>
      <div style="font-size:.75rem;color:oklch(0.641 0.156 295);margin-bottom:.3rem">${poi.category_name}</div>
      ${poi.address ? `<div style="font-size:.72rem;color:oklch(0.553 0.016 264);margin-bottom:.6rem"><i class="fa-solid fa-road" style="margin-right:.25rem"></i>${poi.address}</div>` : ''}
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <button onclick="addToRoute(${poi.id})" style="flex:1;background:${inRoute ? 'oklch(0.527 0.154 155)' : 'oklch(0.487 0.167 295)'};color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;font-weight:600;cursor:pointer">
          ${inRoute ? '<i class="fa-solid fa-check"></i> Ditambahkan' : '<i class="fa-solid fa-plus"></i> Tambah Rute'}
        </button>
        ${IS_LOGGED ? `<button onclick="openUpload(${poi.id},'${poi.name.replace(/'/g, "\\'")}\")" style="background:oklch(0.623 0.214 231);color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;cursor:pointer"><i class="fa-solid fa-camera"></i></button>` : ''}
      </div>
    </div>`;
  }

  renderMarkers();

  document.querySelectorAll('.cat-filter').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.cat-filter').forEach(b => {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline-primary');
      });
      this.classList.add('active', 'btn-primary');
      this.classList.remove('btn-outline-primary');
      activeCat = this.dataset.cat;
      renderMarkers();
    });
  });

  document.getElementById('searchPoi').addEventListener('input', function () {
    const q   = this.value.toLowerCase();
    const box = document.getElementById('searchPoiResults');
    box.innerHTML = '';
    if (!q) { box.style.display = 'none'; return; }
    const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
    box.style.display = 'block';
    if (!matches.length) {
      box.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
      return;
    }
    matches.forEach(p => {
      const el = document.createElement('button');
      el.type      = 'button';
      el.className = 'btn-popup';
      el.innerHTML = `<span
      class="text-purple">${p.name}</span><span>${p.category_name || ''}</span>`;
      el.addEventListener('click', () => {
        box.style.display = 'none';
        document.getElementById('searchPoi').value = '';
        const marker = markers[p.id];
        if (marker) {
          map.flyTo([p.latitude, p.longitude], 16, { duration: 1 });
          setTimeout(() => marker.openPopup(), 1000);
        }
      });
      box.appendChild(el);
    });
  });

  document.addEventListener('click', e => {
    if (!e.target.closest('#searchPoi') && !e.target.closest('#searchPoiResults'))
      document.getElementById('searchPoiResults').style.display = 'none';
  });

  function searchStartPoint(q) {
    const el = document.getElementById('startResults');
    if (!q) { el.style.display = 'none'; return; }
    const matches = POIS.filter(p => p.name.toLowerCase().includes(q.toLowerCase())).slice(0, 6);
    el.innerHTML = '';
    el.style.display = '';
    if (!matches.length) {
      el.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
      return;
    }
    matches.forEach(p => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn-popup';
      btn.innerHTML = `
        <span class="text-purple">${p.name}</span><span>${p.category_name || ''}</span>
      `;
      btn.addEventListener('click', () => {
        startPoint = { name: p.name, lat: parseFloat(p.latitude), lng:
        parseFloat(p.longitude), poi_image: p.poi_image || null,
        description: p.description || '', poi_url: p.poi_url || null };
        document.getElementById('startName').textContent = startPoint.name;
        document.getElementById('startName2').textContent = startPoint.name;
        document.getElementById('startDesc').textContent = startPoint.description || 'Deskripsi belum tersedia.';
        document.getElementById('startImg').innerHTML = startPoint.poi_image
          ? `<img src="${escHtml(startPoint.poi_image)}" class="card-img" onerror="this.src='uploads/poi-placeholder.jpg'">`
          : `<img src="uploads/poi-placeholder.jpg" class="card-img">`;
        document.getElementById('startSelected').style.display = '';
        document.getElementById('startInput').value = '';
        el.style.display = 'none';
        updatePlannerUI();
      });
      el.appendChild(btn);
    });
  }

  document.getElementById('startInput').addEventListener('input', function () {
    searchStartPoint(this.value.trim());
  });
  document.getElementById('startInput').addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('startResults').style.display = 'none';
  });

  window.addToRoute = function (poi_id) {
    window.isLoadedTrip = false;
    if (!startPoint) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Pilih titik awal dulu', showConfirmButton: false, timer: 2000 });
      return;
    }
    if (routes.some(r => r.poi_id == poi_id)) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Sudah ada di rute', showConfirmButton: false, timer: 1500 });
      return;
    }
    const poi = POIS.find(p => p.id == poi_id);
    if (!poi) return;
    routes.push({ poi_id: poi.id, name: poi.name, lat: parseFloat(poi.latitude),
    lng: parseFloat(poi.longitude), note: '', poi_image: poi.poi_image || null, description: poi.description || '', poi_url: poi.poi_url || null });
    map.closePopup();
    updatePlannerUI();
    updateRouteOnMap();
  };

  function updatePlannerUI() {
    const list  = document.getElementById('routeList');
    const empty = document.getElementById('routeEmpty');
    const btnG  = document.getElementById('btnGenerateRoute');
    const btnS  = document.getElementById('btnSaveTrip');
    const distI = document.getElementById('distanceInfo');

    if (routes.length === 0) {
      if (empty) empty.style.display = '';
      list.innerHTML = '';
      if (empty) list.appendChild(empty);
      btnG.disabled = true;
      if (btnS) btnS.disabled = true;
      distI.style.display = 'none';
      return;
    }

    if (empty) empty.style.display = 'none';
    list.innerHTML = routes.map((r, i) => `
      <div class="d-flex align-items-start gap-2 mb-3 p-2" data-idx="${i}">
        <div class="flex-grow-1 min-w-0">
          <div class="small fw-semibold text-truncate mb-2">
            <span class="text-purple">
              ${i + 1}
            </span>
            • ${r.name}
          </div>
          ${r.distance_from_prev ? `<div class="text-muted mb-2" style="font-size:.7rem"><i class="fa-solid fa-ruler me-1"></i>${r.distance_from_prev} km dari titik sebelumnya</div>` : ''}
          <div class="card card-flatty mb-2">
          <div class="card-body">
          <button class="btn badge badge-primary btn-remove-route position-absolute top-0 end-0 m-2" data-idx="${i}">
          <i class="fa-solid fa-xmark"></i>
          </button>
            ${r.poi_image
              ? `<img src="${escHtml(r.poi_image)}" class="card-img" onerror="this.src='uploads/poi-placeholder.jpg'">`
              : `<img src="uploads/poi-placeholder.jpg" class="card-img">`
            }
            <h6>${escHtml(r.name)}</h6>
            <p class="text-muted small">${escHtml(r.description || 'Deskripsi belum tersedia.')}</p>
          </div>
          </div>
          ${IS_LOGGED ? `<div class="mt-1"><input type="text" class="form-control note-input" data-idx="${i}" placeholder="Tambah catatan untuk POI ini..." value="${escHtml(r.note)}" style="font-size:.9rem" ${window.isLoadedTrip ? 'disabled' : ''}></div>` : ''}
        </div>
      </div>`).join('');

    list.querySelectorAll('.note-input').forEach(inp => {
      inp.addEventListener('input', function () { routes[this.dataset.idx].note = this.value; });
    });
    list.querySelectorAll('.btn-remove-route').forEach(btn => {
      btn.addEventListener('click', function () {
        routes.splice(parseInt(this.dataset.idx), 1);
        updatePlannerUI();
        updateRouteOnMap();
      });
    });

    btnG.disabled = !(startPoint && routes.length > 0);
    if (btnS) btnS.disabled = !(startPoint && routes.length > 0);

    const total = routes.reduce((s, r) => s + (parseFloat(r.distance_from_prev) || 0), 0);
    if (total > 0) {
      document.getElementById('totalDist').textContent = total.toFixed(1);
      document.getElementById('totalStops').textContent = `· ${routes.length} lokasi`;
      distI.style.display = '';
    }
  }

  document.getElementById('btnGenerateRoute').addEventListener('click', async () => {
    if (!startPoint || routes.length === 0) return;
    const points = [[startPoint.lat, startPoint.lng], ...routes.map(r => [r.lat, r.lng])];
    const fd = new FormData();
    fd.append('coordinates', JSON.stringify(points));
    const btn = document.getElementById('btnGenerateRoute');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Membuat rute...';
    btn.disabled  = true;
    try {
      const res  = await fetch(`${BASE}/api/map/api-route.php`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
      const data = await res.json();
      if (data.success) {
        routePolyline = data.polyline;
        routeDuration = data.duration;
        updateRouteOnMap(routePolyline);
        document.getElementById('totalDist').textContent = data.distance;
        document.getElementById('totalStops').textContent = `· ${routes.length} lokasi · ~${data.duration} menit`;
        document.getElementById('distanceInfo').style.display = '';
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title:
        'Rute berhasil dibuat!', showConfirmButton: false, timer: 2000 });
      } else {
        Swal.fire('Gagal', data.message, 'error');
      }
    } catch (e) {
      Swal.fire('Error', 'Tidak bisa buat rute', 'error');
    } finally {
      btn.innerHTML = '<i class="fa-solid fa-route me-1"></i>Buat Rute';
      btn.disabled  = false;
    }
  });

  function updateRouteOnMap(points) {
    if (routeLine) map.removeLayer(routeLine);
    if (!points || points.length < 2) return;
    routeLine = L.polyline(points, { color: '#9061f9', weight: 4, opacity: .85 }).addTo(map);
    map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });
  }

  document.getElementById('btnResetTrip').addEventListener('click', async () => {
    const conf = await Swal.fire({
      title: 'Reset rute?', icon: 'warning',
      showCancelButton: true, confirmButtonText: 'Ya, reset',
      cancelButtonText: 'Batal', confirmButtonColor: '#dc3545'
    });
    if (!conf.isConfirmed) return;
    startPoint = null; routes = []; routePolyline = null; routeDuration = 0;
    window.isLoadedTrip = false;
    document.getElementById('startSelected').style.display = 'none';
    document.getElementById('startInput').value = '';
    if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
    updatePlannerUI();
    renderMarkers();
  });

  if (IS_LOGGED) {
    document.getElementById('btnSaveTrip').addEventListener('click', () => {
      const sf = document.getElementById('saveForm');
      sf.style.display = sf.style.display === 'none' ? '' : 'none';
    });

    document.getElementById('tripTitle').addEventListener('keydown', async e => {
      if (e.key === 'Enter') await doSaveTrip();
    });
    document.getElementById('btnConfirmSave').addEventListener('click', doSaveTrip);

    async function doSaveTrip() {
      const title = document.getElementById('tripTitle').value.trim() || 'Trip Bandungku';
      const fd    = new FormData();
      fd.append('action',           'save');
      fd.append('csrf_token',       CSRF);
      fd.append('title',            title);
      fd.append('start_point_name', startPoint.name);
      fd.append('start_lat',        startPoint.lat);
      fd.append('start_lng',        startPoint.lng);
      fd.append('route_polyline',   routePolyline ? JSON.stringify(routePolyline) : '');
      fd.append('duration',         routeDuration ?? 0);
      fd.append('items', JSON.stringify(routes.map((r, i) => ({
        poi_id: r.poi_id, order_index: i + 1,
        distance_from_prev: r.distance_from_prev || 0, note: r.note
      }))));
      try {
        const res  = await fetch(API_TRIP, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
        const data = await res.json();
        if (data.success) {
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title:
          'Rute trip disimpan!', showConfirmButton: false, timer: 2000 });
          document.getElementById('saveForm').style.display = 'none';
          if (typeof window.refreshTripku === 'function') window.refreshTripku();
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch (e) {
        Swal.fire('Error', 'Tidak bisa menyimpan trip', 'error');
      }
    }
  }

  window.loadTripById = async function (id) {
    try {
      const res  = await fetch(`${API_TRIP}?id=${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      if (!json.success) { Swal.fire('Gagal', json.message, 'error'); return; }
      const trip = json.data;
      startPoint = { name: trip.start_point_name, lat: parseFloat(trip.start_lat), lng: parseFloat(trip.start_lng) };
      document.getElementById('startName').textContent = startPoint.name;
      document.getElementById('startSelected').style.display = '';
      routes = trip.items.map(item => ({
        poi_id: item.poi_id, name: item.poi_name,
        lat: parseFloat(item.latitude), lng: parseFloat(item.longitude),
        distance_from_prev: item.distance_from_prev, note: item.note || ''
      }));
      window.isLoadedTrip = true;
      updatePlannerUI();
      if (trip.route_polyline) {
        routePolyline = JSON.parse(trip.route_polyline);
        routeDuration = trip.duration || 0;
        updateRouteOnMap(routePolyline);
        document.getElementById('totalDist').textContent = trip.total_distance ?? '—';
        document.getElementById('totalStops').textContent = `· ${routes.length} lokasi${trip.duration ? ' · ~' + trip.duration + ' menit' : ''}`;
        document.getElementById('distanceInfo').style.display = '';
      } else {
        routePolyline = null;
        updateRouteOnMap([[startPoint.lat, startPoint.lng], ...routes.map(r => [r.lat, r.lng])]);
      }
      setTimeout(() => {
        const mapEl = document.getElementById('mainMap');
        if (mapEl) { mapEl.scrollIntoView({ behavior: 'smooth', block: 'start' }); map.invalidateSize(); }
      }, 150);
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `Trip "${trip.title}" dimuat!`, showConfirmButton: false, timer: 2000 });
    } catch (e) {
      Swal.fire('Error', 'Gagal memuat trip', 'error');
    }
  };

  window.deleteTripById = async function (id, title) {
    const conf = await Swal.fire({
      title: 'Hapus trip?', text: `"${title}" akan dihapus permanen`, icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc3545',
      confirmButtonText: 'Hapus', cancelButtonText: 'Batal'
    });
    if (!conf.isConfirmed) return;
    const fd = new FormData();
    fd.append('action', 'delete'); fd.append('csrf_token', CSRF); fd.append('trip_id', id);
    const res  = await fetch(API_TRIP, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
    const data = await res.json();
    if (data.success) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Trip dihapus!', showConfirmButton: false, timer: 2000 });
      if (typeof window.refreshTripku === 'function') window.refreshTripku();
    } else {
      Swal.fire('Gagal', data.message, 'error');
    }
  };

  if (IS_LOGGED) {
    window.openUpload = function (poi_id, poi_name) {
      if (typeof window.openUploadModal === 'function') {
        window.openUploadModal(poi_id, poi_name);
        return;
      }
      document.getElementById('uploadPoiId').value = poi_id;
      document.getElementById('uploadPoiName').textContent = poi_name;
      document.getElementById('uploadPoiSelected').style.display = '';
      document.getElementById('uploadPoiSearch').value = poi_name;
      document.getElementById('uploadPreview').style.display = 'none';
      document.getElementById('uploadFile').value = '';
      document.getElementById('uploadCredit').value = '';
      bootstrap.Modal.getOrCreateInstance(document.getElementById('uploadModal')).show();
    };

    document.getElementById('uploadPoiSearch').addEventListener('input', function () {
      const q   = this.value.toLowerCase();
      const box = document.getElementById('uploadPoiResults');
      box.innerHTML = '';
      if (!q) { box.style.display = 'none'; return; }
      box.style.display = '';
      const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
      if (!matches.length) { box.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>'; return; }
      matches.forEach(p => {
        const el = document.createElement('button');
        el.type = 'button'; el.className = 'btn-popup text-purple'; el.textContent = p.name;
        el.addEventListener('click', () => {
          document.getElementById('uploadPoiId').value = p.id;
          document.getElementById('uploadPoiName').textContent = p.name;
          document.getElementById('uploadPoiSelected').style.display = '';
          box.style.display = 'none';
        });
        box.appendChild(el);
      });
    });

    document.getElementById('uploadFile').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 10 * 1024 * 1024) {
        Swal.fire('File terlalu besar', 'Maksimal 10MB', 'warning');
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });

    document.getElementById('btnKirimUpload').addEventListener('click', async () => {
      const poi_id = document.getElementById('uploadPoiId').value;
      const file   = document.getElementById('uploadFile').files[0];
      if (!poi_id || !file) { Swal.fire('Oops!', 'Pilih lokasi dan foto dulu', 'warning'); return; }
      const btn = document.getElementById('btnKirimUpload');
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengupload...';
      btn.disabled  = true;
      const fd = new FormData();
      fd.append('csrf_token', CSRF); fd.append('poi_id', poi_id);
      fd.append('photo', file); fd.append('caption', document.getElementById('uploadCredit').value.trim());
      try {
        const res  = await fetch(API_GAL, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
        const data = await res.json();
        if (data.success) {
          bootstrap.Modal.getInstance(document.getElementById('uploadModal'))?.hide();
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Foto berhasil diupload!', showConfirmButton: false, timer: 2500 });
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch (e) {
        Swal.fire('Error', 'Gagal upload foto', 'error');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-upload me-1"></i>Upload';
        btn.disabled  = false;
      }
    });

    const _uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
    window.openUploadModal = function (poiId, poiName) {
      document.getElementById('uploadPoiId').value = poiId || '';
      document.getElementById('uploadPoiName').textContent = poiName || '';
      document.getElementById('uploadPoiSelected').style.display = (poiId && poiName) ? '' : 'none';
      _uploadModal.show();
    };
  }

  function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function loadExplorePoi() {
  const wrap = document.getElementById('explorePoiList');
  const overlay = document.getElementById('exploreOverlay');
  let activeCatExplore = '';
  let searchQuery = '';
  let showAll = false;

  function render() {
    let filtered = POIS.filter(poi => {
      const matchCat = !activeCatExplore || poi.category_id == activeCatExplore;
      const matchQ   = !searchQuery || poi.name.toLowerCase().includes(searchQuery);
      return matchCat && matchQ;
    });

    if (!filtered.length) {
      wrap.innerHTML = `<div class="tp-empty-state" style="grid-column:1/-1"><i class="fa-solid fa-compass"></i><p>Tidak ada hasil.</p></div>`;
      overlay.style.display = 'none';
      return;
    }

    const limited = (!showAll && filtered.length > 10) ? filtered.slice(0, 10) : filtered;

    wrap.innerHTML = limited.map(poi => `
      <div class="card card-flatty mb-4">
      <div class="card-body">
        ${poi.poi_image
          ? `<img src="${escHtml(poi.poi_image)}" class="card-img" onerror="this.src='uploads/poi-placeholder.jpg'">`
          : `<img src="uploads/poi-placeholder.jpg" class="card-img">`
        }
          <h6>${escHtml(poi.name)}</h6>
          <p class="text-muted small">${escHtml(poi.description || 'Deskripsi belum tersedia.')}</p>
          ${poi.poi_url
            ? `<a href="${escHtml(poi.poi_url)}" class="btn btn-primary btn-sm" target="_blank" rel="noopener">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Lihat
               </a>`
            : `<span class="btn btn-outline-accent btn-sm disabled">
                <i class="fa-solid fa-link-slash me-1"></i>Link ?
               </span>`
          }
        </div>
      </div>`).join('');

    overlay.style.display = (!showAll && filtered.length > 10) ? 'flex' : 'none';
  }

  document.getElementById('btnShowAllPoi').addEventListener('click', () => {
    showAll = true;
    render();
  });

  document.getElementById('exploreSearch').addEventListener('input', function() {
    showAll = false;
    searchQuery = this.value.toLowerCase().trim();
    render();
  });

  document.querySelectorAll('.explore-cat').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.explore-cat').forEach(b => {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline-primary');
      });
      this.classList.add('active', 'btn-primary');
      this.classList.remove('btn-outline-primary');
      activeCatExplore = this.dataset.cat;
      showAll = false;
      render();
    });
  });

  render();
}

  function loadTripku() {
    const wrap = document.getElementById('tripkuList');
    wrap.innerHTML = `<div class="text-center py-4 text-muted small" style="grid-column:1/-1"><i class="fa-solid fa-spinner fa-spin me-1"></i>Memuat trip tersimpan...</div>`;
    fetch(API_TRIP + '?action=list')
      .then(r => r.json())
      .then(data => {
        const list    = Array.isArray(data) ? data : (data.data || []);
        const countEl = document.getElementById('profileTripCount');
        if (countEl) countEl.textContent = list.length;
        if (!list.length) {
          wrap.innerHTML = `<div class="tp-empty-state" style="grid-column:1/-1"><i class="fa-solid fa-suitcase"></i><p>Belum ada trip tersimpan.<br>Buat trip pertamamu di tab Map POI!</p></div>`;
          return;
        }
        wrap.innerHTML = list.map(trip => `
        <div class="card card-flatty">
          <div class="card-body">
            <h5>${escHtml(trip.title || 'Trip tanpa nama')}</h5>
            <p class="text-muted small">
              ${trip.total_stops ? `<span class="me-2"><i class="fa-solid fa-map-pin me-1 text-purple"></i>${trip.total_stops} stop</span>` : ''}
              ${trip.total_distance ? `<span><i class="fa-solid fa-ruler me-1 text-muted"></i>${trip.total_distance} km</span>` : ''}
              ${(!trip.total_stops && !trip.total_distance) ? escHtml(trip.notes || 'Catatan kosong.') : ''}
            </p>
          </div>
          <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="loadSavedTrip(${trip.id})">
              <i class="fa-solid fa-route me-1"></i>Buka di Map POI
            </button>
            <button class="btn btn-danger btn-sm" onclick="window.deleteTripById(${trip.id}, '${escHtml(trip.title || 'Trip ini')}')">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>`).join('');
      })
      .catch(() => {
        wrap.innerHTML = `<div class="tp-empty-state" style="grid-column:1/-1"><i class="fa-solid fa-triangle-exclamation"></i><p>Gagal memuat trip. Coba refresh halaman.</p></div>`;
      });
  }

  window.refreshTripku = loadTripku;

  window.loadSavedTrip = function (tripId) {
    document.querySelector('[data-tab="map"]').click();
    if (typeof window.loadTripById === 'function') window.loadTripById(tripId);
  };

  window.openPoiGallery = function (poiId) {
    if (typeof window.showPoiGallery === 'function') window.showPoiGallery(poiId);
  };

  (function initTabs() {
    const tabs     = document.querySelectorAll('.tp-tab');
    const contents = document.querySelectorAll('.tp-tab-content');
    let tripkuLoaded   = false;
    let mapInitialized = false;

    tabs.forEach(tab => {
      tab.addEventListener('click', function () {
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const target = this.dataset.tab;
        contents.forEach(c => c.style.display = 'none');
        document.getElementById('tab-' + target).style.display = '';

        if (target === 'tripku' && !tripkuLoaded && IS_LOGGED) {
          loadTripku();
          tripkuLoaded = true;
        }
        if (target === 'map' && !mapInitialized) {
          setTimeout(() => { if (window.mainMap) window.mainMap.invalidateSize(); }, 50);
          mapInitialized = true;
        }
      });
    });

    loadExplorePoi();

    if (IS_LOGGED) {
      fetch(API_TRIP + '?action=count')
        .then(r => r.json())
        .then(d => {
          const el = document.getElementById('profileTripCount');
          if (el && d.count !== undefined) el.textContent = d.count;
        }).catch(() => {});
    }
  })();

})();