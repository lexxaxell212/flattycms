(function () {
  let currentPage       = 1;
  let currentPoi        = '';
  let currentPoiName    = '';
  let currentReviewPage = 1;
  let activeTab         = 'gallery';

  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
  }
  function stars(rating) {
    return Array.from({length:5}, (_,i) =>
      `<i class="fa-${i < rating ? 'solid' : 'regular'} fa-star"></i>`
    ).join('');
  }
  function poiSearch(inputId, resultsId, hiddenId, nameId, selectedId, onSelect) {
    const input   = document.getElementById(inputId);
    const results = document.getElementById(resultsId);
    if (!input) return;
    input.addEventListener('input', function() {
      const q = this.value.toLowerCase();
      results.innerHTML = '';
      if (!q) { results.classList.remove('open'); return; }
      const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
      results.classList.add('open');
      if (!matches.length) {
        results.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
        return;
      }
      matches.forEach(p => {
        const el       = document.createElement('button');
        el.type        = 'button';
        el.className   = 'list-group-item list-group-item-action small';
        el.innerHTML   = `<i class="fa-solid ${p.category_icon} me-2 text-primary"></i>${p.name}`;
        el.addEventListener('click', () => {
          input.value = p.name;
          document.getElementById(hiddenId).value         = p.id;
          document.getElementById(nameId).textContent     = p.name;
          document.getElementById(selectedId).style.display = '';
          results.classList.remove('open');
          onSelect?.(p);
        });
        results.appendChild(el);
      });
    });
    document.addEventListener('click', e => {
      if (!e.target.closest('#' + inputId) && !e.target.closest('#' + resultsId)) {
        results.classList.remove('open');
      }
    });
  }

  async function loadGallery(page = 1, poi_id = '') {
    currentPage = page;
    currentPoi  = poi_id;
    const grid  = document.getElementById('galleryGrid');
    grid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat foto...</div>';
    let url = `${API_GAL}?page=${page}`;
    if (poi_id) url += `&poi_id=${poi_id}`;
    try {
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      document.getElementById('statTotal').textContent = json.total;
      if (!json.data.length) {
        grid.innerHTML = `
          <div class="col-12 text-center py-5 text-muted">
            <i class="fa-solid fa-image fa-2x mb-3 d-block opacity-25"></i>
            Belum ada foto${poi_id ? ' untuk lokasi ini' : ''}
            ${IS_LOGGED ? '<br><button class="gal-btn gal-btn--primary mt-3" id="btnEmptyUpload"><i class="fa-solid fa-camera me-1"></i>Upload pertama!</button>' : ''}
          </div>`;
        if (IS_LOGGED) document.getElementById('btnEmptyUpload')?.addEventListener('click', () => openUploadModal());
        renderPagination(0, 0);
        return;
      }
      grid.innerHTML = json.data.map(p => `
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius:.875rem">
            <div class="position-relative" style="padding-top:75%;cursor:pointer"
              onclick="openLightbox('${BASE}/uploads/${p.photo_path}','${p.poi_name}','${p.uploader_name}','${p.caption||''}','${p.created_at}',${p.id},${p.user_id})">
              <img src="${BASE}/uploads/${p.photo_path}"
                   class="position-absolute top-0 start-0 w-100 h-100"
                   style="object-fit:cover;transition:.2s"
                   loading="lazy"
                   onerror="this.src='${BASE}/assets/img/placeholder.jpg'">
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-dark bg-opacity-75 small">${p.poi_name}</span>
              </div>
            </div>
            <div class="card-body p-2">
              <div class="small fw-semibold text-truncate">${p.poi_name}</div>
              <div class="d-flex align-items-center gap-1 mt-1">
                <img src="${p.uploader_avatar || BASE+'/assets/img/avatar.png'}"
                     class="rounded-circle" width="18" height="18" style="object-fit:cover">
                <span class="text-muted" style="font-size:.7rem">${p.uploader_name}</span>
              </div>
              ${p.caption ? `<div class="text-muted mt-1" style="font-size:.7rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${p.caption}</div>` : ''}
              <div class="text-muted mt-1" style="font-size:.68rem">${formatDate(p.created_at)}</div>
            </div>
          </div>
        </div>
      `).join('');
      renderPagination(json.page, json.pages);
    } catch(e) {
      grid.innerHTML = '<div class="col-12 text-center py-4 text-muted">Gagal memuat foto</div>';
    }
  }
  function renderPagination(page, pages) {
    const el = document.getElementById('pagination');
    if (pages <= 1) { el.innerHTML = ''; return; }
    el.innerHTML = Array.from({length:pages}, (_,i) => i+1).map(p =>
      `<button class="btn btn-sm ${p === page ? 'btn-primary' : 'btn-outline-secondary'}" onclick="loadGallery(${p},'${currentPoi}')">${p}</button>`
    ).join('');
  }
  window.loadGallery = loadGallery;
  async function loadReviews(page = 1, poi_id = '') {
    currentReviewPage = page;
    const grid = document.getElementById('reviewGrid');
    grid.innerHTML = '<div class="text-center py-5 text-muted"><i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat review...</div>';
    let url = `${API_REV}?page=${page}`;
    if (poi_id) url += `&poi_id=${poi_id}`;
    try {
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      document.getElementById('statReview').textContent = json.total ?? 0;
      if (!json.data || !json.data.length) {
        grid.innerHTML = `
          <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-star fa-2x mb-3 d-block opacity-25"></i>
            Belum ada review${poi_id ? ' untuk lokasi ini' : ''}
            ${IS_LOGGED ? '<br><button class="gal-btn gal-btn--primary mt-3" id="btnEmptyReview"><i class="fa-solid fa-pen-to-square me-1"></i>Tulis pertama!</button>' : ''}
          </div>`;
        if (IS_LOGGED) document.getElementById('btnEmptyReview')?.addEventListener('click', () => openReviewModal());
        renderPaginationReview(0, 0);
        return;
      }
      grid.innerHTML = json.data.map(r => `
        <div class="gal-review-card">
          <div class="gal-review-card__header">
            <div class="gal-review-card__user">
              <img src="${r.avatar || BASE+'/assets/img/avatar.png'}" class="gal-review-card__avatar"
                   onerror="this.src='${BASE}/assets/img/avatar.png'">
              <div>
                <div class="gal-review-card__name">${r.user_name}</div>
                <div class="gal-review-card__poi"><i class="fa-solid fa-location-dot me-1"></i>${r.poi_name}</div>
              </div>
            </div>
            <div class="text-end">
              <div class="gal-review-card__stars">${stars(r.rating)}</div>
              <div class="gal-review-card__date">${formatDate(r.created_at)}</div>
            </div>
          </div>
          ${r.judul ? `<div class="gal-review-card__title">${r.judul}</div>` : ''}
          <div class="gal-review-card__body">${r.cerita}</div>
        </div>
      `).join('');
      renderPaginationReview(json.page, json.pages);
    } catch(e) {
      grid.innerHTML = '<div class="text-center py-4 text-muted">Gagal memuat review</div>';
    }
  }
  function renderPaginationReview(page, pages) {
    const el = document.getElementById('paginationReview');
    if (pages <= 1) { el.innerHTML = ''; return; }
    el.innerHTML = Array.from({length:pages}, (_,i) => i+1).map(p =>
      `<button class="btn btn-sm ${p === page ? 'btn-primary' : 'btn-outline-secondary'}" onclick="loadReviews(${p},'${currentPoi}')">${p}</button>`
    ).join('');
  }
  document.querySelectorAll('.gal-tab').forEach(tab => {
    tab.addEventListener('click', function() {
      document.querySelectorAll('.gal-tab').forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      activeTab = this.dataset.tab;
      document.getElementById('tab-gallery').style.display = activeTab === 'gallery' ? '' : 'none';
      document.getElementById('tab-review').style.display  = activeTab === 'review'  ? '' : 'none';
      if (activeTab === 'review') loadReviews(1, currentPoi);
    });
  });
  // SEARCH & RESET
  document.getElementById('searchPoiFilter').addEventListener('input', function() {
    const q   = this.value.trim().toLowerCase();
    const box = document.getElementById('searchPoiFilterResults');
    box.innerHTML = '';
    if (!q) { box.classList.remove('open'); resetFilter(); return; }
    const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
    box.classList.add('open');
    if (!matches.length) {
      box.innerHTML = '<div class="list-group-item small text-muted">Tidak ditemukan</div>';
      return;
    }
    matches.forEach(p => {
      const el     = document.createElement('button');
      el.type      = 'button';
      el.className = 'list-group-item list-group-item-action small';
      el.innerHTML = `<i class="fa-solid ${p.category_icon} me-2 text-primary"></i>${p.name}`;
      el.addEventListener('click', () => {
        document.getElementById('searchPoiFilter').value = p.name;
        box.classList.remove('open');
        currentPoi     = p.id;
        currentPoiName = p.name;
        loadGallery(1, p.id);
        if (activeTab === 'review') loadReviews(1, p.id);
      });
      box.appendChild(el);
    });
  });
  document.addEventListener('click', e => {
    if (!e.target.closest('#searchPoiFilter') && !e.target.closest('#searchPoiFilterResults')) {
      document.getElementById('searchPoiFilterResults').classList.remove('open');
    }
  });
  function resetFilter() {
    currentPoi     = '';
    currentPoiName = '';
    document.getElementById('searchPoiFilter').value = '';
    document.getElementById('searchPoiFilterResults').classList.remove('open');
    loadGallery(1, '');
    if (activeTab === 'review') loadReviews(1, '');
  }
  document.getElementById('btnResetSearch').addEventListener('click', resetFilter);
  window.openLightbox = function(src, poi, uploader, credit, date, photo_id, owner_id) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxInfo').innerHTML = `
      <div class="fw-semibold">${poi}</div>
      <div class="small text-muted mt-1">
        <i class="fa-solid fa-user me-1"></i>${uploader}
        ${credit ? `· <i class="fa-solid fa-link me-1"></i>${credit}` : ''}
        · ${formatDate(date)}
      </div>
      <div class="d-flex gap-2 justify-content-center mt-2">
        <a href="${src}" target="_blank" class="btn btn-outline-light btn-sm">
          <i class="fa-solid fa-expand me-1"></i>Lihat HD
        </a>
        ${owner_id === MY_ID ? `<button class="btn btn-danger btn-sm" onclick="deletePhoto(${photo_id})"><i class="fa-solid fa-trash me-1"></i>Hapus</button>` : ''}
      </div>`;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
  };
  window.deletePhoto = async function(photo_id) {
    const conf = await Swal.fire({ title:'Hapus foto?', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', confirmButtonText:'Hapus', cancelButtonText:'Batal' });
    if (!conf.isConfirmed) return;
    const fd = new FormData();
    fd.append('action',     'delete');
    fd.append('csrf_token', CONFIG.csrfToken);
    fd.append('photo_id',   photo_id);
    const res  = await fetch(API_GAL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
    const data = await res.json();
    if (data.success) {
      bootstrap.Modal.getInstance(document.getElementById('lightboxModal')).hide();
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Foto dihapus!', showConfirmButton:false, timer:2000 });
      loadGallery(currentPage, currentPoi);
    } else {
      Swal.fire('Gagal', data.message, 'error');
    }
  };
  if (IS_LOGGED) {
    const uploadModal = document.getElementById('uploadModal');
    const reviewModal = document.getElementById('reviewModal');
    function openUploadModal(poi_id = '', poi_name = '') {
      document.getElementById('uploadPoiId').value              = poi_id;
      document.getElementById('uploadPoiSearch').value          = poi_name;
      document.getElementById('uploadPoiName').textContent      = poi_name;
      document.getElementById('uploadPoiSelected').style.display = poi_name ? '' : 'none';
      document.getElementById('uploadPoiResults').classList.remove('open');
      document.getElementById('uploadPreview').style.display     = 'none';
      document.getElementById('uploadFile').value                = '';
      document.getElementById('uploadCredit').value              = '';
      uploadModal.style.display = 'flex';
    }
    function openReviewModal(poi_id = '', poi_name = '') {
      document.getElementById('reviewPoiId').value              = poi_id;
      document.getElementById('reviewPoiSearch').value          = poi_name;
      document.getElementById('reviewPoiName').textContent      = poi_name;
      document.getElementById('reviewPoiSelected').style.display = poi_name ? '' : 'none';
      document.getElementById('reviewPoiResults').classList.remove('open');
      document.getElementById('reviewRating').value              = '0';
      document.getElementById('reviewJudul').value               = '';
      document.getElementById('reviewCerita').value              = '';
      document.querySelectorAll('.gal-star').forEach(s => s.classList.remove('active','fa-solid'));
      document.querySelectorAll('.gal-star').forEach(s => { s.classList.remove('fa-solid'); s.classList.add('fa-regular'); });
      reviewModal.style.display = 'flex';
    }
    window.openUploadModal = openUploadModal;
    window.openReviewModal = openReviewModal;
    document.getElementById('btnOpenUpload').addEventListener('click', () => openUploadModal(currentPoi, currentPoiName));
    document.getElementById('btnOpenReview').addEventListener('click', () => openReviewModal(currentPoi, currentPoiName));
    ['btnBatalUpload','btnBatalUpload2'].forEach(id => {
      document.getElementById(id)?.addEventListener('click', () => { uploadModal.style.display = 'none'; });
    });
    ['btnBatalReview','btnBatalReview2'].forEach(id => {
      document.getElementById(id)?.addEventListener('click', () => { reviewModal.style.display = 'none'; });
    });
    uploadModal.addEventListener('click', e => { if (e.target === uploadModal) uploadModal.style.display = 'none'; });
    reviewModal.addEventListener('click', e => { if (e.target === reviewModal) reviewModal.style.display = 'none'; });
    poiSearch('uploadPoiSearch', 'uploadPoiResults', 'uploadPoiId', 'uploadPoiName', 'uploadPoiSelected');
    poiSearch('reviewPoiSearch', 'reviewPoiResults', 'reviewPoiId', 'reviewPoiName', 'reviewPoiSelected');
    document.getElementById('uploadFile').addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      if (file.size > 10 * 1024 * 1024) { Swal.fire('File terlalu besar', 'Maksimal 10MB', 'warning'); this.value = ''; return; }
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPreview').style.display = '';
      };
      reader.readAsDataURL(file);
    });
    // Star rating
    document.querySelectorAll('.gal-star').forEach(star => {
      star.addEventListener('click', function() {
        const val = parseInt(this.dataset.val);
        document.getElementById('reviewRating').value = val;
        document.querySelectorAll('.gal-star').forEach((s, i) => {
          s.classList.toggle('fa-solid', i < val);
          s.classList.toggle('fa-regular', i >= val);
          s.classList.toggle('active', i < val);
        });
      });
      star.addEventListener('mouseenter', function() {
        const val = parseInt(this.dataset.val);
        document.querySelectorAll('.gal-star').forEach((s, i) => {
          s.style.color = i < val ? '#f59e0b' : '#d1d5db';
        });
      });
      star.addEventListener('mouseleave', () => {
        const current = parseInt(document.getElementById('reviewRating').value);
        document.querySelectorAll('.gal-star').forEach((s, i) => {
          s.style.color = i < current ? '#f59e0b' : '#d1d5db';
        });
      });
    });
    // Submit upload
    document.getElementById('btnKirimUpload').addEventListener('click', async () => {
      const poi_id = document.getElementById('uploadPoiId').value;
      const file   = document.getElementById('uploadFile').files[0];
      if (!poi_id || !file) { Swal.fire('Oops!', 'Pilih lokasi dan foto dulu', 'warning'); return; }
      const btn = document.getElementById('btnKirimUpload');
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengupload...';
      btn.disabled  = true;
      const fd = new FormData();
      fd.append('csrf_token', CSRF);
      fd.append('poi_id',     poi_id);
      fd.append('photo',      file);
      fd.append('caption',    document.getElementById('uploadCredit').value.trim());
      try {
        const res  = await fetch(API_GAL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const data = await res.json();
        if (data.success) {
          uploadModal.style.display = 'none';
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Foto berhasil diupload!', showConfirmButton:false, timer:2500 });
          loadGallery(1, currentPoi);
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch(e) {
        Swal.fire('Error', 'Gagal upload foto', 'error');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-upload me-1"></i>Upload';
        btn.disabled  = false;
      }
    });
    // Submit review
    document.getElementById('btnKirimReview').addEventListener('click', async () => {
      const poi_id = document.getElementById('reviewPoiId').value;
      const rating = document.getElementById('reviewRating').value;
      const cerita = document.getElementById('reviewCerita').value.trim();
      if (!poi_id)        { Swal.fire('Oops!', 'Pilih lokasi dulu', 'warning'); return; }
      if (rating < 1)     { Swal.fire('Oops!', 'Kasih rating dulu', 'warning'); return; }
      if (cerita.length < 10) { Swal.fire('Oops!', 'Ceritamu terlalu singkat', 'warning'); return; }
      const btn = document.getElementById('btnKirimReview');
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengirim...';
      btn.disabled  = true;
      const fd = new FormData();
      fd.append('csrf_token', CSRF);
      fd.append('poi_id',     poi_id);
      fd.append('rating',     rating);
      fd.append('judul',      document.getElementById('reviewJudul').value.trim());
      fd.append('cerita',     cerita);
      try {
        const res  = await fetch(API_REV, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const data = await res.json();
        if (data.success) {
          reviewModal.style.display = 'none';
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Review berhasil dikirim!', showConfirmButton:false, timer:2500 });
          if (activeTab === 'review') loadReviews(1, currentPoi);
        } else {
          Swal.fire('Gagal', data.message, 'error');
        }
      } catch(e) {
        Swal.fire('Error', 'Gagal kirim review', 'error');
      } finally {
        btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i>Kirim Review';
        btn.disabled  = false;
      }
    });
  }
  loadGallery();
})();
