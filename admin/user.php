<?php
// ============================================================
// LAB BAC - Daftar Pengguna (Tugas Kuliah) — kikikokok
// KERENTANAN: TIDAK ADA autentikasi SAMA SEKALI.
// Siapa pun (bahkan tanpa login) bisa melihat seluruh akun, password, dsb.
// ============================================================
$base = '../';
$judul = 'LAB BAC · Semua Pengguna';
require_once __DIR__ . '/../includes/header.php';

// BUG: tidak ada cek_login() dan tidak ada cek role!
$users = $pdo->query('SELECT id, username, password, nama, role, alamat, kartu FROM users')->fetchAll();
?>
<div class="card">
  <h2>👥 Seluruh Akun Pengguna</h2>
  <table class="tbl">
    <thead><tr><th>ID</th><th>Username</th><th>Password</th><th>Nama</th><th>Role</th><th>Kartu</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= e($u['username']) ?></td>
        <td><code><?= e($u['password']) ?></code></td>
        <td><?= e($u['nama']) ?></td>
        <td><span class="badge <?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
        <td>•••• <?= e($u['kartu']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="muted">Bisakah kamu membuka halaman ini <b>tanpa log masuk sama sekali</b>?</p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>