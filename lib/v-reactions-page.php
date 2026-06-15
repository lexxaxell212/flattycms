<?php
$stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM reactions WHERE content_type='page' AND content_id=?");
$stmt->execute([$_page_id]);
$_reaction_count = $stmt->fetchColumn();

$_user_liked = false;
if (isset($_SESSION['user'])) {
  $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM reactions WHERE user_id=? AND content_type='page' AND content_id=?");
  $stmt->execute([$_SESSION['user']['id'], $_page_id]);
  $_user_liked = (bool) $stmt->fetch();
}