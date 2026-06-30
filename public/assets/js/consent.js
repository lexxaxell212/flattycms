document.addEventListener('DOMContentLoaded', () => {
  const banner = document.getElementById('consentBanner');
  if (banner) requestAnimationFrame(() => banner.classList.add('show'));
});

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