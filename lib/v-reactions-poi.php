<?php
$stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM reactions WHERE content_type='place' AND content_id=?");
$stmt->execute([$slug]);
$reaction_count = $stmt->fetchColumn();

$user_liked = false;

if (isset($_SESSION['user'])) {
  $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM reactions WHERE user_id=? AND content_type='place' AND content_id=?");
  $stmt->execute([$_SESSION['user']['id'], $slug]);
  $user_liked = (bool) $stmt->fetch();
}