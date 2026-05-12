<?php
http_response_code(404);
$page_title = "404 page";
require "../includes/header.php";
?>
<div class="text-center my-10 flex flex-col justify-center items-center">
  <h1 style="font-size:4rem;">
    404
  </h1>
  <p class="text-muted mb-3">
    Mohon maaf halaman yang kamu cari tidak ada.
  </p>
  <p class="text-muted mb-3">
    <i class="small">redirect ke halaman awal..3s..</i>
  </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.location.href = "https://ayokebandung.id";
    }, 4000);
});
</script>

<?php require "../includes/footer.php";
?>
