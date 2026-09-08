<?php
// ============================================================
// LAB BAC - Registrasi (Tugas Kuliah) — kikikokok
// KERENTANAN: pengguna bisa memilih role sendiri (privilege escalation).
// ============================================================
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $nama     = trim((string)($_POST['nama'] ?? ''));
    $role     = (string)($_POST['role'] ?? 'pelanggan'); // BUG: role dari user!

    $cek = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $cek->execute([$username]);
    if ($cek->fetch()) {
        $galat = 'Username sudah dipakai.';
    } else {
        $in = $pdo->prepare('INSERT INTO users (username, password, nama, role) VALUES (?,?,?,?)');
        $in->execute([$username, $password, $nama, $role]);
        $pesan = 'Pendaftaran berhasil. Silakan log masuk.';
    }
}
?>
<div class="card" style="max-width:420px">
  <h2>📝 Daftar Akun Baru</h2>
  <?php if (isset($pesan)): ?><div class="notice"><?= e($pesan) ?></div><?php endif; ?>
  <?php if (isset($galat)): ?><div class="danger"><?= e($galat) ?></div><?php endif; ?>
  <form method="post">
    <label>Nama</label>
    <input name="nama" required>
    <label>Username</label>
    <input name="username" required>
    <label>Password</label>
    <input name="password" type="password" required>
    <label>Role (😈 coba pilih Admin)</label>
    <select name="role">
      <option value="pelanggan">pelanggan</option>
      <option value="kasir">kasir</option>
      <option value="admin">admin</option>
    </select>
    <button class="btn">Daftar</button>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>