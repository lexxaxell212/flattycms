const flattyIcons = {
  success: 'fa-circle-check',
  error:   'fa-circle-xmark',
  warning: 'fa-triangle-exclamation',
  info:    'fa-circle-info'
};

function flattyToast(type, message, position = 'top-end') {
  const container = document.getElementById(
    position === 'bottom' ? 'flatty-container-bottom' : 'flatty-container-top-end'
  );

  const toast = document.createElement('div');
  toast.className = `flatty-toast flatty-toast--${type}`;
  toast.innerHTML = `<i class="fa-solid ${flattyIcons[type] ?? 'fa-bell'}"></i><span>${message}</span>`;

  container.prepend(toast);

  requestAnimationFrame(() => {
    requestAnimationFrame(() => toast.classList.add('flatty-toast--show'));
  });

  setTimeout(() => {
    toast.classList.remove('flatty-toast--show');
    toast.classList.add('flatty-toast--hide');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function flattyConfirm(message, onConfirm, onCancel = null) {
  const overlay = document.createElement('div');
  overlay.className = 'flatty-overlay';
  overlay.innerHTML = `
    <div class="flatty-modal">
      <p class="flatty-modal__message">${message}</p>
      <div class="flatty-modal__actions">
        <button class="flatty-modal__btn flatty-modal__btn--cancel">Batal</button>
        <button class="flatty-modal__btn flatty-modal__btn--confirm">Konfirmasi</button>
      </div>
    </div>
  `;

  document.body.appendChild(overlay);

  requestAnimationFrame(() => {
    requestAnimationFrame(() => overlay.classList.add('flatty-overlay--show'));
  });

  overlay.querySelector('.flatty-modal__btn--confirm').addEventListener('click', () => {
    flattyClose(overlay);
    if (onConfirm) onConfirm();
  });

  overlay.querySelector('.flatty-modal__btn--cancel').addEventListener('click', () => {
    flattyClose(overlay);
    if (onCancel) onCancel();
  });
}

function flattyClose(overlay) {
  overlay.classList.remove('flatty-overlay--show');
  setTimeout(() => overlay.remove(), 250);
}

/** usage
flattyToast('success', 'Data berhasil disimpan!');
flattyToast('error', 'Email sudah digunakan.');
flattyToast('warning', 'Sesi akan segera berakhir.');
flattyToast('info', 'Update tersedia.');
flattyToast('success', 'Berhasil!', 'bottom');
flattyToast('error', 'Gagal upload.', 'bottom');
flattyConfirm('Yakin mau hapus ini?', () => {
action kalau confirm});
flattyConfirm('Yakin mau hapus ini?', 
   () => flattyToast('success', 'Dihapus!'),
  () => flattyToast('info', 'Dibatalkan.')
  );
flattyConfirm('Yakin logout?', () => {
   window.location.href = '/api/auth/logout.php';
  });
**/