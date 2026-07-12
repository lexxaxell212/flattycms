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

 /* ===== Flashlight background effect ===== */
:root {
  --fl-x: 50%;
  --fl-y: 40%;
  --fl-amber: #FFC98A;
 }

 .stage404 {
  position: fixed;
  inset: 0;
  z-index: -1;
  background: #0B0E14;
  overflow: hidden;
 }

 .stars404 {
  position: absolute;
  inset: 0;
  background-image:
  radial-gradient(1px 1px at 10% 20%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 25% 15%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 40% 30%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 60% 10%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 75% 25%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 90% 18%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 15% 40%, #fff 0, transparent 50%),
  radial-gradient(1px 1px at 85% 35%, #fff 0, transparent 50%);
  opacity: 0.35;
 }

 .skyline404 {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 38vh;
  display: flex;
  align-items: flex-end;
 }
 .skyline404 .bld {
  background: #161B26;
  flex-shrink: 0;
 }
 .skyline404 .bld.alt {
  background: #1F2531;
 }
 .skyline404 .tower {
  position: relative;
  width: 60px;
  height: 60%;
  background: #1F2531;
 }
 .skyline404 .tower::before {
  content: "";
  position: absolute;
  top: -28px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-bottom: 32px solid #1F2531;
 }
 .skyline404 .tower::after {
  content: "";
  position: absolute;
  top: -38px;
  left: 50%;
  transform: translateX(-50%);
  width: 8px;
  height: 14px;
  background: #1F2531;
 }

 .lamp-hint404 {
  position: absolute;
  bottom: 14vh;
  left: 50%;
  transform: translateX(-50%);
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--fl-amber);
  box-shadow: 0 0 12px 4px rgba(255,201,138,0.5);
  opacity: 0.5;
  animation: flicker404 4s infinite ease-in-out;
 }
 @keyframes flicker404 {
  0%, 100% {
   opacity: 0.35;
  }
  50% {
   opacity: 0.7;
  }
 }

 .flashlight404 {
  position: absolute;
  inset: 0;
  background: #05070B;
  pointer-events: none;
  -webkit-mask-image: radial-gradient(circle at var(--fl-x) var(--fl-y),
  transparent 0px,
  transparent 90px,
  rgba(0,0,0,0.55) 260px,
  black 460px);
  mask-image: radial-gradient(circle at var(--fl-x) var(--fl-y),
  transparent 0px,
  transparent 90px,
  rgba(0,0,0,0.55) 260px,
  black 460px);
 }

 @media (prefers-reduced-motion: reduce) {
  .lamp-hint404 {
   animation: none;
  }
 }

 @media (max-width: 600px) {
  .flashlight404 {
   -webkit-mask-image: radial-gradient(circle at var(--fl-x) var(--fl-y),
   transparent 0px, transparent 60px,
   rgba(0,0,0,0.55) 180px, black 340px);
   mask-image: radial-gradient(circle at var(--fl-x) var(--fl-y),
   transparent 0px, transparent 60px,
   rgba(0,0,0,0.55) 180px, black 340px);
  }
 }
</style>

<div class="stage404" id="stage404">
 <div class="stars404"></div>
 <div class="skyline404" id="skyline404"></div>
 <div class="lamp-hint404"></div>
 <div class="flashlight404"></div>
</div>

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
 // Build randomized skyline silhouette
 (function() {
  const skyline = document.getElementById('skyline404');
  const buildingCount = 14;
  let html = '';
  for (let i = 0; i < buildingCount; i++) {
   if (i === Math.floor(buildingCount / 2)) {
    html += '<div class="tower"></div>';
    continue;
   }
   const w = 30 + Math.random() * 50;
   const h = 25 + Math.random() * 65;
   const alt = Math.random() > 0.5 ? ' alt': '';
   html += `<div class="bld${alt}" style="width:${w}px;height:${h}%"></div>`;
  }
  skyline.innerHTML = html;

  const root = document.documentElement;
  function setLight(x, y) {
   root.style.setProperty('--fl-x', x + 'px');
   root.style.setProperty('--fl-y', y + 'px');
  }
  setLight(window.innerWidth / 2, window.innerHeight * 0.4);

  window.addEventListener('mousemove', (e) => setLight(e.clientX, e.clientY));
  window.addEventListener('touchmove', (e) => {
   if (e.touches.length > 0) setLight(e.touches[0].clientX, e.touches[0].clientY);
  },
   {
    passive: true
   });
  window.addEventListener('touchstart',
   (e) => {
    if (e.touches.length > 0) setLight(e.touches[0].clientX, e.touches[0].clientY);
   },
   {
    passive: true
   });
 })();

 // Report broken link
 let lastReportSubmit = 0;
 const REPORT_RATE_LIMIT_MS = 5000;

 document.getElementById('btnReport404').addEventListener('click', async function() {
  if (Date.now() - lastReportSubmit < REPORT_RATE_LIMIT_MS) {
   const remaining = Math.ceil((REPORT_RATE_LIMIT_MS - (Date.now() - lastReportSubmit)) / 1000);
   flattyToast('warning', `${remaining} sebelum kirim lagi.`);
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