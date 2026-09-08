<?php
// ============================================================
// LAB BAC - Hapus Pengguna (Tugas Kuliah) — kikikokok
// KERENTANAN KRITIS: aksi penghapusan bisa dilakukan SIAPA SAJA,
// tanpa login, tanpa CSRF, tanpa konfirmasi.
// ============================================================
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));

if ($id > 0) {
    // BUG: tidak ada autentikasi / otorisasi di sini!
    $pdo->prepare('DELETE FROM pesanan WHERE user_id=?')->execute([$id]);
    $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
    $pesan = "User id $id beserta pesanannya TELAH DIHAPUS.";
}
?>
<div class="card" style="max-width:520px">
  <h2>🗑️ Penghapusan Pengguna</h2>
  <?php if (isset($pesan)): ?><div class="danger"><?= e($pesan) ?></div><?php endif; ?>
  <form method="get">
    <label>ID user yang mau dihapus</label>
    <input name="id" type="number" value="<?= (int)($id ?? 3) ?>">
    <button class="btn merah">Hapus Sekarang</button>
  </form>
  <p class="muted">Contoh serangan langsung: <code>hapus_user.php?id=5</code> — siapa saja bisa memanggilnya.</p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>