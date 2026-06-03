<?php
$_khb_stmt  = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('trending') ORDER BY id DESC");
$_khb_items = $_khb_stmt->fetchAll(PDO::FETCH_ASSOC);

$_khb_icons = [
'Udara Sejuk Menenangkan' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="var(--bg-green)" class="icon icon-tabler icons-tabler-filled icon-tabler-leaf">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3.055 14.328l-.018 -.168l-.004 -.043a11 11 0 0 1 -.047 -1.12c.018 -6.29 4.29 -9.997 13 -9.997h4.014a1 1 0 0 1 1 1l-.002 2.057c-.498 8.701 -4.74 12.943 -11.998 12.943h-2.631a16 16 0 0 0 -.375 2.11a1 1 0 1 1 -1.988 -.22q .174 -1.568 .58 -2.947l-.118 -.146l-.208 -.28l-.157 -.229l-.182 -.293l-.098 -.171l-.065 -.122a6 6 0 0 1 -.397 -.941l-.072 -.237l-.085 -.327l-.057 -.268l-.043 -.242zm8.539 -4.242c-2.845 1.265 -4.854 3.13 -6.108 5.583q .098 .2 .218 .4l.185 .281l.07 .097q .12 .164 .258 .329l.197 .224h.649c1.037 -2.271 2.777 -3.946 5.343 -5.086a1 1 0 0 0 -.812 -1.828" />
</svg>',

'Event Seni Internasional' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Stage/theater curtain base -->
  <rect x="14" y="28" width="72" height="52" rx="4" fill="var(--dotid)" opacity=".15"/>
  <!-- Curtain left -->
  <path d="M14 28 Q22 42 18 80 L14 80Z" fill="var(--dotid)"/>
  <!-- Curtain right -->
  <path d="M86 28 Q78 42 82 80 L86 80Z" fill="var(--dotid)"/>
  <!-- Stage floor -->
  <rect x="14" y="74" width="72" height="6" rx="2" fill="var(--dotid)"/>
  <!-- Top bar -->
  <rect x="14" y="26" width="72" height="7" rx="2" fill="var(--dotid)"/>
  <!-- Star accent center -->
  <polygon points="50,38 53,47 62,47 55,53 57,62 50,57 43,62 45,53 38,47 47,47" fill="#d4aaff"/>
  <!-- Spotlight beams -->
  <g fill="#d4aaff" opacity=".5">
    <path d="M32 33 L22 70 L38 70Z"/>
    <path d="M68 33 L62 70 L78 70Z"/>
  </g>
  <!-- Stage lights dots -->
  <g fill="#d4aaff">
    <circle cx="26" cy="29" r="3"/>
    <circle cx="38" cy="29" r="3"/>
    <circle cx="50" cy="29" r="3"/>
    <circle cx="62" cy="29" r="3"/>
    <circle cx="74" cy="29" r="3"/>
  </g>
</svg>',

'Ruang Publik Inklusif' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- People figures -->
  <!-- Person 1 (left) -->
  <circle cx="28" cy="28" r="8" fill="var(--dotid)" opacity=".7"/>
  <path d="M18 52 Q28 44 38 52 L36 72 H20Z" fill="var(--dotid)" opacity=".7"/>
  <!-- Person 2 (center, highlighted) -->
  <circle cx="50" cy="25" r="10" fill="var(--dotid)"/>
  <path d="M38 52 Q50 42 62 52 L60 76 H40Z" fill="var(--dotid)"/>
  <!-- Person 3 (right) -->
  <circle cx="72" cy="28" r="8" fill="var(--dotid)" opacity=".7"/>
  <path d="M62 52 Q72 44 82 52 L80 72 H64Z" fill="var(--dotid)" opacity=".7"/>
  <!-- Connecting arc (inclusive ring) -->
  <path d="M20 62 Q50 48 80 62" fill="none" stroke="#d4aaff" stroke-width="3.5" stroke-linecap="round"/>
  <!-- Heart accent on center person -->
  <path d="M46 22 Q46 18 50 20 Q54 18 54 22 Q54 26 50 30 Q46 26 46 22Z" fill="#d4aaff"/>
  <!-- Ground / base line -->
  <rect x="14" y="80" width="72" height="5" rx="2.5" fill="#d4aaff" opacity=".5"/>
</svg>',

'Kultur Ngopi yang Kuat' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Cup body -->
  <path d="M24 45 L30 80 H70 L76 45Z" fill="var(--dotid)"/>
  <!-- Cup handle -->
  <path d="M70 52 Q86 52 86 62 Q86 72 70 72" fill="none" stroke="var(--dotid)" stroke-width="5" stroke-linecap="round"/>
  <!-- Saucer -->
  <ellipse cx="50" cy="83" rx="30" ry="6" fill="var(--dotid)" opacity=".4"/>
  <!-- Coffee surface (lighter) -->
  <ellipse cx="50" cy="46" rx="26" ry="5" fill="#d4aaff" opacity=".6"/>
  <!-- Steam lines -->
  <g stroke="#d4aaff" stroke-width="3" stroke-linecap="round" fill="none">
    <path d="M38 38 Q36 31 38 24 Q40 17 38 10"/>
    <path d="M50 36 Q48 29 50 22 Q52 15 50 8"/>
    <path d="M62 38 Q60 31 62 24 Q64 17 62 10"/>
  </g>
  <!-- Coffee top shine -->
  <ellipse cx="44" cy="46" rx="8" ry="2" fill="#d4aaff" opacity=".8"/>
</svg>',

'Arsitektur Bersejarah' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Main building body -->
  <rect x="22" y="48" width="56" height="36" fill="var(--dotid)"/>
  <!-- Pediment / roof triangle -->
  <polygon points="12,48 50,16 88,48" fill="var(--dotid)"/>
  <!-- Pediment highlight -->
  <polygon points="22,48 50,24 78,48" fill="#d4aaff" opacity=".25"/>
  <!-- Columns -->
  <g fill="#d4aaff">
    <rect x="28" y="48" width="6" height="36"/>
    <rect x="40" y="48" width="6" height="36"/>
    <rect x="54" y="48" width="6" height="36"/>
    <rect x="66" y="48" width="6" height="36"/>
  </g>
  <!-- Column caps -->
  <g fill="var(--dotid)">
    <rect x="26" y="46" width="10" height="4"/>
    <rect x="38" y="46" width="10" height="4"/>
    <rect x="52" y="46" width="10" height="4"/>
    <rect x="64" y="46" width="10" height="4"/>
  </g>
  <!-- Door -->
  <rect x="43" y="64" width="14" height="20" rx="7" fill="var(--dotid)"/>
  <!-- Entablature top line -->
  <rect x="18" y="44" width="64" height="5" fill="var(--dotid)"/>
  <!-- Base / steps -->
  <rect x="16" y="84" width="68" height="5" rx="1" fill="var(--dotid)" opacity=".6"/>
  <rect x="12" y="88" width="76" height="4" rx="1" fill="var(--dotid)" opacity=".4"/>
  <!-- Star / rosette on pediment -->
  <circle cx="50" cy="38" r="5" fill="#d4aaff"/>
  <circle cx="50" cy="38" r="2.5" fill="var(--dotid)"/>
</svg>',

'Kiblat Fashion Lokal' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Clothing hanger -->
  <path d="M50 18 Q50 14 54 14 Q60 14 60 18 Q60 22 50 26" fill="none" stroke="var(--dotid)" stroke-width="3" stroke-linecap="round"/>
  <!-- Hanger bar -->
  <path d="M50 26 L14 52 L86 52 Z" fill="none" stroke="var(--dotid)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Dress / garment body -->
  <path d="M28 54 L22 88 H78 L72 54Z" fill="var(--dotid)"/>
  <!-- Dress accent panel -->
  <path d="M42 54 L38 88 H62 L58 54Z" fill="#d4aaff" opacity=".7"/>
  <!-- Waist belt line -->
  <rect x="26" y="65" width="48" height="5" rx="2.5" fill="#d4aaff"/>
  <!-- Collar detail -->
  <path d="M38 54 Q50 62 62 54" fill="none" stroke="#d4aaff" stroke-width="2.5" stroke-linecap="round"/>
  <!-- Decorative dots (buttons) -->
  <g fill="var(--dotid)">
    <circle cx="50" cy="57" r="2"/>
    <circle cx="50" cy="63" r="2"/>
  </g>
  <!-- Stars accent -->
  <g fill="#d4aaff" opacity=".9">
    <polygon points="18,30 19.5,35 24,35 20.5,38 22,43 18,40 14,43 15.5,38 12,35 16.5,35"/>
    <polygon points="82,28 83,32 87,32 84,34.5 85,38.5 82,36 79,38.5 80,34.5 77,32 81,32"/>
  </g>
</svg>',

'Wisata Alam Estetik' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Sky / backdrop -->
  <rect x="10" y="10" width="80" height="55" rx="6" fill="var(--dotid)" opacity=".12"/>
  <!-- Mountain far (lighter) -->
  <polygon points="10,65 38,28 66,65" fill="var(--dotid)" opacity=".5"/>
  <!-- Mountain near (main) -->
  <polygon points="34,65 62,22 90,65" fill="var(--dotid)"/>
  <!-- Snow cap near -->
  <polygon points="53,30 62,22 71,30 66,34 58,34" fill="#d4aaff"/>
  <!-- Snow cap far (small) -->
  <polygon points="31,37 38,28 45,37 42,40 34,40" fill="#d4aaff" opacity=".6"/>
  <!-- Ground / grass base -->
  <rect x="10" y="65" width="80" height="12" rx="0" fill="var(--dotid)" opacity=".35"/>
  <!-- Water reflection -->
  <ellipse cx="50" cy="80" rx="36" ry="6" fill="#d4aaff" opacity=".4"/>
  <!-- Sun -->
  <circle cx="76" cy="22" r="8" fill="#d4aaff"/>
  <circle cx="76" cy="22" r="5" fill="var(--dotid)" opacity=".5"/>
  <!-- Tree silhouette left -->
  <rect x="16" y="58" width="4" height="10" fill="var(--dotid)"/>
  <polygon points="12,60 18,46 24,60" fill="var(--dotid)" opacity=".7"/>
</svg>',

'Ikon Kuliner Global' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Plate circle -->
  <circle cx="50" cy="56" r="34" fill="var(--dotid)" opacity=".15"/>
  <circle cx="50" cy="56" r="34" fill="none" stroke="var(--dotid)" stroke-width="4"/>
  <!-- Cloche / dome lid -->
  <path d="M22 50 Q22 24 50 24 Q78 24 78 50Z" fill="var(--dotid)"/>
  <!-- Lid handle knob -->
  <rect x="44" y="18" width="12" height="8" rx="4" fill="var(--dotid)"/>
  <!-- Lid highlight arc -->
  <path d="M32 42 Q50 30 68 42" fill="none" stroke="#d4aaff" stroke-width="3" stroke-linecap="round" opacity=".8"/>
  <!-- Plate base -->
  <ellipse cx="50" cy="50" rx="28" ry="4" fill="var(--dotid)" opacity=".4"/>
  <!-- Fork left -->
  <g fill="var(--dotid)">
    <rect x="16" y="30" width="3" height="40" rx="1.5"/>
    <rect x="13" y="30" width="2" height="14" rx="1"/>
    <rect x="19" y="30" width="2" height="14" rx="1"/>
    <rect x="16" y="30" width="3" height="4" rx="1"/>
  </g>
  <!-- Knife right -->
  <g fill="var(--dotid)">
    <rect x="81" y="30" width="3" height="40" rx="1.5"/>
    <path d="M81 30 Q88 36 84 44 L81 44Z" fill="var(--dotid)"/>
  </g>
  <!-- Globe grid lines on cloche (accent) -->
  <g stroke="#d4aaff" stroke-width="1.5" fill="none" opacity=".7">
    <path d="M30 40 Q50 36 70 40"/>
    <path d="M26 46 Q50 42 74 46"/>
    <line x1="50" y1="24" x2="50" y2="50"/>
    <line x1="36" y1="26" x2="36" y2="50"/>
    <line x1="64" y1="26" x2="64" y2="50"/>
  </g>
</svg>',

'Konektivitas Kilat' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Signal rings -->
  <g fill="none" stroke="var(--dotid)" stroke-linecap="round">
    <path d="M14 50 Q14 22 50 22 Q86 22 86 50" stroke-width="5" opacity=".3"/>
    <path d="M23 50 Q23 30 50 30 Q77 30 77 50" stroke-width="5" opacity=".55"/>
    <path d="M32 50 Q32 38 50 38 Q68 38 68 50" stroke-width="5" opacity=".8"/>
  </g>
  <!-- Signal rings accent -->
  <g fill="none" stroke="#d4aaff" stroke-linecap="round">
    <path d="M14 50 Q14 22 50 22 Q86 22 86 50" stroke-width="2" opacity=".4"/>
    <path d="M23 50 Q23 30 50 30 Q77 30 77 50" stroke-width="2" opacity=".6"/>
    <path d="M32 50 Q32 38 50 38 Q68 38 68 50" stroke-width="2" opacity=".9"/>
  </g>
  <!-- Lightning bolt center -->
  <polygon points="56,18 44,52 54,52 44,82 66,46 54,46 64,18" fill="var(--dotid)"/>
  <!-- Lightning bolt highlight -->
  <polygon points="56,18 50,36 57,36 50,54 62,38 55,38 60,22" fill="#d4aaff" opacity=".8"/>
  <!-- Base dot -->
  <circle cx="50" cy="86" r="5" fill="var(--dotid)"/>
  <circle cx="50" cy="86" r="2.5" fill="#d4aaff"/>
</svg>',

'Pusat Inovasi Digital' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <!-- Dual-tone: base=var(--dotid), accent=#d4aaff -->
  <!-- Circuit board background -->
  <rect x="12" y="12" width="76" height="76" rx="10" fill="var(--dotid)" opacity=".12"/>
  <!-- Circuit lines -->
  <g stroke="var(--dotid)" stroke-width="3" fill="none" stroke-linecap="round" opacity=".6">
    <polyline points="12,50 28,50 28,30 50,30"/>
    <polyline points="88,50 72,50 72,70 50,70"/>
    <polyline points="50,12 50,30"/>
    <polyline points="50,70 50,88"/>
    <polyline points="12,30 28,30"/>
    <polyline points="72,70 88,70"/>
  </g>
  <!-- Circuit nodes -->
  <g fill="var(--dotid)" opacity=".6">
    <circle cx="28" cy="30" r="4"/>
    <circle cx="72" cy="70" r="4"/>
    <circle cx="50" cy="30" r="4"/>
    <circle cx="50" cy="70" r="4"/>
  </g>
  <!-- Central chip -->
  <rect x="32" y="32" width="36" height="36" rx="6" fill="var(--dotid)"/>
  <!-- Chip pins -->
  <g fill="#d4aaff">
    <rect x="28" y="40" width="6" height="4" rx="2"/>
    <rect x="28" y="50" width="6" height="4" rx="2"/>
    <rect x="28" y="60" width="6" height="4" rx="2"/>
    <rect x="66" y="40" width="6" height="4" rx="2"/>
    <rect x="66" y="50" width="6" height="4" rx="2"/>
    <rect x="66" y="60" width="6" height="4" rx="2"/>
    <rect x="40" y="28" width="4" height="6" rx="2"/>
    <rect x="50" y="28" width="4" height="6" rx="2"/>
    <rect x="60" y="28" width="4" height="6" rx="2"/>
    <rect x="40" y="66" width="4" height="6" rx="2"/>
    <rect x="50" y="66" width="4" height="6" rx="2"/>
    <rect x="60" y="66" width="4" height="6" rx="2"/>
  </g>
  <!-- Chip inner circuit accent -->
  <rect x="38" y="38" width="24" height="24" rx="4" fill="#d4aaff" opacity=".25"/>
  <!-- Center core -->
  <circle cx="50" cy="50" r="7" fill="#d4aaff"/>
  <circle cx="50" cy="50" r="3.5" fill="var(--dotid)"/>
</svg>',

];
?>

<style>
.khb-section {
  position: relative;
  overflow: hidden;
  padding: 5rem 0 6rem;
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
  position: relative;
  transition: background 0.3s ease;
  text-align: center;
}

@media (max-width: 640px) {
  .khb-item {
    padding: 2rem 0.25rem;
  }
}

/* Icon */
.khb-icon-wrap {
  border-radius: 100%;
  padding: 7px;
  width: 64px;
  height: 64px;
}

.khb-icon-wrap svg {
  width: 100%;
  height: 100%;
  display: block;
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
        $icon = $_khb_icons[$title] ?? '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="32" r="22" stroke="currentColor" stroke-width="2.2"/></svg>';
      ?>
      <div class="khb-item">
        <div class="khb-icon-wrap">
          <?= $icon ?>
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
