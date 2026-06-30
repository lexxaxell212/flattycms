let bannerShown = false;

function showBannerOnScroll() {
  const banner = document.getElementById('consentBanner');
  if (!banner || bannerShown) return;
  if (window.scrollY > 200) {
    banner.classList.add('show');
    bannerShown = true;
    window.removeEventListener('scroll', showBannerOnScroll);
  }
}

function dismissBanner() {
  const banner = document.getElementById('consentBanner');
  if (banner) banner.classList.remove('show');
}

function saveConsent(all = true, rejectAll = false) {
  const cats = {
    necessary: true,
    analytics: !rejectAll,
    marketing: !rejectAll
  };
  fetch("/api/api-consent.php", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({
      consent_given: !rejectAll, categories: cats
    })
  })
  .then(() => {
    flattyToast('success', rejectAll ? 'Preferensi cookie disimpan.' : 'Cookie diterima!');
    setTimeout(() => location.reload(), 1000);
  })
  .catch(err => {
    flattyToast('error', 'Gagal menyimpan preferensi cookie.');
    console.error('Consent error:', err);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const banner = document.getElementById('consentBanner');
  if (!banner) return;

  window.addEventListener('scroll', showBannerOnScroll);

  document.getElementById('btnDismissConsent')?.addEventListener('click', dismissBanner);
  document.getElementById('btnAcceptConsent')?.addEventListener('click', () => saveConsent(true));
  document.getElementById('btnRejectConsent')?.addEventListener('click', () => saveConsent(false, true));
});