(function() {
  const btn = document.getElementById('btn-reaction');
  if (!btn) return;

  const isLoggedIn = CONFIG.isLoggedIn;

  if (!isLoggedIn) {
    btn.addEventListener('click', function() {
      flattyToast('info', 'Login dulu ya buat kasih ❤️');
    });
    return;
  }

  btn.addEventListener('click', function() {
    fetch('/api/api-reactions.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams( {
        content_type: btn.dataset.type,
        content_id: btn.dataset.id,
        csrf_token: CONFIG.csrfToken
      })
    })
    .then(r => r.json())
    .then(data => {
      if (!data.success) {
        flattyToast('error', data.message);
        return;
      }
      document.getElementById('reaction-count').textContent = data.count;
      btn.classList.toggle('btn-danger', data.liked);
      btn.classList.toggle('btn-outline-primary', !data.liked);
    });
  });
})();

function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    flattyToast('success', 'Link disalin!');
  });
}