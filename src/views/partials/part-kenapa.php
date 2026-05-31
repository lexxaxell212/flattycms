<?php
require_once LIB_PATH . "v-kenapa.php";

$_khb_icons = [
    'Pusat Inovasi Digital'    => '<path d="M12 2a7 7 0 0 1 7 7c0 3.17-2.11 5.84-5 6.71V17h-4v-1.29C7.11 14.84 5 12.17 5 9a7 7 0 0 1 7-7zm0 2a5 5 0 0 0-5 5c0 2.38 1.63 4.41 4 4.9V15h2v-1.1c2.37-.49 4-2.52 4-4.9a5 5 0 0 0-5-5zm-1 13h2v2h-2z"/>',
    'Konektivitas Kilat'        => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>',
    'Ikon Kuliner Global'       => '<path d="M3 2v7c0 2.76 2.24 5 5 5h1v6h2v-6h1c2.76 0 5-2.24 5-5V2h-2v5h-2V2h-2v5H9V2H3zm15 0h-2v9h2V2z"/>',
    'Wisata Alam Estetik'       => '<path d="M14 6l-1-2H5v17h2v-7h5l1 2h7V6h-6zm4 8h-4l-1-2H7V6h5l1 2h5v6z"/><path d="M8.5 11.5l1.5-2 1.5 2 2.5-3.5L17 13H7l1.5-2.5z"/>',
    'Kiblat Fashion Lokal'      => '<path d="M12 3C9.24 3 7 5.24 7 8c0 1.85 1.01 3.45 2.5 4.33V21h5v-8.67C15.99 11.45 17 9.85 17 8c0-2.76-2.24-5-5-5zm0 2c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3zm-1.5 8.5h3V19h-3v-5.5z"/>',
    'Arsitektur Bersejarah'     => '<path d="M12 3L2 9v1h2v9h3v-6h6v6h3V10h2V9L12 3zm0 2.31L19 9.5V10h-1v9h-1v-6H7v6H6V10H5v-.5L12 5.31z"/><path d="M10 14h4v5h-4z" opacity=".3"/>',
    'Kultur Ngopi yang Kuat'    => '<path d="M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z"/>',
    'Ruang Publik Inklusif'     => '<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>',
    'Event Seni Internasional'  => '<path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zm1-11h-2v3H8v2h3v3h2v-3h3v-2h-3V8z"/><circle cx="12" cy="12" r="2" opacity=".5"/>',
    'Udara Sejuk Menenangkan'   => '<path d="M12 4C9.24 4 7 6.24 7 9c0 2.85 2.22 5.19 5 5.46V18H9v2h6v-2h-3v-3.54c2.78-.27 5-2.61 5-5.46 0-2.76-2.24-5-5-5zm0 8c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"/><path d="M6.5 10.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm11 0c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zM12 2c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5S12.28 2 12 2z"/>',
];

$_khb_colors = [
    '#7c3aed', '#6d28d9', '#4f46e5', '#7c3aed',
    '#5b21b6', '#4338ca', '#6d28d9', '#7c3aed',
    '#4f46e5', '#5b21b6',
];
?>
<?php if (!empty($_khb_items)): ?>
<section class="khb-section">
  <div class="container">
    <div class="khb-header">
      <div class="khb-header__left">
        <span class="text-eyebrow">
          Discover Bandung
        </span>
        <h2 class="text-sub-hero">
          Kenapa Harus Bandung?
        </h2>
      </div>
      <div>
        <p>Bandung 2026: Perpaduan sempurna inovasi digital, kesejukan alam, dan kreativitas kuliner terbaik.</p>
      </div>
    </div>
    <div class="khb-grid">
      <?php foreach ($_khb_items as $i => $_khb_item):
        $title   = htmlspecialchars($_khb_item['title'] ?? 'Untitled');
        $excerpt = htmlspecialchars(substr($_khb_item['excerpt'] ?? '', 0, 120));
        $icon    = $_khb_icons[$title] ?? '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>';
        $color   = $_khb_colors[$i % count($_khb_colors)];
        $num     = str_pad(count($_khb_items) - $i, 2, '0', STR_PAD_LEFT);
        $is_wide = in_array($i, [0, 4, 8]);
      ?>
      <div class="khb-card <?= $is_wide ? 'khb-card--wide' : '' ?>">
        <div class="khb-card__icon" style="--card-color:<?= $color ?>">
          <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <?= $icon ?>
          </svg>
        </div>
        <div class="khb-card__content">
          <h3 class="khb-card__title"><?= $title ?></h3>
          <?php if ($excerpt): ?>
          <p class="khb-card__excerpt"><?= $excerpt ?></p>
          <?php endif; ?>
        </div>
        <div class="khb-card__bar" style="background:<?= $color ?>"></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="khb-cta">
      <div class="khb-cta__inner">
        <h3>Siap Petualangan ke Bandung ?</h3>
        <p>Jadwal akhir pekan sudah penuh? Booking sekarang sebelum ketinggalan!</p>
        <div class="khb-cta__btns">
          <a href="https://google.com/search?q=Tiket+ke+bandung" target="_blank"
          rel="noopener" class="btn btn-outline-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
            Pesan Tiket
          </a>
          <a href="https://google.com/search?q=Booking+hotel+bandung"
          target="_blank" rel="noopener" class="btn btn-outline-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
            Cari Hotel
          </a>
          <a href="/trip" class="mt-2 btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Mulai Rencanakan
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>