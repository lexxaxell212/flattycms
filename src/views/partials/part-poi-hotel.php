<?php
require_once LIB_PATH . "v-poi-hotel.php";
?>

<style>
.hotel-wrapper {
    position: relative;
}

/* MOBILE */
.hotel-list {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 0 1rem 1rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

.hotel-card {
    flex: 0 0 calc(100% - 2rem);
    height: 360px;
    border-radius: var(--radius);
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: hidden;
    scroll-snap-align: start;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.hotel-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 4rem 1.2rem 1.2rem;
    background: linear-gradient(to top, rgba(0,0,0.9), transparent);
    color: #fff;
}

.hotel-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.4rem;
}

.hotel-desc {
    font-size: 0.9rem;
    margin: 0 0 0.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    opacity: 0.92;
    line-height: 1.4;
}

.hotel-btn {
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

.hotel-btn:hover {
    background: #f8f8f8;
    transform: translateY(-1px);
}

/* Tombol geser */
.hotel-nav {
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

.hotel-nav.prev { left: 0.3rem; }
.hotel-nav.next { right: 0.3rem; }

/* TABLET KE ATAS - BENTO */
@media (min-width: 768px) {
    .hotel-wrapper {
        margin: 1rem 0;
    }

    .hotel-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto auto;
        gap: 1rem;
        padding: 0;
        overflow: visible;
    }

    .hotel-card:nth-child(1) {
        grid-column: span 2;
        grid-row: span 2;
        height: 320px;
    }
    .hotel-card:nth-child(2) {
        grid-column: span 2;
        height: 155px;
    }
    .hotel-card:nth-child(3) {
        grid-column: span 1;
        grid-row: span 2;
        height: 320px;
    }
    .hotel-card:nth-child(4) {
        grid-column: span 1;
        height: 155px;
    }
    .hotel-card:nth-child(5) {
        grid-column: span 2;
        height: 155px;
    }
    .hotel-card:nth-child(6) {
        grid-column: span 1;
        height: 155px;
    }

    .hotel-nav {
        display: none;
    }
}
</style>

<section id="hotel-recomendations">
    <div class="hotel-wrapper">
        <button class="hotel-nav prev" id="hotelPrev">←</button>
        <button class="hotel-nav next" id="hotelNext">→</button>

        <div class="hotel-list" id="hotelList">
            <?php if (!empty($hotel_poi)): ?>
                <?php foreach ($hotel_poi as $item): ?>
                    <div class="hotel-card" 
                         style="background-image: url('<?= htmlspecialchars($item['poi_image'] ?? 'assets/images/default-hotel.jpg') ?>')">
                         
                        <div class="hotel-overlay">
                            <h3 class="hotel-name"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="hotel-desc"><?= htmlspecialchars(mb_substr($item['description'] ?? '', 0, 80) . (mb_strlen($item['description'] ?? '') > 80 ? '...' : '')) ?></p>
                            <?php if (!empty($item['poi_url'])): ?>
                                <a href="<?= htmlspecialchars($item['poi_url']) ?>" class="hotel-btn" target="_blank" rel="noopener noreferrer">Lihat Detail</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 2rem; color: #666; text-align:center; width:100%;">Belum ada data penginapan tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('hotelList');
    document.getElementById('hotelPrev').addEventListener('click', () => {
        list.scrollBy({ left: -window.innerWidth * 0.85, behavior: 'smooth' });
    });
    document.getElementById('hotelNext').addEventListener('click', () => {
        list.scrollBy({ left: window.innerWidth * 0.85, behavior: 'smooth' });
    });
});
</script>
