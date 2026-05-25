function saveConsent(all = true, rejectAll = false) {
    const cats = {
        necessary: true,
        analytics: rejectAll ? false : (all || document.getElementById('check_analytics').checked),
        marketing: rejectAll ? false : (all || document.getElementById('check_marketing').checked)
    };

    fetch("/api/api-consent.php", {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ consent_given: !rejectAll, categories: cats })
    })
    .then(() => location.reload())
    .catch(err => console.error('Consent error:', err));
}