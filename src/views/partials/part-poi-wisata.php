<?php
require_once LIB_PATH . "v-poi-wisata.php";
?>

<style>
.wisata-wrapper {
    position: relative;
}

/* ============= MOBILE STYLE ============= */
.wisata-list {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 0 1rem 1rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

.wisata-card {
    flex: 0 0 calc(100% - 2rem);
    height: 320px;
    border-radius: 16px;
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: hidden;
    scroll-snap-align: start;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.wisata-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 4rem 1.2rem 1.2rem;
    background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
    color: #fff;
}

.wisata-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.4rem;
}

.wisata-desc {
    font-size: 0.9rem;
    margin: 0 0 0.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    opacity: 0.92;
    line-height: 1.4;
}

.wisata-btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #fff;
    color: #222;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.wisata-btn:hover {
    background: #f8f8f8;
    transform: translateY(-1px);
}

/* Tombol Navigasi Mobile */
.wisata-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #333;
    z-index: 10;
    cursor: pointer;
}

.wisata-nav.prev {
    left: 0.3rem;
}
.wisata-nav.next {
    right: 0.3rem;
}

/* ============= TABLET KE ATAS (BENTO GRID) ============= */
@media (min-width: 768px) {
    .wisata-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto auto;
        gap: 1rem;
        padding: 0;
        overflow: visible;
    }

    /* Variasi ukuran bento */
    .wisata-card:nth-child(1) {
        grid-column: span 2;
        grid-row: span 2;
        height: 320px;
    }
    .wisata-card:nth-child(2) {
        grid-column: span 2;
        height: 155px;
    }
    .wisata-card:nth-child(3) {
        grid-column: span 1;
        grid-row: span 2;
        height: 320px;
    }
    .wisata-card:nth-child(4) {
        grid-column: span 1;
        height: 155px;
    }
    .wisata-card:nth-child(5) {
        grid-column: span 2;
        height: 155px;
    }
    .wisata-card:nth-child(6) {
        grid-column: span 1;
        height: 155px;
    }

    .wisata-nav {
        display: none; 
    }
}
</style>

<section id="wisata-recomendations" class="container">
    <div class="wisata-wrapper">
        <button class="wisata-nav prev" id="wisataPrev">←</button>
        <button class="wisata-nav next" id="wisataNext">→</button>

        <div class="wisata-list" id="wisataList">
            <?php if (!empty($wisata_poi)): ?>
                <?php foreach ($wisata_poi as $item): ?>
                    <div class="wisata-card" 
                         style="background-image: url('<?= htmlspecialchars($item['poi_image'] ?? 'assets/images/default-wisata.jpg') ?>')">
                         
                        <div class="wisata-overlay">
                            <h3 class="wisata-name text-white"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="wisata-desc"><?= htmlspecialchars(mb_substr($item['description'] ?? '', 0, 80) . (mb_strlen($item['description'] ?? '') > 80 ? '...' : '')) ?></p>
                            <?php if (!empty($item['poi_url'])): ?>
                                <a href="<?= htmlspecialchars($item['poi_url']) ?>" class="wisata-btn" target="_blank" rel="noopener noreferrer">Lihat Detail</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 2rem; color: #666; text-align:center; width:100%;">Belum ada data wisata tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('wisataList');
    const prevBtn = document.getElementById('wisataPrev');
    const nextBtn = document.getElementById('wisataNext');

    prevBtn.addEventListener('click', () => {
        list.scrollBy({ left: -window.innerWidth * 0.85, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
        list.scrollBy({ left: window.innerWidth * 0.85, behavior: 'smooth' });
    });
});
</script>
