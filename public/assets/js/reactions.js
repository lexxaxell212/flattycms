(function() {
    const btn = document.getElementById('btn-reaction');
    if (!btn || !<?= isset($_SESSION['user']) ? 'true' : 'false' ?>) return;

    btn.addEventListener('click', function() {
        fetch('/api/api-reactions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                content_type: 'blog',
                content_id: btn.dataset.id,
                csrf_token: document.querySelector('meta[name="csrf-token"]').content
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            document.getElementById('reaction-count').textContent = data.count;
            btn.classList.toggle('btn-danger', data.liked);
            btn.classList.toggle('btn-outline-danger', !data.liked);
        });
    });
})();