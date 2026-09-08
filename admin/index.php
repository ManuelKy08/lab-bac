<?php
// ============================================================
// LAB BAC - Panel Admin (Tugas Kuliah) — kikikokok
// KERENTANAN #1: cek login SAJA (role apa pun bisa masuk),
// dan id admin diambil dari parameter ?user_id — bukan sesi.
// ============================================================
$base = '../';
$judul = 'LAB BAC · Admin';
require_once __DIR__ . '/../includes/header.php';

// "Cek keamanan" yang salah kaprah: cukup login, tanpa cek role!
if (!$me) {
    header('Location: ' . $base . 'login.php');
    exit;
}

// BUG: bisa memilih "saya adalah admin" lewat URL ?user_id=1
$as = (int)($_GET['user_id'] ?? $me['id']);
$akun = $pdo->prepare('SELECT * FROM users WHERE id=?');
$akun->execute([$as]);
$aktor = $akun->fetch() ?? $me;

$jmlOrder = (int)$pdo->query('SELECT COUNT(*) FROM pesanan')->fetchColumn();
$jmlUser  = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
?>
<div class="card">
  <h2>🛠️ Dashboard Admin</h2>
  <div class="notice" style="margin-bottom:14px">
    Sesuai sesi kamu = <b><?= e($me['nama']) ?></b> (<?= e($me['role']) ?>).
    &nbsp;Aktor saat ini = <b><?= e($aktor['nama']) ?></b> (<?= e($aktor['role']) ?>).
    <br>Coba: bikin akun pelanggan, lalu buka <code>admin/index.php?user_id=1</code> 😉
  </div>
  <table class="tbl">
    <tr><th>Total pengguna</th><td><?= $jmlUser ?></td></tr>
    <tr><th>Total pesanan</th><td><?= $jmlOrder ?></td></tr>
  </table>
  <p style="margin-top:12px"><a class="btn" href="<?= $base ?>admin/user.php">Lihat semua pengguna</a>
  <a class="btn merah" href="<?= $base ?>admin/ubah_role.php">⚠️ Terkait role…</a></p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>