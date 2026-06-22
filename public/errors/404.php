<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

http_response_code(404);
$page_title = "404";

require_once SRC_PATH . "headerv2.php";
?>
<style>
  code {
    background: var(--bg-accent-subtle);
    padding: 2px 6px;
    border-radius: 4px;
    word-break: break-all;
    color: var(--text-accent);
    font-size: 0.8rem;
  }
</style>
<div class="container row main-content min-vh-100 align-items-center">
  <div class="col-12 col-md-4 mx-auto">
    <div class="card card-flatty">
      <div class="card-body">
        <h1 class="text-hero mb-4"><span class="me-2" style="opacity:0.85;font-size:0.9rem;font-weight:500;letter-spacing:1.5px">You found a.. </span>404</h1>
        <code><?= BASE_URL . ltrim($_SERVER['REQUEST_URI'], '/') ?></code>
        <p class="text-muted mt-2 small">
          Halaman yang kamu tuju tidak ada. Beritahu kami untuk segera memperbaikinya.
        </p>
      </div>
      <div class="card-footer">
        <button id="btnReport404"class="btn btn-primary">Laporkan halaman ini</button>
      </div>
    </div>
  </div>
</div>
<script>
  let lastReportSubmit = 0;
  const REPORT_RATE_LIMIT_MS = 5000;

  document.getElementById('btnReport404').addEventListener('click', async function() {
    if (Date.now() - lastReportSubmit < REPORT_RATE_LIMIT_MS) {
      const remaining = Math.ceil((REPORT_RATE_LIMIT_MS - (Date.now() - lastReportSubmit)) / 1000);
      flattyToast('warning', `${remaining} toast.feedback.warning`);
      return;
    }
    lastReportSubmit = Date.now();

    const btn = this;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';

    try {
      await new Promise(r => setTimeout(r, 1000));
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000);

      const response = await fetch('/api/api-feedback.php', {
        method: 'POST',
        body: new URLSearchParams( {
          kritik: `Link rusak/404: ${window.location.href}`,
          saran: '',
          kategori: 'broken_link',
          rating: '0'
        }),
        signal: controller.signal,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      clearTimeout(timeoutId);
      const data = await response.json();

      if (data.success) {
        flattyToast('success', 'Laporan terkirim, terima kasih!');
        btn.innerHTML = '<i class="fas fa-check"></i> Terkirim';
      } else {
        throw new Error(data.message || data.error || 'Server error');
      }
    } catch (error) {
      let msg = 'Gagal mengirim laporan';
      if (error.name === 'AbortError') msg = 'Timeout 10 detik. Cek koneksi internet.';
      else if (error.name === 'TypeError') msg = 'Tidak bisa connect ke server.';
      else msg += ': ' + error.message;
      flattyToast('error', msg);
      btn.disabled = false;
      btn.innerHTML = originalHtml;
    }
  });
</script>
<?php
require_once SRC_PATH . "footer.php";
?>