<?php
// Header bersama — LAB BAC (kikikokok)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/koneksi.php';
$me = cek_login();
$judul = $judul ?? 'LAB BAC - Broken Access Control';
$base  = $base ?? '';                    // '../' bila halaman berada dalam subfolder
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?></title>
<link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="wrap">
    <div class="logo">🛡️ <b>LAB BAC</b> <span class="muted">— Broken Access Control</span></div>
    <nav>
      <a href="<?= $base ?>index.php">Beranda</a>
      <?php if ($me): ?>
      <a href="<?= $base ?>pesanan.php">Pesanan Saya</a>
      <a href="<?= $base ?>profil.php?id=<?= (int)$me['id'] ?>">Profil</a>
      <?php if ($me['role'] === 'admin'): ?><a href="<?= $base ?>admin/index.php">Admin</a><?php endif; ?>
      <a href="<?= $base ?>logout.php">Keluar (<?= e($me['username']) ?>)</a>
      <?php else: ?>
      <a href="<?= $base ?>login.php">Masuk</a>
      <a href="<?= $base ?>register.php">Daftar</a>
      <?php endif; ?>
    </nav>
  </div>
</div>
<div class="wrap">