<?php
$_khb_stmt  = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('trending') ORDER BY id DESC");
$_khb_items = $_khb_stmt->fetchAll(PDO::FETCH_ASSOC);

$_khb_icons = [

'Udara Sejuk Menenangkan' => '
  <g class="khb-icon-g">
    <circle cx="32" cy="22" r="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M18 34 C14 34 8 30 8 24 C8 18 13 14 19 15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M46 34 C50 34 56 30 56 24 C56 18 51 14 45 15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M20 42 Q32 36 44 42" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M24 50 Q32 45 40 50" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".6"/>
    <path d="M28 57 Q32 54 36 57" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <circle cx="32" cy="22" r="5" fill="currentColor" opacity=".15"/>
    <path d="M32 10 L32 7M32 37 L32 34M44 22 L47 22M20 22 L17 22M40.5 13.5 L42.6 11.4M23.5 13.5 L21.4 11.4M40.5 30.5 L42.6 32.6M23.5 30.5 L21.4 32.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
  </g>',

'Event Seni Internasional' => '
  <g class="khb-icon-g">
    <rect x="8" y="20" width="48" height="36" rx="4" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <path d="M8 28 L56 28" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
    <circle cx="20" cy="14" r="4" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <circle cx="44" cy="14" r="4" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <path d="M20 18 L20 28M44 18 L44 28" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M26 38 L30 42 L38 34" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M18 48 L30 48M18 48" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <path d="M36 48 L46 48" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <circle cx="48" cy="38" r="6" fill="currentColor" opacity=".1" stroke="currentColor" stroke-width="1.5"/>
    <path d="M45 38 L51 38M48 35 L48 41" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".6"/>
  </g>',

'Ruang Publik Inklusif' => '
  <g class="khb-icon-g">
    <circle cx="22" cy="16" r="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <circle cx="42" cy="16" r="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <path d="M10 44 C10 34 16 28 22 28 C28 28 30 32 32 32 C34 32 36 28 42 28 C48 28 54 34 54 44" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M22 44 L22 56M42 44 L42 56" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M16 56 L28 56M36 56 L48 56" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M28 44 Q32 40 36 44" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <circle cx="32" cy="10" r="3" fill="currentColor" opacity=".2"/>
    <path d="M32 7 L32 4M29.5 8.5 L27 6M34.5 8.5 L37 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
  </g>',

'Kultur Ngopi yang Kuat' => '
  <g class="khb-icon-g">
    <path d="M14 26 L42 26 L38 54 Q37 58 32 58 Q27 58 26 54 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M42 32 L48 32 Q54 32 54 38 Q54 44 48 44 L40 44" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M12 62 L52 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
    <path d="M22 20 Q22 14 26 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <path d="M28 20 Q28 12 32 8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <path d="M34 20 Q34 14 38 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <circle cx="32" cy="42" r="7" fill="currentColor" opacity=".08" stroke="currentColor" stroke-width="1.5" opacity=".3"/>
    <path d="M29 42 Q32 38 35 42 Q32 46 29 42Z" fill="currentColor" opacity=".25"/>
  </g>',

'Arsitektur Bersejarah' => '
  <g class="khb-icon-g">
    <path d="M8 56 L8 32 L32 10 L56 32 L56 56 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M8 32 L56 32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <rect x="24" y="38" width="16" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <rect x="14" y="36" width="10" height="10" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".7"/>
    <rect x="40" y="36" width="10" height="10" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".7"/>
    <path d="M32 10 L32 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <circle cx="32" cy="5" r="2.5" fill="currentColor" opacity=".4"/>
    <path d="M24 32 L24 56M40 32 L40 56" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity=".2"/>
    <path d="M16 56 L16 44M48 56 L48 44" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
  </g>',

'Kiblat Fashion Lokal' => '
  <g class="khb-icon-g">
    <path d="M20 8 L12 24 L8 24 L8 40 L20 40 L20 60 L44 60 L44 40 L56 40 L56 24 L52 24 L44 8 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M20 8 Q26 16 32 14 Q38 12 44 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".6"/>
    <path d="M12 24 L20 24M44 24 L52 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <path d="M20 40 L44 40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <circle cx="32" cy="34" r="6" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".5"/>
    <path d="M29 34 L31 36 L35 30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" opacity=".7"/>
    <path d="M26 52 L38 52" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
  </g>',

'Wisata Alam Estetik' => '
  <g class="khb-icon-g">
    <path d="M4 52 L20 20 L36 52 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M24 52 L42 14 L60 52 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M4 52 L60 52" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".35"/>
    <circle cx="48" cy="22" r="7" fill="none" stroke="currentColor" stroke-width="2" opacity=".6"/>
    <path d="M48 18 L48 15M48 29 L48 32M44 22 L41 22M52 22 L55 22M45.5 19.5 L43.4 17.4M50.5 24.5 L52.6 26.6M50.5 19.5 L52.6 17.4M45.5 24.5 L43.4 26.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
    <path d="M14 40 Q20 36 26 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
    <path d="M32 44 Q42 38 52 44" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
  </g>',

'Ikon Kuliner Global' => '
  <g class="khb-icon-g">
    <path d="M10 28 Q10 14 22 14 Q28 14 30 20 Q32 14 38 14 Q50 14 50 28 Q50 40 32 50 Q14 40 10 28 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M30 20 Q30 28 32 50" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
    <path d="M14 26 Q22 24 30 26M30 26 Q38 24 50 26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <path d="M16 34 Q24 32 30 34M30 34 Q38 32 48 34" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity=".25"/>
    <path d="M24 52 L28 62M40 52 L36 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M22 62 L42 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
    <circle cx="32" cy="28" r="5" fill="currentColor" opacity=".1"/>
  </g>',

'Konektivitas Kilat' => '
  <g class="khb-icon-g">
    <path d="M10 8 L56 8 Q58 8 58 10 L58 30 Q58 32 56 32 L38 32 L32 42 L26 32 L8 32 Q6 32 6 30 L6 10 Q6 8 8 8 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M20 50 Q32 44 44 50M24 56 Q32 52 40 56M28 62 Q32 60 36 62" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <path d="M34 14 L26 22 L31 22 L28 30 L38 20 L33 20 Z" fill="currentColor" opacity=".7" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
    <circle cx="14" cy="20" r="2.5" fill="currentColor" opacity=".3"/>
    <circle cx="50" cy="20" r="2.5" fill="currentColor" opacity=".3"/>
    <path d="M14 14 L14 26M50 14 L50 26" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".2"/>
  </g>',

'Pusat Inovasi Digital' => '
  <g class="khb-icon-g">
    <rect x="18" y="18" width="28" height="28" rx="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <circle cx="32" cy="32" r="6" fill="none" stroke="currentColor" stroke-width="2"/>
    <circle cx="32" cy="32" r="2.5" fill="currentColor" opacity=".5"/>
    <path d="M32 10 L32 18M32 46 L32 54M10 32 L18 32M46 32 L54 32" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M14.7 14.7 L20.5 20.5M43.5 43.5 L49.3 49.3M49.3 14.7 L43.5 20.5M20.5 43.5 L14.7 49.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <circle cx="32" cy="10" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="32" cy="54" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="10" cy="32" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="54" cy="32" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="14.7" cy="14.7" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="49.3" cy="14.7" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="14.7" cy="49.3" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="49.3" cy="49.3" r="2.5" fill="currentColor" opacity=".2"/>
  </g>',
];
?>

<style>
.khb-section {
  position: relative;
  overflow: hidden;
  padding: 5rem 0 6rem;
}

.khb-section::before {
  content: "";
  position: absolute;
  top: -200px;
  right: -200px;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(167,139,250,.08) 0%, transparent 70%);
  pointer-events: none;
}

.khb-header {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 3.5rem;
}

.khb-header__left {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

/* Grid */
.khb-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0;
}

@media (max-width: 640px) {
  .khb-grid {
    grid-template-columns: 1fr;
  }
}

/* Item */
.khb-item {
  padding: 2.25rem 2rem 2.5rem;
  border-bottom: 1px solid color-mix(in srgb, currentColor 8%, transparent);
  position: relative;
  transition: background 0.3s ease;
}

.khb-item:nth-child(odd) {
  border-right: 1px solid color-mix(in srgb, currentColor 8%, transparent);
}

@media (max-width: 640px) {
  .khb-item:nth-child(odd) {
    border-right: none;
  }
  .khb-item {
    padding: 2rem 0.25rem;
  }
}

/* Last row — no bottom border */
.khb-item:nth-last-child(-n+2):not(.khb-item:nth-child(odd):last-child) {
  border-bottom: none;
}
.khb-item:last-child {
  border-bottom: none;
}
@media (max-width: 640px) {
  .khb-item:nth-last-child(-n+2) {
    border-bottom: 1px solid color-mix(in srgb, currentColor 8%, transparent);
  }
  .khb-item:last-child {
    border-bottom: none;
  }
}

/* Hover bg */
.khb-item::after {
  content: "";
  position: absolute;
  inset: 0;
  background: color-mix(in srgb, var(--color-primary, #7c3aed) 4%, transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}
.khb-item:hover::after {
  opacity: 1;
}

/* Icon */
.khb-icon-wrap {
  width: 64px;
  height: 64px;
  margin-bottom: 1.5rem;
  color: var(--color-primary, #7c3aed);
  position: relative;
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.khb-item:hover .khb-icon-wrap {
  transform: translateY(-4px);
}

.khb-icon-wrap svg {
  width: 64px;
  height: 64px;
  overflow: visible;
}

/* Animated stroke on hover */
.khb-item .khb-icon-g {
  transition: opacity 0.3s ease;
}

.khb-item:hover .khb-icon-g {
  filter: drop-shadow(0 0 8px color-mix(in srgb, var(--color-primary, #7c3aed) 50%, transparent));
}

/* Title */
.khb-item__title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-heading);
  margin: 0 0 0.6rem;
  line-height: 1.3;
  transition: color 0.2s;
}

.khb-item:hover .khb-item__title {
  color: var(--color-primary, #7c3aed);
}

/* Excerpt */
.khb-item__excerpt {
  font-size: 0.84rem;
  color: var(--text-muted);
  line-height: 1.7;
  margin: 0;
}
</style>

<?php if (!empty($_khb_items)): ?>
<section class="khb-section">
  <div class="container">

    <div class="khb-header">
      <div class="khb-header__left">
        <span class="text-eyebrow">Discover Bandung</span>
        <h2 class="text-sub-hero">Kenapa Harus Bandung?</h2>
      </div>
      <div>
        <p class="khb-lead">Bandung 2026: Perpaduan sempurna inovasi digital,<br>kesejukan alam, dan kreativitas kuliner terbaik.</p>
      </div>
    </div>

    <div class="khb-grid">
      <?php foreach ($_khb_items as $i => $_khb_item):
        $title   = htmlspecialchars($_khb_item['title'] ?? 'Untitled');
        $excerpt = htmlspecialchars($_khb_item['excerpt'] ?? '');
        $icon    = $_khb_icons[$title] ?? '
          <g class="khb-icon-g">
            <circle cx="32" cy="32" r="22" fill="none" stroke="currentColor" stroke-width="2.2"/>
            <circle cx="32" cy="32" r="8" fill="currentColor" opacity=".2"/>
          </g>';
      ?>
      <div class="khb-item">
        <div class="khb-icon-wrap">
          <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <?= $icon ?>
          </svg>
        </div>
        <h3 class="khb-item__title"><?= $title ?></h3>
        <?php if ($excerpt): ?>
          <p class="khb-item__excerpt"><?= $excerpt ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>
