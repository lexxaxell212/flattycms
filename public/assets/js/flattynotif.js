const flattyIcons = {
  success: 'fa-circle-check',
  error: 'fa-circle-xmark',
  warning: 'fa-triangle-exclamation',
  info: 'fa-circle-info'
};

const flattyMessages = {
  'toast.fill.first': { id: 'Tulis dulu rencanamu', en: 'Write your plan first' },
  'toast.login.required': { id: 'Silakan masuk dulu', en: 'Please login first' },
  'toast.save.success': { id: 'Berhasil disimpan', en: 'Saved successfully' },
  'toast.delete.confirm': { id: 'Item berhasil dihapus', en: 'Item deleted successfully' },
  'confirm.btn.cancel': { id: 'Batal', en: 'Cancel' },
  'confirm.btn.confirm': { id: 'Konfirmasi', en: 'Confirm' }
};

function flattyText(key) {
  const lang = localStorage.getItem('lang') || 'id';
  const entry = flattyMessages[key];
  if (!entry) return key;
  return entry[lang] || entry.id;
}

function flattyToast(type, message, position = 'top-end') {
  const container = document.getElementById(
    position === 'bottom' ? 'flatty-container-bottom': 'flatty-container-top-end'
  );

  const resolvedMessage = flattyMessages[message] ? flattyText(message) : message;

  const toast = document.createElement('div');
  toast.className = `flatty-toast flatty-toast--${type}`;
  toast.innerHTML = `<i class="fa-solid ${flattyIcons[type] ?? 'fa-bell'}"></i><span>${resolvedMessage}</span>`;

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
  const resolvedMessage = flattyMessages[message] ? flattyText(message) : message;

  const overlay = document.createElement('div');
  overlay.className = 'flatty-overlay';
  overlay.innerHTML = `
  <div class="flatty-modal">
  <p class="flatty-modal__message">${resolvedMessage}</p>
  <div class="flatty-modal__actions">
  <button class="flatty-modal__btn flatty-modal__btn--cancel">${flattyText('confirm.btn.cancel')}</button>
  <button class="flatty-modal__btn flatty-modal__btn--confirm">${flattyText('confirm.btn.confirm')}</button>
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

  overlay.querySelector('.flatty-modal__btn--cancel').addEventListener('click',
    () => {
      flattyClose(overlay);
      if (onCancel) onCancel();
    });
}

function flattyClose(overlay) {
  overlay.classList.remove('flatty-overlay--show');
  setTimeout(() => overlay.remove(),
    250);
}