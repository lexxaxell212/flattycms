<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

http_response_code(404);
$page_title = "404";

require_once SRC_PATH . "headerv2.php";
?>

<div style="height:60svh;width:100%" class="d-flex justify-content-center">
  <div class="py-5 text-center">
    <h1 class="text-title mb-4">404</h1>
    <p class="text-muted">Halaman yang kamu cari tidak ditemukan.<br>Mungkin sudah dipindah atau dihapus.</p>
  </div>
</div>

<?php
require_once SRC_PATH . "footer.php";
?>