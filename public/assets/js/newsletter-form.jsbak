document.addEventListener('DOMContentLoaded', function() {
    const allowed = ['gmail.com', 'googlemail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'proton.me'];
    const form  = document.getElementById('newsletterForm');
    const input = document.getElementById('emailInput');
    const btn   = document.getElementById('submitBtn');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email  = input.value.trim();
        const domain = email.split('@')[1];
        if (!allowed.includes(domain)) {
            flattyToast('warning', 'Gunakan email umum ya (Gmail/Yahoo/Outlook)');
            return;
        }
        btn.disabled      = true;
        btn.style.opacity = '0.7';
        btn.innerHTML     = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
        const formData = new FormData(form);
        fetch(CONFIG.baseUrl + '/api/api-newsletter.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                flattyToast('success', data.message);
                input.value = '';
            } else {
                flattyToast('error', data.message);
            }
        })
        .catch(() => {
            flattyToast('error', 'Koneksi lagi bermasalah nih..');
        })
        .finally(() => {
            btn.disabled      = false;
            btn.style.opacity = '1';
            btn.innerHTML     = 'BERLANGGANAN <i class="fa-solid fa-paper-plane"></i>';
        });
    });
});