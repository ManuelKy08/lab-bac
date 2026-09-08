<?php
// ============================================================
// LAB BAC - Login (Tugas Kuliah) — kikikokok
// KERENTANAN: role diambil dari parameter POST (privilege escalation).
// ============================================================
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = (string)($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    // BUG: role ikut dari input POST, padahal seharusnya dari database.
    $role = (string)($_POST['role'] ?? '');

    $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([$username]);
    $user = $st->fetch();

    if ($user && $user['password'] === $password) { // BUG: plaintext compare
        $_SESSION['login'] = $user;
        if ($role !== '' && in_array($role, ['admin', 'kasir', 'pelanggan'], true)) {
            $_SESSION['login']['role'] = $role; // privilege escalation!
        }
        header('Location: index.php');
        exit;
    }
    $galat = 'Username atau password salah.';
}
?>
<div class="card" style="max-width:420px">
  <h2>🔐 Log Masuk</h2>
  <?php if (isset($galat)): ?><div class="danger"><?= e($galat) ?></div><?php endif; ?>
  <form method="post">
    <label>Username</label>
    <input name="username" required>
    <label>Password</label>
    <input name="password" type="password" required>
    <label>Role (isi otomatis — boleh dicoba diubah 😈)</label>
    <select name="role">
      <option value="">…</option>
      <option value="pelanggan">pelanggan</option>
      <option value="kasir">kasir</option>
      <option value="admin">admin</option>
    </select>
    <button class="btn">Masuk</button>
  </form>
  <p class="muted">Akun demo: <code>admin/admin123</code> · <code>budi/budi123</code></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>