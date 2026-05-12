<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once SRC_PATH . "header.php";

$page_title = "Tentang";
?>
<div class="container py-5">
    
    <!-- Hero Section -->
    <section id="tentang" class="text-center">
        <div class="container mb-6">
            <h1 class="mb-6">
                Tentang
            </h1>
            <p class="mb-6">
                Dokumentasi website
            </p>
        </div>
    </section>

    <!-- Info Cards Grid -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="glass glass-hover h-100 rounded-3 border-0 shadow-sm">
                
            </div>
        </div>

    </div>

</div>

<?php
require_once SRC_PATH . "footer.php"; ?>