<?php
// ============================================================
// LAB BAC - Invoice (Tugas Kuliah) — kikikokok
// KERENTANAN: invoice bisa dibuka siapa saja, bahkan tanpa login!
// (Missing Function-Level Access Control + IDOR)
// ============================================================
require_once __DIR__ . '/includes/header.php';

$id  = (int)($_GET['id'] ?? 0);
$inv = $pdo->prepare('SELECT p.*, u.nama, u.alamat, u.kartu FROM pesanan p JOIN users u ON u.id = p.user_id WHERE p.id = ?');
$inv->execute([$id]);
$data = $inv->fetch(); // BUG: TIDAK ada pengecekan login / kepemilikan
?>
<div class="card" style="max-width:560px">
  <?php if (!$data): ?>
    <h2>Invoice tidak ditemukan</h2>
  <?php else: ?>
  <h2>🧾 Invoice <?= e($data['kode']) ?></h2>
  <table class="tbl">
    <tr><th>Pembeli</th><td><?= e($data['nama']) ?></td></tr>
    <tr><th>Alamat</th><td><?= e($data['alamat']) ?></td></tr>
    <tr><th>Sisa kartu</th><td>•••• <?= e($data['kartu']) ?></td></tr>
    <tr><th>Total</th><td>Rp <?= number_format((int)$data['total'], 0, ',', '.') ?></td></tr>
    <tr><th>Tanggal</th><td><?= e($data['tanggal']) ?></td></tr>
  </table>
  <p class="muted">Buka link ini <b>di browser incognito / tanpa login</b> → data tetap tampil 🤯</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>