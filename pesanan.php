<?php
// ============================================================
// LAB BAC - Pesanan (Tugas Kuliah) — kikikokok
// KERENTANAN IDOR: ?user_id= bisa mengintip pesanan pengguna lain.
// ============================================================
require_once __DIR__ . '/includes/header.php';

// Seharusnya diambil dari sesi, tapi di sini bisa dari parameter!
$user_id = (int)($_GET['user_id'] ?? ($_SESSION['login']['id'] ?? 0));

$items = $pdo->prepare('SELECT p.*, u.nama AS nama FROM pesanan p JOIN users u ON u.id = p.user_id WHERE p.user_id = ? ORDER BY p.tanggal DESC');
$items->execute([$user_id]);
$daftar = $items->fetchAll();
$akun   = $pdo->prepare('SELECT nama FROM users WHERE id=?');
$akun->execute([$user_id]);
$pemilik = $akun->fetch();
?>
<div class="card">
  <h2>🧾 Pesanan Pengguna #<?= $user_id ?> <?= $pemilik ? '(' . e($pemilik['nama']) . ')' : '' ?></h2>
  <?php if (!$daftar): ?>
    <p class="muted">Tidak ada pesanan.</p>
  <?php else: ?>
  <table class="tbl">
    <thead><tr><th>Kode</th><th>Total</th><th>Tanggal</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $p): ?>
      <tr>
        <td><a href="invoice.php?id=<?= (int)$p['id'] ?>"><?= e($p['kode']) ?></a></td>
        <td>Rp <?= number_format((int)$p['total'], 0, ',', '.') ?></td>
        <td><?= e($p['tanggal']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="muted">Coba <code>pesanan.php?user_id=1</code> dan <code>?user_id=5</code> — bisa tak?</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>