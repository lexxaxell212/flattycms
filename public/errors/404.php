<?php
// 404.php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, sans-serif;
            background: #0f0f0f;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        h1 { font-size: 6rem; font-weight: 800; color: #f44; margin-bottom: 0.5rem; }
        p  { color: #888; margin-bottom: 1.5rem; }
        a  {
            color: #fff;
            text-decoration: none;
            border: 1px solid #333;
            padding: 0.5rem 1.5rem;
            border-radius: 999px;
            font-size: 0.85rem;
        }
        a:hover { background: #1e1e1e; }
    </style>
</head>
<body>
    <div>
        <h1>404</h1>
        <p>Halaman yang kamu cari tidak ditemukan.<br>Mungkin sudah dipindah atau dihapus.</p>
        <a href="<?= BASE_URL ?>">← Kembali ke Beranda</a>
    </div>
</body>
</html>