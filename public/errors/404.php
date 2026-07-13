<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

http_response_code(404);
$page_title = "404";

require_once SRC_PATH . "headerv2.php";
?>
<main class="main-content">
 <div class="container">
  <div class="tp-main-hero"></div>
  <div class="tp-main-outer" style="padding-top:0">
   <style>
    code {
     background: var(--bg-primary);
     padding: 2px 6px;
     border-radius: 4px;
     word-break: break-all;
     color: var(--text-white);
     font-size: 0.8rem;
    }
    body {
     background: transparent;
    }

:root {
     --fl-x: 50%;
     --fl-y: 40%;
     --fl-amber: #ffc98a;
    }

    .stage404 {
     position: fixed;
     inset: 0;
     z-index: -1;
     background: #0b0e14;
     overflow: hidden;
     top: var(--navbar-height);
    }

    .stage404 img.bg404 {
     position: absolute;
     inset: 0;
     width: 100%;
     height: 100vh;
     object-fit: cover;
     filter: saturate(0.9) brightness(0.85);
    }

    .card {
     opacity: 0.2;
     filter: blur(4px);
     transition: opacity 0.3s ease, filter 0.3s ease;
    }

    .card:hover {
     opacity: 0.85;
     filter: blur(0);
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
     box-shadow: 0 0 12px 4px rgba(255, 201, 138, 0.5);
     opacity: 0.5;
     animation: flicker404 4s infinite ease-in-out;
    }
    @keyframes flicker404 {
     0%,
     100% {
      opacity: 0.35;
     }
     50% {
      opacity: 0.7;
     }
    }

    .flashlight404 {
     position: absolute;
     inset: 0;
     background: #05070b;
     pointer-events: none;
     -webkit-mask-image: radial-gradient(
     circle at var(--fl-x) var(--fl-y),
     rgba(0, 0, 0, 0.35) 22px,
     rgba(0, 0, 0, 0.45) 65px,
     rgba(0, 0, 0, 0.75) 130px,
     rgba(0, 0, 0, 0.95) 200px
     );
     mask-image: radial-gradient(
     circle at var(--fl-x) var(--fl-y),
     rgba(0, 0, 0, 0.35) 22px,
     rgba(0, 0, 0, 0.45) 65px,
     rgba(0, 0, 0, 0.75) 130px,
     rgba(0, 0, 0, 0.95) 200px
     );
    }

    @media (min-width: 1200px) {
     .flashlight404 {
      -webkit-mask-image: radial-gradient(
      circle at var(--fl-x) var(--fl-y),
      rgba(0, 0, 0, 0.35) 50px,
      rgba(0, 0, 0, 0.45) 150px,
      rgba(0, 0, 0, 0.75) 300px,
      rgba(0, 0, 0, 0.95) 400px
      );
      mask-image: radial-gradient(
      circle at var(--fl-x) var(--fl-y),
      rgba(0, 0, 0, 0.35) 50px,
      rgba(0, 0, 0, 0.45) 150px,
      rgba(0, 0, 0, 0.75) 300px,
      rgba(0, 0, 0, 0.95) 400px
      );
     }
    }
   </style>
   <div class="stage404" id="stage404">
    <img src="/assets/images/forest.webp" alt="" class="bg404" />
   <div class="flashlight404"></div>
  </div>
  <div style="height: 100vh" class="row align-items-center">
   <div class="col-12 col-md-5 mx-auto">
    <div class="card" style="background:rgba(10,10,10,0.2);border:1px solid rgba(50,50,50,0.5);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)">
     <div class="py-4 px-3">
      <h1 class="text-hero text-white mb-4">
       <span
        class="me-2"
        style="
        opacity: 0.85;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1.5px;
        "
        >It's a.. </span
       >404
      </h1>
      <code><?= BASE_URL . ltrim($_SERVER['REQUEST_URI'], '/') ?></code>
      <p class="text-white mt-2 mb-4 small">
       Hmm, halaman yang kamu tuju tidak ada. Beritahu kami untuk segera
       memperbaikinya.
      </p>
      <button id="btnReport404" class="btn btn-outline-white">
       Laporkan
      </button>
     </div>
    </div>
   </div>
  </div>
  <script>
   (function () {
    const root = document.documentElement;
    function setLight(x, y) {
     root.style.setProperty("--fl-x", x + "px");
     root.style.setProperty("--fl-y", y + "px");
    }
    setLight(window.innerWidth / 2, window.innerHeight * 0.4);

    window.addEventListener("mousemove", e =>
     setLight(e.clientX, e.clientY)
    );

    window.addEventListener(
     "touchmove",
     e => {
      if (e.target.closest("main.main-content")) return;
      if (e.touches.length > 0)
       setLight(e.touches[0].clientX, e.touches[0].clientY);
     },
     {
      passive: true
     }
    );

    window.addEventListener(
     "touchstart",
     e => {
      if (e.target.closest("main.main-content")) return;
      if (e.touches.length > 0)
       setLight(e.touches[0].clientX, e.touches[0].clientY);
     },
     {
      passive: true
     }
    );
   })();

   // Report broken link
   let lastReportSubmit = 0;
   const REPORT_RATE_LIMIT_MS = 10000;

   document
   .getElementById("btnReport404")
   .addEventListener("click", async function () {
    if (Date.now() - lastReportSubmit < REPORT_RATE_LIMIT_MS) {
     const remaining = Math.ceil(
      (REPORT_RATE_LIMIT_MS - (Date.now() - lastReportSubmit)) / 1000
     );
     flattyToast("warning", `${remaining} detik sebelum kirim lagi.`);
     return;
    }
    lastReportSubmit = Date.now();

    const btn = this;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML =
    '<div class="btn-fetch"><span></span><span></span><span></span></div>';

    try {
     await new Promise(r => setTimeout(r, 1000));
     const controller = new AbortController();
     const timeoutId = setTimeout(() => controller.abort(), 10000);

     const response = await fetch("/api/api-feedback.php", {
      method: "POST",
      body: new URLSearchParams( {
       kritik: `Link rusak/404: ${window.location.href}`,
       saran: "",
       kategori: "broken_link",
       rating: "0"
      }),
      signal: controller.signal,
      headers: {
       "X-Requested-With": "XMLHttpRequest"
      }
     });

     clearTimeout(timeoutId);
     const data = await response.json();

     if (data.success) {
      flattyToast("success", "Laporan terkirim, terima kasih!");
      btn.innerHTML = '<i class="fas fa-check"></i> Terkirim';
     } else {
      throw new Error(data.message || data.error || "Server error");
     }
    } catch (error) {
     let msg = "Gagal mengirim laporan";
     if (error.name === "AbortError")
      msg = "Timeout 10 detik. Cek koneksi internet.";
     else if (error.name === "TypeError")
      msg = "Tidak bisa connect ke server.";
     else msg += ": " + error.message;
     flattyToast("error", msg);
     btn.disabled = false;
     btn.innerHTML = originalHtml;
    }
   });
  </script>
 </div>
</div>
</main>
<?php
require_once SRC_PATH . "footer.php";
?>