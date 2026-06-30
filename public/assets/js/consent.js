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

document.addEventListener('DOMContentLoaded', () => {
  const banner = document.getElementById('consentBanner');
  if (!banner) return;
  window.addEventListener('scroll', showBannerOnScroll);
});

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
  .then(() => location.reload())
  .catch(err => console.error('Consent error:', err));
}