<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";

$page_title = "Sejarah";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h1 class="mb-6 fs-1 text-center">Sejarah Bandung</h1>
            <p class="mb-6 text-center">Berawal dari dasar danau purba yang mengering, Bandung berevolusi dari sekadar titik nol di Jalan Raya Pos menjadi 'Paris van Java' yang anggun, hingga kini bertransformasi menjadi pusat kreativitas digital dunia.</p>
            <div class="timeline-container">

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-mountain-sun"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">Zaman Prasejarah</span>
                        <h5 class="h5 fw-bold">Danau Purba Bandung</h5>
                        <p class="text-muted mb-0">Wilayah Bandung awalnya adalah dasar danau raksasa yang terbentuk akibat letusan Gunung Sunda purba. Sisa-sisa geologisnya kini membentuk dataran tinggi yang kita kenal sebagai cekungan Bandung.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-chess-rook"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">Abad ke-15</span>
                        <h5 class="h5 fw-bold">Era Kerajaan Pajajaran</h5>
                        <p class="text-muted mb-0">Wilayah Bandung masuk dalam kekuasaan Kerajaan Pajajaran. Nama "Bandung" mulai muncul, merujuk pada kendaraan air yang digunakan untuk membendung sungai (Bendungan).</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">1810</span>
                        <h5 class="h5 fw-bold">Pemindahan Ibu Kota Kabupaten</h5>
                        <p class="text-muted mb-0">Gubernur Jenderal Herman Willem Daendels memerintahkan pemindahan ibu kota dari Dayeuhkolot ke pusat kota saat ini agar lebih dekat dengan Jalan Raya Pos (Grote Postweg). Tanggal 25 September ditetapkan sebagai hari jadi Kota Bandung.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-train"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">1884</span>
                        <h5 class="h5 fw-bold">Revolusi Transportasi & Ekonomi</h5>
                        <p class="text-muted mb-0">Pembukaan jalur kereta api Batavia-Bandung mengubah kota ini menjadi pusat distribusi hasil perkebunan teh dan kopi. Bandung mulai berkembang menjadi kota modern dengan gaya arsitektur Eropa.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">1920-an</span>
                        <h5 class="h5 fw-bold">Julukan Paris van Java</h5>
                        <p class="text-muted mb-0">Berkat keindahan alam dan menjamurnya bangunan bergaya Art Deco serta pusat mode seperti jalan Braga, Bandung mendapat julukan "Paris van Java". Bandung juga hampir direncanakan menjadi ibu kota Hindia Belanda.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">1946</span>
                        <h5 class="h5 fw-bold">Bandung Lautan Api</h5>
                        <p class="text-muted mb-0">Peristiwa heroik pada 24 Maret di mana sekitar 200.000 penduduk Bandung membakar rumah mereka sendiri dalam waktu tujuh jam untuk mencegah tentara Sekutu dan NICA menggunakan kota tersebut sebagai markas militer.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="card card-glass p-4">
                        <span class="badge-year">1955</span>
                        <h5 class="h5 fw-bold">Konferensi Asia Afrika (KAA)</h5>
                        <p class="text-muted mb-0">Bandung menjadi tuan rumah KAA yang dihadiri pemimpin dari 29 negara Asia dan Afrika di Gedung Merdeka. Peristiwa ini melahirkan Dasa Sila Bandung yang membangkitkan semangat kemerdekaan bangsa-bangsa di dua benua tersebut.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <div class="card card-glass p-4 border-start border-4 border-primary">
                        <span class="badge-year">Masa Kini</span>
                        <h5 class="h5 fw-bold">Kota Kreatif & Teknologi</h5>
                        <p class="text-muted mb-0">Resmi ditetapkan sebagai
                        "UNESCO Creative Cities Network" dalam bidang desain.
                        Bandung kini menjadi pusat inovasi teknologi, industri
                        kreatif, fashion, dan pendidikan tinggi (ITB) di
                        Indonesia.</p>
                    </div>
                </div>

            </div> 
        </div>
    </div>
</div>

<?php
require_once SRC_PATH . "footer.php"; ?>