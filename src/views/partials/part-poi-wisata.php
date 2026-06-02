<?php
require_once LIB_PATH . 'poi-actions.php';
require_once . LIB_PATH "v-poi-wisata.php";
?>

<style>
.wisata-wrapper {
    margin: 1rem 0;
}

.wisata-list {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}
.wisata-card {
    flex: 0 0 85%;
    height: 220px;
    border-radius: 12px;
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: hidden;
    scroll-snap-align: start;
}
.wisata-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 3rem 1rem 1rem;
    background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
    color: #fff;
}
.wisata-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.3rem;
}
.wisata-desc {
    font-size: 0.85rem;
    margin: 0 0 0.7rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    opacity: 0.9;
}
.wisata-btn {
    display: inline-block;
    padding: 0.4rem 0.9rem;
    background: #fff;
    color: #222;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
}

@media (min-width: 768px) {
    .wisata-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        overflow: visible;
        padding: 0;
    }
    .wisata-card {
        flex: unset;
        height: 200px;
    }
}
</style>
<section id="wisata-recomendations">
<div class="wisata-wrapper">
    <div class="wisata-list">
        <?php if (!empty($wisata_poi)): ?>
            <?php foreach ($wisata_poi as $item): ?>
                <div class="wisata-card" 
                     style="background-image: url('<?= htmlspecialchars($item['poi_image'] ?? 'assets/images/default-wisata.jpg') ?>')">
                     
                    <div class="wisata-overlay">
                        <h3 class="wisata-name"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="wisata-desc"><?= htmlspecialchars($item['description'] ?? 'Tidak ada deskripsi') ?></p>
                        <?php if (!empty($item['poi_url'])): ?>
                            <a href="<?= htmlspecialchars($item['poi_url']) ?>" class="wisata-btn" target="_blank" rel="noopener noreferrer">Lihat Detail</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="padding: 1rem; color: #666;">Belum ada data wisata tersedia.</p>
        <?php endif; ?>
    </div>
</div>
</section>