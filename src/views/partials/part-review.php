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
      <span class="rev-eyebrow">
        <span class="rev-eyebrow__dot"></span>
        Dari Traveler
      </span>
      <h2 class="rev-heading">Cerita <br>dari <em>Komunitas</em></h2>
    </div>

    <div class="rev-stage">
      <div class="rev-quote-mark">"</div>

      <?php foreach ($_rev_items as $i => $_rev): ?>
      <div class="rev-item <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
        <p class="rev-text">
          <?= htmlspecialchars(mb_substr($_rev['cerita'], 0, 220) . (mb_strlen($_rev['cerita']) > 220 ? '…' : '')) ?>
        </p>
        <div class="rev-author">
          <img src="<?= !empty($_rev['avatar']) ? safe_html($_rev['avatar']) : BASE_URL . 'assets/img/avatar.png' ?>"
               class="rev-author__avatar"
               onerror="this.src='<?= BASE_URL ?>assets/img/avatar.png'"
               alt="<?= safe_html($_rev['user_name']) ?>">
          <div class="rev-author__info">
            <span class="rev-author__name"><?= safe_html($_rev['user_name']) ?></span>
            <span class="rev-author__poi">
              <i class="fas fa-location-dot me-1"></i><?= safe_html($_rev['poi_name']) ?>
            </span>
          </div>
          <div class="rev-author__stars">
            <?php for ($s = 1; $s <= 5; $s++): ?>
              <i class="fa-<?= $s <= $_rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
            <?php endfor; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <div class="rev-nav">
        <?php foreach ($_rev_items as $i => $_rev): ?>
        <button class="rev-dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></button>
        <?php endforeach; ?>
      </div>

      <div class="rev-arrows">
        <button class="rev-arrow" id="revPrev">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button class="rev-arrow" id="revNext">
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>

    </div>

  </div>
</section>

<style>
.rev-section {
  background: #0f0a1e;
  padding: 6rem 0;
  position: relative;
  overflow: hidden;
}
.rev-section::before {
  content: '';
  position: absolute;
  top: -200px;
  left: 50%;
  transform: translateX(-50%);
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(124,58,237,.15) 0%, transparent 70%);
  pointer-events: none;
}

.rev-header {
  text-align: center;
  margin-bottom: 4rem;
}
.rev-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: #a78bfa;
  margin-bottom: 1rem;
}
.rev-eyebrow__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #a78bfa;
  animation: revBlink 2s ease-in-out infinite;
}
@keyframes revBlink {
  0%, 100% { opacity: 1; }
  50%       { opacity: .3; }
}
.rev-heading {
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
  color: #fff;
  letter-spacing: -.03em;
  line-height: 1.15;
  margin: 0;
}
.rev-heading em {
  font-style: normal;
  background: linear-gradient(135deg, #a78bfa, #818cf8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.rev-stage {
  max-width: 760px;
  margin: 0 auto;
  position: relative;
  min-height: 260px;
}

.rev-quote-mark {
  font-size: 8rem;
  line-height: .8;
  color: #7c3aed;
  opacity: .25;
  font-family: Georgia, serif;
  position: absolute;
  top: -1rem;
  left: -2rem;
  pointer-events: none;
  user-select: none;
}

.rev-item {
  display: none;
  animation: revFadeIn .5s ease forwards;
}
.rev-item.active { display: block; }
@keyframes revFadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

.rev-text {
  font-size: clamp(1.1rem, 2.5vw, 1.4rem);
  color: #e2e8f0;
  line-height: 1.75;
  font-weight: 400;
  margin-bottom: 2.5rem;
  text-align: center;
}

.rev-author {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}
.rev-author__avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #4c1d95;
}
.rev-author__info {
  display: flex;
  flex-direction: column;
  gap: .2rem;
}
.rev-author__name {
  font-size: .9rem;
  font-weight: 700;
  color: #fff;
}
.rev-author__poi {
  font-size: .75rem;
  color: #a78bfa;
}
.rev-author__stars {
  color: #f59e0b;
  font-size: .8rem;
  letter-spacing: .1em;
}

.rev-nav {
  display: flex;
  justify-content: center;
  gap: .5rem;
  margin-top: 2.5rem;
}
.rev-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(255,255,255,.2);
  border: none;
  cursor: pointer;
  transition: all .3s;
  padding: 0;
}
.rev-dot.active {
  background: #7c3aed;
  width: 24px;
  border-radius: 4px;
}

.rev-arrows {
  display: flex;
  justify-content: center;
  gap: .75rem;
  margin-top: 1.5rem;
}
.rev-arrow {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  color: #fff;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .85rem;
  transition: all .2s;
}
.rev-arrow:hover {
  background: #7c3aed;
  border-color: #7c3aed;
}

@media (max-width: 767px) {
  .rev-section { padding: 4rem 0; }
  .rev-quote-mark { font-size: 5rem; left: 0; }
  .rev-text { font-size: 1.05rem; }
  .rev-stage { min-height: 300px; }
}
</style>

<script>
(function() {
  const items   = document.querySelectorAll('.rev-item');
  const dots    = document.querySelectorAll('.rev-dot');
  let current   = 0;
  let interval;

  function show(index) {
    items.forEach(el => el.classList.remove('active'));
    dots.forEach(el => el.classList.remove('active'));
    items[index].classList.add('active');
    dots[index].classList.add('active');
    current = index;
  }

  function next() { show((current + 1) % items.length); }
  function prev() { show((current - 1 + items.length) % items.length); }

  function start() { interval = setInterval(next, 5000); }
  function stop()  { clearInterval(interval); }

  document.getElementById('revNext').addEventListener('click', () => { stop(); next(); start(); });
  document.getElementById('revPrev').addEventListener('click', () => { stop(); prev(); start(); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => { stop(); show(i); start(); });
  });

  start();
})();
</script>
<?php endif; ?>
