//lang
(function() {
  const translations = {
    id: {
      //btn
      'btn.tp': 'Rencanakan',
      'btn.tp.2': 'Mulai Bikin Rute',
      'btn.ttd': 'Aktifitas',
      'btn.all': 'Lihat Semua',
      'btn.more': 'Selengkapnya',
      'btn.read.more': 'Baca Selengkapnya',
      'btn.it': 'Buat Itinerary',
      'btn.detail': 'Lihat Detail',
      "btn.check": "let's Check it",
      'btn.subs': 'Berlangganan',
      //nav
      'nav.home': 'Beranda',
      'nav.ue' : 'Event Mendatang',
      'nav.ttd': 'Aktifitas',
      'nav.tp': 'Perencana Perjalanan',
      'nav.gr': 'Galeri & Ulasan',
      'nav.b': 'Blog',
      //hero
      'hero.eyebrow': 'Jelajahi',
      'hero.title': 'Bandung',
      'hero.excerpt': 'Mulai dari mana? Mau kemana? Ngapain aja?',
      //part.ue
      'ue.eyebrow': 'Hightlight',
      'ue.title': 'Event Mendatang',
      'ue.rotate': 'Cek Sekarang | Event Terdekat | Segera Hadir',
      //part.khb
      'khb.eyebrow': 'Discover',
      'khb.title': 'Kenapa Harus Bandung?',
      'khb.excerpt': 'Bandung 2026: Perpaduan sempurna inovasi digital, kesejukan alam, dan kreativitas kuliner terbaik.',
      'khb.1.title': 'Surga Kuliner & Kafe Estetik',
      'khb.1.excerpt': 'Dari kaki lima legendaris hingga kafe aesthetic di dataran tinggi, Bandung selalu punya tren kuliner baru yang bikin ketagihan.',
      'khb.2.title': 'Pesona Alam & Udara Sejuk',
      'khb.2.excerpt': 'Dikelilingi Lembang dan Ciwidey - kebun teh, hutan pinus berkabut, dan udara sejuk yang sempurna untuk healing.',
      'khb.3.title': 'Kiblat Fashion & Kreativitas',
      'khb.3.excerpt': 'Dari factory outlet hingga brand lokal kreatif, Bandung adalah kiblat fashion dengan harga yang tetap ramah di kantong.',
      'khb.4.title': 'Romantisme Kota & Akses Cepat',
      'khb.4.excerpt': 'Arsitektur Art Deco klasik di Braga, dipadu kemudahan akses Kereta Cepat Whoosh - liburan akhir pekan yang praktis dan romantis.',
      //part.itinerary
      'it.eyebrow': 'Itinerary',
      'it.title': 'Buatin Dong',
      'it.excerpt': 'Ceritain mau ngpain di Bandung, biar AI yang susunin itinerary-nya.',
      'it.qp': 'Pilih Cepat',
      'it.qp.1': 'Kuliner',
      'it.qp.2': 'Alam',
      'it.qp.3': 'Belanja',
      'it.qp.4': 'Sejarah',
      'it.qp.5': 'Budget',
      'it.qp.6': 'Premium',
      'it.ticker': 'Rute wisata alam Lembang seharian... | Itinerary belanja factory outlet Bandung... | Buat itinerary kuliner Bandung 2 hari...',
      //part.wisata
      'w.eyebrow': 'Recommendations',
      'w.title': 'Wisata Favorit',
      //part.tp
      'tp.eyebrow': 'Perencana Perjalanan',
      'tp.title': 'Bikin Rencana Liburanmu di Bandung',
      'tp.excerpt': 'Dari kuliner legendaris hingga wisata alam tersembunyi, susun agenda liburan terbaikmu di Bandung dengan mudah.',
      'tp.li.1': 'Jelajahi ratusan tempat hits di Bandung',
      'tp.li.2': 'Atur & simpan rute sesukamu',
      'tp.li.3': 'Peta POI Bandung',
      //part.gallery
      'gal.eyebrow': 'Gallery',
      'gal.title': 'Momen Wisatawan',
      //part.hotel
      'h.eyebrow': 'Recommendations',
      'h.title': 'Hotel Favorit',
      //part.cta.1
      'cta.1.title': 'Bandung Menantimu!',
      'cta.1.excerpt': 'Persiapkan mulai hari ini, cari hotel atau rencanakan destinasi favoritmu.',
      //part.blogs
      'b.eyebrow': 'Blogs',
      'b.title': 'Artikel',
      'b.excerpt': 'Cerita, tips dan rekomendasi terbaik untuk perjalananmu.',
      //footer
      'f.newsletter.title': 'Newsletter',
      'f.newsletter.excerpt': 'Dapatkan event dan update Bandung terkini via email.',
      'f.newsletter.placeholder': 'nama@email.com',
      'f.pages.title': 'Halaman',
      'f.pages.1': 'Beranda',
      'f.pages.2': 'Blog',
      'f.pages.3': 'Sejarah Bandung',
      'f.pages.4': 'Layanan di Bandung',
      'f.info.title': 'Informasi',
      'f.info.1': 'Tentang',
      'f.info.2': 'Privasi',
      'f.info.3': 'Kritik dan Saran',
      'f.user.title': 'Untuk Pengunjung',
      'f.user.1': 'Aktifitas',
      'f.user.2': 'Perencana Perjalanan',
      'f.user.3': 'Galeri & Ulasan',
      'f.follow.title': 'Ikuti Kami',
    },
    en: {
      'home': 'Home',
      'newsletter.input': 'name@email.com',
      
    }
  };

  const savedLang = localStorage.getItem('lang') || 'id';
  function applyLang(lang) {
    const t = translations[lang];
    document.querySelectorAll('[data-bhs]').forEach(el => {
      const key = el.dataset.bhs;
      if (!t[key]) return;
      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        el.placeholder = t[key];
      } else {
        el.textContent = t[key];
      }
    });
    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.lang === lang);
    });
    localStorage.setItem('lang',
      lang);
    document.documentElement.lang = lang;
  }

  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', () => applyLang(btn.dataset.lang));
  });

  applyLang(savedLang);
})();
