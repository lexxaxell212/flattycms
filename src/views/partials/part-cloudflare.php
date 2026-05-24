
<div class="row g-3 mb-4" id="cfStatsWrapper">
  <h2>Cloudflare Analytics</h2>
  <!-- Hit Hari Ini -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Hit Hari Ini</span>
          <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
            <i class="fa-solid fa-eye fa-xs"></i>
          </span>
        </div>
        <div class="fw-bold fs-4 text-primary cf-hit-today">—</div>
        <div class="small text-muted mt-1">Total request masuk</div>
      </div>
    </div>
  </div>

  <!-- Unique Visitor Hari Ini -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Unik Hari Ini</span>
          <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
            <i class="fa-solid fa-user fa-xs"></i>
          </span>
        </div>
        <div class="fw-bold fs-4 text-success cf-uniq-today">—</div>
        <div class="small text-muted mt-1">Unique visitor hari ini</div>
      </div>
    </div>
  </div>

  <!-- Hit Minggu Ini -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Hit 7 Hari</span>
          <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">
            <i class="fa-solid fa-chart-line fa-xs"></i>
          </span>
        </div>
        <div class="fw-bold fs-4 text-warning cf-hit-week">—</div>
        <div class="small text-muted mt-1">Total request 7 hari</div>
      </div>
    </div>
  </div>

  <!-- Unique Visitor Minggu Ini -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Unik 7 Hari</span>
          <span class="badge bg-info bg-opacity-10 text-info rounded-pill">
            <i class="fa-solid fa-users fa-xs"></i>
          </span>
        </div>
        <div class="fw-bold fs-4 text-info cf-uniq-week">—</div>
        <div class="small text-muted mt-1">Unique visitor 7 hari</div>
      </div>
    </div>
  </div>

  <!-- Label sumber data -->
  <div class="col-12">
    <div class="d-flex align-items-center gap-2">
      <img src="https://www.cloudflare.com/favicon.ico" width="14" height="14" alt="CF">
      <span class="small text-muted" style="font-size:.72rem">
        Data dari <strong>Cloudflare Analytics</strong> — sudah difilter bot otomatis
      </span>
      <span class="ms-auto small text-muted cf-last-updated" style="font-size:.7rem"></span>
    </div>
  </div>

</div>

<script>
(function () {
  const API_CF = CONFIG.baseUrl + '/api/api-cf-analytics.php';

  function fmt(n) {
    return parseInt(n).toLocaleString('id-ID');
  }

  async function loadCFStats() {
    try {
      const res  = await fetch(API_CF, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();

      if (!json.success) return;

      const d = json.data;
      document.querySelector('.cf-hit-today').textContent  = fmt(d.hit_today);
      document.querySelector('.cf-uniq-today').textContent = fmt(d.uniq_today);
      document.querySelector('.cf-hit-week').textContent   = fmt(d.hit_week);
      document.querySelector('.cf-uniq-week').textContent  = fmt(d.uniq_week);

      const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      document.querySelector('.cf-last-updated').textContent = 'Update: ' + now;

    } catch(e) {
      document.querySelector('.cf-hit-today').textContent  = 'Error';
      document.querySelector('.cf-uniq-today').textContent = 'Error';
      document.querySelector('.cf-hit-week').textContent   = 'Error';
      document.querySelector('.cf-uniq-week').textContent  = 'Error';
    }
  }

  loadCFStats();
})();
</script>
