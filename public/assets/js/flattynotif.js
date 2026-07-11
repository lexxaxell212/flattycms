const flattyIcons = {
 success: 'fa-circle-check',
 error: 'fa-circle-xmark',
 warning: 'fa-triangle-exclamation',
 info: 'fa-circle-info'
};

const flattyMessages = {
 'toast.fill.first': {
  id: 'Tulis dulu rencanamu',
  en: 'Write your plan first'
 },
 'toast.link.unset': {
  id: 'Belum ada link.',
  en: 'Link is not available yet.'
 },
 'toast.email.required': {
  id: 'Email-nya diisi dulu ya..',
  en: 'Email required.'
 },
 'toast.email.invalid': {
  id: 'Gunakan email umum ya (Gmail/Yahoo/Outlook)',
  en: 'Please use common email (Gmail/Yahoo/Outlook)'
 },
 'toast.username.required': {
  id: 'Nama pengguna hanya boleh huruf, angka, dan underscore.',
  en: 'Username only word, number and underscore allowed.'
 },
 'toast.password.required': {
  id: 'Kata sandi minimal 8 karakter.',
  en: 'Password minimal 8 character.'
 },
 'toast.password.confirm.required': {
  id: 'Konfirmasi kata sandi tidak cocok.',
  en: 'Password confirm did not match.'
 },
 'toast.email.password.required': {
  id: 'Email/nama pengguna dan kata sandi wajib diisi.',
  en: 'Email/username and password required.'
 },
 'toast.login.required': {
  id: 'Silakan masuk dulu',
  en: 'Please login first'
 },
 'toast.email.send.error': {
  id: 'Gagal mengirim email.',
  en: 'Failed send email.'
 },
 'toast.form.error': {
  id: 'Semua field wajib diisi.',
  en: 'All field required.'
 },
 'toast.password.error': {
  id: 'Gagal mengubah kata sandi.',
  en: 'Failed change password.'
 },
 'toast.new.account.error': {
  id: 'Pendaftaran gagal.',
  en: 'Failed to register.'
 },
 'toast.feedback.warning': {
  id: ' detik sebelum kirim lagi.',
  en: ' second before send again.'
 },
 'toast.connection.error': {
  id: 'Maaf, koneksi lagi bermasalah nih.',
  en: 'Sorry, connection error has occured.'
 },
 'toast.login.denied': {
  id: 'Email/nama pengguna atau password salah.',
  en: 'Email/username or password wrong.'
 },
 'toast.save.success': {
  id: 'Berhasil disimpan',
  en: 'Saved successfully'
 },
 'toast.reset.password.success': {
  id: 'Kata sandi berhasil diubah! Mengalihkan ke login...',
  en: 'Password changed successfully! Redirect to login...'
 },
 'toast.email.send.success': {
  id: 'Link reset password sudah dikirim. Cek inbox atau folder spam.',
  en: 'Reset password link sent. Please check your inbox or spam folder.'
 },
 'toast.new.account.success': {
  id: 'Akun berhasil dibuat! cek emailmu untuk verifikasi sebelum login.',
  en: 'Account succesfully created! please check your email to verify before login.'
 },
 'toast.delete.confirm': {
  id: 'Item berhasil dihapus',
  en: 'Item deleted successfully'
 },
 'confirm.btn.cancel': {
  id: 'Batal',
  en: 'Cancel'
 },
 'confirm.btn.confirm': {
  id: 'Konfirmasi',
  en: 'Confirm'
 }
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

 const resolvedMessage = flattyMessages[message] ? flattyText(message): message;

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
 const resolvedMessage = flattyMessages[message] ? flattyText(message): message;

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