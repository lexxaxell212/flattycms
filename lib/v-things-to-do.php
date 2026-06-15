<?php
$zona_bandung = $GLOBALS['pdo']->query("
    SELECT * FROM cmpt WHERE status = 'active'
    AND category IN ('bandung_pusat','bandung_utara','bandung_selatan','bandung_timur','bandung_barat')
    ORDER BY id DESC
  ")->fetchAll(PDO::FETCH_ASSOC);

foreach ($zona_bandung as &$item) {
  $item['is_new'] = strtotime($item['created_at']) >= strtotime('-7 days');
}
unset($item);

$zona_map = [];
foreach ($zona_bandung as $item) {
  $zona_map[$item['category']][] = $item;
}

$latest_items = array_filter($zona_bandung, fn($i) => $i['is_new']);