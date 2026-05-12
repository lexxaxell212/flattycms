document.addEventListener('DOMContentLoaded', function() {
    const allowed = ['gmail.com', 'googlemail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'proton.me'];
    const form = document.getElementById('newsletterForm');
    const input = document.getElementById('emailInput');
    const btn = document.getElementById('submitBtn');

    if (!form) return;

    const notify = (msg, icon = 'success') => {
    Swal.fire({
        text: msg,
        icon: icon,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#fff',
        color: '#1a2478',
        });
    };


    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = input.value.trim();
        const domain = email.split('@')[1];

        if (!allowed.includes(domain)) {
            notify('Gunakan email umum ya (Gmail/Yahoo/Outlook)', 'warning');
            return;
        }

        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';

        const formData = new FormData(form);

        fetch(CONFIG.baseUrl + '/api/api-newsletter.php', {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                notify(data.message, 'success');
                input.value = '';
            } else {
                notify(data.message, 'error');
            }
        })
        .catch(() => {
            notify('Koneksi lagi bermasalah nih..', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.innerHTML = 'BERLANGGANAN <i class="fas fa-paper-plane"></i>';
        });
    });
});
