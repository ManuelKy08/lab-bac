<?php
// ============================================================
// LAB BAC - Beranda (Tugas Kuliah) — kikikokok
// ============================================================
require_once __DIR__ . '/includes/header.php';
?>
<div class="card">
  <h2>🧭 Latihan Praktikum — Broken Access Control</h2>
  <p>Severity <b>OWASP Top 10 2021 — A01</b>. Coba temukan setiap kerentanan di bawah
  dengan memahami <i>siapa yang boleh akses apa</i>.</p>

  <table class="tbl">
    <thead><tr><th>#</th><th>Latihan</th><th>Pola Kerentanan</th></tr></thead>
    <tbody>
      <tr><td>1</td><td><a href="register.php">Daftar akun</a> lalu pilih role sendiri</td><td>Privilege escalation (privilege by hidden input)</td></tr>
      <tr><td>2</td><td><a href="login.php">Log masuk</a> sisipkan <code>role=admin</code></td><td>Privilege escalation lewat parameter</td></tr>
      <tr><td>3</td><td><a href="profil.php?id=1">Lihat profil <code>?id=1</code></a></td><td>IDOR — objek milik pengguna lain</td></tr>
      <tr><td>4</td><td><a href="pesanan.php?user_id=3">Lihat pesanan <code>?user_id=3</code></a></td><td>IDOR — data milik orang lain</td></tr>
      <tr><td>5</td><td><a href="invoice.php?id=1">Buka invoice <code>?id=1</code></a> tanpa login</td><td>Missing function-level access control</td></tr>
      <tr><td>6</td><td><a href="admin/user.php">Buka panel admin langsung</a></td><td>Missing function-level access control</td></tr>
      <tr><td>7</td><td><a href="admin/hapus_user.php?id=3">Hapus user tanpa autentikasi</a></td><td>Direct method / insecure object reference</td></tr>
      <tr><td>8</td><td>Naikkan role lewat <code>admin/ubah_role.php</code></td><td>Privilege escalation via POST</td></tr>
    </tbody>
  </table>

  <?php if ($me): ?>
  <div class="notice" style="margin-top:14px">
    Kamu masuk sebagai <b><?= e($me['nama']) ?></b> (<?= e($me['role']) ?>).
  </div>
  <?php else: ?>
  <div class="notice" style="margin-top:14px">Belum masuk — beberapa kerentanan justru bisa dicoba <b>tanpa login</b>.</div>
  <?php endif; ?>
</div>

<div class="card">
  <h2>🎯 Tujuan Tugas</h2>
  <ol>
    <li>Identifikasi <b>minimal 5</b> bentuk Broken Access Control pada aplikasi ini.</li>
    <li>Buktikan eksploitasinya (screenshot + penjelasan serangan).</li>
    <li>Robek &amp; tuliskan <b>perbaikan</b> (guard per role, cek kepemilikan objek).</li>
  </ol>
  <p class="muted">Lihat file <code>LAPORAN.md</code> di repositori untuk panduan + contoh mitigasi.</p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>