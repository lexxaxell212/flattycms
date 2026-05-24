<?php
$_rev_stmt = $GLOBALS['pdo']->query("
    SELECT r.cerita, r.rating, r.judul,
           u.name AS user_name, u.avatar,
           p.name AS poi_name
    FROM poi_reviews r
    JOIN users u ON u.id = r.user_id
    JOIN poi   p ON p.id = r.poi_id
    ORDER BY r.rating DESC, r.created_at DESC
    LIMIT 6
");
$_rev_items = $_rev_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_rev_items)): ?>
<section class="rev-section container-fluid">
  <div class="container">

    <div class="rev-header">
      <div>
        <span class="rev-eyebrow">
          <span class="rev-eyebrow__dot"></span>
          Dari Traveler
        </span>
        <h2 class="rev-heading">Cerita Nyata dari <em>Komunitas</em></h2>
      </div>
      <a href="<?= BASE_URL ?>gallery/" class="rev-link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="rev-grid">
      <?php foreach ($_rev_items as $i => $_rev): ?>
      <div class="rev-card">
        <div class="rev-card__top">
          <span class="rev-card__quote">"</span>
          <div class="rev-card__stars">
            <?php for ($s = 1; $s <= 5; $s++): ?>
              <i class="fa-<?= $s <= $_rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
            <?php endfor; ?>
          </div>
        </div>
        <?php if (!empty($_rev['judul'])): ?>
        <div class="rev-card__title"><?= safe_html($_rev['judul']) ?></div>
        <?php endif; ?>
        <p class="rev-card__text">
          <?= safe_html(mb_substr($_rev['cerita'], 0, 160) . (mb_strlen($_rev['cerita']) > 160 ? '…' : '')) ?>
        </p>
        <div class="rev-card__author">
          <img src="<?= !empty($_rev['avatar']) ? safe_html($_rev['avatar']) : BASE_URL . 'assets/img/avatar.png' ?>"
               class="rev-card__avatar"
               onerror="this.src='<?= BASE_URL ?>assets/img/avatar.png'"
               alt="<?= safe_html($_rev['user_name']) ?>">
          <div>
            <div class="rev-card__name"><?= safe_html($_rev['user_name']) ?></div>
            <div class="rev-card__poi">
              <i class="fas fa-location-dot me-1"></i><?= safe_html($_rev['poi_name']) ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<style>
.rev-section {
  padding: 6rem 0;
  position: relative;
  overflow: hidden;
  background:
    radial-gradient(ellipse at 20% 50%, rgba(167,139,250,.12) 0%, transparent 60%),
    radial-gradient(ellipse at 80% 20%, rgba(129,140,248,.1) 0%, transparent 55%),
    radial-gradient(ellipse at 60% 90%, rgba(196,181,253,.08) 0%, transparent 50%),
    #f8f7ff;
}

.rev-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 3rem;
  flex-wrap: wrap;
  gap: 1rem;
}
.rev-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: #7c3aed;
  margin-bottom: .5rem;
}
.rev-eyebrow__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #7c3aed;
  animation: revBlink 2s ease-in-out infinite;
}
@keyframes revBlink {
  0%, 100% { opacity: 1; }
  50%       { opacity: .3; }
}
.rev-heading {
  font-size: clamp(1.6rem, 3vw, 2.4rem);
  font-weight: 800;
  color: #1e1b4b;
  letter-spacing: -.03em;
  line-height: 1.2;
  margin: 0;
}
.rev-heading em {
  font-style: normal;
  color: #7c3aed;
}
.rev-link-all {
  font-size: .875rem;
  font-weight: 600;
  color: #7c3aed;
  text-decoration: none;
  white-space: nowrap;
  transition: opacity .2s;
  padding-bottom: .25rem;
}
.rev-link-all:hover { opacity: .65; }

.rev-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}
@media (max-width: 991px) { .rev-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px) { .rev-grid { grid-template-columns: 1fr; } }

.rev-card {
  background: rgba(255,255,255,.7);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(167,139,250,.2);
  border-radius: 1.25rem;
  padding: 1.75rem;
  display: flex;
  flex-direction: column;
  gap: .875rem;
  transition: transform .25s, box-shadow .25s, background .25s;
}
.rev-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 1rem 2.5rem rgba(124,58,237,.1);
  background: rgba(255,255,255,.9);
}

.rev-card__top {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.rev-card__quote {
  font-size: 2.5rem;
  line-height: 1;
  color: #c4b5fd;
  font-family: Georgia, serif;
  font-weight: 700;
}
.rev-card__stars {
  color: #f59e0b;
  font-size: .8rem;
  letter-spacing: .08em;
}

.rev-card__title {
  font-size: .95rem;
  font-weight: 700;
  color: #1e1b4b;
  line-height: 1.3;
}
.rev-card__text {
  font-size: .875rem;
  color: #4b5563;
  line-height: 1.7;
  flex: 1;
  margin: 0;
}

.rev-card__author {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding-top: .875rem;
  border-top: 1px solid rgba(167,139,250,.15);
  margin-top: auto;
}
.rev-card__avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ede9fe;
  flex-shrink: 0;
}
.rev-card__name {
  font-size: .85rem;
  font-weight: 700;
  color: #1e1b4b;
}
.rev-card__poi {
  font-size: .72rem;
  color: #a78bfa;
}
</style>
<?php endif; ?>
