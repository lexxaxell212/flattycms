<?php
$zona_bandung = $GLOBALS['pdo']->query("
    SELECT * FROM cmpt WHERE status = 'active' 
    AND category IN ('bandung_pusat','bandung_utara','bandung_selatan','bandung_timur','bandung_barat') 
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$zona_map = [];
foreach ($zona_bandung as $item) {
    $zona_map[$item['category']][] = $item;
}