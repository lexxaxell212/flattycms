document.addEventListener('DOMContentLoaded', function() {
    const allowed = ['gmail.com', 'googlemail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'proton.me'];
    const form = document.getElementById('newsletterForm');
    const input = document.getElementById('emailInput');
    const btn = document.getElementById('submitBtn');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = input.value.trim();
        const domain = email.split('@')[1];

        if (!allowed.includes(domain)) {
            alert('Gunakan domain email umum (Gmail/Yahoo/Outlook)');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData(form);
        formData.append('action', 'newsletter');

        fetch(CONFIG.baseUrl + 'newsletter-ajax.php', {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) input.value = '';
        })
        .catch(() => alert('Gangguan koneksi!'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        });
    });
});