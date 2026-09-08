<?php
// ============================================================
// LAB BAC - Ubah Role (Tugas Kuliah) — kikikokok
// KERENTANAN: endpoint rahasia tanpa autentikasi.
// POST user_id + role cukup untuk me-launching privilege escalation.
// ============================================================
require_once __DIR__ . '/../includes/header.php';

$hasil = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $role    = (string)($_POST['role'] ?? '');
    $valid   = ['admin', 'kasir', 'pelanggan'];

    if ($user_id > 0 && in_array($role, $valid, true)) {
        // BUG: tidak ada cek "apakah yang memanggil itu admin?"
        $pdo->prepare('UPDATE users SET role=? WHERE id=?')->execute([$role, $user_id]);
        $hasil = "Role user id $user_id diubah menjadi $role — TANPA otorisasi!";
    }
}
?>
<div class="card" style="max-width:520px">
  <h2>⚙️ Kevin &amp; Role — <span class="muted">tanpa&nbsp;pengaman</span></h2>
  <?php if ($hasil): ?><div class="danger"><?= e($hasil) ?></div><?php endif; ?>
  <form method="post">
    <label>ID user</label>
    <input name="user_id" type="number" required>
    <label>Role baru</label>
    <select name="role">
      <option value="pelanggan">pelanggan</option>
      <option value="kasir">kasir</option>
      <option value="admin">admin</option>
    </select>
    <button class="btn">Ubah Role</button>
  </form>
  <p class="muted">Minta bantuan <code>curl</code>:<br>
  <code>curl -s -d "user_id=4&role=admin" http://127.0.0.1:8090/admin/ubah_role.php</code></p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>