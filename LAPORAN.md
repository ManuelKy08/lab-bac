<div align="center">
  <img src="BAC.png" alt="Preview Aplikasi LAB BAC" style="max-width:100%; border-radius:14px; border:1px solid #e5e7eb;">
  <h1>🛡️ LAB-RED Team — Broken Access Control (BAC)</h1>
  <p><b>Tugas Kuliah Keamanan Web</b> · dibuat oleh <b>kikikokok</b></p>
  <p>PHP 8 + MariaDB · OWASP Top 10 Web 2021 — <b>A01 Broken Access Control</b></p>
  <p>⚠️ Khusus praktikum lokal — jangan dijalankan di server publik/produksi.</p>
  <hr style="max-width:420px;">
</div>

---

## 1. Tujuan

Mempraktikkan identifikasi dan eksploitasi *Broken Access Control* pada aplikasi web,
serta memahami pola perbaikan yang benar (authorization per objek & per fungsi).

## 2. Ringkasan Aplikasi

Aplikasi simulasi toko bernama **LAB BAC** memiliki 3 role: `admin`, `kasir`, `pelanggan`.
Akun demo diisi di `database/lab_bac.sql`:

| ID | Username | Password | Role |
|----|----------|----------|------|
| 1  | admin    | admin123 | admin |
| 2  | kasir    | kasir123 | kasir |
| 3  | budi     | budi123  | pelanggan |
| 4  | sari     | sari123  | pelanggan |
| 5  | riko     | riko123  | pelanggan |

## 3. Menjalankan Lab

```bash
./jalankan.sh
# buka http://127.0.0.1:8090   — stop: ./jalankan.sh stop
```

## 4. Temuan Kerentanan (8 Poin Latihan)

### 4.1 · Privilege Escalation — daftar pilih role sendiri
- **File:** `register.php`
- **Hak:** pelanggan baru
- **Alur eksploit:**
  1. Buka `register.php`
  2. Isi nama, username, password, lalu pada dropdown **Role pilih `admin`**
  3. Log masuk → menu *Admin* muncul → akses penuh.
- **Kategori:** *Privilege escalation* melalui *hidden/control input* yang dipercaya tanpa validasi.

### 4.2 · Privilege Escalation — sisipkan `role=admin` saat login
- **File:** `login.php`
- **Alur eksploit:**
  ```
  POST /login.php
  username=budi&password=budi123&role=admin
  ```
  Role diambil dari input POST, bukan dari database → sesi jadi admin tanpa jadi admin di DB.

### 4.3 · IDOR — melihat profil pengguna lain
- **File:** `profil.php`
- **Alur eksploit:** `profil.php?id=1` memperlihatkan profil admin + 4 digit kartu miliknya.
- **Akar masalah:** objek dipilih dari `?id=` tanpa cek kepemilikan.

### 4.4 · IDOR — melihat riwayat pesanan orang lain
- **File:** `pesanan.php`
- **Alur eksploit:** `pesanan.php?user_id=3` menampilkan seluruh pesanan milik budi.

### 4.5 · Missing Function-Level Access Control — invoice publik
- **File:** `invoice.php`
- **Alur eksploit:** `invoice.php?id=5` diakses dari browser **incognito / tanpa login** tetap menampilkan data pelanggan & invoice.

### 4.6 · Missing Function-Level Access Control — panel admin terbuka
- **File:** `admin/user.php`, `admin/index.php`
- **Alur eksploit:** `admin/user.php` bisa dibuka **tanpa login** → seluruh akun (termasuk `password` plaintext) bocor.

### 4.7 · Direct Method — hapus pengguna tanpa otorisasi
- **File:** `admin/hapus_user.php`
- **Alur eksploit:** `hapus_user.php?id=3` menghapus user + menghapus pesanannya. Tidak ada autentikasi/CSRF.

### 4.8 · Privilege Escalation via endpoint rahasia
- **File:** `admin/ubah_role.php`
- **Alur eksploit:**
  ```bash
  curl -s -d "user_id=4&role=admin" http://127.0.0.1:8090/admin/ubah_role.php
  ```
  Role user 4 (sari) berubah jadi `admin` tanpa verifikasi siapa pemanggil.

## 5. Analisis Akar Masalah

| Kelas BAC | Mengapa terjadi |
|-----------|-----------------|
| IDOR | Tidak ada pengecekan *ownership* — objek langsung dimuat dari input milik client. |
| Privilege escalation | Sumber kebenaran role bukan dari sesi/database server. |
| Missing function-level access | Tidak ada *guard* seragam; beberapa endpoend tidak memanggil otorisasi sama sekali. |
| Direct method / CSRF | Aksi sensitif hanya bergantung pada pemanggilan URL/POST tanpa token & tanpa cek sesi. |

## 6. Rekomendasi Mitigasi (Contoh Kode)

### 6.1 · Guard role seragam
```php
function butuh_role(string $role): void {
    $me = cek_login();
    if (!$me || $me['role'] !== $role) {
        http_response_code(403);
        exit('403 Forbidden — akun tidak berhak.');
    }
}
```

### 6.2 · Cek kepemilikan objek (perbaikan IDOR profil)
```php
$id = (int)($_GET['id'] ?? 0);
if ($id !== (int)$me['id']) {        // pemiliknya harus aku!
    http_response_code(403);
    exit('403 — bukan profilmu.');
}
```

### 6.3 · Role dari database, bukan dari form
```php
$st = $pdo->prepare('SELECT * FROM users WHERE username=?');
$st->execute([$username]);
$user = $st->fetch();
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['login'] = ['id' => $user['id'], 'role' => $user['role'], 'nama' => $user['nama']];
}
```

### 6.4 · Amankan aksi hapus & ubah role
```php
butuh_role('admin');                       // siapa yang boleh
if ($_POST['csrf'] !== $_SESSION['csrf']) die('CSRF');  // dari mana
// lalu proses: $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
```

## 7. Kesimpulan

Seluruh 8 latihan membuktikan bahwa **akses kontrol yang benar tidak cukup hanya
"user sudah login"** — perlu: (1) dapatkan identitas & role dari server/sesi,
(2) cek otorisasi per fungsi, dan (3) cek kepemilikan di setiap akses objek.
Bug BAC masuk peringkat pertama OWASP Top 10 karena dampaknya bisa berupa
bocornya data semua user, hingga akun admin jatuh ke tangan penyerang.

## 8. Terima Kasih

Lab & dokumentasi ini disusun oleh **kikikokok** untuk tugas mata kuliah keamanan web.
Semua data bersifat dummy dan aplikasi hanya boleh dijalankan dalam lingkungan pembelajaran lokal.

---
*LAB BAC — Broken Access Control · watermark "kikikokok" tertanam di setiap halaman (CSS overlay), footer, dan komentar kode.*