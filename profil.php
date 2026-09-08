<?php
// ============================================================
// LAB BAC - Profil (Tugas Kuliah) — kikikokok
// KERENTANAN IDOR: menyajikan profil pengguna lain via ?id=
// tanpa cek kepemilikan — termasuk data kartu rahasia.
// ============================================================
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$st->execute([$id]);
$orang = $st->fetch(); // BUG: tanpa cek "apakah ?id == session saya"
?>
<div class="card" style="max-width:520px">
  <?php if (!$orang): ?>
    <h2>Profil tidak ditemukan</h2>
  <?php else: ?>
  <h2>👤 Profil Pengguna #<?= (int)$orang['id'] ?></h2>
  <table class="tbl">
    <tr><th>Nama</th><td><?= e($orang['nama']) ?></td></tr>
    <tr><th>Username</th><td><?= e($orang['username']) ?></td></tr>
    <tr><th>Alamat</th><td><?= e($orang['alamat']) ?></td></tr>
    <tr><th>4 digit kartu</th><td>•••• <?= e($orang['kartu']) ?></td></tr>
    <tr><th>Role</th><td><span class="badge <?= e($orang['role']) ?>"><?= e($orang['role']) ?></span></td></tr>
  </table>
  <p class="muted">Coba ganti <code>?id=1</code> … <code>?id=5</code>. Mana yang bukan milikmu?</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>