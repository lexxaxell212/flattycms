function sanitize(str) {
    if (!str) return '-';
    const map = { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' };
    return String(str).replace(/[&<>"']/g, m => map[m]);
}

let lastSubmit = 0;
const RATE_LIMIT_MS = 5000;

document.getElementById('feedback-anonymous').addEventListener('change', function() {
    document.getElementById('feedback-submitBtn').disabled = !this.checked;
});

document.getElementById('feedback-rating').addEventListener('input', function() {
    const val   = parseInt(this.value);
    const badge = document.getElementById('feedback-ratingValue');
    badge.textContent = val;
    badge.className   = `badge fs-6 px-3 py-2 fw-bold ${val >= 8 ? 'bg-success' : val >= 6 ? 'bg-warning' : 'bg-danger'}`;
});

document.getElementById('feedback-feedbackFormMain').addEventListener('submit', async function(e) {
    e.preventDefault();

    if (Date.now() - lastSubmit < RATE_LIMIT_MS) {
        const remaining = Math.ceil((RATE_LIMIT_MS - (Date.now() - lastSubmit)) / 1000);
        flattyToast('warning', `Tunggu ${remaining} detik sebelum kirim lagi.`);
        return;
    }
    lastSubmit = Date.now();

    const form       = document.getElementById('feedback-feedbackForm');
    const successMsg = document.getElementById('feedback-successMsg');
    const submitBtn  = document.getElementById('feedback-submitBtn');
    const btnTextEl  = document.getElementById('feedback-btnText');
    const spinner    = document.getElementById('feedback-loadingSpinner');

    submitBtn.disabled = true;
    btnTextEl.textContent = 'Mengirim...';
    spinner.classList.remove('d-none');

    try {
        const formData   = new FormData(this);
        const controller = new AbortController();
        const timeoutId  = setTimeout(() => controller.abort(), 10000);

        const response = await fetch('/api/api-feedback.php', {
            method: 'POST',
            body: formData,
            signal: controller.signal,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        clearTimeout(timeoutId);
        const data = await response.json();

        if (data.success) {
            document.getElementById('feedback-summaryDetail').innerHTML =
                `Rating: ${sanitize(String(formData.get('rating')))}/10<br>` +
                `Kategori: ${sanitize(formData.get('kategori'))}<br>` +
                `Waktu: ${new Date().toLocaleString('id-ID')}`;

            form.classList.add('d-none');
            successMsg.classList.remove('d-none');
            successMsg.scrollIntoView({ behavior: 'smooth' });
        } else {
            throw new Error(data.message || data.error || 'Server error');
        }
    } catch (error) {
        let msg = 'Gagal mengirim feedback';
        if (error.name === 'AbortError')     msg = 'Timeout 10 detik. Cek koneksi internet.';
        else if (error.name === 'TypeError') msg = 'Tidak bisa connect ke server.';
        else msg += ': ' + error.message;
        flattyToast('error', msg);
    } finally {
        submitBtn.disabled = false;
        btnTextEl.textContent = 'Kirim Feedback';
        spinner.classList.add('d-none');
    }
});