<script src="<?= JS_URL ?>part-gallery.js" defer></script>
<div class="container">
 <section id="poi-gallery-part">
  <div class="d-flex align-items-end justify-content-between mb-4">
   <div>
    <span class="text-eyebrow" data-bhs="gal.eyebrow">GALLERY</span>
    <h2 class="text-sub-hero">Traveler <em class="styled">Mom</em>ents</h2>
   </div>
   <a href="/gallery" class="link-all">
    <span data-bhs="btn.all">Lihat Semua</span> <i class="fas fa-arrow-right ms-2"></i>
   </a>
  </div>
  <div class="poi-gallery-masonry" id="poiGalleryGrid">
   <?php for ($i = 0; $i < 3; $i++): ?>
   <div class="poi-gallery-card poi-gallery-skeleton"></div>
   <?php endfor; ?>
  </div>
 </section>
</div>