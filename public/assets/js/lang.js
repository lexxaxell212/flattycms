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
      'btn.login': 'Masuk',
      'btn.reg': 'Daftar',
      'btn.logout': 'Keluar',
      //nav
      'nav.home': 'Beranda',
      'nav.ue': 'Event Mendatang',
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
      'it.qp.1.val': 'Kuliner',
      'it.qp.2.val': 'Alam',
      'it.qp.3.val': 'Belanja',
      'it.qp.4.val': 'Sejarah',
      'it.qp.5.val': 'Budget',
      'it.qp.6.val': 'Premium',
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
      //btn
      'btn.tp': 'Plan',
      'btn.tp.2': 'Start Building Route',
      'btn.ttd': 'Things To Do',
      'btn.all': 'View All',
      'btn.more': 'More Details',
      'btn.read.more': 'Read More',
      'btn.it': 'Create Itinerary',
      'btn.detail': 'View Details',
      "btn.check": "Let's Check it",
      'btn.subs': 'Subscribe',
      'btn.login': 'Login',
      'btn.reg': 'Register',
      'btn.logout': 'Logout',
      //nav
      'nav.home': 'Home',
      'nav.ue': 'Upcoming Events',
      'nav.ttd': 'Things To Do',
      'nav.tp': 'Trip Planner',
      'nav.gr': 'Gallery & Reviews',
      'nav.b': 'Blogs',
      //hero
      'hero.eyebrow': 'Explore',
      'hero.title': 'Bandung',
      'hero.excerpt': 'Where to start? Where to go? What to do?',
      //part.ue
      'ue.eyebrow': 'Highlight',
      'ue.title': 'Upcoming Events',
      'ue.rotate': 'Check Now | Nearest Event | Coming Soon',
      //part.khb
      'khb.eyebrow': 'Discover',
      'khb.title': 'Why Bandung?',
      'khb.excerpt': 'Bandung 2026: A perfect blend of digital innovation, cool nature, and the best culinary creativity.',
      'khb.1.title': 'Culinary Paradise & Aesthetic Cafes',
      'khb.1.excerpt': 'From legendary street food to aesthetic cafes in the highlands, Bandung always has new culinary trends that are addictive.',
      'khb.2.title': 'Natural Charm & Cool Air',
      'khb.2.excerpt': 'Surrounded by Lembang and Ciwidey - tea plantations, misty pine forests, and cool air perfect for healing.',
      'khb.3.title': 'Fashion & Creativity Hub',
      'khb.3.excerpt': 'From factory outlets to creative local brands, Bandung is a fashion hub with wallet-friendly prices.',
      'khb.4.title': 'City Romance & Fast Access',
      'khb.4.excerpt': 'Classic Art Deco architecture in Braga, combined with the ease of Whoosh High-Speed Rail - a practical and romantic weekend getaway.',
      //part.itinerary
      'it.eyebrow': 'Itinerary',
      'it.title': 'Make It For Me',
      'it.excerpt': 'Tell us what you want to do in Bandung, and let AI arrange your itinerary.',
      'it.qp': 'Quick Picks',
      'it.qp.1': 'Culinary',
      'it.qp.2': 'Nature',
      'it.qp.3': 'Shopping',
      'it.qp.4': 'History',
      'it.qp.5': 'Budget',
      'it.qp.6': 'Premium',
      'it.qp.1.val': 'Culinary',
      'it.qp.2.val': 'Nature',
      'it.qp.3.val': 'Shopping',
      'it.qp.4.val': 'History',
      'it.qp.5.val': 'Budget',
      'it.qp.6.val': 'Premium',
      'it.ticker': 'All-day Lembang nature tour... | Bandung factory outlet shopping itinerary... | Create a 2-day Bandung culinary itinerary...',
      //part.wisata
      'w.eyebrow': 'Recommendations',
      'w.title': 'Favorite Attractions',
      //part.tp
      'tp.eyebrow': 'Trip Planner',
      'tp.title': 'Plan Your Holiday in Bandung',
      'tp.excerpt': 'From legendary culinary spots to hidden natural gems, easily organize your best holiday agenda in Bandung.',
      'tp.li.1': 'Explore hundreds of trending spots in Bandung',
      'tp.li.2': 'Arrange & save your routes as you like',
      'tp.li.3': 'Bandung POI Map',
      //part.gallery
      'gal.eyebrow': 'Gallery',
      'gal.title': 'Traveler Moments',
      //part.hotel
      'h.eyebrow': 'Recommendations',
      'h.title': 'Favorite Hotels',
      //part.cta.1
      'cta.1.title': 'Bandung Awaits You!',
      'cta.1.excerpt': 'Prepare starting today, find hotels or plan your favorite destinations.',
      //part.blogs
      'b.eyebrow': 'Blogs',
      'b.title': 'Articles',
      'b.excerpt': 'Stories, tips, and best recommendations for your journey.',
      //footer
      'f.newsletter.title': 'Newsletter',
      'f.newsletter.excerpt': 'Get the latest Bandung events and updates via email.',
      'f.newsletter.placeholder': 'name@email.com',
      'f.pages.title': 'Pages',
      'f.pages.1': 'Home',
      'f.pages.2': 'Blog',
      'f.pages.3': 'History of Bandung',
      'f.pages.4': 'Services in Bandung',
      'f.info.title': 'Information',
      'f.info.1': 'About',
      'f.info.2': 'Privacy',
      'f.info.3': 'Feedback & Suggestions',
      'f.user.title': 'For Visitors',
      'f.user.1': 'Things To Do',
      'f.user.2': 'Trip Planner',
      'f.user.3': 'Gallery & Reviews',
      'f.follow.title': 'Follow Us',
    }
  };

  const savedLang = translations[localStorage.getItem('lang')] ? localStorage.getItem('lang') : 'id';

  function applyLang(lang) {
    const t = translations[lang] || translations.id;

    document.querySelectorAll('[data-bhs]').forEach(el => {
      const key = el.dataset.bhs;
      if (!t[key]) return;

      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        el.placeholder = t[key];
      } 
      else if (el.hasAttribute('data-ticker')) {
        el.setAttribute('data-ticker', t[key]);
        el.textContent = t[key].split('|')[0].trim();
      } 
      else if (el.querySelector('span[data-bhs]')) {
        return; 
      } 
      else if (el.hasAttribute('data-bhs-val')) {
        const valKey = el.getAttribute('data-bhs-val');
        if (t[valKey]) {
          el.setAttribute('data-val', t[valKey]);
        }
        if (el.children.length === 0) {
          el.textContent = t[key];
        }
      } 
      else {
        el.textContent = t[key];
      }
    });

    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.lang === lang);
    });

    localStorage.setItem('lang', lang);
    document.documentElement.lang = lang;
  }

  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', () => applyLang(btn.dataset.lang));
  });

  applyLang(savedLang);
  
})();
