<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

http_response_code(404);
$page_title = "404";
require_once SRC_PATH . "header.php";
?>

 <div class="py-5 text-center">
   <h2 class="text-primary mb-4">404</h2>
   <p>Halaman yang kamu cari tidak ditemukan.<br>Mungkin sudah dipindah atau dihapus.</p>
 </div>
 
<?php
require_once SRC_PATH . "footer.php"; ?>