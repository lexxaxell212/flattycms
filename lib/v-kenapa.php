<?php
$_khb_stmt = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND
category IN ('trending') ORDER BY id DESC");
$_khb_items = $_khb_stmt->fetchAll(PDO::FETCH_ASSOC);
?>