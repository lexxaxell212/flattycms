(function () {
  "use strict";

  let bannerShown = false;

  function showBannerOnScroll() {
    const banner = document.getElementById("consentBanner");
    if (!banner || bannerShown) return;
    if (window.scrollY > 400) {
      banner.classList.add("show");
      bannerShown = true;
      window.removeEventListener("scroll", showBannerOnScroll);
    }
  }

  function dismissBanner() {
    const banner = document.getElementById("consentBanner");
    if (banner) banner.classList.remove("show");
  }

  function injectTracking(categories) {
    if (GTAG_ID) {
      const gaScript = document.createElement("script");
      gaScript.src = `https://www.googletagmanager.com/gtag/js?id=${GTAG_ID}`;
      gaScript.async = true;
      document.head.appendChild(gaScript);
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag("js", new Date());
      gtag("config", GTAG_ID);
    }

    if (FB_PIXEL_ID && categories.marketing) {
      !function(f,b,e,v,n,t,s){
        if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)
      }(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', FB_PIXEL_ID);
      fbq('track', 'PageView');
    }
  }

  async function saveConsent(rejectAll = false, clickedBtn = null, silent = false) {
    const cats = {
      necessary: true,
      analytics: !rejectAll,
      marketing: !rejectAll,
    };

    const btnDismiss = document.getElementById("btnDismissConsent");
    const originalHTML = clickedBtn?.innerHTML;

    if (clickedBtn) { clickedBtn.disabled = true; clickedBtn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>'; }
    if (btnDismiss) { btnDismiss.disabled = true; }

    try {
      await new Promise((resolve) => setTimeout(resolve, 1000));
      await fetch("/api/api-consent.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ consent_given: !rejectAll, categories: cats }),
      });

      dismissBanner();
      if (!silent) {
        flattyToast("success", rejectAll ? "Preferensi disimpan." : "Terima kasih.");
      }
      if (!rejectAll) injectTracking(cats);

    } catch (err) {
      if (!silent) {
        flattyToast("error", "Gagal menyimpan preferensi cookie.");
      }
      console.error("Consent error:", err);

      if (clickedBtn) { clickedBtn.disabled = false; clickedBtn.innerHTML = originalHTML; }
      if (btnDismiss) { btnDismiss.disabled = false; }
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    const banner = document.getElementById("consentBanner");
    if (!banner) return;

    window.addEventListener("scroll", showBannerOnScroll);

    document.getElementById("btnDismissConsent")?.addEventListener("click", function () { saveConsent(true, null, true); });
    document.getElementById("btnAcceptConsent")?.addEventListener("click", function () { saveConsent(false, this); });
    document.getElementById("btnRejectConsent")?.addEventListener("click", function () { saveConsent(true, this); });
  });
})();