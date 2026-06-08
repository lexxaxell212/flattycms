<?php
require_once LIB_PATH . "v-poi-hotel.php";
?>

<style>
.poi-hotel-grid {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1.25rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    scrollbar-width: none;
}

.poi-hotel-grid::-webkit-scrollbar {
    display: none;
}

.poi-hotel-card {
    position: relative;
    flex: 0 0 100%;
    height: 380px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    scroll-snap-align: start;
    background-size: cover;
    background-position: center top;
    background-color: oklch(20% 0.02 270);
    cursor: pointer;
}

.poi-hotel-card:hover .poi-hotel-name {
    font-size: 1.5rem;
}

.poi-hotel-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        oklch(8% 0.02 270 / 0.92) 0%,
        oklch(8% 0.02 270 / 0.55) 45%,
        oklch(8% 0.02 270 / 0.0) 100%
    );
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 1.25rem;
    transition: background 0.3s ease;
}

.poi-hotel-name {
    color: oklch(98% 0.005 270);
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.35rem;
    line-height: 1.3;
    letter-spacing: -0.01em;
    transition: font-size 0.3s ease;
}

.poi-hotel-desc {
    color: oklch(85% 0.01 270);
    font-size: 0.89rem;
    margin: 0 0 0.9rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: font-size 0.3s ease;
}

.poi-hotel-btn {
    padding: 0.45rem 1rem;
    font-size: 0.85rem;
    width: fit-content;
}

.poi-hotel-empty {
    padding: 3rem 1.5rem;
    text-align: center;
    color: oklch(55% 0.02 270);
    width: 100%;
    font-size: 0.9rem;
}

@media (min-width: 768px) {
    .poi-hotel-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: 220px 200px;
        gap: 1rem;
        padding: 0;
        overflow: visible;
    }

    .poi-hotel-card {
        flex: none;
        height: auto;
    }

    .poi-hotel-card:nth-child(1),
    .poi-hotel-card:nth-child(2) {
        grid-column: span 2;
        grid-row: 1;
    }

    .poi-hotel-card:nth-child(3),
    .poi-hotel-card:nth-child(4),
    .poi-hotel-card:nth-child(5),
    .poi-hotel-card:nth-child(6) {
        grid-column: span 1;
        grid-row: 2;
    }

    .poi-hotel-card:nth-child(n+3) .poi-hotel-desc {
        display: none;
    }

    .poi-hotel-card:nth-child(n+3) .poi-hotel-name {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .poi-hotel-card:nth-child(n+3) .poi-hotel-overlay {
        padding: 1rem;
    }
}

@media (min-width: 1024px) {
    .poi-hotel-grid {
        grid-template-rows: 260px 220px;
    }
}
</style>

<section id="hotel-recommendations" class="container">
  <span class="text-eyebrow">
    Recommendations
  </span>
  <h2 class="text-sub-hero mb-4">
    Hotel Favorit
  </h2>
    <div class="poi-hotel-grid" id="poiHotelGrid">
        <?php if (!empty($hotel_poi)): ?>
            <?php foreach ($hotel_poi as $item): ?>
                <?php
                    $img   = htmlspecialchars($item['poi_image'] ?? 'assets/images/default.jpg');
                    $name  = htmlspecialchars($item['name'] ?? '');
                    $desc  = htmlspecialchars(mb_substr($item['description'] ?? '', 0, 90));
                    $desc .= mb_strlen($item['description'] ?? '') > 90 ? '...' : '';
                    $url   = htmlspecialchars($item['slug'] ?? '');
                ?>
                <div class="poi-hotel-card"
                     style="background-image: url('<?= $img ?>')">
                    <div class="poi-hotel-overlay">
                        <h3 class="poi-hotel-name"><?= $name ?></h3>
                        <p class="poi-hotel-desc"><?= $desc ?></p>
                        <?php if (!empty($url)): ?>
                            <a href="/poi/<?= $url ?>"
                               class="btn btn-outline-white poi-hotel-btn"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="Lihat detail <?= $name ?>">
                                Lihat Detail
                                <i class="arrow-icon fas fa-angle-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="poi-hotel-empty">Belum ada data tersedia.</p>
        <?php endif; ?>
    </div>
</section>