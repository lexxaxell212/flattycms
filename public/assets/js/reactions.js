(function() {
    const btn = document.getElementById('btn-reaction');
    if (!btn) return;
    
    const isLoggedIn = CONFIG.isLoggedIn;
    
    if (!isLoggedIn) {
        btn.addEventListener('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Login dulu ya~',
                text: 'Yuk masuk pakai Google buat kasih ❤️',
                confirmButtonText: 'Masuk',
                showCancelButton: true,
                cancelButtonText: 'Nanti dulu',
            }).then(result => {
                if (result.isConfirmed) window.location.href = '/api/auth/google.php';
            });
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
            body: new URLSearchParams({
                content_type: 'blog',
                content_id: btn.dataset.id,
                csrf_token: CONFIG.csrfToken
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({ icon: 'error', title: 'Ups!', text: data.message, timer: 2000, showConfirmButton: false });
                return;
            }
            document.getElementById('reaction-count').textContent = data.count;
            btn.classList.toggle('btn-danger', data.liked);
            btn.classList.toggle('btn-outline-danger', !data.liked);
        });
    });
})();