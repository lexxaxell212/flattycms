<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

$token = trim($_GET['token'] ?? '');

if (!$token) {
  header('Location: /?verified=invalid');
  exit;
}

$pdo = $GLOBALS['pdo'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE verify_token = ? AND verify_expires > NOW() AND email_verified = 0 LIMIT 1");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
  header('Location: /?verified=invalid');
  exit;
}

$stmt = $pdo->prepare("UPDATE users SET email_verified = 1, verify_token = NULL, verify_expires = NULL WHERE id = ?");
$stmt->execute([$user['id']]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Email berhasil diverifikasi! Silakan login.'];
session_write_close();

header('Location: /login');
exit;