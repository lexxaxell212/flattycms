<?php
require_once LIB_PATH . "v-poi-wisata.php";
?>

<style>
.poi-wisata-grid {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1.25rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    scrollbar-width: none;
}

.poi-wisata-grid::-webkit-scrollbar {
    display: none;
}

.poi-wisata-card {
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

.poi-wisata-card:hover .poi-wisata-name {
    font-size: 1.5rem;
}
.poi-wisata-card:hover .poi-wisata-desc {
    font-size: 1rem;
}

.poi-wisata-overlay {
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

.poi-wisata-card:hover .poi-wisata-overlay {
    background: linear-gradient(
        to top,
        oklch(8% 0.02 270 / 0.96) 0%,
        oklch(8% 0.02 270 / 0.65) 50%,
        oklch(8% 0.02 270 / 0.1) 100%
    );
}

.poi-wisata-name {
    color: oklch(98% 0.005 270);
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.35rem;
    line-height: 1.3;
    letter-spacing: -0.01em;
    transition: font-size 0.3s ease;
}

.poi-wisata-desc {
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

.poi-wisata-btn {
    padding: 0.45rem 1rem;
    font-size: 0.85rem;
    width: fit-content;
}

.poi-wisata-empty {
    padding: 3rem 1.5rem;
    text-align: center;
    color: oklch(55% 0.02 270);
    width: 100%;
    font-size: 0.9rem;
}

@media (min-width: 768px) {
    .poi-wisata-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: 220px 200px;
        gap: 1rem;
        padding: 0;
        overflow: visible;
    }

    .poi-wisata-card {
        flex: none;
        height: auto;
    }

    .poi-wisata-card:nth-child(1),
    .poi-wisata-card:nth-child(2) {
        grid-column: span 2;
        grid-row: 1;
    }

    .poi-wisata-card:nth-child(3),
    .poi-wisata-card:nth-child(4),
    .poi-wisata-card:nth-child(5),
    .poi-wisata-card:nth-child(6) {
        grid-column: span 1;
        grid-row: 2;
    }

    .poi-wisata-card:nth-child(n+3) .poi-wisata-desc {
        display: none;
    }

    .poi-wisata-card:nth-child(n+3) .poi-wisata-name {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .poi-wisata-card:nth-child(n+3) .poi-wisata-overlay {
        padding: 1rem;
    }
}

@media (min-width: 1024px) {
    .poi-wisata-grid {
        grid-template-rows: 260px 220px;
    }
}
</style>

<section id="wisata-recommendations" class="container">
  <span class="text-eyebrow">
    Recommendations
  </span>
  <h2 class="text-sub-hero mb-4">
    wisata Favorit
  </h2>
    <div class="poi-wisata-grid" id="poiwisataGrid">
        <?php if (!empty($wisata_poi)): ?>
            <?php foreach ($wisata_poi as $item): ?>
                <?php
                    $img   = htmlspecialchars($item['poi_image'] ?? 'assets/images/default.jpg');
                    $name  = htmlspecialchars($item['name'] ?? '');
                    $desc  = htmlspecialchars(mb_substr($item['description'] ?? '', 0, 90));
                    $desc .= mb_strlen($item['description'] ?? '') > 90 ? '...' : '';
                    $url   = htmlspecialchars($item['poi_url'] ?? '');
                ?>
                <div class="poi-wisata-card"
                     style="background-image: url('<?= $img ?>')">
                    <div class="poi-wisata-overlay">
                        <h3 class="poi-wisata-name"><?= $name ?>Tes title</h3>
                        <p class="poi-wisata-desc"><?= $desc ?>Tes desc</p>
                        <?php if (!empty($url)): ?>
                            <a href="<?= $url ?>"
                               class="btn btn-outline-white poi-wisata-btn"
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
            <p class="poi-wisata-empty">Belum ada data tersedia.</p>
        <?php endif; ?>
    </div>
</section>