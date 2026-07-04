(function () {
  let currentPage = 1;
  let currentPoi = '';
  let currentPoiName = '';
  let currentReviewPage = 1;
  let activeTab = 'gallery';

  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID', {
      day: 'numeric', month: 'short', year: 'numeric'
    });
  }
  function stars(rating) {
    return Array.from({
      length: 5
    }, (_, i) =>
      `<i class="fa-${i < rating ? 'solid': 'regular'} fa-star"></i>`
    ).join('');
  }
  function poiSearch(inputId, resultsId, hiddenId, nameId, selectedId, onSelect) {
    const input = document.getElementById(inputId);
    const results = document.getElementById(resultsId);
    if (!input) return;
    input.addEventListener('input', function() {
      const q = this.value.toLowerCase();
      results.innerHTML = '';
      if (!q) {
        results.classList.remove('open'); return;
      }
      const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
      results.classList.add('open');
      if (!matches.length) {
        results.innerHTML = '<div class="small text-muted">Tidak ditemukan</div>';
        return;
      }
      matches.forEach(p => {
        const el = document.createElement('button');
        el.type = 'button';
        el.className = 'btn-popup';
        el.innerHTML = `<span>${p.name}</span> • <span class="text-muted small">${p.category_name || ''}</span>`;
        el.addEventListener('click', () => {
          input.value = p.name;
          document.getElementById(hiddenId).value = p.id;
          document.getElementById(nameId).textContent = p.name;
          document.getElementById(selectedId).style.display = '';
          results.classList.remove('open');
          onSelect?.(p);
        });
        results.appendChild(el);
      });
    });
    document.addEventListener('click',
      e => {
        if (!e.target.closest('#' + inputId) && !e.target.closest('#' + resultsId)) {
          results.classList.remove('open');
        }
      });
  }

  async function loadGallery(page = 1,
    poi_id = '') {
    currentPage = page;
    currentPoi = poi_id;
    const grid = document.getElementById('galleryGrid');
    grid.innerHTML = '<div class="col-12"><div class="skeleton-wrapper"><div></div></div>';
    let url = `${API_GAL}?page=${page}`;
    if (poi_id) url += `&poi_id=${poi_id}`;
    try {
      await new Promise(r => setTimeout(r, 1000));
      const res = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const json = await res.json();
      document.getElementById('statTotal').textContent = json.total;
      if (!json.data.length) {
        grid.innerHTML = `
        <div class="col-12 text-center py-5 text-muted small justify-content-center">
        <i class="fa-solid fa-image fa-2x mb-3 d-block opacity-25 mx-auto"></i>
        Belum ada foto${poi_id ? ' untuk lokasi ini': ''}
        ${IS_LOGGED ? '<br><button class="btn btn-primary mt-4" id="btnEmptyUpload"><i class="fa-solid fa-camera me-1"></i>Upload pertama!</button>': ''}
        </div>`;
        if (IS_LOGGED) document.getElementById('btnEmptyUpload')?.addEventListener('click', () => openUploadModal());
        renderPagination(0, 0);
        return;
      }
      grid.innerHTML = json.data.map(p => `
        <div class="col-12 col-md-6">
        <div class="card card-glass h-100">
          <div class="position-relative" style="padding-top:60%;cursor:pointer" onclick="openLightbox('${BASE}/uploads/${p.photo_path}','${p.poi_name}','${p.uploader_name}','${p.caption || ''}','${p.created_at}',${p.id},${p.user_id})">
        <img src="${BASE}/uploads/${p.photo_path}" class="position-absolute top-0 start-0" style="object-fit:cover;transition:.2s;aspect-ratio:16/9;width:100%;height:100%" loading="lazy" onerror="this.src='${BASE}/assets/images/default.png'">
        </div>
        <div class="card-body text-center">
        <div class="text-truncate">
          <h2 class="h4">${p.poi_name}</h2>
        </div>
        <div class="text-muted small mb-2">${formatDate(p.created_at)}</div>
        ${p.caption ? `<div><i class="fas fa-link me-1"></i>${p.caption}</div>`: ''}
        </div>
        <div class="card-footer">
          <div class="d-flex align-items-center gap-1">
        <img src="${p.uploader_avatar || BASE+'/assets/images/avatar.png'}"
        class="rounded-circle me-2" width="18" height="18" style="object-fit:cover">
        <span class="fw-medium me-2"><small class="small fw-normal">Dari •</small> ${p.uploader_name}</span>
        </div>
        </div>
        </div>
        </div>
        `).join('');
      renderPagination(json.page, json.pages);
    } catch(e) {
      grid.innerHTML = '<div class="badge badge-red btn-fit">Gagal memuat foto</div>';
    }
  }
  function renderPagination(page, pages) {
    const el = document.getElementById('pagination');
    if (pages <= 1) {
      el.innerHTML = ''; return;
    }
    el.innerHTML = Array.from({
      length: pages
    }, (_, i) => i+1).map(p =>
      `<button class="btn ${p === page ? 'btn-primary': 'btn-outline-primary'}"
      onclick="loadGallery(${p},'${currentPoi}')">${p}</button>`
    ).join('');
  }
  window.loadGallery = loadGallery;
  async function loadReviews(page = 1, poi_id = '') {
    currentReviewPage = page;
    const grid = document.getElementById('reviewGrid');
    grid.innerHTML = '<div class="skeleton-wrapper"><div></div></div>';
    let url = `${API_REV}?page=${page}`;
    if (poi_id) url += `&poi_id=${poi_id}`;
    try {
      await new Promise(r => setTimeout(r, 1000));
      const res = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const json = await res.json();
      document.getElementById('statReview').textContent = json.total ?? 0;
      if (!json.data || !json.data.length) {
        grid.innerHTML = `
        <div class="text-center py-5 text-muted small justify-content-center">
        <i class="fa-solid fa-star fa-2x mb-3 d-block opacity-25 mx-auto"></i>
        Belum ada review${poi_id ? ' untuk lokasi ini': ''}
        ${IS_LOGGED ? '<br><button class="btn btn-primary mt-3" id="btnEmptyReview"><i class="fa-solid fa-pen-to-square me-1"></i>Tulis pertama!</button>': ''}
        </div>`;
        if (IS_LOGGED) document.getElementById('btnEmptyReview')?.addEventListener('click', () => openReviewModal());
        renderPaginationReview(0, 0);
        return;
      }
      grid.innerHTML = json.data.map(r => `
        <div class="gal-review-card">
        <div class="gal-review-card__header">
        <div class="gal-review-card__user">
        <img src="${r.avatar || BASE+'/uploads/default.jpg'}" class="gal-review-card__avatar"
        onerror="this.src='${BASE}/assets/images/avatar.png'">
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
        ${r.judul ? `<div class="gal-review-card__title">${r.judul}</div>`: ''}
        <div class="gal-review-card__body">${r.cerita}</div>
        </div>
        `).join('');
      renderPaginationReview(json.page, json.pages);
    } catch(e) {
      grid.innerHTML = '<div class="badge badge-red btn-fit">Gagal memuat review</div>';
    }
  }
  function renderPaginationReview(page, pages) {
    const el = document.getElementById('paginationReview');
    if (pages <= 1) {
      el.innerHTML = ''; return;
    }
    el.innerHTML = Array.from({
      length: pages
    }, (_, i) => i+1).map(p =>
      `<button class="btn ${p === page ? 'btn-primary': 'btn-outline-primary'}"
      onclick="loadReviews(${p},'${currentPoi}')">${p}</button>`
    ).join('');
  }
  document.querySelectorAll('.gal-tab').forEach(tab => {
    tab.addEventListener('click', function() {
      document.querySelectorAll('.gal-tab').forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      activeTab = this.dataset.tab;
      document.getElementById('tab-gallery').style.display = activeTab === 'gallery' ? '': 'none';
      document.getElementById('tab-review').style.display = activeTab === 'review' ? '': 'none';
      if (activeTab === 'review') loadReviews(1, currentPoi);
    });
  });
  // SEARCH & RESET
  document.getElementById('searchPoiFilter').addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    const box = document.getElementById('searchPoiFilterResults');
    box.innerHTML = '';
    if (!q) {
      box.classList.remove('open'); resetFilter(); return;
    }
    const matches = POIS.filter(p => p.name.toLowerCase().includes(q)).slice(0, 6);
    box.classList.add('open');
    if (!matches.length) {
      box.innerHTML = '<div class="text-center small">Tidak ditemukan</div>';
      return;
    }
    matches.forEach(p => {
      const el = document.createElement('button');
      el.type = 'button';
      el.className = 'btn-popup';
      el.innerHTML = `<span>${p.name}</span> • <span class="text-muted small">${p.category_name || ''}</span>`;
      el.addEventListener('click', () => {
        document.getElementById('searchPoiFilter').value = p.name;
        box.classList.remove('open');
        currentPoi = p.id;
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
    currentPoi = '';
    currentPoiName = '';
    document.getElementById('searchPoiFilter').value = '';
    document.getElementById('searchPoiFilterResults').classList.remove('open');
    loadGallery(1,
      '');
    if (activeTab === 'review') loadReviews(1, '');
  }
  document.getElementById('btnResetSearch').addEventListener('click', resetFilter);
  
  window.openLightbox = function(src, poi, uploader, credit, date, photo_id, owner_id) {
    document.getElementById('lightboxImg').src = src;
    const hdUrl = src.replace(/\/\/uploads\//, '/uploads/original/');
    document.getElementById('lightboxInfo').innerHTML = `
    <div class="fw-bold mb-2">${poi}</div>
    <div class="small">
    <i class="fas fa-user me-1"></i>${uploader} ${credit ? ` • <i class="fas fa-link me-1"></i>${credit}`: ''} • ${formatDate(date)}
    </div>
    <div class="d-flex gap-2 justify-content-center mt-2">
    <a href="${hdUrl}" target="_blank" class="btn btn-primary">
    <i class="fas fa-expand me-1"></i>Lihat HD
    </a>
    ${owner_id === MY_ID ? `<button class="btn btn-danger" onclick="deletePhoto(${photo_id})"><i class="fas fa-trash me-1"></i>Hapus</button>`: ''}
    </div>`;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
};
  
  window.deletePhoto = async function(photo_id) {
    flattyConfirm('Hapus foto ini?', async () => {
      const fd = new FormData();
      fd.append('action', 'delete');
      fd.append('csrf_token', CONFIG.csrfToken);
      fd.append('photo_id', photo_id);
      const res = await fetch(API_GAL, {
        method: 'POST', headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }, body: fd
      });
      const data = await res.json();
      if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('lightboxModal')).hide();
        flattyToast('success', 'Foto dihapus!');
        loadGallery(currentPage, currentPoi);
      } else {
        flattyToast('error', data.message ?? 'Gagal menghapus foto.');
      }
    });
  };
  if (IS_LOGGED) {
    const uploadModal = document.getElementById('uploadModal');
    const reviewModal = document.getElementById('reviewModal');
    function openUploadModal(poi_id = '', poi_name = '') {
      document.getElementById('uploadPoiId').value = poi_id;
      document.getElementById('uploadPoiSearch').value = poi_name;
      document.getElementById('uploadPoiName').textContent = poi_name;
      document.getElementById('uploadPoiSelected').style.display = poi_name ? '': 'none';
      document.getElementById('uploadPoiResults').classList.remove('open');
      document.getElementById('uploadPreview').style.display = 'none';
      document.getElementById('uploadFile').value = '';
      document.getElementById('uploadCredit').value = '';
      uploadModal.style.display = 'flex';
    }
    function openReviewModal(poi_id = '', poi_name = '') {
      document.getElementById('reviewPoiId').value = poi_id;
      document.getElementById('reviewPoiSearch').value = poi_name;
      document.getElementById('reviewPoiName').textContent = poi_name;
      document.getElementById('reviewPoiSelected').style.display = poi_name ? '': 'none';
      document.getElementById('reviewPoiResults').classList.remove('open');
      document.getElementById('reviewRating').value = '0';
      document.getElementById('reviewJudul').value = '';
      document.getElementById('reviewCerita').value = '';
      document.querySelectorAll('.gal-star').forEach(s => s.classList.remove('active', 'fa-solid'));
      document.querySelectorAll('.gal-star').forEach(s => {
        s.classList.remove('fa-solid'); s.classList.add('fa-regular');
      });
      reviewModal.style.display = 'flex';
    }
    window.openUploadModal = openUploadModal;
    window.openReviewModal = openReviewModal;
    document.getElementById('btnOpenUpload').addEventListener('click', () => openUploadModal(currentPoi, currentPoiName));
    document.getElementById('btnOpenReview').addEventListener('click', () => openReviewModal(currentPoi, currentPoiName));
    ['btnBatalUpload',
      'btnBatalUpload2'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
          uploadModal.style.display = 'none';
        });
      });
    ['btnBatalReview',
      'btnBatalReview2'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
          reviewModal.style.display = 'none';
        });
      });
    uploadModal.addEventListener('click', e => {
      if (e.target === uploadModal) uploadModal.style.display = 'none';
    });
    reviewModal.addEventListener('click',
      e => {
        if (e.target === reviewModal) reviewModal.style.display = 'none';
      });
    poiSearch('uploadPoiSearch',
      'uploadPoiResults',
      'uploadPoiId',
      'uploadPoiName',
      'uploadPoiSelected');
    poiSearch('reviewPoiSearch',
      'reviewPoiResults',
      'reviewPoiId',
      'reviewPoiName',
      'reviewPoiSelected');
    document.getElementById('uploadFile').addEventListener('change',
      function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 10 * 1024 * 1024) {
          flattyToast('warning', 'File terlalu besar, maksimal 10MB.');
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
          s.style.color = i < val ? '#f59e0b': '#d1d5db';
        });
      });
      star.addEventListener('mouseleave', () => {
        const current = parseInt(document.getElementById('reviewRating').value);
        document.querySelectorAll('.gal-star').forEach((s, i) => {
          s.style.color = i < current ? '#f59e0b': '#d1d5db';
        });
      });
    });
    // Submit upload
    document.getElementById('btnKirimUpload').addEventListener('click',
      async () => {
        const poi_id = document.getElementById('uploadPoiId').value;
        const file = document.getElementById('uploadFile').files[0];
        if (!poi_id || !file) {
          flattyToast('warning', 'Pilih lokasi dan foto dulu.'); return;
        }
        const btn = document.getElementById('btnKirimUpload');
        btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
        btn.disabled = true;
        const fd = new FormData();
        fd.append('csrf_token', CSRF);
        fd.append('poi_id', poi_id);
        fd.append('photo', file);
        fd.append('caption', document.getElementById('uploadCredit').value.trim());
        try {
          await new Promise(r => setTimeout(r, 1000));
          const res = await fetch(API_GAL, {
            method: 'POST', headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }, body: fd
          });
          const data = await res.json();
          if (data.success) {
            uploadModal.style.display = 'none';
            flattyToast('success', 'Foto berhasil diupload!');
            loadGallery(1, currentPoi);
          } else {
            flattyToast('error', data.message ?? 'Gagal upload foto.');
          }
        } catch(e) {
          flattyToast('error', 'Gagal upload foto.');
        } finally {
          btn.innerHTML = 'Upload';
          btn.disabled = false;
        }
      });
    // Submit review
    document.getElementById('btnKirimReview').addEventListener('click',
      async () => {
        const poi_id = document.getElementById('reviewPoiId').value;
        const rating = document.getElementById('reviewRating').value;
        const cerita = document.getElementById('reviewCerita').value.trim();
        if (!poi_id) {
          flattyToast('warning', 'Pilih lokasi dulu.'); return;
        }
        if (rating < 1) {
          flattyToast('warning', 'Kasih rating dulu.'); return;
        }
        if (cerita.length < 2) {
          flattyToast('warning', 'Ceritamu terlalu singkat.'); return;
        }
        const btn = document.getElementById('btnKirimReview');
        btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
        btn.disabled = true;
        const fd = new FormData();
        fd.append('csrf_token', CSRF);
        fd.append('poi_id', poi_id);
        fd.append('rating', rating);
        fd.append('judul', document.getElementById('reviewJudul').value.trim());
        fd.append('cerita', cerita);
        try {
          await new Promise(r => setTimeout(r, 1000));
          const res = await fetch(API_REV, {
            method: 'POST', headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }, body: fd
          });
          const data = await res.json();
          if (data.success) {
            reviewModal.style.display = 'none';
            flattyToast('success', 'Review berhasil dikirim!');
            if (activeTab === 'review') loadReviews(1, currentPoi);
          } else {
            flattyToast('error', data.message ?? 'Gagal kirim review.');
          }
        } catch(e) {
          flattyToast('error', 'Gagal kirim review.');
        } finally {
          btn.innerHTML = 'Kirim Review';
          btn.disabled = false;
        }
      });
  } else {
    document.getElementById('btnOpenUpload')?.addEventListener('click', () => {
      flattyToast('warning', 'Silakan masuk terlebih dahulu.');
    });
    document.getElementById('btnOpenReview')?.addEventListener('click', () => {
      flattyToast('warning', 'Silakan masuk terlebih dahulu.');
    });
  }
  loadGallery();
  loadReviews();
})();