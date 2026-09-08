<div align="center">
  <img src="BAC9.png" alt="Preview Aplikasi LAB BAC" style="max-width:100%; border-radius:14px; border:1px solid #e5e7eb;">
  <h1>🛡️ LAB-RED Team — Broken Access Control (BAC)</h1>
  <p><b>Tugas Kuliah Keamanan Web</b> · dibuat oleh <b>kikikokok</b></p>
  <p>PHP 8 + MariaDB · OWASP Top 10 Web 2021 — <b>A01 Broken Access Control</b></p>
  <p>⚠️ Khusus praktikum lokal — jangan dijalankan di server publik/produksi.</p>
</div>

---

Aplikasi simulasi toko yang **sengaja rentan** terhadap *Broken Access Control* untuk
tugas praktikum red team / keamanan web. Terdapat **8 latihan** yang bisa dieksploitasi:

| # | Latihan | Pola |
|---|---------|------|
| 1 | Pilih role `admin` sendiri saat [daftar](register.php) | Privilege escalation |
| 2 | Sisipkan `role=admin` saat [log masuk](login.php) | Privilege escalation |
| 3 | [profil.php?id=1](profil.php?id=1) — lihat profil orang lain | IDOR |
| 4 | [pesanan.php?user_id=3](pesanan.php?user_id=3) — pesanan orang lain | IDOR |
| 5 | [invoice.php?id=5](invoice.php?id=5) tanpa login | Missing function-level access |
| 6 | [admin/user.php](admin/user.php) tanpa login | Missing function-level access |
| 7 | [admin/hapus_user.php?id=3](admin/hapus_user.php?id=3) | Direct method / tanpa otorisasi |
| 8 | [admin/ubah_role.php](admin/ubah_role.php) POST without auth | Privilege escalation |

## 🚀 Menjalankan

```bash
./jalankan.sh                 # import DB lab_bac + server di :8090
./jalankan.sh stop            # matikan server
# buka http://127.0.0.1:8090
```

## 🔑 Akun Demo

| Username | Password | Role |
|----------|----------|------|
| admin    | admin123 | admin |
| kasir    | kasir123 | kasir |
| budi     | budi123  | pelanggan |
| sari     | sari123  | pelanggan |
| riko     | riko123  | pelanggan |

## 📁 Struktur

```
lab-bac/
├── BAC.png                  # preview aplikasi
├── README.md                # landing ini
├── LAPORAN.md               # laporan: alur eksploit + mitigasi + kesimpulan
├── jalankan.sh              # peluncur (DB + server)
├── database/lab_bac.sql     # skema + data dummy
├── includes/                # koneksi, header, footer (watermark)
├── assets/style.css         # tema + watermark "kikikokok" overlay
├── index.php · login.php · register.php · logout.php
├── profil.php · pesanan.php · invoice.php
└── admin/                   # panel "tanpa pengaman"
```

## 📝 Dokumentasi Lengkap

Panduan tiap serangan (langkah + screenshot), akar masalah, dan contoh kode perbaikan
terdapat di **[`LAPORAN.md`](LAPORAN.md)**.

---

<div align="center">
  <small>LAB BAC — Broken Access Control · dibuat oleh <b>kikikokok</b> untuk tugas kuliah</small>
</div>
