function sanitize(str) {
    if (!str) return '-';
    const map = { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' };
    return String(str).replace(/[&<>"']/g, m => map[m]);
}

let lastSubmit = 0;
const RATE_LIMIT_MS = 5000;

document.getElementById('feedback-rating').addEventListener('input', function() {
    const val = parseInt(this.value);
    const badge = document.getElementById('feedback-ratingValue');
    badge.textContent = val;
    badge.className = `badge fs-6 px-3 py-2 fw-bold ${val >= 8 ? 'bg-success' : val >= 6 ? 'bg-warning' : 'bg-danger'}`;
});

document.getElementById('feedback-feedbackFormMain').addEventListener('submit', async function(e) {
    e.preventDefault();

    if (Date.now() - lastSubmit < RATE_LIMIT_MS) {
        const remaining = Math.ceil((RATE_LIMIT_MS - (Date.now() - lastSubmit)) / 1000);
        alert(`Tunggu ${remaining} detik sebelum kirim lagi`);
        return;
    }
    lastSubmit = Date.now();

    const form      = document.getElementById('feedback-feedbackForm');
    const successMsg = document.getElementById('feedback-successMsg');
    const submitBtn  = this.querySelector('button[type="submit"]');
    const btnTextEl  = document.getElementById('feedback-btnText');
    const spinner    = document.getElementById('feedback-loadingSpinner');

    submitBtn.disabled = true;
    btnTextEl.textContent = 'Mengirim...';
    spinner.classList.remove('d-none');

    try {
        const formData = new FormData(this);
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000);

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
                `Nama: ${sanitize(formData.get('nama'))}<br>` +
                `Rating: ${sanitize(String(formData.get('rating')))}/10<br>` +
                `Kategori: ${sanitize(formData.get('kategori'))}<br>` +
                `Waktu: ${new Date().toLocaleString('id-ID')}`;

            form.classList.add('d-none');
            successMsg.classList.remove('d-none');
            successMsg.scrollIntoView({ behavior: 'smooth' });
        } else {
            throw new Error(data.error || 'Server error');
        }
    } catch (error) {
        let errorMsg = 'Gagal mengirim feedback';
        if (error.name === 'AbortError') errorMsg = 'Timeout 10 detik. Cek koneksi internet';
        else if (error.name === 'TypeError') errorMsg = 'Tidak bisa connect ke server';
        else errorMsg += ': ' + error.message;
        alert(errorMsg);
    } finally {
        submitBtn.disabled = false;
        btnTextEl.textContent = 'Kirim Feedback';
        spinner.classList.add('d-none');
    }
});