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
      !function(f,b,e,v,n,t,s){/* Script FB Pixel Standard */}
      (window,document,"script","https://connect.facebook.net/en_US/fbevents.js");
      fbq("init", FB_PIXEL_ID);
      fbq("track", "PageView");
    }
  }

  async function saveConsent(rejectAll = false) {
    const cats = {
      necessary: true,
      analytics: !rejectAll,
      marketing: !rejectAll,
    };

    const btnAccept  = document.getElementById("btnAcceptConsent");
    const btnReject  = document.getElementById("btnRejectConsent");
    const btnDismiss = document.getElementById("btnDismissConsent");
    const originalAccept = btnAccept?.innerHTML;
    const originalReject = btnReject?.innerHTML;

    if (btnAccept)  { btnAccept.disabled  = true; btnAccept.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>'; }
    if (btnReject)  { btnReject.disabled  = true; btnReject.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>'; }
    if (btnDismiss) { btnDismiss.disabled = true; }

    try {
      await fetch("/api/api-consent.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ consent_given: !rejectAll, categories: cats }),
      });

      dismissBanner();
      flattyToast("success", rejectAll ? "Preferensi cookie disimpan." : "Cookie diterima!");
      if (!rejectAll) injectTracking(cats);

    } catch (err) {
      flattyToast("error", "Gagal menyimpan preferensi cookie.");
      console.error("Consent error:", err);

      if (btnAccept)  { btnAccept.disabled  = false; btnAccept.innerHTML  = originalAccept; }
      if (btnReject)  { btnReject.disabled  = false; btnReject.innerHTML  = originalReject; }
      if (btnDismiss) { btnDismiss.disabled = false; }
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    const banner = document.getElementById("consentBanner");
    if (!banner) return;

    window.addEventListener("scroll", showBannerOnScroll);

    document.getElementById("btnDismissConsent")?.addEventListener("click", () => saveConsent(true));
    document.getElementById("btnAcceptConsent")?.addEventListener("click", () => saveConsent(false));
    document.getElementById("btnRejectConsent")?.addEventListener("click", () => saveConsent(true));
  });
})();